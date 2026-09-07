<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Geofocus extends CI_Controller{

// Variables generales
//-----------------------------------------------------------------------------
    public $views_folder = 'app/geofocus/';
    public $url_controller = URL_APP . 'geofocus/';

// Constructor
//-----------------------------------------------------------------------------
    
    function __construct() 
    {
        parent::__construct();
        $this->load->model('Geofocus_model');
        date_default_timezone_set("America/Bogota");    //Para definir hora local
    }
    
    function index($priorizacion_id = NULL)
    {
        if ( is_null($priorizacion_id) ) {
            redirect("app/geofocus/priorizaciones/");
        } else {
            redirect("app/geofocus/priorizaciones/{$priorizacion_id}");
        }
    }

// CRUD Priorizaciones
//-----------------------------------------------------------------------------

    function panel()
    {
        $data['head_title'] = 'Geofocus';
        $data['view_a'] = $this->views_folder . 'panel/panel_v';
        $data['nav_2'] = $this->views_folder . 'geofocus_menu_v';

        $this->App_model->view('templates/easypml/minimal', $data);
    }

    /**
     * Vista de exploración de capas geográficas base
     * 2026-09-02
     */
    function z_capas_base()
    {
        $data['capasBase'] = $this->Geofocus_model->capas_base();
        $data['variables'] = $this->Geofocus_model->get_variables()->result();

        $data['head_title'] = 'Esquemas territoriales';
        $data['view_a'] = $this->views_folder . 'capas_base/capas_base_v';
        $data['nav_2'] = $this->views_folder . 'geofocus_menu_v';

        $this->App_model->view('templates/easypml/minimal', $data);
    }

    /**
     * Vista de exploración de priorizaciones geográficas creadas por los usuarios
     * 2026-09-02
     */
    function priorizaciones()
    {
        $data['elementos'] = $this->Geofocus_model->get_priorizaciones();
        $data['capas_base'] = $this->Geofocus_model->capas_base();

        $data['head_title'] = 'Priorizaciones';
        $data['view_a'] = $this->views_folder . 'priorizaciones/priorizaciones_v';
        $data['nav_2'] = $this->views_folder . 'geofocus_menu_v';

        $this->App_model->view('templates/easypml/minimal', $data);
    }    

// Priorización geográfica
//-----------------------------------------------------------------------------

    /**
     * Vista de la herramienta de priorización geográfica distrital
     * 2024-08-17
     */
    function priorizacion($priorizacionId)
    {
        $data = $this->Geofocus_model->basic($priorizacionId);
        $data['view_a'] = $this->views_folder . 'priorizacion/priorizacion_v';
        $data['nav_2'] = $this->views_folder . 'geofocus_menu_v';

        $data['arrTemas'] = $this->Item_model->arr_options('category_id = 131');

        $condition_variables = "estado = 1 AND key_capa = '{$data['row']->key_capa}'";
        $data['variables'] = $this->Geofocus_model->get_variables($condition_variables)->result();

        // Identificar capa base de la priorización
        $capas_base = $this->Geofocus_model->capas_base();
        $data['capa_base'] = NULL;
        foreach ($capas_base as $capa) {
            if ( $capa['key_capa'] == $data['row']->key_capa ) {
                $data['capa_base'] = $capa;
                break;
            }
        }

        $data['localidades'] = $this->App_model->getJsonContent(PATH_CONTENT . 'json/sig/localidades.json');
        $data['territorios'] = $this->Geofocus_model->getPriorizacion($priorizacionId);

        $this->App_model->view('templates/easypml/minimal', $data);
    }

    /**
     * Exportar detalles de priorización
     * 2024-11-18
     */
    function export($priorizacionId)
    {
        set_time_limit(120);    //120 segundos, 2 minutos para el proceso

        //Identificar filtros y búsqueda
        $this->load->model('Search_model');
        $filters = $this->Search_model->filters();

        $condition = "priorizacion_id = {$priorizacionId}";
        $data['query'] = $this->Geofocus_model->query_export_territorios_valor($condition);

        if ( $data['query']->num_rows() > 0 ) {
            //Preparar datos
                $data['sheet_name'] = 'territorios';

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
     * Mapas
     * 2024-11-09
     */
    function mapas($variableId = 28)
    {
        $filePath = PATH_CONTENT . 'json/geofocus/variables.json';
        $data['variables'] = $this->App_model->getJsonContent($filePath);
        $data['variableId'] = $variableId;
        
        $data['head_title'] = 'Geofocus Mapas';
        $data['view_a'] = $this->views_folder . 'mapas/mapas_v';
        $this->App_model->view('templates/easypml/minimal', $data);
    }

    /**
     * Exportar valores de una variable para cada territorio
     * 2025-03-13
     */
    function export_variable($variableId, $variableClave)
    {
        set_time_limit(120);    //120 segundos, 2 minutos para el proceso

        $this->load->model('Geofocus_model');
        $this->load->model('Search_model');

        $condition = "variable_id = {$variableId}";
        $data['query'] = $this->Geofocus_model->query_export_territorios_variable($condition);

        if ( $data['query']->num_rows() > 0 ) {
            //Preparar datos
                $data['sheet_name'] = substr($variableClave,0,30);

            //Objeto para generar archivo excel
                $this->load->library('Excel');
                $file_data['obj_writer'] = $this->excel->file_query($data);

            //Nombre de archivo
                $file_data['file_name'] = date('Ymd_His') . '_territorios_' . $variableClave;

            $this->load->view('common/download_excel_file_v', $file_data);
            //Salida JSON
            //$this->output->set_content_type('application/json')->set_output(json_encode($file_data['obj_writer']));
        } else {
            $data = array('message' => 'No se encontraron registros para exportar');
            //Salida JSON
            $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }
    }

// Gestión de Variables
//-----------------------------------------------------------------------------

    /**
     * Exploración de variables de geofocus
     * 2026-08-31
     */
    function variables($key_capa = 'sector_catastral_0526', $section = 'lista', $variable_id = NULL)
    {
        $data['variables'] = $this->Geofocus_model->get_variables();
        $data['arrEstadoVariable'] = $this->Item_model->arr_options('category_id = 42');
        $data['key_capa'] = $key_capa;
        $data['section'] = $section;
        $data['variable_id'] = $variable_id;
        $data['capasBase'] = $this->Geofocus_model->capas_base();

        $data['head_title'] = 'Variables';
        $data['view_a'] = $this->views_folder . 'variables/variables_v';
        $data['nav_2'] = $this->views_folder . 'geofocus_menu_v';

        $this->App_model->view('templates/easypml/minimal', $data);
    }

    /**
     * Vista de formulario para importar valores de variable
     * 2026-09-01
     */
    function variables_importar_valores($variable_id)
    {
        $data['row'] = $this->Db_model->row('gf_variables', "id = {$variable_id}");

        $data['help_note'] = 'Se importarán valores de la variable <b>\'' . $data['row']->nombre . '\' </b>para cada territorio a la base de datos';
        $data['help_tips'] = [
            'El archivo Excel debe tener una hoja con nombre <b>valores</b> y las siguientes columnas: <b>poligono_id, valor</b>',
            'El archivo Excel debe tener una hoja con nombre <b>valores</b>',
            'Debe tener una fila por cada polígono de la capa base.',
            'Antes de la importación los valores existentes de la variable serán eliminados y reemplazados por los nuevos valores del archivo Excel.'
        ];
        $data['template_file_name'] = 'f61_variables_valor.xlsx';
        $data['sheet_name'] = 'valores';
        $data['head_subtitle'] = 'Importar valores de variable';
        $data['destination_form'] = "app/geofocus/variables_importar_valores_e/{$variable_id}";

        $data['url_file'] = URL_RESOURCES . 'import_templates/' . $data['template_file_name'];
        

        $data['head_title'] = 'Importar valores de variable';
        $data['view_a'] = 'common/import_v';
        $data['nav_2'] = $this->views_folder . 'geofocus_menu_v';
        
        $this->App_model->view('templates/easypml/minimal', $data);
    }

    /**
     * Ejecuta la importación de valores de variable con archivo Excel
     * 2026-09-01
     */
    function variables_importar_valores_e($variable_id)
    {
        //Proceso
        $this->load->library('excel');            
        $imported_data = $this->excel->arr_sheet_default($this->input->post('sheet_name'));
        
        if ( $imported_data['status'] == 1 )
        {
            $row_variable = $this->Db_model->row('gf_variables', "id = {$variable_id}");
            $data = $this->Geofocus_model->import_variable_values($imported_data['arr_sheet'], $row_variable);
        }

        // Normalizar valores de la variable
        $this->Geofocus_model->normalizarValores('variable_id', $variable_id);

        // Actualizar el orden de los valores
        $this->Geofocus_model->actualizarOrden('variable_id', $variable_id);

        // Actualizar el campo gf_territorios_valor.poligono_id con el valor de gf_territorios.poligono_id
        $this->Geofocus_model->actualizar_poligono_id($variable_id);

        // Actualizar resumen de la variable
        $this->Geofocus_model->update_variable_summary($variable_id);

        //Cargue de variables
            $data['status'] = $imported_data['status'];
            $data['message'] = $imported_data['message'];
            $data['arr_sheet'] = $imported_data['arr_sheet'];
            $data['sheet_name'] = $this->input->post('sheet_name');
            $data['back_destination'] = URL_APP . "geofocus/variables_importar_valores/{$variable_id}";
        
        //Cargar vista
            $data['head_title'] = 'Variable';
            $data['head_subtitle'] = 'Resultado de importación';
            $data['view_a'] = 'common/bs5/import_result_v';
            $data['nav_2'] = $this->views_folder . 'geofocus_menu_v';

        $this->App_model->view('templates/easypml/minimal', $data);
    }

}
