<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Preguntas extends CI_Controller{

// Variables generales
//-----------------------------------------------------------------------------
    public $views_folder = 'admin/mediciones/preguntas/';
    public $url_controller = URL_ADMIN . 'preguntas/';

// Constructor
//-----------------------------------------------------------------------------
    
    function __construct() 
    {
        parent::__construct();
        $this->load->model('Pregunta_model');
        date_default_timezone_set("America/Bogota");    //Para definir hora local
    }
    
    function index($pregunta_id = NULL)
    {
        if ( is_null($pregunta_id) ) {
            redirect("admin/preguntas/explore/");
        } else {
            redirect("admin/preguntas/info/{$pregunta_id}");
        }
    }
    
// EXPLORACIÓN
//---------------------------------------------------------------------------------------------------

    /** Exploración de preguntas */
    function explore($num_page = 1)
    {
        //Identificar filtros de búsqueda
        $this->load->model('Search_model');
        $filters = $this->Search_model->filters();

        //Datos básicos de la exploración
            $data = $this->Pregunta_model->explore_data($filters, $num_page, 10);
        
        //Opciones de filtros de búsqueda
            $data['arrRoles'] = $this->Item_model->arr_options('category_id = 155');
            
        //Cargar vista
            $this->App_model->view(TPL_ADMIN, $data);
    }

    /**
     * JSON
     * Listado de preguntas, filtrados por búsqueda
     */
    function get($num_page = 1, $per_page = 10)
    {
        $this->load->model('Search_model');
        $filters = $this->Search_model->filters();

        $data = $this->Pregunta_model->get($filters, $num_page, $per_page);
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }
    
    /**
     * AJAX JSON
     * Eliminar un conjunto de preguntas seleccionados
     */
    function delete_selected()
    {
        $selected = explode(',', $this->input->post('selected'));
        $data['qty_deleted'] = 0;
        
        foreach ( $selected as $row_id ) 
        {
            $data['qty_deleted'] += $this->Pregunta_model->delete($row_id);
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
    function open($pregunta_id, $meta_id = 0)
    {
        $row = $this->Db_model->row_id('med_pregunta', $pregunta_id);
        $row_meta = $this->Db_model->row_id('users_meta', $meta_id); //Registro de asignación
        $destination = "admin/preguntas/read/{$pregunta_id}";

        if ( $row->type_id == 2 ) $destination = "app/preguntas/ver/{$row->id}/{$row->slug}";
        
        redirect($destination);
    }

    /**
     * Mostrar post en vista lectura
     */
    function read($pregunta_id)
    {
        //Datos básicos
        $data = $this->Pregunta_model->basic($pregunta_id);
        unset($data['nav_2']);
        $data['view_a'] = $this->Pregunta_model->type_folder($data['row']) . 'read_v';

        $this->App_model->view(TPL_ADMIN, $data);
    }

    /**
     * Información general de la pregunta
     * 2022-06-17
     */
    function info($pregunta_id)
    {        
        //Datos básicos
        $data = $this->Pregunta_model->basic($pregunta_id);
        $data['explode_codigo'] = $this->Pregunta_model->explode_codigo($data['row']->codigo);
        $data['opciones'] = $this->Pregunta_model->opciones($pregunta_id);
        $data['back_link'] = $this->url_controller . 'explore';
        $data['view_a'] = $this->views_folder . 'info_v';

        $this->App_model->view(TPL_ADMIN, $data);
    }

    /**
     * Información detallada de la pregunta desde la perspectiva de base de datos
     * 2020-08-18
     */
    function details($pregunta_id)
    {        
        //Datos básicos
        $data = $this->Pregunta_model->basic($pregunta_id);
        $data['back_link'] = $this->url_controller . 'explore';
        $data['view_a'] = 'common/row_details_v';

        $this->App_model->view(TPL_ADMIN, $data);
    }

    function get_info($pregunta_id)
    {
        $data = $this->Pregunta_model->basic($pregunta_id);
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
        $data['fields'] = $this->db->field_data('med_pregunta');
        $data['hiddenFields'] = ['id', 'created_at', 'updated_at', 'updater_id', 'creator_id'];

        //Configuración de campos
        $data['requiredFields'] = ['medicion_id', 'indice_pregunta', 'enunciado_1',
            'nombre', 'rol', 'tipo'];

        $data['head_title'] = 'Nueva pregunta';
        $data['nav_2'] = $this->views_folder . 'explore/menu_v';
        $data['view_a'] = 'common/forms/add_1';
        
        $this->App_model->view(TPL_ADMIN, $data);
    }

    function save()
    {
        $data = $this->Pregunta_model->save();
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }
    
    
// EDICIÓN Y ACTUALIZACIÓN
//-----------------------------------------------------------------------------

    /**
     * Formulario para la edición de los datos de un user. Los datos que se
     * editan dependen de la $section elegida.
     */
    function edit($pregunta_id)
    {
        //Datos básicos
        $data = $this->Pregunta_model->basic($pregunta_id);
        
        //Configuración del formulario
        $data['formDestination'] = URL_API . 'preguntas/save';
        
        //Campos a editar
        $data['fields'] = $this->db->field_data('med_pregunta');
        $data['hiddenFields'] = ['id', 'created_at', 'updated_at', 'updater_id', 'creator_id'];

        //Configuración de campos
        $data['requiredFields'] = ['medicion_id', 'indice_pregunta', 'enunciado_1',
            'nombre', 'rol', 'tipo'];

        $data['back_link'] = $this->url_controller . 'explore';
        $data['view_a'] = 'common/forms/edit_1';
        
        $this->App_model->view(TPL_ADMIN, $data);
    }
}