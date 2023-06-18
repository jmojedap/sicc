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
        $data['head_title'] = 'Documentación MECC';

        $this->load->view(TPL_FRONT, $data);
    }

    function visualizaciones_datos()
    {
        /* $this->load->library('google_sheets');
        $data['tableros'] = $this->google_sheets->sheetToArray('1K4Ly_hU0j6-bIo-SAtXHMeBiizrUEsg4w8XE8o5Lpik', 0); */

        $data['head_title'] = 'Tableros Power Bi';
        $data['fileId'] = '1K4Ly_hU0j6-bIo-SAtXHMeBiizrUEsg4w8XE8o5Lpik';
        $data['gid'] = '0';

        $data['view_a'] = $this->views_folder . "visualizaciones_datos_v";
        $this->App_model->view(TPL_FRONT, $data);
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
        $this->App_model->view(TPL_FRONT . '_fluid', $data);
    }

    
}