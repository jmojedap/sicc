<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mediciones extends CI_Controller{

// Variables generales
//-----------------------------------------------------------------------------
    public $views_folder = 'admin/mediciones/mediciones/';
    public $url_controller = URL_ADMIN . 'mediciones/';

// Constructor
//-----------------------------------------------------------------------------
    
    function __construct() 
    {
        parent::__construct();
        $this->load->model('Medicion_model');
        date_default_timezone_set("America/Bogota");    //Para definir hora local
    }
    
    function index($medicion_id = NULL)
    {
        if ( is_null($medicion_id) ) {
            redirect("admin/mediciones/explore/");
        } else {
            redirect("admin/mediciones/info/{$medicion_id}");
        }
    }
    
//EXPLORE FUNCTIONS
//---------------------------------------------------------------------------------------------------

    /**
     * Exploración de mediciones
     * 2022-06-02
     */
    function explore($num_page = 1, $per_page = 30)
    {
        //Identificar filtros de búsqueda
        $this->load->model('Search_model');
        $filters = $this->Search_model->filters();

        //Datos básicos de la exploración
            $data = $this->Medicion_model->explore_data($filters, $num_page, $per_page);
            
        //Arrays con valores para contenido en lista
            $data['arrType'] = $this->Item_model->arr_options('category_id = 143');
            $data['arrUnidadObservacion'] = $this->Item_model->arr_options('category_id = 144');
            
        //Cargar vista
            $this->App_model->view(TPL_ADMIN, $data);
    }

    /**
     * Listado de mediciones, filtrados por búsqueda, JSON
     */
    function get($num_page = 1, $per_page = 30)
    {
        $this->load->model('Search_model');
        $filters = $this->Search_model->filters();

        $data = $this->Medicion_model->get($filters, $num_page, $per_page);
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }
    
    /**
     * AJAX JSON
     * Eliminar un conjunto de mediciones seleccionados
     */
    function delete_selected()
    {
        $selected = explode(',', $this->input->post('selected'));
        $data['qty_deleted'] = 0;
        
        foreach ( $selected as $row_id ) 
        {
            $data['qty_deleted'] += $this->Medicion_model->delete($row_id);
        }

        //Establecer resultado
        if ( $data['qty_deleted'] > 0 ) { $data['status'] = 1; }
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    
    /**
     * Exportar resultados de búsqueda
     * 2022-04-14
     */
    function export()
    {
        set_time_limit(120);    //120 segundos, 2 minutos para el proceso

        //Identificar filtros y búsqueda
        $this->load->model('Search_model');
        $filters = $this->Search_model->filters();

        $data['query'] = $this->Medicion_model->query_export($filters);

        if ( $data['query']->num_rows() > 0 ) {
            //Preparar datos
                $data['sheet_name'] = 'mediciones';

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
     * Abrir o redireccionar a la vista pública de un post
     */
    function open($medicion_id, $meta_id = 0)
    {
        $row = $this->Db_model->row_id('med_medicion', $medicion_id);
        $row_meta = $this->Db_model->row_id('users_meta', $meta_id); //Registro de asignación
        $destination = "admin/mediciones/read/{$medicion_id}";

        if ( $row->type_id == 2 ) $destination = "app/mediciones/ver/{$row->id}/{$row->slug}";
        
        redirect($destination);
    }

    /**
     * Mostrar post en vista lectura
     */
    function read($medicion_id)
    {
        //Datos básicos
        $data = $this->Medicion_model->basic($medicion_id);
        unset($data['nav_2']);
        $data['view_a'] = $this->Medicion_model->type_folder($data['row']) . 'read_v';

        $this->App_model->view(TPL_ADMIN, $data);
    }

    /**
     * Información general del post
     */
    function info($medicion_id)
    {        
        //Datos básicos
        $data = $this->Medicion_model->basic($medicion_id);
        $data['back_link'] = $this->url_controller . 'explore';
        $data['view_a'] = $this->views_folder . 'info_v';

        $this->App_model->view(TPL_ADMIN, $data);
    }

    /**
     * Información detallada del post desde la perspectiva de base de datos
     * 2020-08-18
     */
    function details($medicion_id)
    {        
        //Datos básicos
        $data = $this->Medicion_model->basic($medicion_id);
        $data['back_link'] = $this->url_controller . 'explore';
        $data['view_a'] = 'common/row_details_v';

        $this->App_model->view(TPL_ADMIN, $data);
    }

    function get_info($medicion_id)
    {
        $data = $this->Medicion_model->basic($medicion_id);
        //Salida JSON
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

// CREACIÓN DE UNA MEDICIÓN
//-----------------------------------------------------------------------------

    /**
     * Vista Formulario para la creación de una nueva medición
     * 2023-02-09
     */
    function add()
    {
        //Configuración del formulario
        $data['formDestination'] = $this->url_controller . 'save';
        $data['editionLink'] = $this->url_controller . 'edit/';
        
        //Campos a editar
        $data['fields'] = $this->db->field_data('med_medicion');
        $data['hiddenFields'] = ['id', 'created_at', 'updated_at', 'updater_id', 'creator_id'];

        //Configuración de campos
        $data['requiredFields'] = ['codigo', 'nombre_medicion', 'descripcion'];

        $data['head_title'] = 'Crear medición';
        $data['nav_2'] = $this->views_folder . 'explore/menu_v';
        $data['back_link'] = $this->url_controller . 'explore';
        $data['view_a'] = 'common/forms/add_1';
        
        $this->App_model->view(TPL_ADMIN, $data);
    }

    /**
     * AJAX JSON
     * Crear o actualizar un registro de una medición
     * 2022-08-16
     */
    function save()
    {
        $data = $this->Medicion_model->save();
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }
    
    
// EDICIÓN Y ACTUALIZACIÓN
//-----------------------------------------------------------------------------

    /**
     * Formulario para la edición de los datos de una medición.
     * 2022-11-12
     */
    function edit($medicion_id)
    {
        //Datos básicos
        $data = $this->Medicion_model->basic($medicion_id);

        $data['arrTipo'] = $this->Item_model->arr_options('category_id = 143');
        $data['arrUnidadObservacion'] = $this->Item_model->arr_options('category_id = 144');
        $data['arrMetodologia'] = $this->Item_model->arr_options('category_id = 145');
        $data['arrCodigoEstrategia'] = $this->Item_model->arr_options('category_id = 221');
        $data['arrTema'] = $this->Item_model->arr_options('category_id = 415');
        $data['arrSubtema'] = $this->Item_model->arr_options('category_id = 416');
        $data['arr'] = $this->Item_model->arr_options('category_id = 416');
        
        //Array data espefícicasD
            $data['back_link'] = $this->url_controller . 'explore';
            $data['view_a'] = $this->views_folder . 'edit_v';
        
        $this->App_model->view(TPL_ADMIN, $data);
    }

    /**
     * Formulario para la edición detallada de un registro en la tabla
     * repo_contenidos
     * 2023-02-07
     */
    function edit_details($medicion_id)
    {
        //Datos básicos
        $data = $this->Medicion_model->basic($medicion_id);
        
        //Configuración del formulario
        $data['formDestination'] = URL_API . 'mediciones/save';
        
        //Campos a editar
        $data['fields'] = $this->db->field_data('med_medicion');
        $data['hiddenFields'] = ['id', 'created_at', 'updated_at', 'updater_id', 'creator_id'];

        //Configuración de campos
        $data['requiredFields'] = ['codigo', 'nombre_medicion', 'descripcion', 'estado'];

        $data['back_link'] = $this->url_controller . 'explore';
        $data['view_a'] = 'common/forms/edit_1';
        
        $this->App_model->view(TPL_ADMIN, $data);
    }
}