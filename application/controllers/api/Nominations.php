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

    function get_access()
    {
        $inputData['email'] = $this->input->post('email');
        $data = $this->Nomination_model->get_access($inputData);

        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    /**
     * JSON
     * Listado de users, según filtros de búsqueda
     */
    function get_users($numPage = 1, $perPage = 10)
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