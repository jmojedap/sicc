<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class App extends CI_Controller {

// Variables generales
//-----------------------------------------------------------------------------
    public $views_folder = 'admin/app/';
    public $url_controller = URL_ADMIN . 'app/';

// Constructor
//-----------------------------------------------------------------------------
    
    function __construct()
    {
        parent::__construct();
        
        //Local time set
        date_default_timezone_set("America/Bogota");
    }

    /**
     * Primera función de la aplicación de administración
     */
    function index()
    {
        if ( $this->session->userdata('logged') )
        {
            $this->logged();
        } else {
            redirect('app/accounts/login');
        }    
    }

    function dashboard()
    {
        $data['summary'] = $this->App_model->summary();
        $data['head_title'] = APP_NAME;
        $data['view_a'] = $this->views_folder . 'dashboard_v';
        $this->App_model->view(TPL_ADMIN, $data);

        //$this->output->enable_profiler(TRUE);
    }

// HELP
//-----------------------------------------------------------------------------

    /**
     * Devuelve tabla de datos de una hoja de cálculo de googlesheet que sea pública
     * 2023-05-19
     * @param string $fileId ID del archivo en Google Drive
     * @param int $gid ID de la hoja de cálculo dentro del archivo, no es el nombre de la hoja
     * @return array
     */
    function googlesheet_array($fileId, $gid = 794709307)
    {
        $this->load->library('google_sheets');
        $data = $this->google_sheets->sheetToArray($fileId, $gid);

        //Salida JSON
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    function help($post_id = 0)
    {
        $data['head_title'] = 'Ayuda';
        $data['view_a'] = $this->views_folder . 'help/help_v';
        $this->App_model->view(TPL_ADMIN, $data);
    }

    function test(){
        $content = $this->pml->get_url_content('https://www.eltiempo.com/buscar/1?q=colombia&publishedAt%5Bfrom%5D=2021-09-01&publishedAt%5Buntil%5D=2022-08-31&contentTypes%5B%5D=article');
        echo $content;
    }

    function d3(){
        $data['head_title'] = 'D3JS';
        $data['view_a'] = 'app/app/d3js/testing';
        $this->App_model->view('templates/easypml/main', $data);
    }
}