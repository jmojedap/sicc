<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class App extends CI_Controller {

// Variables generales
//-----------------------------------------------------------------------------
public $views_folder = 'app/app/';
public $url_controller = URL_APP . 'app/';

// Constructor
//-----------------------------------------------------------------------------
    
    function __construct()
    {
        parent::__construct();
        
        //Local time set
        date_default_timezone_set("America/Bogota");
    }

    /**
     * Primera función de la aplicación
     */
    function index()
    {
        if ( $this->session->userdata('logged') )
        {
            $this->logged();
        } else {
            redirect('app/accounts/login');
        }    
    }

    function denied()
    {
        $data['head_title'] = 'Acceso no permitido';
        $data['view_a'] = 'app/app/denied_v';

        $this->load->view('templates/easypml/start', $data);
    }

    function template()
    {
        $data['head_title'] = 'Template elements';
        $data['view_a'] = $this->views_folder . 'template_v';

        $this->load->view(TPL_FRONT, $data);
    }

    /**
     * Devolver JSON con contenido HTML de un contenido o documento de especificaciones técnicas
     * 2023-04-04
     */
    function get_doc($page = '100_inicio', $view = false)
    {
        $settings = $this->input->post();
        $data['content'] = $this->App_model->get_doc($settings);

        //Salida JSON
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    function cultured_bogota()
    {
        $data['head_title'] = 'CultuRed_Bogotá';
        $data['view_a'] = 'templates/cultured_bogota/home';

        $data['modulos'] = file_get_contents(PATH_CONTENT . "json/cultured_bogota/modulos.json");
        $data['componentes'] = file_get_contents(PATH_CONTENT . "json/cultured_bogota/componentes.json");

        $this->load->view('templates/cultured_bogota/main', $data);
        
    }
}