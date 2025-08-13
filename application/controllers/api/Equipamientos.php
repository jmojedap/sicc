<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Equipamientos extends CI_Controller {
        
// Variables generales
//-----------------------------------------------------------------------------
    public $views_folder = 'app/equipamientos/';
    public $url_controller = URL_APP . 'equipamientos/';

// Constructor
//-----------------------------------------------------------------------------
    
    function __construct()
    {
        parent::__construct();

        $this->load->model('Equipamiento_model');
        
        //Local time set
        date_default_timezone_set("America/Bogota");
    }

    /**
     * Primera función
     */
    function index($equipamientoId = 0)
    {
        if ( $equipamientoId > 0 ) {
            if ( $this->session->userdata('user_id') > 0 ) {
                redirect("app/equipamientos/asistentes/{$equipamientoId}");
            } else {
                redirect("app/equipamientos/info/{$equipamientoId}");
            }
        } else {
            redirect("app/equipamientos/explorar");
        }
    }

// EXPLORACIÓN
//-----------------------------------------------------------------------------

    /**
     * Listado de equipamientos, filtrados por búsqueda, JSON
     * 2025-04-17
     */
    function get($num_page = 1, $per_page = 100)
    {
        if ( $per_page > 250 ) $per_page = 250;

        $this->load->model('Search_model');
        $filters = $this->Search_model->filters();

        $data = $this->Equipamiento_model->get($filters, $num_page, $per_page);
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

// INFORMACIÓN
//-----------------------------------------------------------------------------

    function info($equipamientoId)
    {
        $data = $this->Accion_model->basic($equipamientoId);
        //$data['view_a'] = $this->views_folder . 'info_v';
        $data['view_a'] = 'common/row_details_v';
        $data['back_link'] = $this->url_controller . 'explorar';

        $this->App_model->view(TPL_FRONT, $data);
    }

// CREACIÓN DE UNA ACCIÓN
//-----------------------------------------------------------------------------

    /**
     * AJAX JSON
     * Crear o actualizar el registro de una equipamiento CC
     * 2022-09-03
     */
    function save()
    {
        $data = $this->Equipamiento_model->save();
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    /**
     * Eliminar un equipamiento
     * 2025-04-17
     * @param int $equipamientoId
     * @return $data
     */
    function delete_selected()
    {
        $selected = explode(',', $this->input->post('selected'));
        $data['qty_deleted'] = 0;
        
        foreach ( $selected as $rowId ) {
            $data['qty_deleted'] += $this->Equipamiento_model->delete($rowId);
        }
        
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

// DETALLES DE LOS LABORATORIOS
//-----------------------------------------------------------------------------

    /**
     * AJAX JSON
     * Guardar un registro en la tabla sie_equipamientos_detalles
     * 2025-04-18
     */
    function save_detail()
    {
        $aRow = $this->Db_model->arr_row();

        $condition = "equipamiento_id = {$aRow['equipamiento_id']} AND tipo_detalle = {$aRow['tipo_detalle']} AND cod_detalle = '{$aRow['cod_detalle']}'";
        $data['saved_id'] = $this->Db_model->save('sie_equipamientos_detalles', $condition, $aRow);

        //Salida JSON
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    /**
     * AJAX JSON
     * Eliminar un detalle de un equipamiento
     * 2025-04-18
     */
    function delete_detail($equipamientoId, $detalleId)
    {
        $condition = "equipamiento_id = {$equipamientoId} AND id = {$detalleId}";
        $data['qty_deleted'] = $this->Equipamiento_model->delete_details($condition);

        //Salida JSON
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    /**
     * AJAX JSON
     * Listado de datalles de equipamientos, filtradas
     * 2025-04-18
     */
    function get_details()
    {
        $this->load->model('Search_model');
        $filters = $this->Search_model->filters();

        $details = $this->Equipamiento_model->get_details($filters);
        $data['details'] = $details->result();
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }
}