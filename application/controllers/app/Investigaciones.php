<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Investigaciones extends CI_Controller{

// Variables generales
//-----------------------------------------------------------------------------
    public $views_folder = 'app/investigaciones/';
    public $url_controller = URL_APP . 'investigaciones/';

// Constructor
//-----------------------------------------------------------------------------
    
    function __construct() 
    {
        parent::__construct();
        $this->load->model('Investigacion_model');
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
    function solicitudes()
    {
        //Datos básicos de la exploración
            $data['head_title'] = 'Solicitudes de investigación';
            $data['view_a'] = $this->views_folder . 'solicitudes/solicitudes_v';

        //Opciones para variables
        $data['arrEntidades'] = $this->Item_model->arr_options('category_id = 213');
            
        //Cargar vista
            $this->App_model->view('templates/easypml/empty', $data);
    }
}