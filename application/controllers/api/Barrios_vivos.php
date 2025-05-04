<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Barrios_vivos extends CI_Controller {
        
// Variables generales
//-----------------------------------------------------------------------------
    public $views_folder = 'app/barrios_vivos/';
    public $url_controller = URL_APP . 'barrios_vivos/';

// Constructor
//-----------------------------------------------------------------------------
    
    function __construct()
    {
        parent::__construct();

        $this->load->model('Barrios_vivos_model');
        
        //Local time set
        date_default_timezone_set("America/Bogota");
    }

    /**
     * Primera función
     */
    function index($laboratorioId = 0)
    {
        if ( $laboratorioId > 0 ) {
            if ( $this->session->userdata('user_id') > 0 ) {
                redirect("app/laboratorios/asistentes/{$laboratorioId}");
            } else {
                redirect("app/laboratorios/info/{$laboratorioId}");
            }
        } else {
            redirect("app/laboratorios/explorar");
        }
    }

// EXPLORACIÓN
//-----------------------------------------------------------------------------

    /**
     * Listado de laboratorios, filtrados por búsqueda, JSON
     * 2025-04-17
     */
    function get($num_page = 1, $per_page = 100)
    {
        if ( $per_page > 250 ) $per_page = 250;

        $this->load->model('Search_model');
        $filters = $this->Search_model->filters();

        $data = $this->Barrios_vivos_model->get($filters, $num_page, $per_page);
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

// INFORMACIÓN
//-----------------------------------------------------------------------------

    function info($laboratorioId)
    {
        $data = $this->Accion_model->basic($laboratorioId);
        //$data['view_a'] = $this->views_folder . 'info_v';
        $data['view_a'] = 'common/row_details_v';
        $data['back_link'] = $this->url_controller . 'explorar';

        $this->App_model->view(TPL_FRONT, $data);
    }

// CREACIÓN DE UNA ACCIÓN
//-----------------------------------------------------------------------------

    /**
     * AJAX JSON
     * Crear o actualizar el registro de una laboratorio CC
     * 2022-09-03
     */
    function save()
    {
        $data = $this->Barrios_vivos_model->save();
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    /**
     * Eliminar un laboratorio
     * 2025-04-17
     * @param int $laboratorioId
     * @return $data
     */
    function delete_selected()
    {
        $selected = explode(',', $this->input->post('selected'));
        $data['qty_deleted'] = 0;
        
        foreach ( $selected as $rowId ) {
            $data['qty_deleted'] += $this->Barrios_vivos_model->delete($rowId);
        }
        
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

// DETALLES DE LOS LABORATORIOS
//-----------------------------------------------------------------------------

    /**
     * AJAX JSON
     * Guardar un registro en la tabla bv_laboratorios_detalles
     * 2025-04-18
     */
    function save_detail()
    {
        $aRow = $this->Db_model->arr_row();

        $condition = "laboratorio_id = {$aRow['laboratorio_id']} AND tipo_detalle = {$aRow['tipo_detalle']} AND cod_detalle = '{$aRow['cod_detalle']}'";
        $data['saved_id'] = $this->Db_model->save('bv_laboratorios_detalles', $condition, $aRow);

        //Salida JSON
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    /**
     * AJAX JSON
     * Eliminar un detalle de un laboratorio
     * 2025-04-18
     */
    function delete_detail($laboratorioId, $detalleId)
    {
        $condition = "laboratorio_id = {$laboratorioId} AND id = {$detalleId}";
        $data['qty_deleted'] = $this->Barrios_vivos_model->delete_details($condition);

        //Salida JSON
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    /**
     * AJAX JSON
     * Listado de datalles de laboratorios, filtradas
     * 2025-04-18
     */
    function get_details()
    {
        $this->load->model('Search_model');
        $filters = $this->Search_model->filters();

        $details = $this->Barrios_vivos_model->get_details($filters);
        $data['details'] = $details->result();
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }
}