<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Nominations extends CI_Controller{

// Variables generales
//-----------------------------------------------------------------------------
    public $url_controller = URL_API . 'nominations/';

// Constructor
//-----------------------------------------------------------------------------
    
    function __construct() 
    {
        parent::__construct();
        $this->load->model('Nomination_model');
        date_default_timezone_set("America/Bogota");    //Para definir hora local
    }
    
    function index($accion_id = NULL)
    {
        if ( is_null($accion_id) ) {
            redirect("app/nominations/explorar/");
        } else {
            redirect("app/nominations/info/{$accion_id}");
        }
    }

    /**
     * AJAX JSON
     * Enviar por correo electrónico un link para iniciar sesión en la
     * aplicación
     * 2025-07-05
     */
    function get_login_link()
    {
        $email = $this->input->post('email');

        //Respuesta por defecto
        $data = [
            'status' => 0,
            'message' => "No existe ningún usuario con el correo '{$email}'",
            'link' => ''
        ];

        //Identificar usuario
        $email = $this->input->post('email');
        $user = $this->Db_model->row('nc_users', "email = '{$email}'");
        
        if ( ! is_null($user) ) {
            $data = $this->Nomination_model->send_login_link($user->id);
        }

        //Salida JSON
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    /**
     * AJAX JSON
     * Devuelve un array que incluye el token JWT generado al validar la clave de activación
     * recibida por email.
     * 2025-08-11
     */
    function get_access_token()
    {
        $activation_key = $this->input->post('key');

        //Resultado por defecto
        $data['status'] = 0;
        $data['message'] = 'El código de activación no es válido';
        $data['activation_key'] = $activation_key;

        $this->load->library('jwt');

        $user = $this->Db_model->row('nc_users', "activation_key = '{$activation_key}'");
        if ( ! is_null($user) ) {
            $userdata = [
                'id' => $user->id, 'email' => $user->email,
                'display_name' => $user->display_name, 'role' => 21,
                'organization' => $user->organization
            ];
            $access_token = $this->jwt->generate($userdata, 60*24*2);   //Dos días
            $data['access_token'] = $access_token;
            $data['status'] = 1;
            $data['message'] = 'El código de activación es válido';
        }

        //Salida JSON
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    function get_access()
    {
        $inputData['email'] = $this->input->post('email');
        $data = $this->Nomination_model->get_access($inputData);

        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    function test_authorization()
    {
        // Valor por defecto
        $data = [
            'status' => 0,
            'message' => 'Token no proporcionado'
        ];

        $this->load->library('jwt');

        $headers = $this->input->request_headers();

        if (isset($headers['Authorization'])) {
            $token = str_replace('Bearer ', '', $headers['Authorization']);
            $payload = $this->jwt->validate($token);

            if ($payload) {
                // Token válido
                $data = [
                    'status' => 1,
                    'message' => 'Token válido',
                    'payload' => $payload
                ];
            } else {
                // Token inválido o expirado
                $data = [
                    'status' => 0,
                    'message' => 'Token inválido o expirado'
                ];
            }
        }

        //Salida JSON
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    /**
     * JSON
     * Listado de users, según filtros de búsqueda
     */
    function get_users($numPage = 1, $perPage = 100)
    {
        $this->load->model('Search_model');
        $filters = $this->Search_model->filters();
        $data = $this->Nomination_model->get_users($filters, $numPage, $perPage);
        unset($data['filters']['localidad']);
        unset($data['filters']['estrategia']);
        unset($data['filters']['linea_e']);
        unset($data['filters']['repo_tipo']);
        unset($data['filters']['repo_tema']);
        unset($data['filters']['repo_subtema']);
        unset($data['filters']['repo_formato']);
        unset($data['filters']['repo_area']);

        //Salida JSON
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    /**
     * Devuelve los datos del usuario que tiene sesión activa con JWT access token
     * 2025-07-28
     */
    function get_user_info()
    {
        $user = $this->Nomination_model->user_info();
        unset($user->activation_key);
        $data['user'] = $user;
        //Salida JSON
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    function update_profile()
    {
        $arr_row = $this->input->post();
        $data = $this->Nomination_model->update_profile($arr_row);
        //Salida JSON
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }


    /**
     * Guarda las respuestas a la encuesta inicia en la tabla nc_users_meta
     * 2025-07-23
     */
    function save_responses()
    {
        $user = $this->Nomination_model->user_info();
        $data = ['status' => 0, 'message' => 'No se guardaron las respuestas'];

        //Si se identifica al usuario
        if ( $user ) {
    
            // Leer el raw input y decodificar JSON
            $json = file_get_contents('php://input');
            $respuestas = json_decode($json, true);

            if (!is_array($respuestas)) {
                return $this->output->set_status_header(400)->set_output(json_encode(['error' => 'Formato inválido']));
            }

            $aRow = $this->Nomination_model->arr_row_meta($user, $user->id);
            $aRow['type_id'] = 10;  //respuesta-encuesta
            $aRow['type'] = 'respuesta-encuesta';  //respuesta-encuesta

            $saved = [];    //IDs de respuestas guardadas
            foreach ($respuestas as $r) {
                // validar campos mínimos
                if (isset($r['pregunta_id'], $r['respuesta'])) {
                    $aRow['related_1'] = $r['pregunta_id'];
                    $aRow['text_1'] = $r['pregunta'];
                    $aRow['text_2'] = $r['respuesta'];

                    //Condición para guardar y no repetir respuestas de una misma pregunta para el mismo usuario
                    $condition = "user_id = {$user->id} AND type_id = 10 AND related_1 = {$r['pregunta_id']}";
                    $saved[] = $this->Db_model->save('nc_users_meta', $condition, $aRow);
                }
            }
            
            //Si se guardaron respuestas
            if ( count($saved) > 0 ) {
                $data['status'] = 1;
                $data['message'] =  'Respuestas guardadas: ' . count($saved);
                $data['saved'] = $saved;

                //Marcar que el usuario ya respondió la encuesta
                $aUser['survey_status'] = 1;
                $this->Db_model->save('nc_users', "id = {$user->id}", $aUser);
            }
        }

        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    /**
     * Nominación de un usuario
     * 2025-07-23
     * Recibe un JSON con los datos de la nominación y lo guarda en la tabla
     * nc_users_meta
     */
    function nominate()
    {
        // Leer el raw input y decodificar JSON
            $json = file_get_contents('php://input');
            $nomination = json_decode($json, true);

        $data = $this->Nomination_model->nominate($nomination);
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    /**
     * Listado de nominaciones
     * 2025-07-23
     * Devuelve un listado de nominaciones según los filtros de búsqueda
     */
    function get_nominations($numPage = 1, $perPage = 1000)
    {
        $this->load->model('Search_model');
        $filters = $this->Search_model->filters();
        $nominations = $this->Nomination_model->nominations($filters, $numPage, $perPage);
        $data['nominations'] = $nominations->result();
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }
}