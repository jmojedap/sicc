<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Observatorio extends CI_Controller{

// Variables generales
//-----------------------------------------------------------------------------
    public $views_folder = 'app/observatorio/';
    public $url_controller = URL_APP . 'observatorio/';

// Constructor
//-----------------------------------------------------------------------------
    
    function __construct() 
    {
        parent::__construct();
        $this->load->model('Repositorio_model');
        date_default_timezone_set("America/Bogota");    //Para definir hora local
    }
    
    function index($contenido_id = NULL)
    {
        if ( is_null($contenido_id) ) {
            redirect("app/observatorio/inicio/");
        } else {
            redirect("app/repositorio/informacion/{$contenido_id}");
        }
    }
    
//EXPLORACIÓN
//---------------------------------------------------------------------------------------------------

    /** 
    * Exploración de contenidos
    * 2022-08-23
    * */
    function inicio()
    {
        /*$this->load->library('google_sheets');
        $driveFileId = '1AXuizcSWlkIBUJBJml8q90_PgRYn4ZAYbGsChJV76qQ';
        $data['modulos'] = $this->google_sheets->sheetToArray($driveFileId, 0);*/

        //Datos básicos de la exploración
            $data['head_title'] = 'Observatorio de Cultura, Recreación y Deporte';
            $data['view_a'] = $this->views_folder . 'inicio/inicio_v';
            
        //Cargar vista
            $this->App_model->view(TPL_FRONT, $data);
    }

    function mapas()
    {
        $data['fileId'] = '1QNHIpeIJqBlDZWTfGNvyGBRMzZ60-aLXPQ130HrhL7g';
        $data['gid'] = '0';

        //Datos básicos de la exploración
        $data['head_title'] = 'Mapas para la Investigación';
        $data['view_a'] = $this->views_folder . 'mapas/mapas_v';

        //Cargar vista
        $this->App_model->view(TPL_FRONT . '_fluid', $data);
    }

    /**
     * Vista del Plan Anual de Investigaciones
     */
    function pai($year = 2023)
    {
        //Datos básicos de la exploración
        $data['head_title'] = 'Mapas para la Investigación';
        $data['view_a'] = $this->views_folder . 'pai_v';

        //Cargar vista
        $this->App_model->view(TPL_FRONT . '_fluid', $data);
    }

    function ebc()
    {
        //Datos básicos de la exploración
        $data['head_title'] = 'Encuesta Bienal de Culturas';
        $data['view_a'] = $this->views_folder . 'pai_v';

        //Cargar vista
        $this->App_model->view('templates/easypml/ebc', $data);
    }

    /** 
    * Exploración de links
    * 2024-02-05
    * */
    function links()
    {
        //Identificar filtros de búsqueda
            $this->load->model('Search_model');
            $filters = $this->Search_model->filters();
            $filters['sf'] = '138_links';
            $filters['type'] = '138';

        //Datos básicos de la exploración
            $this->load->model('Post_model');
            $data = $this->Post_model->explore_data($filters, $num_page, $perPage);
            $data['view_a'] = $this->views_folder . 'links/explore_v';
            $data['nav_2'] = $this->views_folder . 'menus/links_v';
            $data['cf'] = 'repositorio/links/';                      //Nombre del controlador
            $data['views_folder'] = 'app/observatorio/links/';      //Carpeta donde están las vistas de exploración
            $data['perPage'] = $perPage;      //Carpeta donde están las vistas de exploración
        
        //Opciones de filtros de búsqueda
            $data['arrEstadoPublicacion'] = $this->Item_model->arr_options('category_id = 406');
            $data['arrFormato'] = $this->Item_model->arr_options('category_id = 410');
            $data['arrTipo'] = $this->Item_model->arr_options('category_id = 412');
            $data['arrTema'] = $this->Item_model->arr_options('category_id = 415');
            $data['arrSubtema'] = $this->Item_model->arr_options('category_id = 416');
            $data['arrEntidad'] = $this->Item_model->arr_options('category_id = 213 AND item_group = 1');
            $data['arrSiNoNa'] = $this->Item_model->arr_options('category_id = 55 AND cod <= 1');
            $data['arrArea'] = $this->Item_model->arr_options('category_id = 616');
            
        //Cargar vista
            $this->App_model->view(TPL_FRONT, $data);
    }

    function enlaces()
    {
        $data['head_title'] = 'Enlaces';
        $data['fileId'] = '1xULiZYp1bBlnPY9m5AdXefbom311MNxC0M-VQ8rNhd0';
        $data['gid'] = '0';

        $data['view_a'] = $this->views_folder . "enlaces/enlaces_v";
        $this->App_model->view(TPL_FRONT, $data);
    }

    function equipos($equipo = 'sistemas')
    {
        $data['head_title'] = 'Equipos';
        $data['fileId'] = '1haEW3eEpn1bObrzi2Q8pQ_WdK5cnYYSarugLTheKNbU';
        $data['gid'] = '0';

        $data['view_a'] = $this->views_folder . "equipos/equipos_v";
        $this->App_model->view(TPL_FRONT, $data);
    }
}