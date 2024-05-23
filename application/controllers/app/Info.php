<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Info extends CI_Controller {
        
// Variables generales
//-----------------------------------------------------------------------------
    public $views_folder = 'app/info/';
    public $url_controller = URL_APP . 'info/';

// Constructor
//-----------------------------------------------------------------------------
    
    function __construct()
    {
        parent::__construct();

        $this->load->model('Info_model');
        
        //Local time set
        date_default_timezone_set("America/Bogota");
    }

    /**
     * Primera función
     */
    function index()
    {
        $this->inicio();
            
    }

// FUNCIONES FRONT INFO
//-----------------------------------------------------------------------------

    function inicio()
    {
        $data['view_a'] = $this->views_folder . 'home_v';
        $data['head_title'] = 'Prototipos SICC';

        $this->load->view(TPL_FRONT, $data);
    }

    

    /**
     * Informe LookerStudio, balance resumen del Plan Anual de Investigaciones
     * 2023-06-17
     */
    function balance_pai()
    {
        $data['head_title'] = 'Balance PAI 2023';
        $data['view_a'] = 'app/info/looker_studio_v';
        $data['reportId'] = '6f953cd3-0c43-4aa8-8a83-19815aaa0240';
        $this->App_model->view(TPL_FRONT, $data);
    }

    function redirigir()
    {
        $this->load->view('app/info/redirigir_v');
    }
}