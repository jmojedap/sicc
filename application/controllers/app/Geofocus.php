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
    function priorizacion($priorizacionId)
    {
        $data = $this->Geofocus_model->basic($priorizacionId);
        $data['view_a'] = $this->views_folder . 'priorizacion/priorizacion_v';

        $filePath = PATH_CONTENT . 'json/geofocus/variables.json';
        $data['variables'] = $this->App_model->getJsonContent($filePath);
        $data['localidades'] = $this->App_model->getJsonContent(PATH_CONTENT . 'json/sig/localidades.json');
        $data['territorios'] = $this->Geofocus_model->getPriorizacion($priorizacionId);

        $this->App_model->view('templates/easypml/minimal', $data);
    }

    /**
     * Exportar detalles de priorización
     * 2024-11-18
     */
    function export($priorizacionId)
    {
        set_time_limit(120);    //120 segundos, 2 minutos para el proceso

        //Identificar filtros y búsqueda
        $this->load->model('Search_model');
        $filters = $this->Search_model->filters();

        $condition = "priorizacion_id = {$priorizacionId}";
        $data['query'] = $this->Geofocus_model->query_export_territorios_valor($condition);

        if ( $data['query']->num_rows() > 0 ) {
            //Preparar datos
                $data['sheet_name'] = 'territorios';

            //Objeto para generar archivo excel
                $this->load->library('Excel');
                $file_data['obj_writer'] = $this->excel->file_query($data);

            //Nombre de archivo
                $file_data['file_name'] = date('Ymd_His') . '_' . $data['sheet_name'];

            $this->load->view('common/download_excel_file_v', $file_data);
            //Salida JSON
            //$this->output->set_content_type('application/json')->set_output(json_encode($file_data['obj_writer']));
        } else {
            $data = array('message' => 'No se encontraron registros para exportar');
            //Salida JSON
            $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }
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