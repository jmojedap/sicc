<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Experimentos extends CI_Controller {
        
// Variables generales
//-----------------------------------------------------------------------------
    public $views_folder = 'app/experimentos/';
    public $url_controller = URL_APP . 'experimentos/';

// Constructor
//-----------------------------------------------------------------------------
    
    function __construct()
    {
        parent::__construct();

        //$this->load->model('Noticia_model');
        
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

// Mediciones especiales
//-----------------------------------------------------------------------------

    function somos_asociacion()
    {
        $data['head_title'] = 'SOMOS - Asociación';
        $data['view_a'] = 'app/experimentos/somos_asociacion/somos_asociacion_v';
        $this->App_model->view('templates/easypml/empty', $data);
    }

    function puntos()
    {
        $data['head_title'] = 'Puntos';
        $data['view_a'] = 'app/experimentos/puntos/puntos_v';
        $this->App_model->view('templates/easypml/empty', $data);
    }
}