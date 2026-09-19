<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Geofocus extends CI_Controller
{

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
        if (is_null($accion_id)) {
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
            $data['saved_id'] = $this->Geofocus_model->save_priorizacion($aRow);
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
     * Recalcular los valores de una variable, normalizando y actualizando los campos de resumen estadístico
     * 2025-09-10
     */
    function recalcular_variable($variable_id)
    {
        // Normalizar valores de la variable
        $this->Geofocus_model->normalizarValores('variable_id', $variable_id);

        // Actualizar el orden de los valores
        $this->Geofocus_model->actualizarOrden('variable_id', $variable_id);

        // Actualizar el campo gf_territorios_valor.poligono_id con el valor de gf_territorios.poligono_id
        $this->Geofocus_model->actualizar_poligono_id($variable_id);

        // Actualizar resumen de la variable
        $this->Geofocus_model->update_variable_summary($variable_id);

        $data['status'] = 1;
        $data['message'] = "Variable con ID {$variable_id} recalculada y normalizada correctamente.";

        //Salida JSON
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    /**
     * Obtener los valores de la tabla gf_territorios_valor, para un campo y valor específico
     * 2026-09-18
     */
    function get_variable_valores($field = 'priorizacion_id', $fieldValue = 1)
    {
        $allowedFields = ['priorizacion_id', 'variable_id'];
        if (!in_array($field, $allowedFields, true)) {
            $data = [
                'status' => 0,
                'message' => 'Campo de consulta no permitido',
                'valores' => [],
                'summary' => ['sum' => 0, 'avg' => 0, 'min' => 0, 'max' => 0, 'count' => 0, 'std_dev' => 0]
            ];
            $this->output
                ->set_status_header(400)
                ->set_content_type('application/json')
                ->set_output(json_encode($data));
            return;
        }

        $this->db->select('gf_territorios.poligono_id AS code, gf_territorios.nombre AS name, gf_territorios_valor.valor AS value');
        $this->db->join('gf_territorios', 'gf_territorios.id = gf_territorios_valor.territorio_id', 'left');
        $this->db->where('gf_territorios_valor.' . $field, (int) $fieldValue);
        $this->db->order_by('gf_territorios_valor.valor', 'DESC');
        $valores = $this->db->get('gf_territorios_valor');

        $data['valores'] = [];
        foreach ($valores->result_array() as $row) {
            $data['valores'][] = [
                'code' => is_null($row['code']) ? null : (string) $row['code'],
                'name' => $row['name'],
                'value' => is_numeric($row['value']) ? (float) $row['value'] : null
            ];
        }

        $summary = $this->pml->field_summary($valores, 'value');
        $data['summary'] = [
            'sum' => (float) $summary['sum'],
            'avg' => (float) $summary['avg'],
            'min' => (float) $summary['min'],
            'max' => (float) $summary['max'],
            'count' => (int) $summary['count'],
            'std_dev' => (float) $summary['std_dev']
        ];
        //Salida JSON
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    /**
     * Genear con IA la descripción en texto de la parametrización de la priorización del usuario
     * 2026-07-26
     */
    function get_descripcion($priorizacionId)
    {
        $this->load->library('Gemini_client');

        // Instrucción para geofocus
        $system_instruction_base = $this->gemini_client->system_instruction('geofocus-1');
        $system_instruction_parts[] = ['text' => $system_instruction_base];

        $request_settings['system_instruction_parts'] = $system_instruction_parts;
        
        // Contenidos enviados en la solicitud
        $inputUser = $this->input->post('texto_parametrizacion');

        $request_settings['contents'] = [
            [
                "role" => "user",
                "parts" => [
                    [
                        "text" => "Parametrización realizada por el usuario:\n{$inputUser}"
                    ]
                ]
            ]
        ];

        // Sobre la generación de contenido
        $request_settings['generationConfig'] = [
            "temperature" => 1,
            "topK" => 40,
            "topP" => 0.95,
            "maxOutputTokens" => 8192,
            "responseMimeType" => "text/plain"
        ];
        
        $responseData = $this->gemini_client->generate($request_settings);

        // Datos de la fila que se va a actualizar
        $aRow['descripcion_generada'] = $responseData['response_text'] ?? 'Ocurrió un error al obtener al generar la descripción.';
        $aRow['updater_id'] = $this->session->userdata('user_id');
        $aRow['updated_at'] = date('Y-m-d H:i:s');

        // Datos de la respuesta
        $data['descripcion_generada'] = $aRow['descripcion_generada'];
        $data['saved_id'] = $this->Db_model->save('gf_priorizaciones', "id = {$priorizacionId}", $aRow);

        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

// GESTIÓN DE VARIABLES
//-----------------------------------------------------------------------------

    /**
     * Guardar datos de un registro de la tabla gf_variables
     * 2026-08-31
     */
    function variables_save()
    {
        $arr_row = $this->Db_model->arr_row();
        $saved_id = $this->Db_model->save_id('gf_variables', $arr_row);

        $data['saved_id'] = $saved_id;

        if ($saved_id) {
            // Actualizar los campos de resumen estadístico de la tabla gf_variables
            $data['summary_updated'] = $this->Geofocus_model->update_variable_summary($saved_id);
        }

        //Salida JSON
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }


    /**
     * Actualiza los campos de resumen estadístico de la tabla gf_variables, a partir de los valores de gf_territorios_valor
     * 2026-08-31
     */
    function update_variable_summary($variable_id)
    {
        $data = $this->Geofocus_model->update_variable_summary($variable_id);

        //Salida JSON
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }


    function upddate_variables_summary()
    {
        $variables = $this->Geofocus_model->get_variables();
        $updating = [];
        foreach ($variables->result() as $row) {
            $updating[$row->id] = $this->Geofocus_model->update_variable_summary($row->id);
        }

        $data['updating'] = $updating;

        //Salida JSON
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

}
