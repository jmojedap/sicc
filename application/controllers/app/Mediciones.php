<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mediciones extends CI_Controller {
        
// Variables generales
//-----------------------------------------------------------------------------
    public $views_folder = 'app/mediciones/';
    public $url_controller = URL_APP . 'mediciones/';

// Constructor
//-----------------------------------------------------------------------------
    
    function __construct()
    {
        parent::__construct();

        $this->load->model('Medicion_model');
        
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

// FUNCIONES FRONT INFO
//-----------------------------------------------------------------------------

    /** Exploración de Mediciones */
    function explorar($num_page = 1)
    {
        //Identificar filtros de búsqueda
        $this->load->model('Search_model');
        $filters = $this->Search_model->filters();
        //$filters['sf'] = 'general';  //Select format

        //Datos básicos de la exploración
            $data['perPage'] = 100;
            $data = $this->Medicion_model->explore_data($filters, $num_page, $data['perPage']);
            $data['page_title'] = 'Mediciones e investigaciones';
            $data['cf'] = 'mediciones/explorar/';
            $data['controller'] = 'mediciones/';
            $data['views_folder'] = $this->views_folder . 'explorar/';      //Carpeta donde están las vistas de exploración
            $data['head_title'] = 'Mediciones';
            $data['view_a'] = $data['views_folder'] . 'explore_v';
            $data['nav_2'] = $data['views_folder'] . 'menu_v';
            //unset($data['nav_2']);
        
        //Opciones de filtros de búsqueda
            $data['arrType'] = $this->Item_model->arr_options('category_id = 143');
            $data['arrUnidadObservacion'] = $this->Item_model->arr_options('category_id = 144');
            $data['arrTematica1'] = $this->Item_model->arr_options('category_id = 141');
            
        //Cargar vista
            $this->App_model->view(TPL_FRONT, $data);
    }

    /**
     * Listado de Mediciones, filtrados por búsqueda, JSON
     */
    function get($num_page = 1, $per_page = 100)
    {
        $this->load->model('Search_model');
        $filters = $this->Search_model->filters();
        //$filters['sf'] = 'general';  //Select format

        $data = $this->Medicion_model->get($filters, $num_page, $per_page);
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    /**
     * Vista Lectura de un contenido
     * 2022-04-02
     */
    function detalles($medicion_id)
    {
        $data = $this->Medicion_model->basic($medicion_id);

        $data['arrType'] = $this->Item_model->arr_options('category_id = 143');
        $data['arrUnidadObservacion'] = $this->Item_model->arr_options('category_id = 144');
        $data['arrTematica1'] = $this->Item_model->arr_options('category_id = 141');

        $data['back_link'] = $this->url_controller . 'explorar/';
        $data['view_a'] = $this->views_folder . 'detalles_v';

        $this->App_model->view(TPL_FRONT, $data);
    }

// CRUD
//-----------------------------------------------------------------------------

    function add()
    {
        $data['arrTipo'] = $this->Item_model->arr_options('category_id = 143');
        $data['arrUnidadObservacion'] = $this->Item_model->arr_options('category_id = 144');
        $data['arrMetodolobia'] = $this->Item_model->arr_options('category_id = 145');

        //Variables generales
            $data['head_title'] = 'Nueva medición';
            $data['nav_2'] = $this->views_folder . 'explorar/menu_v';
            $data['view_a'] = $this->views_folder . 'add/add_v';

        $this->App_model->view(TPL_FRONT, $data);
    }

    function edit($medicion_id)
    {
        $data = $this->Medicion_model->basic($medicion_id);

        $data['options_tipo'] = $this->Item_model->arr_options('category_id = 143');
        $data['options_unidad_observacion'] = $this->Item_model->arr_options('category_id = 144');
        $data['options_metodologia'] = $this->Item_model->arr_options('category_id = 145');
        $data['options_cod_estrategia'] = $this->Item_model->arr_options('category_id = 221');

        $data['back_link'] = $this->url_controller . 'explorar';

        $data['view_a'] = $this->views_folder . 'edit/edit_v';
        $this->App_model->view(TPL_FRONT, $data);
    }

// INFO
//-----------------------------------------------------------------------------

    function powerbi($medicion_id = 107)
    {
        $data = $this->Medicion_model->basic($medicion_id);

        $data['back_link'] = $this->url_controller . 'explorar';
        $data['view_a'] = $this->views_folder . 'powerbi_v';

        $this->App_model->view(TPL_FRONT, $data);
    }

// INFORMACIÓN Y DETALLES DE LA MEDICIÓN
//-----------------------------------------------------------------------------

    function formulario($medicion_id)
    {
        $data = $this->Medicion_model->basic($medicion_id);

        $data['secciones'] = $this->Medicion_model->secciones($medicion_id);
        $data['preguntas'] = $this->Medicion_model->preguntas($medicion_id);
        $data['variables'] = $this->Medicion_model->variables("medicion_id = {$medicion_id}");
        $data['opciones'] = $this->Medicion_model->opciones("medicion_id = {$medicion_id}");

        $data['back_link'] = $this->url_controller . 'explorar/';
        $data['view_a'] = $this->views_folder . 'formulario/formulario_v';

        $this->App_model->view(TPL_FRONT, $data);
    }

// OTROS DESARROLLO Y DOCUMENTACIÓN
//-----------------------------------------------------------------------------

    /**
     * Diccionario de datos, detalle datos de campos de tabla
     * 2023-04-09
     */
    function diccionario_de_datos_ant($table = 'mediciones', $format = '')
    {
        $data['diccionario'] = file_get_contents(PATH_CONTENT . "json/diccionarios/{$table}.json");

        //Elementos de datos
        $this->load->model('Post_model');
        $select = $this->Post_model->select('132_elementos_datos');
        $data['tables'] = $this->db->select($select)
            ->where('type_id', 132)->where('related_1',2)->get('posts');

        $data['table'] = $table;
        $data['head_title'] = 'Diccionario de datos';

        if ( $format == 'print' ) {
            $data['view_a'] = $this->views_folder . "diccionario_print_v";
            $this->App_model->view('templates/print/main', $data);
        } else {
            $data['view_a'] = $this->views_folder . "diccionario_v";
            $this->App_model->view(TPL_FRONT, $data);
        }
    }

    /**
     * Diccionario de datos, detalle datos de campos de tabla
     * 2023-04-09
     */
    function diccionario_de_datos($table = 'mediciones', $format = '')
    {
        $this->load->library('google_sheets');
        $data['tables'] = $this->google_sheets->sheetToArray('1RapjEh3V_UzBCsU5jTuFCvjVnY6T3nwTMVVrNFSoY-w', 575993992);

        //$data['diccionario'] = file_get_contents(PATH_CONTENT . "json/diccionarios/{$table}.json");

        $data['table'] = $table;
        $data['head_title'] = 'Diccionario de datos';
        $data['file_id'] = '1RapjEh3V_UzBCsU5jTuFCvjVnY6T3nwTMVVrNFSoY-w';

        if ( $format == 'print' ) {
            $data['view_a'] = $this->views_folder . "diccionario_print_v";
            $this->App_model->view('templates/print/main', $data);
        } else {
            $data['view_a'] = $this->views_folder . "diccionario_v";
            $this->App_model->view(TPL_FRONT, $data);
        }
    }

// RESULTADOS
//-----------------------------------------------------------------------------

    /**
     * Vista pantalla completa de los resultados de una pregunta de una medición
     * o encuesta
     * 2023-11-21
     */
    function hc_resultados_pregunta($pregunta_id)
    {
        $pregunta = $this->Db_model->row_id('med_pregunta', $pregunta_id);

        $data['pregunta'] = $pregunta;
        $data['medicion'] = $this->Db_model->row_id('med_medicion', $pregunta->medicion_id);
        $data['variables'] = $this->Medicion_model->variables("medicion_id = {$pregunta->medicion_id} AND pregunta_id = {$pregunta_id}");
        $data['opciones'] = $this->Medicion_model->opciones_agrupadas("medicion_id = {$pregunta->medicion_id} AND pregunta_id = {$pregunta_id}");

        $sumatoria_encuestados = $this->Medicion_model->sumatoria_encuestados($pregunta->medicion_id);
        $frecuencias = $this->Medicion_model->frecuencias($pregunta->medicion_id, $pregunta_id);
        $frecuencias_array = $this->Medicion_model->frecuencias_array($frecuencias, $sumatoria_encuestados);

        $data['sumatoria_encuestados'] = $sumatoria_encuestados;
        $data['frecuencias'] = $frecuencias;
        $data['frecuencias_array'] = $frecuencias_array;

        //Establecer vista según el tipo de pregunta
        $view_a = $this->views_folder . 'resultados/hc_resultados_pregunta_multivar/resultados_v';
        if ( $data['variables']->num_rows() <= 1 ) $view_a = $this->views_folder . 'resultados/hc_resultados_pregunta/resultados_v';

        $data['head_title'] = 'Resultados pregunta ' . $pregunta_id;
        $data['view_a'] = $view_a;
        $this->App_model->view('templates/easypml/empty', $data);
    }

    function hc_resultados_pregunta_test($pregunta_id)
    {
        $pregunta = $this->Db_model->row_id('med_pregunta', $pregunta_id);
        $medicion_id = 

        $data['pregunta'] = $pregunta;
        $data['medicion'] = $this->Db_model->row_id('med_medicion', $data['pregunta']->medicion_id);
        $data['variables'] = $this->Medicion_model->variables("pregunta_id = {$pregunta_id}");
        $data['opciones'] = $this->Medicion_model->opciones_agrupadas("pregunta_id = {$pregunta_id}");

        $sumatoria_encuestados = $this->Medicion_model->sumatoria_encuestados($pregunta->medicion_id);
        $frecuencias = $this->Medicion_model->frecuencias($pregunta->medicion_id, $pregunta_id);
        $frecuencias_array = $this->Medicion_model->frecuencias_array($frecuencias, $sumatoria_encuestados);

        $data['sumatoria_encuestados'] = $sumatoria_encuestados;
        $data['frecuencias'] = $frecuencias;
        $data['frecuencias_array'] = $frecuencias_array;
        
        $data['head_title'] = 'Resultados pregunta' . $pregunta_id;
        $data['view_a'] = $this->views_folder . 'resultados/hc_resultados_pregunta/test_v';

        //Salida JSON
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

}