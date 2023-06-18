<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Parametros extends CI_Controller {
        
// Variables generales
//-----------------------------------------------------------------------------
    public $views_folder = 'app/parametros/';
    public $url_controller = URL_APP . 'parametros/';

// Constructor
//-----------------------------------------------------------------------------
    
    function __construct()
    {
        parent::__construct();

        $this->load->model('Item_model');
        
        //Local time set
        date_default_timezone_set("America/Bogota");
    }

    /**
     * Primera función
     */
    function index()
    {
        $this->valores();
    }

// FUNCIONES FRONT INFO
//-----------------------------------------------------------------------------

    function valores($category_cod = 121, $scope = '')
    {
        $categories_condition = 'category_id = 0 AND item_group = 10';
        if ( $scope != '' ) {
            $categories_condition .= " AND filters LIKE '%-{$scope}-%'";
        }
        $data['categories'] = $this->Item_model->get_items($categories_condition);
        
        $data['head_title'] = 'Valores de parámetros';
        $data['view_a'] = $this->views_folder . 'valores/valores_v';
        $data['category_cod'] = $category_cod;
        $data['scope'] = $scope;
        $this->App_model->view(TPL_FRONT, $data);
    }
    
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
     * Exportar resultados de búsqueda
     * 2022-08-24
     */
    function exportar($ambito = '')
    {
        set_time_limit(120);    //120 segundos, 2 minutos para el proceso

        //Identificar filtros y búsqueda
        $this->load->model('Search_model');
        $filters = $this->Search_model->filters();
        $filters['sf'] = 'export_sicc';

        $filters['condition'] = 'variables.item_group = 10 AND ';
        if ( strlen($ambito) > 0 ) {
            $filters['condition'] .= " variables.filters LIKE '%-{$ambito}-%' AND ";
        }

        $data['query'] = $this->Item_model->query_export($filters);

        if ( $data['query']->num_rows() > 0 ) {
            //Preparar datos
                $data['sheet_name'] = 'opciones_valor';

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
}