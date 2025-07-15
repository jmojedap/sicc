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

    function perfil($user_id)
    {
        $this->load->model('User_model');
        $data = $this->User_model->basic($user_id);
        $data['view_a'] = $this->views_folder . 'perfil/perfil_v';

        $this->db->select('*');
        $this->db->where('user_id', $user_id);
        $data['metadata'] = $this->db->get('users_meta');

        $data['following_status'] = $this->Rci_model->following_status($user_id);

        unset($data['nav_2']);
        //$data['nav_2'] = '';
        $this->App_model->view(RCI_TPL_APP, $data);
    }

    function me_interesa()
    {
        $user_id = $this->session->userdata('user_id');
        $this->load->model('User_model');
        $data = $this->User_model->basic($user_id);

        //$data['nav_2'] = $this->views_folder . 'me_interesa/menu_v';
        unset($data['nav_2']);
        $data['view_a'] = $this->views_folder . 'me_interesa/me_interesa_v';
        $data['following'] = $this->User_model->following($user_id);
        $data['followers'] = $this->User_model->followers($user_id);

        $this->App_model->view(RCI_TPL_APP, $data);

    }
}