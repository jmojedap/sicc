<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Barrios_vivos extends CI_Controller {
        
// Variables generales
//-----------------------------------------------------------------------------
    public $views_folder = 'app/barrios_vivos/';
    public $url_controller = URL_APP . 'barrios_vivos/';

// Constructor
//-----------------------------------------------------------------------------
    
    function __construct()
    {
        parent::__construct();

        $this->load->model('Barrios_vivos_model');
        
        //Local time set
        date_default_timezone_set("America/Bogota");
    }

    /**
     * Primera función
     */
    function index($laboratorioId = 0)
    {
        if ( $laboratorioId > 0 ) {
            if ( $this->session->userdata('user_id') > 0 ) {
                redirect("app/barrios_vivos/asistentes/{$laboratorioId}");
            } else {
                redirect("app/barrios_vivos/info/{$laboratorioId}");
            }
        } else {
            redirect("app/barrios_vivos/explorar");
        }
    }

// EXPLORACIÓN
//-----------------------------------------------------------------------------

    /** Exploración de laboratorios */
    function explorar($num_page = 1)
    {
        //Identificar filtros de búsqueda
        $this->load->model('Search_model');
        $filters = $this->Search_model->filters();
        $filters['sf'] = 'general';  //Select format
        if ( is_null($filters['y']) ) $filters['y'] = 2025;

        //Datos básicos de la exploración
            $data = $this->Barrios_vivos_model->explore_data($filters, $num_page, 60);
            $data['cf'] = 'barrios_vivos/explorar/';
            $data['controller'] = 'barrios_vivos/';
            $data['views_folder'] = $this->views_folder . 'explorar/';      //Carpeta donde están las vistas de exploración
            $data['head_title'] = 'Laboratorios';
            $data['view_a'] = $data['views_folder'] . 'explore_v';
            $data['nav_2'] = $this->views_folder . 'menus/explorar_v';
        
        //Opciones de filtros de búsqueda
            $data['arrTipoBV'] = $this->Item_model->arr_options('category_id = 431');
            $data['arrCategoriaBV'] = $this->Item_model->arr_options('category_id = 432');
            $data['arrFaseBV'] = $this->Item_model->arr_options('category_id = 433');
            
        //Cargar vista
            $this->App_model->view(TPL_FRONT, $data);
    }

    /**
     * Exploración de Barrios Vivos
     * 2025-04-17
     */
    function laboratorios()
    {
        //Identificar filtros de búsqueda
        $this->load->model('Search_model');
        $filters = $this->Search_model->filters();

        $data = $this->Barrios_vivos_model->get($filters, 1, 1000);
        $data['laboratorios'] = $data['list'];

        $data['head_title'] = 'Laboratorios';
        $data['page_title'] = 'Laboratorios';

        $data['view_a'] = $this->views_folder . "laboratorios/laboratorios_v";
        $this->App_model->view('templates/easypml/minimal', $data);
    }

    /**
     * Exportar resultados de búsqueda
     * 2021-09-27
     */
    function export()
    {
        set_time_limit(120);    //120 segundos, 2 minutos para el proceso

        //Identificar filtros y búsqueda
        $this->load->model('Search_model');
        $filters = $this->Search_model->filters();

        $data['query'] = $this->Barrios_vivos_model->query_export($filters);

        if ( $data['query']->num_rows() > 0 ) {
            //Preparar datos
                $data['sheet_name'] = 'laboratorios';

            //Objeto para generar archivo excel
                $this->load->library('Excel');
                $file_data['obj_writer'] = $this->excel->file_query($data);

            //Nombre de archivo
                $file_data['file_name'] = date('Ymd_His') . '_' . $data['sheet_name'];

            $this->load->view('common/download_excel_file_v', $file_data);
        } else {
            $data = array('message' => 'No se encontraron laboratorios para exportar');
            //Salida JSON
            $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }
    }

// INFORMACIÓN
//-----------------------------------------------------------------------------

    function info($laboratorioId)
    {
        $data = $this->Barrios_vivos_model->basic($laboratorioId);
        $data['nav_2'] = $this->views_folder . 'menus/laboratorio_v';
        $data['view_a'] = 'common/row_details_v';
        $data['back_link'] = $this->url_controller . 'explorar';

        $this->App_model->view(TPL_FRONT, $data);
    }

// CREACIÓN DE UNA ACCIÓN
//-----------------------------------------------------------------------------

    /**
     * Vista formulario para la creación de una nueva laboratorio 
     * 2025-04-16
     */
    function add()
    {
        $data['arrTipo'] = $this->Item_model->arr_options('category_id = 431');
        $data['arrCategoria'] = $this->Item_model->arr_options('category_id = 432');
        $data['arrDependencia'] = $this->Item_model->arr_options('category_id = 215');
        $data['arrFase'] = $this->Item_model->arr_options('category_id = 433');

        //Variables generales
            $data['head_title'] = 'Nuevo laboratorio';
            $data['page_title'] = 'Nuevo laboratorio';
            $data['nav_2'] = $this->views_folder . 'menus/explorar_v';
            $data['view_a'] = $this->views_folder . 'add/add_v';

        $this->App_model->view(TPL_FRONT, $data);
    }

// EDICIÓN LABORATORIOS
//-----------------------------------------------------------------------------

    /**
     * Formulario de edición de los laboratorios de Barrios Vivos
     * 2025-04-17
     */
    function edit($laboratorioId, $section = 'basic')
    {
        $data = $this->Barrios_vivos_model->basic($laboratorioId);

        $data['page_title'] = "BV {$laboratorioId}) {$data['row']->nombre_corto}";

        $data['arrTipo'] = $this->Item_model->arr_options('category_id = 431');
        $data['arrCategoria'] = $this->Item_model->arr_options('category_id = 432');
        $data['arrDependencia'] = $this->Item_model->arr_options('category_id = 215');
        $data['arrFase'] = $this->Item_model->arr_options('category_id = 433');
        $data['arrLocalidad'] = $this->Item_model->arr_options('category_id = 121');

        //Barrios
        $this->db->select('*');
        $this->db->where('key_capa', 'barrios_planeacion_2023');
        $this->db->order_by('nombre', 'ASC');
        $barrios = $this->db->get('gf_territorios');
        $data['arrBarrios'] = $barrios->result();
        
        $data['back_link'] = $this->url_controller . 'explorar';
        $data['view_a'] = $this->views_folder . "edit/{$section}_v";
        $data['nav_2'] = $this->views_folder . 'menus/laboratorio_v';

        $this->App_model->view(TPL_FRONT, $data);
    }

// DETALLES DE LOS LABORATORIOS
//-----------------------------------------------------------------------------

    function actividades($laboratorioId)
    {
        $data = $this->Barrios_vivos_model->basic($laboratorioId);
        $data['view_a'] = $this->views_folder . 'actividades/actividades_v';
        $data['nav_2'] = $this->views_folder . 'menus/laboratorio_v';
        $data['back_link'] = $this->url_controller . 'explorar';

        $data['arrFase'] = $this->Item_model->arr_options('category_id = 433');

        $this->App_model->view(TPL_FRONT, $data);
    }
}