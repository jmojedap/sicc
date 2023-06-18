<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cuidado extends CI_Controller{

// Variables generales
//-----------------------------------------------------------------------------
    public $views_folder = 'admin/cuidado/actividades/';
    public $url_controller = URL_ADMIN . 'cuidado/';

// Constructor
//-----------------------------------------------------------------------------
    
    function __construct() 
    {
        parent::__construct();
        $this->load->model('Cuidado_model');
        date_default_timezone_set("America/Bogota");    //Para definir hora local
    }
    
    function index($medicion_id = NULL)
    {
        if ( is_null($medicion_id) ) {
            redirect("admin/cuidado/explore/");
        } else {
            redirect("admin/cuidado/info/{$medicion_id}");
        }
    }

//EXPLORE FUNCTIONS
//---------------------------------------------------------------------------------------------------

    /** 
    * Exploración de Posts
    * 2022-08-23
    * */
    function explore($num_page = 1)
    {
        //Identificar filtros de búsqueda
            $this->load->model('Search_model');
            $filters = $this->Search_model->filters();

        //Datos básicos de la exploración
            $data = $this->Cuidado_model->explore_data($filters, $num_page);
        
        //Opciones de filtros de búsqueda
            $data['arrLocalidad'] = $this->Item_model->arr_options('category_id = 121');
            $start = $this->pml->date_add_months(date('Y-m-d'), -11);
            $end = $this->pml->date_add_months(date('Y-m-d'),12);
            $data['arrMonth'] = $this->App_model->arr_periods("type_id = 7 AND start >= '{$start}' AND end <= '{$end}'");
            
        //Cargar vista
            $this->App_model->view(TPL_ADMIN, $data);
    }

    /**
     * Listado de actividades, filtrados por búsqueda, JSON
     * 2022-08-23
     */
    function get($num_page = 1, $per_page = 10)
    {
        if ( $per_page > 250 ) $per_page = 250;

        $this->load->model('Search_model');
        $filters = $this->Search_model->filters();

        $data = $this->Cuidado_model->get($filters, $num_page, $per_page);
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }
    
    /**
     * AJAX JSON
     * Eliminar un conjunto de actividades seleccionadas
     */
    function delete_selected()
    {
        $selected = explode(',', $this->input->post('selected'));
        $data['qty_deleted'] = 0;
        
        foreach ( $selected as $row_id ) 
        {
            $data['qty_deleted'] += $this->Cuidado_model->delete($row_id);
        }

        //Establecer resultado
        if ( $data['qty_deleted'] > 0 ) { $data['status'] = 1; }
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    /**
     * Exportar resultados de búsqueda
     * 2022-08-17
     */
    function export()
    {
        set_time_limit(120);    //120 segundos, 2 minutos para el proceso

        //Identificar filtros y búsqueda
        $this->load->model('Search_model');
        $filters = $this->Search_model->filters();

        $data['query'] = $this->Cuidado_model->query_export($filters);

        if ( $data['query']->num_rows() > 0 ) {
            //Preparar datos
                $data['sheet_name'] = 'actividades_ehc';

            //Objeto para generar archivo excel
                $this->load->library('Excel');
                $file_data['obj_writer'] = $this->excel->file_query($data);

            //Nombre de archivo
                $file_data['file_name'] = date('Ymd_His') . '_' . $data['sheet_name'];
            $this->load->view('common/download_excel_file_v', $file_data);
        } else {
            $data = array('message' => 'No se encontraron registros para exportar');
            //Salida JSON
            $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }
    }

// INFORMACÍON LECTURA Y APERTURA
//-----------------------------------------------------------------------------

    /**
     * Información general del post
     */
    function info($actividad_id)
    {        
        //Datos básicos
        $data = $this->Cuidado_model->basic($actividad_id);
        $data['back_link'] = $this->url_controller . 'explore';
        $data['view_a'] = $this->views_folder . 'info_v';

        $this->App_model->view(TPL_ADMIN, $data);
    }

    /**
     * Información detallada del post valores en la base de datos
     * 2020-08-18
     */
    function details($actividad_id)
    {        
        //Datos básicos
        $data = $this->Cuidado_model->basic($actividad_id);
        $data['back_link'] = $this->url_controller . 'explore';
        $data['view_a'] = 'common/row_details_v';

        $this->App_model->view(TPL_ADMIN, $data);
    }


// CREACIÓN DE UNA ACTIVIDAD DE LA ESCUELA
//-----------------------------------------------------------------------------

    /**
     * Vista Formulario para la creación de una actividad de la escuela
     */
    function add()
    {
        $data['arrModalidad'] = $this->Item_model->arr_options('category_id = 510');
        $data['arrTipo'] = $this->Item_model->arr_options('category_id = 512');
        $data['arrLocalidad'] = $this->Item_model->arr_options('category_id = 121');
        $data['arrSiNoNa'] = $this->Item_model->arr_options('category_id = 55');

        //Variables generales
            $data['head_title'] = 'Nueva actividad';
            $data['nav_2'] = $this->views_folder . 'explore/menu_v';
            $data['view_a'] = $this->views_folder . 'add/add_v';

        $this->App_model->view(TPL_ADMIN, $data);
    }

    /**
     * AJAX JSON
     * Crear o actualizar el registro de un post
     */
    function save()
    {
        $data = $this->Cuidado_model->save();
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

// EDICIÓN Y ACTUALIZACIÓN
//-----------------------------------------------------------------------------

    /**
     * Formulario para la edición de los datos de una actividad.
     * 2022-11-13
     */
    function edit($actividad_id)
    {
        //Datos básicos
        $data = $this->Cuidado_model->basic($actividad_id);

        $data['arrModalidad'] = $this->Item_model->arr_options('category_id = 510');
        $data['arrTipo'] = $this->Item_model->arr_options('category_id = 512');
        $data['arrLocalidad'] = $this->Item_model->arr_options('category_id = 121');
        $data['arrSiNoNa'] = $this->Item_model->arr_options('category_id = 55');
        
        //Array data espefícicas
            $data['back_link'] = $this->url_controller . 'explore';
            $data['view_a'] = $this->views_folder . 'edit/edit_v';
        
        $this->App_model->view(TPL_ADMIN, $data);
    }

    /**
     * Geovisor y geolocalizador de la actividad
     * 2022-11-19
     */
    function location($actividad_id)
    {
        $data = $this->Cuidado_model->basic($actividad_id);
        $data['back_link'] = $this->url_controller . 'explore';
        $data['view_a'] = $this->views_folder . 'location/location_v';        
        $this->App_model->view(TPL_ADMIN, $data);
    }

// MAPA
//-----------------------------------------------------------------------------

    function mapa()
    {
        $data['head_title'] = 'Mapa de actividades';
        $data['view_a'] = $this->views_folder . 'mapa/mapa_v_arcgis';
        $data['nav_2'] = $this->views_folder . 'explore/menu_v';
        
        $this->load->model('Search_model');
        $filters = $this->Search_model->filters();
        $data['actividades'] = $this->Cuidado_model->get($filters,1,500);

        $this->App_model->view(TPL_ADMIN, $data);
    }

// Exportar datos
//-----------------------------------------------------------------------------

    function export_panel()
    {
        $data['head_title'] = 'Exportar datos';
        $data['view_a'] = 'admin/cuidado/export/panel_v';
        $data['nav_2'] = 'admin/cuidado/actividades/explore/menu_v';
        $this->App_model->view(TPL_ADMIN, $data);
    }

    
    /**
     * Exportar estudiantes
     * 2021-09-27
     */
    function export_students()
    {
        set_time_limit(120);    //120 segundos, 2 minutos para el proceso

        //Identificar filtros y búsqueda
        $this->load->model('Search_model');
        $filters = $this->Search_model->filters();
        $filters['role'] = '22';
        $filters['sf'] = 'cuidado_estudiantes';

        $this->load->model('User_model');
        $data['query'] = $this->User_model->query_export($filters);

        if ( $data['query']->num_rows() > 0 ) {
            //Preparar datos
                $data['sheet_name'] = 'cuidado_estudiantes';

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
     * Exportar listado de manzanas de cuidado
     * 2022-11-10
     */
    function export_manzanas()
    {
        set_time_limit(120);    //120 segundos, 2 minutos para el proceso

        //Identificar filtros y búsqueda
        $condition = $this->input->post('condition');

        $data['query'] = $this->Cuidado_model->query_export_manzanas($condition);

        if ( $data['query']->num_rows() > 0 ) {
            //Preparar datos
                $data['sheet_name'] = "manzanas_cuidado";

            //Objeto para generar archivo excel
                $this->load->library('Excel');
                $file_data['obj_writer'] = $this->excel->file_query($data);

            //Nombre de archivo
                $file_data['file_name'] = date('Ymd_His') . '_' . $data['sheet_name'];

            $this->load->view('common/download_excel_file_v', $file_data);
        } else {
            $data = array('message' => 'No se encontraron registros para exportar');
            //Salida JSON
            $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }
    }

    /**
     * Exportar asistencia de usuarios a sesiones, tabla users_meta
     * 2022-11-10
     */
    function export_asistencia()
    {
        set_time_limit(120);    //120 segundos, 2 minutos para el proceso

        //Identificar filtros y búsqueda
        $condition = $this->input->post('condition');

        $data['query'] = $this->Cuidado_model->query_export_asistencia($condition);

        if ( $data['query']->num_rows() > 0 ) {
            //Preparar datos
                $data['sheet_name'] = "actividades_asistentes";

            //Objeto para generar archivo excel
                $this->load->library('Excel');
                $file_data['obj_writer'] = $this->excel->file_query($data);

            //Nombre de archivo
                $file_data['file_name'] = date('Ymd_His') . '_' . $data['sheet_name'];

            $this->load->view('common/download_excel_file_v', $file_data);
        } else {
            $data = array('message' => 'No se encontraron registros para exportar');
            //Salida JSON
            $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }
    }

    /**
     * Exportar asistencia de usuarios a sesiones, tabla users_meta
     * 2022-11-10
     */
    function export_actividades_sesiones()
    {
        set_time_limit(120);    //120 segundos, 2 minutos para el proceso

        //Identificar filtros y búsqueda
        $condition = $this->input->post('condition');

        $data['query'] = $this->Cuidado_model->query_export_actividades_sesiones($condition);

        if ( $data['query']->num_rows() > 0 ) {
            //Preparar datos
                $data['sheet_name'] = "actividades_sesiones";

            //Objeto para generar archivo excel
                $this->load->library('Excel');
                $file_data['obj_writer'] = $this->excel->file_query($data);

            //Nombre de archivo
                $file_data['file_name'] = date('Ymd_His') . '_' . $data['sheet_name'];

            $this->load->view('common/download_excel_file_v', $file_data);
        } else {
            $data = array('message' => 'No se encontraron registros para exportar');
            //Salida JSON
            $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }
    }

// Detalles de actividades
//-----------------------------------------------------------------------------

    /**
     * Formulario para registrar y editar el listao de asistentes a una sesión
     * de la escuela
     */
    function actividad_asistentes($actividad_id)
    {
        $this->load->model('Cuidado_model');
        $data = $this->Cuidado_model->basic($actividad_id);

        $data['nav_2'] = 'admin/cuidado/actividades/menu_v';
        $data['view_a'] = $this->views_folder . 'asistentes_v';
        $data['back_link'] = URL_ADMIN . 'cuidado/explore/';

        $this->App_model->view(TPL_ADMIN, $data);
    }

    /**
     * Formulario para registrar y editar el listao de sesiones realizadas en
     * las actividades
     * 2022-11-13
     */
    function actividad_sesiones($actividad_id)
    {
        $this->load->model('Cuidado_model');
        $data = $this->Cuidado_model->basic($actividad_id);

        $data['arrModulo'] = $this->Item_model->arr_options('category_id = 515');

        $data['nav_2'] = 'admin/cuidado/actividades/menu_v';
        $data['view_a'] = $this->views_folder . 'sesiones_v';
        $data['back_link'] = URL_ADMIN . 'cuidado/explore/';

        $this->App_model->view(TPL_ADMIN, $data);
    }

// Sobre usuarios
//-----------------------------------------------------------------------------

    /**
     * Vista formulario para gregar personas del hogar de un estudiante
     * inscrito en la escuela.
     * 2022-11-05
     */
    function user_home_persons($user_id)
    {
        $this->load->model('User_model');
        $data = $this->User_model->basic($user_id);

        $data['arrSiNo'] = $this->Item_model->arr_options('category_id = 55 AND cod <= 1');

        $data['view_a'] = $this->views_folder . 'users/persons_v';

        $this->App_model->view(TPL_ADMIN, $data);
    }
}