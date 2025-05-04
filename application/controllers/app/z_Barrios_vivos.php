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
        $data['tablas'] = [
            ['nombre' => 'laboratorios', 'gid' => '1188213236'],
            ['nombre' => 'actividades', 'gid' => '407441694'],
        ];

        $basePath = PATH_CONTENT . 'json/barrios_vivos/';
        $data['laboratorios'] = $this->App_model->getJsonContent($basePath . 'laboratorios.json');
        $data['actividades'] = $this->App_model->getJsonContent($basePath . 'actividades.json');

        $data['view_a'] = $this->views_folder . "laboratorios/laboratorios_v";
        $this->App_model->view('templates/easypml/minimal', $data);
    }

    /**
     * Exploración de actividades de Barrios Vivos
     * 2024-08-20
     */
    function actividades()
    {
        $data['head_title'] = 'Actividades - Barrios Vivos';
        $data['page_title'] = 'Actividades - Barrios Vivos';
        $data['fileId'] = '19QV6vBAX7nh5rK1ro24wsVqlEFKrrCvDH6tNvKFR4hM';
        $data['gid'] = '1188213236';
        $data['tablas'] = [
            ['nombre' => 'laboratorios', 'gid' => '1188213236'],
            ['nombre' => 'actividades', 'gid' => '407441694'],
        ];

        $basePath = PATH_CONTENT . 'json/barrios_vivos/';
        $data['laboratorios'] = $this->App_model->getJsonContent($basePath . 'laboratorios.json');
        $data['actividades'] = $this->App_model->getJsonContent($basePath . 'actividades.json');

        $data['view_a'] = $this->views_folder . "actividades/actividades_v";
        $this->App_model->view('templates/easypml/minimal', $data);
    }

    /**
     * Exploración de Barrios Vivos
     * 2024-08-20
     */
    function cartas_localidad($localidadCod = 1)
    {
        $data['head_title'] = 'Cartas';
        $data['page_title'] = 'Cartas';
        $data['fileId'] = '19QV6vBAX7nh5rK1ro24wsVqlEFKrrCvDH6tNvKFR4hM';
        $data['gid'] = '1188213236';

        $filePath = PATH_CONTENT . 'json/barrios_vivos/laboratorios.json';
        $data['laboratorios'] = $this->App_model->getJsonContent($filePath);
        $filePath = PATH_CONTENT . 'json/barrios_vivos/localidades.json';
        $data['localidades'] = $this->App_model->getJsonContent($filePath);
        $data['localidadCod'] = $localidadCod;

        $data['view_a'] = $this->views_folder . "cartas_localidad/cartas_localidad_v";
        $this->App_model->view('templates/easypml/minimal', $data);
    }

    
}