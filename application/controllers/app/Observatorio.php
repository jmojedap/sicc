<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Observatorio extends CI_Controller{

// Variables generales
//-----------------------------------------------------------------------------
    public $views_folder = 'app/observatorio/';
    public $url_controller = URL_APP . 'observatorio/';

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

    /** 
    * Exploración de contenidos
    * 2022-08-23
    * */
    function inicio()
    {
        /*$this->load->library('google_sheets');
        $driveFileId = '1AXuizcSWlkIBUJBJml8q90_PgRYn4ZAYbGsChJV76qQ';
        $data['modulos'] = $this->google_sheets->sheetToArray($driveFileId, 0);*/

        //Datos básicos de la exploración
            $data['head_title'] = 'Observatorio de Cultura, Recreación y Deporte';
            $data['view_a'] = $this->views_folder . 'inicio/inicio_v';
            
        //Cargar vista
            $this->App_model->view(TPL_FRONT, $data);
    }

    function mapas()
    {
        $data['fileId'] = '1QNHIpeIJqBlDZWTfGNvyGBRMzZ60-aLXPQ130HrhL7g';
        $data['gid'] = '0';

        //Datos básicos de la exploración
        $data['head_title'] = 'Mapas para la Investigación';
        $data['view_a'] = $this->views_folder . 'mapas/mapas_v';

        //Cargar vista
        $this->App_model->view(TPL_FRONT . '_fluid', $data);
    }

    /**
     * Vista del Plan Anual de Investigaciones
     */
    function pai($year = 2023)
    {
        //Datos básicos de la exploración
        $data['head_title'] = 'Mapas para la Investigación';
        $data['view_a'] = $this->views_folder . 'pai_v';

        //Cargar vista
        $this->App_model->view(TPL_FRONT . '_fluid', $data);
    }

    function ebc()
    {
        //Datos básicos de la exploración
        $data['head_title'] = 'Encuesta Bienal de Culturas';
        $data['view_a'] = $this->views_folder . 'pai_v';

        //Cargar vista
        $this->App_model->view('templates/easypml/ebc', $data);
    }
}