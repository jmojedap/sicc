<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Acciones extends CI_Controller{

// Variables generales
//-----------------------------------------------------------------------------
    public $url_controller = URL_API . 'acciones/';

// Constructor
//-----------------------------------------------------------------------------
    
    function __construct() 
    {
        parent::__construct();
        $this->load->model('Accion_model');
        date_default_timezone_set("America/Bogota");    //Para definir hora local
    }
    
    function index($accion_id = NULL)
    {
        if ( is_null($accion_id) ) {
            redirect("app/acciones/explorar/");
        } else {
            redirect("app/acciones/info/{$accion_id}");
        }
    }
    
//EXPLORE FUNCTIONS
//---------------------------------------------------------------------------------------------------

    /**
     * Listado de acciones, filtrados por búsqueda, JSON
     * 2024-05-28
     */
    function get($num_page = 1, $per_page = 100)
    {
        if ( $per_page > 250 ) $per_page = 250;

        $this->load->model('Search_model');
        $filters = $this->Search_model->filters();

        $data = $this->Repositorio_model->get($filters, $num_page, $per_page);
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }
}