<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sig extends CI_Controller{

// Variables generales
//-----------------------------------------------------------------------------
    public $views_folder = 'app/observatorio/sig/';
    public $url_controller = URL_APP . 'sig/';

// Constructor
//-----------------------------------------------------------------------------
    
    function __construct() 
    {
        parent::__construct();
        $this->load->model('Repositorio_model');
        date_default_timezone_set("America/Bogota");    //Para definir hora local
    }
    
    function index($contenido_id = NULL)
    {
        if ( is_null($contenido_id) ) {
            redirect("app/observatorio/inicio/");
        } else {
            redirect("app/repositorio/informacion/{$contenido_id}");
        }
    }
    
//EXPLORACIÓN
//---------------------------------------------------------------------------------------------------

    function charlas_barriales()
    {
        //Datos básicos de la exploración
        $data['head_title'] = 'Charlas Barriales';
        $data['view_a'] = $this->views_folder . 'charlas_barriales/charlas_barriales_v';

        $this->load->library('google_sheets');
        $driveFileId = '11GFICk_5xlNrHldEK4r5vetoiolYkNy0hUaU1FVkRKg';
        $data['compromisos'] = $this->google_sheets->sheetToArray($driveFileId, 0);

        //Cargar vista
        $this->App_model->view('templates/easypml/empty', $data);
    }

// Priorización geográfica
//-----------------------------------------------------------------------------

    /**
     * Vista de la herramienta de priorización geográfica distrital
     * 2024-08-17
     */
    function priorizacion_geografica()
    {
        $data['head_title'] = 'Priorización Geográfica';
        $data['view_a'] = $this->views_folder . 'priorizacion_geografica/priorizacion_geografica_v';
        //$data['nav_2'] = '';

        $filePath = PATH_CONTENT . 'json/priorizacion_geografica/variables.json';
        $data['variables'] = $this->App_model->getJsonContent($filePath);

        $this->App_model->view('templates/easypml/minimal', $data);
    }

    function highmaps()
    {
        $data['head_title'] = 'High Maps Pruebas';
        $data['view_a'] = 'app/observatorio/sig/highmaps/highmaps_v';
        $this->App_model->view('templates/easypml/minimal', $data);
    }
}