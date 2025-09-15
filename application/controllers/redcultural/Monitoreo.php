<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Monitoreo extends CI_Controller {
        
// Variables generales
//-----------------------------------------------------------------------------
    public $views_folder = 'redcultural/monitoreo/';
    public $url_controller = RCI_URL_APP . 'monitoreo/';

// Constructor
//-----------------------------------------------------------------------------
    
    function __construct()
    {
        parent::__construct();

        $this->load->model('Rci_model');
        
        //Local time set
        date_default_timezone_set("America/Bogota");
    }

    /**
     * Perfiles de invitados más visitados
     */
    function visitas()
    {
        $data['head_title'] = 'Más visitados';
        $data['view_a'] = $this->views_folder . 'visitas_v';
        $data['visitas'] = $this->Rci_model->visitas();

        $this->App_model->view(RCI_TPL_APP, $data);
    }

    /**
     * Perfiles de invitados más visitados
     */
    function intereses()
    {
        $data['head_title'] = 'Intereses entre usuarios';
        $data['view_a'] = $this->views_folder . 'intereses_v';
        $data['intereses'] = $this->Rci_model->intereses();

        $this->App_model->view(RCI_TPL_APP, $data);
    }

    /**
     * Perfiles de invitados más visitados
     */
    function contenidos_ia()
    {
        $data['head_title'] = 'Contenidos IA Generados';
        $data['view_a'] = $this->views_folder . 'contenidos_ia_v';
        $data['contenidos_ia'] = $this->Rci_model->contenidos_ia();

        $this->App_model->view(RCI_TPL_APP, $data);
    }
}