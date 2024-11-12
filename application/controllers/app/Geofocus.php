<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Geofocus extends CI_Controller{

// Variables generales
//-----------------------------------------------------------------------------
    public $views_folder = 'app/geofocus/';
    public $url_controller = URL_APP . 'geofocus/';

// Constructor
//-----------------------------------------------------------------------------
    
    function __construct() 
    {
        parent::__construct();
        $this->load->model('Geofocus_model');
        date_default_timezone_set("America/Bogota");    //Para definir hora local
    }
    
    function index($priorizacion_id = NULL)
    {
        if ( is_null($priorizacion_id) ) {
            redirect("app/geofocus/priorizaciones/");
        } else {
            redirect("app/geofocus/priorizaciones/{$priorizacion_id}");
        }
    }

// CRUD Priorizaciones
//-----------------------------------------------------------------------------

    function priorizaciones()
    {
        $data['elementos'] = $this->Geofocus_model->get_priorizaciones();

        $data['head_title'] = 'Priorizaciones';
        $data['view_a'] = $this->views_folder . 'priorizaciones/priorizaciones_v';
        //$data['nav_2'] = '';

        $this->App_model->view('templates/easypml/minimal', $data);
    }
    

// Priorización geográfica
//-----------------------------------------------------------------------------

    /**
     * Vista de la herramienta de priorización geográfica distrital
     * 2024-08-17
     */
    function parametrizacion($priorizacionId)
    {
        $data = $this->Geofocus_model->basic($priorizacionId);
        $data['view_a'] = $this->views_folder . 'parametrizacion/parametrizacion_v';

        $filePath = PATH_CONTENT . 'json/geofocus/variables.json';
        $data['variables'] = $this->App_model->getJsonContent($filePath);
        $data['localidades'] = $this->App_model->getJsonContent(PATH_CONTENT . 'json/sig/localidades.json');

        $this->App_model->view('templates/easypml/minimal', $data);
    }

    /**
     * Mapas
     * 2024-11-09
     */
    function mapas($variableId = 28)
    {
        $filePath = PATH_CONTENT . 'json/geofocus/variables.json';
        $data['variables'] = $this->App_model->getJsonContent($filePath);
        $data['variableId'] = $variableId;
        
        $data['head_title'] = 'Geofocus Mapas';
        $data['view_a'] = $this->views_folder . 'mapas/mapas_v';
        $this->App_model->view('templates/easypml/minimal', $data);
    }
}