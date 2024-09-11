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
        //Datos básicos de la exploración
            $data['head_title'] = 'Observatorio de Cultura, Recreación y Deporte';
            $data['page_title'] = 'Observatorio de Culturas';
            $data['view_a'] = $this->views_folder . 'inicio/inicio_v';
            
        //Cargar vista
            $this->App_model->view('templates/easypml/minimal', $data);
    }

    function mapas()
    {
        $data['fileId'] = '1QNHIpeIJqBlDZWTfGNvyGBRMzZ60-aLXPQ130HrhL7g';
        $data['gid'] = '0';

        //Datos básicos de la exploración
        $data['head_title'] = 'Mapas para la Investigación';
        $data['view_a'] = $this->views_folder . 'mapas/mapas_v';

        //Cargar vista
        $this->App_model->view(TPL_FRONT, $data);
    }

    /**
     * Vista del Plan Anual de Investigaciones
     */
    function pai($year = 2023)
    {
        //Datos básicos de la exploración
        $data['head_title'] = 'Plan Anual de Investigaciones ' . $year;
        
        $data['view_a'] = $this->views_folder . 'pai_v';
        if ( $year == 2024 ) {
            $data['fileId'] = '1vdCB9ZOyay0eHCqSxNVKcylW4FHU0jeHyAL0kuM2OB0';
            $data['tablas'] = [
                ['nombre' => 'investigaciones', 'gid' => '1209666804'],
            ];
            $basePath = PATH_CONTENT . 'json/pai_2024/';
            $data['investigaciones'] = $this->App_model->getJsonContent($basePath . 'investigaciones.json');
            $data['view_a'] = $this->views_folder . 'pai_2024/pai_2024_v';
        }

        //Cargar vista
        $this->App_model->view('templates/easypml/minimal', $data);
    }

    function ebc()
    {
        //Datos básicos de la exploración
        $data['head_title'] = 'Encuesta Bienal de Culturas';
        $data['view_a'] = $this->views_folder . 'ebc/ebc_v';

        //Cargar vista
        $this->App_model->view('templates/easypml/minimal', $data);
    }

    /**
     * Listado de investigaciones, exporación y búsqueda
     * 2024-05-20
     */
    function investigaciones()
    {
        $data['head_title'] = 'Investigaciones';
        $data['page_title'] = 'Investigaciones';
        $data['fileId'] = '1mTpRd2lgxaY_FJj9XDcXHfMHEOfg2c6rxmUE-zR68WA';
        $data['gid'] = '1186279524';

        $filePath = PATH_CONTENT . 'json/investigaciones/investigaciones.json';
        $data['elementos'] = $this->App_model->getJsonContent($filePath);
        $data['productos'] = $this->App_model->getJsonContent(PATH_CONTENT . 'json/investigaciones/productos.json');

        $data['view_a'] = $this->views_folder . "investigaciones/investigaciones_v";
        $this->App_model->view('templates/easypml/minimal', $data);
    }

    function links()
    {
        $data['head_title'] = 'Contenidos de trabajo';
        $data['page_title'] = 'Contenidos de trabajo';
        $data['fileId'] = '1xULiZYp1bBlnPY9m5AdXefbom311MNxC0M-VQ8rNhd0';
        $data['gid'] = '0';

        $filePath = PATH_CONTENT . 'json/observatorio/links.json';
        $data['links'] = $this->App_model->getJsonContent($filePath);

        $data['view_a'] = $this->views_folder . "links/links_v";
        $this->App_model->view('templates/easypml/minimal', $data);
    }

    /**
     * Listado de informes de visualización de datos implementados por el
     * Observatorio
     * 2024-05-13
     */
    function visualizaciones_datos()
    {
        $data['head_title'] = 'Visualización de datos';
        $data['fileId'] = '1K4Ly_hU0j6-bIo-SAtXHMeBiizrUEsg4w8XE8o5Lpik';
        $data['gid'] = '0';

        $filePath = PATH_CONTENT . 'json/observatorio/dataviz.json';
        $data['tableros'] = $this->App_model->getJsonContent($filePath);

        $data['view_a'] = $this->views_folder . "visualizaciones_datos/visualizaciones_datos_v";
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