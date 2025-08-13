<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Equipamientos extends CI_Controller {
        
// Variables generales
//-----------------------------------------------------------------------------
    public $views_folder = 'app/equipamientos/';
    public $url_controller = URL_APP . 'equipamientos/';

// Constructor
//-----------------------------------------------------------------------------
    
    function __construct()
    {
        parent::__construct();

        $this->load->model('Equipamiento_model');
        
        //Local time set
        date_default_timezone_set("America/Bogota");
    }

    /**
     * Primera función
     */
    function index($equipamientoId = 0)
    {
        if ( $equipamientoId > 0 ) {
            if ( $this->session->userdata('user_id') > 0 ) {
                redirect("app/equipamientos/asistentes/{$equipamientoId}");
            } else {
                redirect("app/equipamientos/info/{$equipamientoId}");
            }
        } else {
            redirect("app/equipamientos/explorar");
        }
    }

// EXPLORACIÓN
//-----------------------------------------------------------------------------

    /** Exploración de equipamientos */
    function explorar($num_page = 1)
    {
        //Identificar filtros de búsqueda
        $this->load->model('Search_model');
        $filters = $this->Search_model->filters();
        $filters['sf'] = 'general';  //Select format
        if ( is_null($filters['y']) ) $filters['y'] = 2025;

        //Datos básicos de la exploración
            $data = $this->Equipamiento_model->explore_data($filters, $num_page, 100);
            $data['cf'] = 'equipamientos/explorar/';
            $data['controller'] = 'equipamientos/';
            $data['views_folder'] = $this->views_folder . 'explorar/';      //Carpeta donde están las vistas de exploración
            $data['head_title'] = 'Equipamientos';
            $data['view_a'] = $data['views_folder'] . 'explore_v';
            $data['nav_2'] = $this->views_folder . 'menus/explorar_v';
        
        //Opciones de filtros de búsqueda
            $data['arrCategoriaEq'] = $this->Item_model->arr_options('category_id = 440');
            $data['arrTipoEq'] = $this->Item_model->arr_options('category_id = 441');
            
        //Cargar vista
            $this->App_model->view(TPL_FRONT, $data);
    }

    /**
     * Exploración de Barrios Vivos
     * 2025-04-17
     */
    function equipamientos()
    {
        //Identificar filtros de búsqueda
        $this->load->model('Search_model');
        $filters = $this->Search_model->filters();

        $data = $this->Equipamiento_model->get($filters, 1, 1000);
        $data['equipamientos'] = $data['list'];

        $data['head_title'] = 'Equipamientos';
        $data['page_title'] = 'Equipamientos';

        $data['view_a'] = $this->views_folder . "equipamientos/equipamientos_v";
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

        $data['query'] = $this->Equipamiento_model->query_export($filters);

        if ( $data['query']->num_rows() > 0 ) {
            //Preparar datos
                $data['sheet_name'] = 'equipamientos';

            //Objeto para generar archivo excel
                $this->load->library('Excel');
                $file_data['obj_writer'] = $this->excel->file_query($data);

            //Nombre de archivo
                $file_data['file_name'] = date('Ymd_His') . '_' . $data['sheet_name'];

            $this->load->view('common/download_excel_file_v', $file_data);
        } else {
            $data = array('message' => 'No se encontraron equipamientos para exportar');
            //Salida JSON
            $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }
    }

// INFORMACIÓN
//-----------------------------------------------------------------------------

    function info($equipamientoId)
    {
        $data = $this->Equipamiento_model->basic($equipamientoId);
        $data['nav_2'] = $this->views_folder . 'menus/equipamiento_v';
        $data['view_a'] = 'common/row_details_v';
        $data['back_link'] = $this->url_controller . 'explorar';

        $this->App_model->view(TPL_FRONT, $data);
    }

// CREACIÓN DE UN EQUIPAMIENTO
//-----------------------------------------------------------------------------

    /**
     * Vista formulario para la creación de una nueva equipamiento 
     * 2025-04-16
     */
    function add()
    {
        $data['arrTipo'] = $this->Item_model->arr_options('category_id = 431');
        $data['arrCategoria'] = $this->Item_model->arr_options('category_id = 432');
        $data['arrDependencia'] = $this->Item_model->arr_options('category_id = 215');
        $data['arrFase'] = $this->Item_model->arr_options('category_id = 433');

        //Variables generales
            $data['head_title'] = 'Nuevo equipamiento';
            $data['page_title'] = 'Nuevo equipamiento';
            $data['nav_2'] = $this->views_folder . 'menus/explorar_v';
            $data['view_a'] = $this->views_folder . 'add/add_v';

        $this->App_model->view(TPL_FRONT, $data);
    }

// EDICIÓN EQUIPAMIENTOS
//-----------------------------------------------------------------------------

    /**
     * Formulario de edición de los equipamientos de Barrios Vivos
     * 2025-04-17
     */
    function edit($equipamientoId, $section = 'basic')
    {
        $data = $this->Equipamiento_model->basic($equipamientoId);

        $data['page_title'] = "BV {$equipamientoId}) {$data['row']->nombre_corto}";

        $data['arrTipo'] = $this->Item_model->arr_options('category_id = 431');
        $data['arrCategoria'] = $this->Item_model->arr_options('category_id = 432');
        $data['arrDependencia'] = $this->Item_model->arr_options('category_id = 215');
        $data['arrFase'] = $this->Item_model->arr_options('category_id = 433');
        $data['arrLocalidad'] = $this->Item_model->arr_options('category_id = 121');
        $data['arrEstadoRegistro'] = $this->Item_model->arr_options('category_id = 435');

        //Barrios
        $this->db->select('*');
        $this->db->where('key_capa', 'barrios_planeacion_2023');
        $this->db->order_by('nombre', 'ASC');
        $barrios = $this->db->get('gf_territorios');
        $data['arrBarrios'] = $barrios->result();
        
        $data['back_link'] = $this->url_controller . 'explorar';
        $data['view_a'] = $this->views_folder . "edit/{$section}_v";
        $data['nav_2'] = $this->views_folder . 'menus/equipamiento_v';

        $this->App_model->view(TPL_FRONT, $data);
    }

// DETALLES DE LOS EQUIPAMIENTOS
//-----------------------------------------------------------------------------
}