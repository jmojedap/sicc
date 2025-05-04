<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Barrios_vivos extends CI_Controller{

// Variables generales
//-----------------------------------------------------------------------------
    public $url_controller = URL_API . 'barrios_vivos/';

// Constructor
//-----------------------------------------------------------------------------
    
    function __construct() 
    {
        parent::__construct();
        $this->load->model('Barrios_vivos_model');
        date_default_timezone_set("America/Bogota");    //Para definir hora local
    }
    
    function index($laboratorio_id = NULL)
    {
        if ( is_null($laboratorio_id) ) {
            redirect("app/barrios_vivos/explorar/");
        } else {
            redirect("app/barrios_vivos/info/{$laboratorio_id}");
        }
    }
    
//EXPLORE FUNCTIONS
//---------------------------------------------------------------------------------------------------

    /**
     * Listado de Posts, filtrados por búsqueda, JSON
     * 2022-08-23
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