<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Educacion extends CI_Controller{

// Variables generales
//-----------------------------------------------------------------------------
    public $views_folder = 'app/educacion/';
    public $url_controller = URL_APP . 'educacion/';

// Constructor
//-----------------------------------------------------------------------------
    
    function __construct() 
    {
        parent::__construct();
        //$this->load->model('Educacion_model');
        date_default_timezone_set("America/Bogota");    //Para definir hora local
    }
    
    function restas()
    {
        $data['head_title'] = 'Restas';
        $data['view_a'] = $this->views_folder . 'restas/restas_v';
        //$data['nav_2'] = '';

        $this->App_model->view('templates/easypml/minimal', $data);
    }

    function tablas_multiplicar()
    {
        $data['head_title'] = 'Tablas de Multiplicar';
        $data['view_a'] = $this->views_folder . 'tablas_multiplicar/tablas_multiplicar';

        $this->App_model->view('templates/easypml/minimal', $data);
    }

}