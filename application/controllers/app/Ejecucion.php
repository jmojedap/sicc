<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ejecucion extends CI_Controller {
        
// Variables generales
//-----------------------------------------------------------------------------
    public $views_folder = 'app/ejecucion/';
    public $url_controller = URL_APP . 'ejecucion/';

// Constructor
//-----------------------------------------------------------------------------
    
    function __construct()
    {
        parent::__construct();

        $this->load->model('Info_model');
        $this->load->model('Ejecucion_model');
        
        //Local time set
        date_default_timezone_set("America/Bogota");
    }

    /**
     * Primera función
     */
    function index()
    {
        $this->obligaciones();
    }

// FUNCIONES FRONT INFO
//-----------------------------------------------------------------------------

    /**
     * HTML
     * Listado de obligaciones contractuales
     * 2022-06-27
     */
    function obligaciones()
    {
        //Datos
        $this->load->model('Post_model');
        $this->db->select($this->Post_model->select('71_obligaciones'));
        $this->db->order_by('integer_1', 'ASC');
        $this->db->where('type_id', 71);
        $obligaciones = $this->db->get('posts');

        $data['obligaciones'] = $obligaciones;

        $data['view_a'] = $this->views_folder . 'obligaciones';
        $data['head_title'] = 'Obligaciones';

        $this->load->view(TPL_FRONT, $data);
    }

    /**
     * HTML
     * Listado de actividades del plan de acción
     * 2022-06-27
     */
    function plan_accion_ant()
    {
        //Datos
        $this->load->model('Post_model');
        $this->db->select($this->Post_model->select('72_actividades'));
        $this->db->order_by('code', 'ASC');
        $this->db->where('type_id', 72);
        $data['actividades'] = $this->db->get('posts');

        $data['view_a'] = $this->views_folder . 'plan_accion';
        $data['head_title'] = 'Plan de acción';

        $this->load->view(TPL_FRONT, $data);
    }

    /**
     * HTML
     * Listado de actividades del plan de acción
     * 2022-06-27
     */
    function bitacora()
    {
        //Datos
        $this->load->model('Post_model');
        $this->db->select($this->Post_model->select('73_bitacora'));
        $this->db->order_by('integer_3', 'DESC');
        $this->db->where('type_id', 73);
        $data['actividades'] = $this->db->get('posts');

        $data['view_a'] = $this->views_folder . 'bitacora';
        $data['head_title'] = 'Bitácora de actividades';

        $this->load->view(TPL_FRONT, $data);
    }

    function reporte_plan()
    {
        //Datos
        $this->load->model('Post_model');

        $data['obligaciones'] = $this->Ejecucion_model->obligaciones();
        $data['actividades'] = $this->Ejecucion_model->actividades();
        $data['bitacora'] = $this->Ejecucion_model->bitacora();

        $data['view_a'] = $this->views_folder . 'reporte_plan/reporte_plan';
        $data['head_title'] = 'Bitácora de actividades';

        $this->load->view('templates/print/main', $data);
        //$this->load->view('templates/easypml/main', $data);
    }

    /**
     * Plan de acción del contrato
     * 2024-06-17
     */
    function plan_accion()
    {
        $this->load->library('excel');            

        $filePath = PATH_CONTENT . 'json/ejecucion/plan_accion_306_2024.json';
        $data['acciones'] = $this->App_model->getJsonContent($filePath);

        $data['head_title'] = 'Plan de acción';
        $data['view_a'] = 'app/ejecucion/2024/plan_accion';

        $this->load->view('templates/print/main', $data);
    }

    function avance_plan()
    {
        //Datos

        $this->load->library('google_sheets');
        //$data['obligaciones'] = $this->google_sheets->sheetToArray('12zTfIv_bnhRnMf8BoMhHdAT0J6mDrV6ZxxPux73cKKM', 0);
        $data['acciones'] = $this->google_sheets->sheetToArray('12zTfIv_bnhRnMf8BoMhHdAT0J6mDrV6ZxxPux73cKKM', 2064995061);
        $data['actividades'] = $this->google_sheets->sheetToArray('12zTfIv_bnhRnMf8BoMhHdAT0J6mDrV6ZxxPux73cKKM', 66831367);

        $data['view_a'] = 'app/ejecucion/2023/cumplimiento_plan';
        $data['head_title'] = 'Avance plan de acción';

        $this->load->view('templates/print/main', $data);
        //$this->load->view('templates/easypml/main', $data);
    }

    function informe_final()
    {
        //Datos

        $this->load->library('google_sheets');
        //$data['obligaciones'] = $this->google_sheets->sheetToArray('12zTfIv_bnhRnMf8BoMhHdAT0J6mDrV6ZxxPux73cKKM', 0);
        $data['acciones'] = $this->google_sheets->sheetToArray('12zTfIv_bnhRnMf8BoMhHdAT0J6mDrV6ZxxPux73cKKM', 2064995061);
        $data['obligaciones'] = $this->google_sheets->sheetToArray('12zTfIv_bnhRnMf8BoMhHdAT0J6mDrV6ZxxPux73cKKM', 0);

        $data['view_a'] = 'app/ejecucion/2023/informe_final';
        $data['head_title'] = 'Informe Ejecutivo Final';

        $this->load->view('templates/print/main', $data);
        //$this->load->view('templates/easypml/main', $data);
    }
}