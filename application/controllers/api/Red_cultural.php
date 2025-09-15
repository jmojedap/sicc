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
     * POST :: Genera contenido usando la API de Gemini.
     * 2025-05-24
     */
    function get_answer()
    {
        
        $user_input = $this->input->post('user_input');

        // Preparar la instrucción del sistema
        $this->load->library('Gemini_client');
        $system_instruction_base = $this->gemini_client->system_instruction('invitados-ecci');
        $system_instruction_parts[] = ['text' => $system_instruction_base];
        $system_instruction_parts[] = ['text' => 'Mi nombre es: ' . $this->session->userdata('display_name')];

        // Leer el archivo que describe a los invitados
        $this->load->helper('file');
        $json_path = PATH_CONTENT . 'redcultural/data/ecci_invitados.json';
        $json_content = read_file($json_path);

        // Verificar si el archivo se leyó correctamente
        if ($json_content === FALSE) {
            // Manejar el error, por ejemplo, loguearlo o mostrar un mensaje
            log_message('error', 'No se pudo leer el archivo JSON: ' . $json_path);
            return; // O lanzar una excepción
        }

        $request_settings = [
            'user_input' => $this->input->post('user_input'),
            'system_instruction_parts' => $system_instruction_parts,
            'model' => 'gemini-2.0-flash-lite',
            'contents' => [
                [
                    "role" => "user",
                    "parts" => [
                        [
                            "text" => 'Los datos de los invitados son: ' . $json_content
                        ],
                        [
                            "text" => $user_input
                        ]
                    ]
                ]
            ],
        ];

        
        $data = $this->gemini_client->generate($request_settings);

        // Guardar la respuesta de la API
        $this->load->model('Post_model');
        $arr_row = $this->Db_model->arr_row(FALSE);
        $arr_row['content'] = $data['response_text'] ?? '';
        $arr_row['content_json'] = json_encode($data['response_details']) ?? '';
        $arr_row['type_id'] = 401; //Contenido generado por AI
        $arr_row['post_name'] = 'Solicitud de ' . $this->session->userdata('display_name');
        $arr_row['text_1'] = $this->session->userdata('username');
        $arr_row['text_2'] = $this->session->userdata('display_name');
        $arr_row['status'] = 2; //Borrador
        $arr_row['excerpt'] = $user_input;
        $arr_row['integer_1'] = $data['response_details']['usageMetadata']['promptTokenCount'] ?? 0;
        $arr_row['integer_2'] = $data['response_details']['usageMetadata']['candidatesTokenCount'] ?? 0;
        $arr_row['integer_3'] = $arr_row['integer_1'] + $arr_row['integer_2'];

        $save_post = $this->Post_model->save($arr_row);
        $data['post_id'] = $save_post['saved_id'];
        $data['token_count'] = $arr_row['integer_3'];

        //Salida JSON
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
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
            $arr_base['creator_id'] = 1;
            $arr_base['updater_id'] = 1;

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