<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Barrios_vivos extends CI_Controller{

// Variables generales
//-----------------------------------------------------------------------------
    public $views_folder = 'app/barrios_vivos/';
    public $url_controller = URL_APP . 'barrios_vivos/';

// Constructor
//-----------------------------------------------------------------------------
    
    function __construct() 
    {
        parent::__construct();
        //$this->load->model('Repositorio_model');
        date_default_timezone_set("America/Bogota");    //Para definir hora local
    }
    
    function index($laboratorio_id = NULL)
    {
        if ( is_null($laboratorio_id) ) {
            redirect("app/barrios_vivos/laboratorios/");
        } else {
            redirect("app/barrios_vivos/info/{$laboratorio_id}");
        }
    }
    
//EXPLORACIÓN
//---------------------------------------------------------------------------------------------------

    /**
     * Exploración de Barrios Vivos
     * 2024-08-20
     */
    function laboratorios()
    {
        $data['head_title'] = 'Laboratorios';
        $data['page_title'] = 'Laboratorios';
        $data['fileId'] = '19QV6vBAX7nh5rK1ro24wsVqlEFKrrCvDH6tNvKFR4hM';
        $data['gid'] = '1188213236';

        $filePath = PATH_CONTENT . 'json/barrios_vivos/laboratorios.json';
        $data['laboratorios'] = $this->App_model->getJsonContent($filePath);

        $data['view_a'] = $this->views_folder . "laboratorios/laboratorios_v";
        $this->App_model->view('templates/easypml/minimal', $data);
    }
}