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
    
//CRUD
//---------------------------------------------------------------------------------------------------
    
    /**
     * Gestión de valores de los ítems, CRUD por categorías
     * 2025-06-30
     */
    function values($category_cod = 121, $scope = '')
    {
        $categories_condition = 'category_id = 0';
        if ( $scope != '' ) {
            $categories_condition .= " AND filters LIKE '%-{$scope}-%'";
        }
        $data['categories'] = $this->Item_model->get_items($categories_condition);
        
        $data['head_title'] = 'Valores de parámetros';
        $data['view_a'] = $this->views_folder . 'values/values_v';
        $data['nav_2'] = $this->views_folder . 'menu_v';
        $data['category_cod'] = $category_cod;
        $data['scope'] = $scope;
        $this->App_model->view(TPL_ADMIN_5, $data);
    }

    /**
     * Vista filtra items por categoría, CRUD de items.
     * DESACTIVADA 2025-06-30
     */
    function z_manage($category_id = '58')
    {
        //Variables específicas
            $data['category_id'] = $category_id;
            $data['categories'] = $this->Item_model->get_items('category_id = 0');
        
        //Array data generales
            $data['head_title'] = 'Ítems';
            $data['view_a'] = $this->views_folder . 'manage/manage_v';
            $data['nav_2'] = $this->views_folder . 'menu_v';
            
        //Cargar vista
            $this->App_model->view(TPL_ADMIN_5, $data);
    }

    /**
     * Exportar resultados de búsqueda
     * 2022-08-24
     */
    function export()
    {
        set_time_limit(120);    //120 segundos, 2 minutos para el proceso

        //Identificar filtros y búsqueda
        $this->load->model('Search_model');
        $filters = $this->Search_model->filters();

        $data['query'] = $this->Item_model->query_export($filters);

        if ( $data['query']->num_rows() > 0 ) {
            //Preparar datos
                $data['sheet_name'] = 'items';

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

// IMPORTACIÓN DE ITEMS
//-----------------------------------------------------------------------------

    /**
     * Mostrar formulario de importación de items
     * con archivo Excel. El resultado del formulario se envía a 
     * 'items/import_e'
     */
    function import()
    {
        $data = $this->Item_model->import_config();

        $data['url_file'] = URL_RESOURCES . 'import_templates/' . $data['template_file_name'];
        

        $data['head_title'] = 'Importar ítems';
        $data['nav_2'] = $this->views_folder . 'menu_v';
        $data['view_a'] = 'common/import_v';
        
        $this->App_model->view(TPL_ADMIN_5, $data);
    }

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
            $data['back_destination'] = "items/values/";
        
        //Cargar vista
            $data['head_title'] = 'Items';
            $data['head_subtitle'] = 'Import result';
            $data['view_a'] = 'common/import_result_v';
            $data['nav_2'] = $this->views_folder . 'menu_v';

        $this->App_model->view(TPL_ADMIN_5, $data);
    }
}