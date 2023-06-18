<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Variables extends CI_Controller{

// Variables generales
//-----------------------------------------------------------------------------
    public $views_folder = 'admin/mediciones/variables/';
    public $url_controller = URL_ADMIN . 'variables/';

// Constructor
//-----------------------------------------------------------------------------
    
    function __construct() 
    {
        parent::__construct();
        $this->load->model('Variable_model');
        date_default_timezone_set("America/Bogota");    //Para definir hora local
    }
    
    function index($variable_id = NULL)
    {
        if ( is_null($variable_id) ) {
            redirect("admin/variables/explore/");
        } else {
            redirect("admin/variables/info/{$variable_id}");
        }
    }
    
// EXPLORACIÓN
//---------------------------------------------------------------------------------------------------

    /** Exploración de variables */
    function explore($num_page = 1)
    {
        //Identificar filtros de búsqueda
        $this->load->model('Search_model');
        $filters = $this->Search_model->filters();

        //Datos básicos de la exploración
            $data = $this->Variable_model->explore_data($filters, $num_page, 10);
        
        //Opciones de filtros de búsqueda
            $data['arrTypes'] = $this->Item_model->arr_options('category_id = 156');
            
        //Cargar vista
            $this->App_model->view(TPL_ADMIN, $data);
    }

    /**
     * JSON
     * Listado de variables, filtrados por búsqueda
     */
    function get($num_page = 1, $per_page = 10)
    {
        $this->load->model('Search_model');
        $filters = $this->Search_model->filters();

        $data = $this->Variable_model->get($filters, $num_page, $per_page);
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }
    
    /**
     * AJAX JSON
     * Eliminar un conjunto de variables seleccionados
     */
    function delete_selected()
    {
        $selected = explode(',', $this->input->post('selected'));
        $data['qty_deleted'] = 0;
        
        foreach ( $selected as $row_id ) 
        {
            $data['qty_deleted'] += $this->Variable_model->delete($row_id);
        }

        //Establecer resultado
        if ( $data['qty_deleted'] > 0 ) { $data['status'] = 1; }
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }
    
// INFORMACÍON LECTURA Y APERTURA
//-----------------------------------------------------------------------------

    /**
     * Abrir o redireccionar a la vista pública de un post
     */
    function open($variable_id, $meta_id = 0)
    {
        $row = $this->Db_model->row_id('med_variable', $variable_id);
        $row_meta = $this->Db_model->row_id('users_meta', $meta_id); //Registro de asignación
        $destination = "admin/variables/read/{$variable_id}";

        if ( $row->type_id == 2 ) $destination = "app/variables/ver/{$row->id}/{$row->slug}";
        
        redirect($destination);
    }

    /**
     * Mostrar post en vista lectura
     */
    function read($variable_id)
    {
        //Datos básicos
        $data = $this->Variable_model->basic($variable_id);
        unset($data['nav_2']);
        $data['view_a'] = $this->Variable_model->type_folder($data['row']) . 'read_v';

        $this->App_model->view(TPL_ADMIN, $data);
    }

    /**
     * Información general de la variable
     * 2022-06-17
     */
    function info($variable_id)
    {        
        //Datos básicos
        $data = $this->Variable_model->basic($variable_id);
        $data['explode_codigo'] = $this->Variable_model->explode_codigo($data['row']->codigo);
        $data['opciones'] = $this->Variable_model->opciones($variable_id);
        $data['back_link'] = $this->url_controller . 'explore';
        $data['view_a'] = $this->views_folder . 'info_v';

        $this->App_model->view(TPL_ADMIN, $data);
    }

    /**
     * Información detallada de la variable desde la perspectiva de base de datos
     * 2020-08-18
     */
    function details($variable_id)
    {        
        //Datos básicos
        $data = $this->Variable_model->basic($variable_id);
        $data['back_link'] = $this->url_controller . 'explore';
        $data['view_a'] = 'common/row_details_v';

        $this->App_model->view(TPL_ADMIN, $data);
    }

    function get_info($variable_id)
    {
        $data = $this->Variable_model->basic($variable_id);
        //Salida JSON
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

// CREACIÓN DE UNA PREGUNTA
//-----------------------------------------------------------------------------

    /**
     * Vista Formulario para la creación de una nueva pregunga
     * 2023-02-09
     */
    function add()
    {
        //Configuración del formulario
        $data['formDestination'] = $this->url_controller . 'save';
        $data['editionLink'] = $this->url_controller . 'edit/';
        
        //Campos a editar
        $data['fields'] = $this->db->field_data('med_variable');
        $data['hiddenFields'] = ['id', 'created_at', 'updated_at', 'updater_id', 'creator_id'];

        //Configuración de campos
        $data['requiredFields'] = ['medicion_id', 'pregunta_id', 'indice_variable',
            'nombre', 'tipo'];

        $data['head_title'] = 'Nueva variable';
        $data['nav_2'] = $this->views_folder . 'explore/menu_v';
        $data['view_a'] = 'common/forms/add_1';
        
        $this->App_model->view(TPL_ADMIN, $data);
    }

    function save()
    {
        $data = $this->Variable_model->save();
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }
    
    
// EDICIÓN Y ACTUALIZACIÓN
//-----------------------------------------------------------------------------

    /**
     * Formulario para la edición de los datos de una variable.
     * 2023-02-09
     */
    function edit($variable_id)
    {
        //Datos básicos
        $data = $this->Variable_model->basic($variable_id);
        
        //Configuración del formulario
        $data['formDestination'] = URL_API . 'variables/save';
        
        //Campos a editar
        $data['fields'] = $this->db->field_data('med_variable');
        $data['hiddenFields'] = ['id', 'created_at', 'updated_at', 'updater_id', 'creator_id'];

        //Configuración de campos
        $data['requiredFields'] = ['medicion_id', 'indice_pregunta', 'enunciado_1',
            'nombre', 'rol', 'tipo'];

        $data['back_link'] = $this->url_controller . 'explore';
        $data['view_a'] = 'common/forms/edit_1';
        
        $this->App_model->view(TPL_ADMIN, $data);
    }
}