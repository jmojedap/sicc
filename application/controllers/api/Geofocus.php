<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Geofocus extends CI_Controller{

// Variables generales
//-----------------------------------------------------------------------------
    public $url_controller = URL_API . 'geofocus/';

// Constructor
//-----------------------------------------------------------------------------
    
    function __construct() 
    {
        parent::__construct();
        $this->load->model('Geofocus_model');
        date_default_timezone_set("America/Bogota");    //Para definir hora local
    }
    
    function index($accion_id = NULL)
    {
        if ( is_null($accion_id) ) {
            redirect("app/geofocus/explorar/");
        } else {
            redirect("app/geofocus/info/{$accion_id}");
        }
    }

// CRUD Priorizaciones
//-----------------------------------------------------------------------------

    /**
     * Listado de priorizaciones
     * 2024-10-17
     */
    function get_priorizaciones()
    {
        $priorizaciones = $this->Geofocus_model->get_priorizaciones();
        $data['list'] = $priorizaciones->result();
        //Salida JSON
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    /**
     * AJAX JSON
     * Crear o actualizar el registro de una priorización
     * 2024-10-17
     */
    function save_priorizacion()
    {
        $data = $this->Geofocus_model->save_priorizacion();
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    /**
     * AJAX
     * Eliminar un registro, devuelve la cantidad de registros eliminados
     * 2024-10-28
     */
    function delete_priorizacion($priorizacionId, $creatorId)
    {
        $condition = "id = {$priorizacionId} AND creator_id = {$creatorId}";
        
        $this->db->where($condition);
        $rows = $this->db->get('gf_priorizaciones');
        $data['qty_deleted'] = 0;

        foreach ($rows->result() as $row) {
            $data['qty_deleted'] += $this->Geofocus_model->delete($row->id);
        }
        
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }


// Ejecución de cálculos
//-----------------------------------------------------------------------------

    /**
     * Calcular la priorización
     * 2024-09-14
     */
    function calcular_priorizacion()
    {
        // Obtener el contenido JSON enviado por POST
        $jsonData = file_get_contents("php://input");
        $settings = json_decode($jsonData, true);

        // Verificar si la decodificación fue exitosa
        if (json_last_error() === JSON_ERROR_NONE) {
            // Proceso para organizar los datos
            $data = $this->Geofocus_model->calcularPriorizacion($settings);

            // Preparar los datos para insertar en la base de datos
            $aRow['configuracion'] = json_encode($settings['variables']); // Guardar el JSON original
            $aRow['id'] = $settings['priorizacion']['id'];
            $daa['saved_id'] = $this->Geofocus_model->save_priorizacion($aRow);
        } else {
            $data = [
                'status' => 'error',
                'message' => 'Datos JSON inválidos'
            ];
        }
    
        //Salida JSON
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    /**
     * Actualizar el valor de la gf_territorios_valor.valor_normalizado
     * En una escala estandarizada mediante el método Z-score
     * 2024-10-12
     */
    function normalizar_variable($variableId)
    {
        $data = $this->Geofocus_model->normalizarValores('variable_id', $variableId);

        //Salida JSON
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    function get_variable_valores($field = 'priorizacion_id', $fieldValue = 1)
    {
        $this->db->select('gf_territorios.poligono_id AS code, gf_territorios.nombre AS name, gf_territorios_valor.valor AS value');
        $this->db->join('gf_territorios', 'gf_territorios.poligono_id = gf_territorios_valor.poligono_id', 'left');
        $this->db->where($field, $fieldValue);
        $this->db->order_by('gf_territorios_valor.valor', 'DESC');
        $this->db->limit(500);
        $valores = $this->db->get('gf_territorios_valor');

        $data['valores'] = $valores->result();
        $data['summary'] = $this->pml->field_summary($valores, 'value');
        //Salida JSON
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

}