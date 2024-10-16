<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Items extends CI_Controller{

// Variables generales
//-----------------------------------------------------------------------------
    public $views_folder = 'admin/items/';
    public $url_controller = URL_ADMIN . 'items/';

// Constructor
//-----------------------------------------------------------------------------
    
    function __construct() 
    {
        parent::__construct();
        
        $this->load->model('Item_model');
        
        //Para definir hora local
        date_default_timezone_set("America/Bogota");
    }

// EXPLORE
//-----------------------------------------------------------------------------

    /**
     * JSON
     * Listado de ítems, según filtros de búsqueda
     */
    function get($num_page = 1, $per_page = 1000)
    {
        $this->load->model('Search_model');
        $filters = $this->Search_model->filters();
        $data = $this->Item_model->get($filters, $num_page, $per_page);

        //Salida JSON
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }
    
//CRUD
//---------------------------------------------------------------------------------------------------
    
    /**
     * AJAX JSON
     * Listado de ítems de una categoría específica, tabla item
     * 
     * @param type $category_id
     */
    function get_list($category_id = '058')
    {
        $items = $this->Item_model->items($category_id);
        $this->output->set_content_type('application/json')->set_output(json_encode($items->result()));
    }
    
    /**
     * AJAX JSON
     * Guarda los datos enviados por post, registro en la tabla item, insertar
     * o actualizar.
     * 
     */
    function save($item_id)
    {
        $arr_row = $this->input->post();
        
        $data = $this->Item_model->save($arr_row, $item_id);
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }
    
    /**
     * AJAX
     * Eliminar un registro, devuelve la cantidad de registros eliminados
     */
    function delete($item_id, $category_id)
    {
        $data = $this->Item_model->delete($item_id, $category_id);   
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }
    
    /**
     * AJAX Eliminar un grupo de items selected
     */
    function delete_selected()
    {
        $str_selected = $this->input->post('selected');
        
        $selected = explode('-', $str_selected);
        
        foreach ( $selected as $elemento_id ) 
        {
            $conditions['id'] = $elemento_id;
            $this->Item_model->delete($conditions);
        }
        
        echo count($selected);
    }

// OPCIONES
//-----------------------------------------------------------------------------

    /**
     * AJAX JSON
     * Array con opciones para input select con items, según condición sql
     * 2021-04-09
     */
    function get_options()
    {
        $condition = $this->input->post('condition');
        $empty_value = $this->input->post('empty_value');
        $data['options'] = $this->Item_model->options($condition, $empty_value);

        //Salida JSON
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

// IMPORTACIÓN DE ITEMS
//-----------------------------------------------------------------------------

    //Ejecuta la importación de items con archivo Excel
    function import_e()
    {
        //Proceso
        $this->load->library('excel');            
        $imported_data = $this->excel->arr_sheet_default($this->input->post('sheet_name'));
        
        if ( $imported_data['status'] == 1 )
        {
            $data = $this->Item_model->import($imported_data['arr_sheet']);
        }

        //Cargue de variables
            $data['status'] = $imported_data['status'];
            $data['message'] = $imported_data['message'];
            $data['arr_sheet'] = $imported_data['arr_sheet'];
            $data['sheet_name'] = $this->input->post('sheet_name');
            $data['back_destination'] = "items/manage/";
        
        //Cargar vista
            $data['head_title'] = 'Items';
            $data['head_subtitle'] = 'Import result';
            $data['view_a'] = 'common/import_result_v';
            $data['nav_2'] = $this->views_folder . 'menu_v';

        $this->App_model->view(TPL_ADMIN, $data);
    }
}