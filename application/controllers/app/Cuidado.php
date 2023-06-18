<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cuidado extends CI_Controller {
        
// Variables generales
//-----------------------------------------------------------------------------
    public $views_folder = 'app/mecc/cuidado/';
    public $url_controller = URL_APP . 'cuidado/';

// Constructor
//-----------------------------------------------------------------------------
    
    function __construct()
    {
        parent::__construct();

        $this->load->model('Accion_model');
        
        //Local time set
        date_default_timezone_set("America/Bogota");
    }

    /**
     * Primera función
     */
    function index()
    {
        $this->inicio();
    }

// EXPLORACIÓN
//-----------------------------------------------------------------------------

    /** Exploración de acciones */
    function explorar($num_page = 1)
    {
        //Identificar filtros de búsqueda
        $this->load->model('Search_model');
        $filters = $this->Search_model->filters();
        $filters['sf'] = 'general';  //Select format

        //Datos básicos de la exploración
            $data = $this->Accion_model->explore_data($filters, $num_page, 60);
            $data['cf'] = 'acciones/explorar/';
            $data['controller'] = 'acciones/';
            $data['views_folder'] = $this->views_folder . 'explorar/';      //Carpeta donde están las vistas de exploración
            $data['head_title'] = 'Acciones CC';
            $data['view_a'] = $data['views_folder'] . 'explore_v';
            $data['nav_2'] = $data['views_folder'] . 'menu_v';
        
        //Opciones de filtros de búsqueda
            $data['arrEstrategia'] = $this->Item_model->arr_options('category_id = 221');
            $data['arrLineaEstrategica'] = $this->Item_model->arr_options('category_id = 222');
            
        //Cargar vista
            $this->App_model->view('templates/easypml/main', $data);
    }

    /**
     * Listado de acciones, filtrados por búsqueda, JSON
     */
    function get($num_page = 1, $per_page = 60)
    {
        $this->load->model('Search_model');
        $filters = $this->Search_model->filters();
        //$filters['sf'] = 'general';  //Select format

        $data = $this->Accion_model->get($filters, $num_page, $per_page);
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
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

        $data['query'] = $this->Accion_model->query_export($filters);

        if ( $data['query']->num_rows() > 0 ) {
            //Preparar datos
                $data['sheet_name'] = 'acciones';

            //Objeto para generar archivo excel
                $this->load->library('Excel');
                $file_data['obj_writer'] = $this->excel->file_query($data);

            //Nombre de archivo
                $file_data['file_name'] = date('Ymd_His') . '_' . $data['sheet_name'];

            $this->load->view('common/download_excel_file_v', $file_data);
        } else {
            $data = array('message' => 'No se encontraron acciones para exportar');
            //Salida JSON
            $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }
    }

// INFORMACIÓN
//-----------------------------------------------------------------------------

    function info($accion_id)
    {
        $data = $this->Accion_model->basic($accion_id);
        $data['view_a'] = $this->views_folder . 'info_v';
        $data['back_link'] = $this->url_controller . 'explorar';

        $this->App_model->view(TPL_FRONT, $data);
    }

// CREACIÓN DE UNA ACCIÓN
//-----------------------------------------------------------------------------

    /**
     * Vista Formulario para la creación de una nueva acción de cultura
     * ciudadana
     * 2022-09-13
     */
    function add()
    {
        $data['arrEstrategia'] = $this->Item_model->arr_options('category_id = 221');
        $data['arrLineaEstrategica'] = $this->Item_model->arr_options('category_id = 222');
        $data['arrCumplimientoObjetivo'] = $this->Item_model->arr_options('category_id = 236');

        //Variables generales
            $data['head_title'] = 'Acciones CC';
            $data['nav_2'] = $this->views_folder . 'explorar/menu_v';
            $data['view_a'] = $this->views_folder . 'add/add_v';

        $this->App_model->view(TPL_FRONT, $data);
    }

    /**
     * AJAX JSON
     * Crear o actualizar el registro de una acción CC
     * 2022-09-03
     */
    function save()
    {
        $data = $this->Accion_model->save();
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

// EDICIÓN ACCIONES
//-----------------------------------------------------------------------------

    function edit($accion_id, $section = 'basic')
    {
        $data = $this->Accion_model->basic($accion_id);

        $data['arrEstrategia'] = $this->Item_model->arr_options('category_id = 221');
        $data['arrLineaEstrategica'] = $this->Item_model->arr_options('category_id = 222');
        $data['arrCumplimientoObjetivo'] = $this->Item_model->arr_options('category_id = 236');
        $data['arrLocalidad'] = $this->Item_model->arr_options('category_id = 121');
        $data['arrMeta'] = $this->Item_model->arr_options('category_id = 218');

        /*$data['head_title'] = '';
        $data['nav_2'] = '';*/
        $data['back_link'] = $this->url_controller . 'explorar';
        $data['view_a'] = $this->views_folder . "edit/{$section}_v";
        $data['nav_3'] = $this->views_folder . 'edit/menu_v';

        $this->App_model->view(TPL_FRONT, $data);
    }
}