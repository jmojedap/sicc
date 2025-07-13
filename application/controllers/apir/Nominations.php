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



    


}