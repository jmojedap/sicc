<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Visualizaciones extends CI_Controller {
        
// Variables generales
//-----------------------------------------------------------------------------
    public $views_folder = 'app/visualizaciones/';
    public $url_controller = URL_APP . 'visualizaciones/';

// Constructor
//-----------------------------------------------------------------------------
    
    function __construct()
    {
        parent::__construct();

        $this->load->model('Accion_model');
        
        //Local time set
        date_default_timezone_set("America/Bogota");
    }

    /**
     * Primera función
     */
    function index()
    {
        $this->filbo2023();
    }

    function especiales($page = 'intervenciones-carrera-septima-')
    {
        $data['head_title'] = 'Visualizaciones';
        $data['view_a'] = $this->views_folder . 'especiales_v';
        $this->App_model->view(TPL_FRONT, $data);
    }

    function intervenciones_carrera_septima()
    {
        $this->load->view('app/visualizaciones/carrera_septima/index');
    }


}