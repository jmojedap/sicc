<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Data_science extends CI_Controller {
        
// Variables generales
//-----------------------------------------------------------------------------
    public $views_folder = 'app/data_science/';
    public $url_controller = URL_APP . 'data_science/';

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

    function filbo2023($section = 'heatMap')
    {
        $folder = PATH_CONTENT . 'data_science/filbo2023/';

        $this->load->library('excel');
        $data['dias'] = $this->excel->get_array($folder . '202304_filbo_asistencia.xlsx', 'dias');
        $data['horas'] = $this->excel->get_array($folder . '202304_filbo_asistencia.xlsx', 'horas');
        $data['conteos'] = $this->excel->get_array($folder . '202304_filbo_asistencia.xlsx', 'conteos');
        $data['zonas'] = $this->excel->get_array($folder . '202304_filbo_asistencia.xlsx', 'zonas');
        $data['section'] = $section;

        $data['head_title'] = 'Filbo 2023';
        $data['view_a'] = $this->views_folder . 'filbo2023/filbo2023_v';
        $this->App_model->view(TPL_FRONT, $data);
    }


}