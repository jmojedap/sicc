<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Events extends CI_Controller{

// Variables generales
//-----------------------------------------------------------------------------
    public $views_folder = 'admin/events/';
    public $url_controller = URL_ADMIN . 'events/';

// Constructor
//-----------------------------------------------------------------------------
    
    function __construct() 
    {
        parent::__construct();

        $this->load->model('Event_model');
        
        //Para definir hora local
        date_default_timezone_set("America/Bogota");
    }
    
    function index($user_id)
    {
        redirect('events/explore');
    }
    
//EXPLORE
//---------------------------------------------------------------------------------------------------
            
    /**
     * Exploración y búsqueda de usuarios
     * 2020-08-01
     */
    function explore($num_page = 1)
    {        
        //Identificar filtros de búsqueda
            $this->load->model('Search_model');
            $filters = $this->Search_model->filters();

        //Datos básicos de la exploración
            $data = $this->Event_model->explore_data($filters, $num_page);
        
        //Opciones de filtros de búsqueda
            $data['options_type'] = $this->Item_model->options('category_id = 13', 'Todos');
            
        //Arrays con valores para contenido en lista
            $data['arr_types'] = $this->Item_model->arr_cod('category_id = 13');
            
        //Cargar vista
            $this->App_model->view(TPL_ADMIN, $data);
    }

    /**
     * JSON
     * Listado de users, según filtros de búsqueda
     */
    function get($num_page = 1, $per_page = 10)
    {
        $this->load->model('Search_model');
        $filters = $this->Search_model->filters();
        $data = $this->Event_model->get($filters, $num_page, $per_page);

        //Salida JSON
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    /**
     * AJAX JSON
     * Eliminar un conjunto de users seleccionados
     * 2021-02-20
     */
    function delete_selected()
    {
        $selected = explode(',', $this->input->post('selected'));
        $data['qty_deleted'] = 0;
        
        foreach ( $selected as $row_id ) $data['qty_deleted'] += $this->Event_model->delete($row_id);
        
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    /**
     * Exportar resultados de búsqueda
     * 2022-08-19
     */
    function export()
    {
        set_time_limit(120);    //120 segundos, 2 minutos para el proceso

        //Identificar filtros y búsqueda
        $this->load->model('Search_model');
        $filters = $this->Search_model->filters();

        $data['query'] = $this->Event_model->query_export($filters);

        if ( $data['query']->num_rows() > 0 ) {
            //Preparar datos
                $data['sheet_name'] = 'eventos';

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

    function summary($qty_days = 7)
    {
        $data['events'] = $this->Event_model->summary($qty_days);
        $data['qty_days'] = $qty_days;

        $data['view_a'] = $this->views_folder . 'summary_v';
        $data['nav_2'] = $this->views_folder . 'explore/menu_v';
        $data['head_title'] = 'Eventos';

        $this->App_model->view(TPL_ADMIN, $data);
    }

// EDICIÓN Y ACTUALIZACIÓN
//-----------------------------------------------------------------------------

    /**
     * Crear o actualizar un registro en events
     * 2021-12-21
     */
    function save()
    {
        $arr_row = $this->input->post();
        $condition_add = NULL;

        if ( $this->input->post('condition_add') != NULL ) {
            $condition_add = $this->input->post('condition_add');
            unset($arr_row['condition_add']);
        }
        $data['saved_id'] = $this->Event_model->save($arr_row, $condition_add);
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    /**
     * Guardar los datos de un event
     * 2021-10-14
     */
    function update()
    {
        $data = $this->Event_model->update();
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    /**
     * Formulario para la edición de los datos de un event.
     * 2021-10-14
     */
    function edit($event_id)
    {
        //Datos básicos
        $data = $this->Event_model->basic($event_id);

        //$data['options_type'] = $this->Item_model->options('category_id = 33', 'Todos');
        
        //Array data espefícicas
            $data['back_link'] = URL_ADMIN . "calendar/calendar/{$data['row']->period_id}/appointments";
            /*$data['back_link'] = $this->url_controller . 'explore';
            if ( $this->input->post('back_link') == 'calendar' ) {
            }*/
            $data['view_a'] = $data['type_folder'] . 'edit_v';
        
        $this->App_model->view(TPL_ADMIN, $data);
    }

// INFORMACIÓN
//-----------------------------------------------------------------------------

    function qty_events()
    {
        $this->load->model('Search_model');
        $filters = $this->Search_model->filters();
        
        $data['qty_events'] = $this->Event_model->qty_events($filters);


        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }
}