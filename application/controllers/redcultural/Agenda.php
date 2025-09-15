<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Agenda extends CI_Controller {
        
// Variables generales
//-----------------------------------------------------------------------------
    public $views_folder = 'redcultural/agenda/';
    public $url_controller = RCI_URL_APP . 'agenda/';

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
    function agendarme()
    {
        $data['head_title'] = 'Agendarme';
        $data['view_a'] = $this->views_folder . 'agendarme/agendarme_v';

        $data['recaptcha_sitekey'] = K_RCSK;    //config/constants.php

        $this->load->model('User_model');
        $this->load->model('Search_model');
        $filters = $this->Search_model->filters();
        $filters['sf'] = 'red_cultural';
        $filters['role'] = 11;
        $filters['o'] = 'integer_1';
        $filters['ot'] = 'DESC';
        $filters['tags'] = 'invitadosFormulario';
        $numPage = 1;
        $perPage = 300;
        $dataSearch = $this->User_model->get($filters, $numPage, $perPage);

        $data['elementos'] = $dataSearch['list'];
        $user_id = $this->session->userdata('user_id') ?? 0;

        $this->App_model->view(RCI_TPL_APP, $data);
    }
}