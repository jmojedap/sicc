<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Red_cultural extends CI_Controller {
        
// Variables generales
//-----------------------------------------------------------------------------
    public $views_folder = 'redcultural/invitados/';
    public $url_controller = RCI_URL_APP . 'invitados/';

// Constructor
//-----------------------------------------------------------------------------
    
    function __construct()
    {
        parent::__construct();

        $this->load->model('Rci_model');
        
        //Local time set
        date_default_timezone_set("America/Bogota");
    }

// EXPLORACIÓN
//-----------------------------------------------------------------------------

    /**
     * Ejecuta (e) la importación de invitados con archivo Excel
     * 2025-08-07
     */
    function importar_invitados($userkey)
    {
        $data = ['status' => 0, 'qty_imported' => 0];

        if ( $userkey == 'okfuwrcmnkpbilhrqhdbyjaomsxvaxfc' ) {
            //Proceso
            $this->load->library('excel');            
            $imported_data = $this->excel->arr_sheet_default($this->input->post('sheet_name'));
            
            if ( $imported_data['status'] == 1 )
            {
                $data = $this->Rci_model->import($imported_data['arr_sheet']);
            }
    
            //Cargue de variables
                $data['status'] = $imported_data['status'];
                $data['message'] = $imported_data['message'];
                $data['arr_sheet'] = $imported_data['arr_sheet'];
                $data['sheet_name'] = $this->input->post('sheet_name');
        }

        //Salida JSON
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    /**
     * Ejecuta (e) la importación de metadatos invitados con archivo Excel
     * 2025-08-07
     */
    function importar_invitados_meta($userkey)
    {
        $data = ['status' => 0, 'qty_imported' => 0];

        if ( $userkey == 'okfuwrcmnkpbilhrqhdbyjaomsxvaxfc' ) {
            //Proceso
            $this->load->library('excel');            
            $imported_data = $this->excel->arr_sheet_default($this->input->post('sheet_name'));
            
            if ( $imported_data['status'] == 1 )
            {
                $data = $this->Rci_model->import_users_meta($imported_data['arr_sheet']);
            }
    
            //Cargue de variables
                $data['status'] = $imported_data['status'];
                $data['message'] = $imported_data['message'];
                $data['arr_sheet'] = $imported_data['arr_sheet'];
                $data['sheet_name'] = $this->input->post('sheet_name');
        }

        //Salida JSON
        $this->output->set_content_type('application/json')->set_output(json_encode($data));

    }

    /**
     * POST :: Valida la consulta y el cupo, registra la petición de IA y guarda el contenido exitoso.
     * Devuelve siempre JSON, incluidos los errores de entrada, generación y guardado.
     * 2026-10-10
     */
    function get_answer()
    {
        $data = [
            'status' => 'error',
            'ai_request_id' => null,
            'model_id' => null,
            'response_text' => null,
            'response_details' => [],
            'post_id' => 0,
            'token_count' => 0,
            'error_message' => null
        ];
        $http_status = 500;
        // Evitar salidas HTML de CodeIgniter cuando falle una consulta de base de datos.
        $previous_db_debug = $this->db->db_debug;
        $this->db->db_debug = false;

        try {
            if ($this->input->method() !== 'post') {
                $http_status = 405;
                $this->output->set_header('Allow: POST');
                throw new InvalidArgumentException('Esta solicitud debe enviarse mediante POST.');
            }
            if (!$this->session->userdata('logged') || (int) $this->session->userdata('user_id') <= 0) {
                $http_status = 401;
                throw new RuntimeException('Es necesario iniciar sesión para generar contenidos.');
            }

            $user_input = $this->input->post('user_input');
            if (!is_string($user_input) || !mb_check_encoding($user_input, 'UTF-8')
                || trim($user_input) === '' || mb_strlen(trim($user_input), 'UTF-8') > 300) {
                $http_status = 400;
                throw new InvalidArgumentException('Escribe una consulta de entre 1 y 300 caracteres.');
            }
            $user_input = trim($user_input);

            $tokens = $this->Rci_model->generation_usage();
            if ($tokens['usage'] >= $tokens['max']) {
                $http_status = 429;
                throw new RuntimeException('Has alcanzado el límite de tokens para generar contenidos.');
            }

            $request_settings = $this->Rci_model->build_ai_request($user_input);
            $this->load->model('Airequest_model');
            $data = array_merge($data, $this->Airequest_model->generate($request_settings, [
                'tool' => 'red_cultural',
                'tool_type_request' => 'get_answer',
                'response_type' => 'markdown',
                'creator_id' => $this->session->userdata('user_id')
            ]));

            $usage = $data['response_details']['usageMetadata'] ?? [];
            $data['token_count'] = ($usage['promptTokenCount'] ?? 0) + ($usage['candidatesTokenCount'] ?? 0);
            if ($data['status'] === 'success') {
                $data['post_id'] = $this->Rci_model->save_ai_content($user_input, $data);
                $http_status = 200;
            }
        } catch (\Throwable $e) {
            $data['status'] = 'error';
            $data['error_message'] = $e->getMessage();
        } finally {
            $this->db->db_debug = $previous_db_debug;
        }

        //Salida JSON
        $this->output->set_status_header($http_status)->set_content_type('application/json')
            ->set_output(json_encode($data, JSON_UNESCAPED_UNICODE | JSON_INVALID_UTF8_SUBSTITUTE));
    }

    /**
     * Editar el estado de publicación de un contenido generado por AI
     * 2025-08-28
     */
    function update_status_ai_content()
    {
        $post_id = $this->input->post('id');
        $arr_row['status'] = $this->input->post('status');
        $arr_row['updated_at'] = date('Y-m-d H:i:s');
        $arr_row['updater_id'] = $this->session->userdata('user_id');
        $condition = "id = {$post_id}";
        if ( $this->session->userdata('role') > 2 ) {
            $condition = "id = {$post_id} AND creator_id = {$this->session->userdata('user_id')}";
        }
        $data['saved_id'] = $this->Db_model->save('posts', $condition, $arr_row);

        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    /**
     * Listado de contenidos generados por IA
     * 2025-08-28
     */
    function get_ai_contents()
    {
        $this->load->model('Search_model');
        $filters = $this->Search_model->filters();

        // Contenidos generados previamente
        $this->load->model('Post_model');
        $filters['sf'] = '401_ai_generados';
        $filters['type'] = '401';
        $filters['status'] = 1; //Publicado
        $data = $this->Post_model->get($filters, 1, 20);

        //Salida JSON
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    /**
     * Estado de seguimiento por parte del usuario actual
     */
    function following_status($user_id)
    {
        $data['following_status'] = $this->Rci_model->following_status($user_id);
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    function guardar_agenda()
    {
        $data = ['error' => '','recaptcha_valid' => FALSE, 'qty_saved' => 0];

        $this->load->model('Validation_model');
        $recaptcha = $this->Validation_model->recaptcha(); //Validación Google ReCaptcha V3

        //Identificar usuario
        $user = $this->Db_model->row('users', "email = '{$this->input->post('email')}'");

        if ( $recaptcha == 1 ) {
            $data['recaptcha_valid'] = TRUE;
            $this->load->model('User_model');
    
            $email = $this->input->post('email');
            $user = $this->Db_model->row('users', "email = '$email'");

            $arr_base['user_id'] = 0;
            $arr_base['title'] = $this->input->post('display_name');
            $arr_base['description'] = $email;
            $arr_base['text_3'] = $this->input->post('grupo_origen');
            $arr_base['text_4'] = $this->input->post('grupo_edad');
            $arr_base['creator_id'] = 1;
            $arr_base['updater_id'] = 1;
            $arr_base['updated_at'] = date('Y-m-d H:i:s');

            if ( ! is_null($user) ) {
                $arr_base['user_id'] = $user->id;
                $arr_base['title'] = $user->display_name;
                $arr_base['creator_id'] = $user->id;
                $arr_base['updater_id'] = $user->id;
            }
    
            //Viernes tarde
            $arr_viernes_tarde = $arr_base;
            $arr_viernes_tarde['type_id'] = '100061';
            $arr_viernes_tarde['type'] = 'agenda-viernes-tarde';
            $arr_viernes_tarde['text_1'] = $this->input->post('viernes_tarde');
            $arr_viernes_tarde['text_2'] = $this->input->post('viernes_tarde_opcion_2');
    
            $result['viernes_tarde'] = $this->User_model->save_meta($arr_viernes_tarde, "description = '{$arr_base['description']}' AND type_id = 100061");
            if ( $result['viernes_tarde'] ) { $data['qty_saved']++; }

            //Sábado
            $arr_sabado = $arr_base;
            $arr_sabado['type_id'] = '100063';
            $arr_sabado['type'] = 'agenda-sabado-manana';
            $arr_sabado['text_1'] = $this->input->post('sabado_manana_opcion_1');
            $arr_sabado['text_2'] = $this->input->post('sabado_manana_opcion_2');
    
            $result['sabado'] = $this->User_model->save_meta($arr_sabado, "description = '{$arr_base['description']}' AND type_id = 100063");
            if ( $result['sabado'] ) { $data['qty_saved']++; }

            //domingo
            $arr_domingo = $arr_base;
            $arr_sabado['type_id'] = '100065';
            $arr_sabado['type'] = 'agenda-recorrido-domingo';
            $arr_sabado['text_1'] = $this->input->post('recorrido_domingo');
            $arr_sabado['text_2'] = '';
    
            $result['domingo'] = $this->User_model->save_meta($arr_sabado, "description = '{$arr_base['description']}' AND type_id = 100065");
            if ( $result['domingo'] ) { $data['qty_saved']++; }
    
            $data['result'] = $result;
        } else {
            $data['error'] = 'Recaptcha no validado';
        }

        //Salida JSON
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }
}
