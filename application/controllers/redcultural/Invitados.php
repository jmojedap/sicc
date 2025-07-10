<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Invitados extends CI_Controller {
        
// Variables generales
//-----------------------------------------------------------------------------
    public $views_folder = 'redcultural/invitados/';
    public $url_controller = RCI_URL_APP . 'invitados/';

// Constructor
//-----------------------------------------------------------------------------
    
    function __construct()
    {
        parent::__construct();

        $this->load->model('Rci_model');
        
        //Local time set
        date_default_timezone_set("America/Bogota");
    }

// EXPLORACIÓN
//-----------------------------------------------------------------------------

    function test()
    {
        $data['head_title'] = 'Prueba del template';
        $data['view_a'] = $this->views_folder . 'test_v';
        //$data['nav_2'] = '';
        $this->App_model->view(RCI_TPL_APP, $data);
    }

    /**
     * Exploración del directorio de invitados
     * 2025-04-17
     */
    function directorio()
    {
        $data['head_title'] = 'Encuentro Ciudades';
        $data['view_a'] = $this->views_folder . 'directorio/directorio_v';

        $this->load->model('User_model');
        $this->load->model('Search_model');
        $filters = $this->Search_model->filters();
        $filters['sf'] = 'red_cultural';
        $filters['role'] = 11;
        $filters['o'] = 'integer_1';
        $filters['ot'] = 'DESC';
        $numPage = 1;
        $perPage = 300;
        $dataSearch = $this->User_model->get($filters, $numPage, $perPage);

        $data['elementos'] = $dataSearch['list'];
        //$data['back_link'] = $this->url_controller . 'explorar';

        //$data['arrFase'] = $this->Item_model->arr_options('category_id = 433');

        $this->App_model->view(RCI_TPL_APP, $data);
    }
}