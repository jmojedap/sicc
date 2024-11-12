<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Contenidos extends CI_Controller {
        
// Variables generales
//-----------------------------------------------------------------------------
    public $views_folder = 'app/contenidos/';
    public $url_controller = URL_APP . 'contenidos/';

// Constructor
//-----------------------------------------------------------------------------
    
    function __construct()
    {
        parent::__construct();

        $this->load->model('Post_model');
        
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

    /** Exploración de Posts */
    function explorar($num_page = 1)
    {
        //Identificar filtros de búsqueda
        $this->load->model('Search_model');
        $filters = $this->Search_model->filters();
        $filters['condition'] = 'type_id IN (110) AND status = 1';
        $filters['sf'] = 'documentacion';  //Select format

        //Datos básicos de la exploración
            $data = $this->Post_model->explore_data($filters, $num_page, 12);
            $data['cf'] = 'contenidos/explorar/';
            $data['controller'] = 'contenidos/';
            $data['views_folder'] = $this->views_folder . 'explorar/';      //Carpeta donde están las vistas de exploración
            $data['head_title'] = 'Contenidos y publicaciones';
            $data['view_a'] = $data['views_folder'] . 'explore_v';
            unset($data['nav_2']);
            
        //Arrays con valores para contenido en lista
            $data['arrCat1'] = $this->Item_model->arr_options('category_id = 21 AND level = 0');
            $data['arrCat2'] = $this->Item_model->arr_options('category_id = 21 AND level = 1');
            $data['arrDocumentType'] = $this->Item_model->arr_options('category_id = 34');
            
        //Cargar vista
            $this->App_model->view(TPL_FRONT, $data);
    }

    /**
     * Listado de Contenidos, filtrados por búsqueda, JSON
     */
    function get($num_page = 1, $per_page = 10)
    {
        $this->load->model('Search_model');
        $filters = $this->Search_model->filters();
        $filters['condition'] = 'type_id IN (110) AND status = 1';
        $filters['sf'] = 'documentacion';  //Select format

        $data = $this->Post_model->get($filters, $num_page, $per_page);
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    /**
     * Vista Lectura de un contenido
     * 2022-04-02
     */
    function leer($post_id)
    {
        $data = $this->Post_model->basic($post_id);
        $data['author'] = $this->Post_model->author($data['row']);

        $data['back_link'] = $this->url_controller . 'explorar/';
        $data['breadcrumb'] = array(
            ['title' => 'Contenidos', 'url' => $this->url_controller . 'explorar'],
        );
        unset($data['nav_2']);
        $data['view_a'] = $this->views_folder . 'leer_v';

        $this->load->view(TPL_FRONT, $data);
    }

    function obtener_googlesheet()
    {
        $data['head_title'] = 'Inicio';
        $data['view_a'] = $this->views_folder . 'obtener_googlesheet_v';

        $this->load->view(TPL_FRONT, $data);
    }

    function combinar_json()
    {
        $data['head_title'] = 'Combinar JSON';
        $data['page_title'] = 'Combinar JSON';

        $filePath = PATH_CONTENT . 'json/barrios_vivos/preguntas.json';
        $data['elementos'] = $this->App_model->getJsonContent($filePath);

        $data['view_a'] = $this->views_folder . "combinar_json/combinar_json_v";
        $this->App_model->view('templates/easypml/minimal', $data);
    }

    
}