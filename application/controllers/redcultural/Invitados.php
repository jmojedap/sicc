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
        $filters['tags'] = 'invitadosFormulario';
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

    // IMPORTACIÓN DE USUARIOS
//-----------------------------------------------------------------------------

    /**
     * Mostrar formulario de importación de usuarios
     * con archivo Excel. El resultado del formulario se envía a 
     * 'users/import_e'
     */
    function import($table = 'users')
    {
        //Iniciales
            $data['help_note'] = 'Se importarán invitados a la herramienta.';
            $data['help_tips'] = [];
        
        //Variables específicas
            $data['destination_form'] = "redcultural/invitados/import_e";
            $data['template_file_name'] = 'f01_usuarios.xlsx';
            $data['sheet_name'] = 'rows';
            $data['url_file'] = URL_RESOURCES . 'import_templates/' . $data['template_file_name'];

        //Importar metadatos
            if ( $table == 'meta' ) {
                $data['help_note'] = 'Se importarán metadatos de los invitados';
                $data['sheet_name'] = 'users_meta';
                $data['destination_form'] = "redcultural/invitados/import_meta_e";
            }
            
        //Variables generales
            $data['head_title'] = 'Importar datos de invitados';
            $data['view_a'] = 'common/import_v';
            //$data['nav_2'] = $this->views_folder . 'menus/explore_v';
        
        $this->App_model->view(RCI_TPL_APP, $data);
    }

    /**
     * Ejecuta (e) la importación de invitados con archivo Excel
     * 2025-08-07
     */
    function import_e()
    {
        //Proceso
        $this->load->library('excel');            
        $imported_data = $this->excel->arr_sheet_default($this->input->post('sheet_name'));
        
        if ( $imported_data['status'] == 1 )
        {
            $data = $this->Rci_model->import($imported_data['arr_sheet']);
        }

        //Cargue de variables
            $data['status'] = $imported_data['status'];
            $data['message'] = $imported_data['message'];
            $data['arr_sheet'] = $imported_data['arr_sheet'];
            $data['sheet_name'] = $this->input->post('sheet_name');
            $data['back_destination'] = "invitados/import/";
        
        //Cargar vista
            $data['head_title'] = 'Usuarios';
            $data['view_a'] = 'common/bs5/import_result_v';
            //$data['nav_2'] = $this->views_folder . 'menus/explore_v';

        $this->App_model->view(RCI_TPL_APP, $data);
    }

    /**
     * Ejecuta (e) la importación de metadatos invitados con archivo Excel
     * 2025-08-07
     */
    function import_meta_e()
    {
        //Proceso
        $this->load->library('excel');            
        $imported_data = $this->excel->arr_sheet_default($this->input->post('sheet_name'));
        
        if ( $imported_data['status'] == 1 )
        {
            $data = $this->Rci_model->import_users_meta($imported_data['arr_sheet']);
        }

        //Cargue de variables
            $data['status'] = $imported_data['status'];
            $data['message'] = $imported_data['message'];
            $data['arr_sheet'] = $imported_data['arr_sheet'];
            $data['sheet_name'] = $this->input->post('sheet_name');
            $data['back_destination'] = "invitados/import/meta";
        
        //Cargar vista
            $data['head_title'] = 'Invitados';
            $data['view_a'] = 'common/bs5/import_result_v';
            //$data['nav_2'] = $this->views_folder . 'menus/explore_v';

        $this->App_model->view(RCI_TPL_APP, $data);
    }
}