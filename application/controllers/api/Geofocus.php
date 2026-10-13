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
     * Calcula y guarda la configuración del escenario, e invalida la descripción anterior.
     * Informa éxito solo cuando la cabecera se guarda para actualizar el estado de la interfaz.
     * 2026-10-11
     */
    function calcular_priorizacion()
    {
        $http_status = 200;
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
            $aRow['descripcion_generada'] = '';
            $aRow['updater_id'] = $this->session->userdata('user_id');
            $aRow['updated_at'] = date('Y-m-d H:i:s');
            $data['saved_id'] = $this->Geofocus_model->save_priorizacion($aRow);
            $data['status'] = $data['saved_id']['saved_id'] > 0 ? 'success' : 'error';
            if ($data['status'] === 'error') {
                $http_status = 500;
                $data['message'] = 'No se pudo guardar la configuración calculada.';
            }
        } else {
            $http_status = 400;
            $data = [
                'status' => 'error',
                'message' => 'Datos JSON inválidos'
            ];
        }

        //Salida JSON
        $this->output->set_status_header($http_status)->set_content_type('application/json')->set_output(json_encode($data));
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
     * Generar con IA la descripción en texto de la parametrización de la priorización del usuario
     * Registra la petición mediante Airequest_model y guarda la descripción solo si tiene éxito.
     * 2026-10-10
     */
    function get_descripcion($priorizacionId)
    {
        $this->load->model('Airequest_model');

        // Instrucción para geofocus
        $system_instruction_base = $this->Airequest_model->system_instruction('geofocus-2');
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
        
        // El modelo ejecuta la API y registra la solicitud, la respuesta y los errores.
        try {
            $responseData = $this->Airequest_model->generate($request_settings, [
                'tool' => 'geofocus',
                'tool_type_request' => 'get_descripcion',
                'response_type' => 'plain_text',
                'creator_id' => $this->session->userdata('user_id')
            ]);
        } catch (\Throwable $e) {
            $responseData = [
                'status' => 'error',
                'ai_request_id' => null,
                'response_text' => null,
                'error_message' => $e->getMessage()
            ];
        }

        $data = [
            'status' => $responseData['status'],
            'ai_request_id' => $responseData['ai_request_id'],
            'descripcion_generada' => $responseData['response_text'],
            'saved_id' => 0,
            'error_message' => $responseData['error_message']
        ];

        // Conservar la descripción anterior cuando falle la generación o su registro.
        if ($responseData['status'] === 'success') {
            $aRow = [
                'descripcion_generada' => $responseData['response_text'],
                'updater_id' => $this->session->userdata('user_id'),
                'updated_at' => date('Y-m-d H:i:s')
            ];
            $data['saved_id'] = $this->Db_model->save(
                'gf_priorizaciones', 'id = ' . (int) $priorizacionId, $aRow
            );
        } else {
            // Activar el manejo de errores de Axios sin reemplazar el texto en pantalla.
            $this->output->set_status_header(500);
        }

        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    /**
     * POST :: Recibe priorizacion_id y prompt y devuelve una propuesta de configuración generada con IA.
     * Valida el acceso, consulta el catálogo de la capa y registra la petición mediante Airequest_model.
     * Valida la propuesta contra el catálogo vigente para permitir su aceptación en la interfaz.
     * Admite JSON directo o un único bloque Markdown de código que lo envuelva.
     * @return void :: Salida JSON con status, ai_request_id, contexto, configuracion y error_message.
     * 2026-10-11
     */
    function get_ai_variables_config()
    {
        $data = [
            'status' => 'error',
            'ai_request_id' => null,
            'priorizacion_id' => null,
            'key_capa' => null,
            'configuracion' => null,
            'configuracion_validada' => false,
            'error_message' => null
        ];
        $http_status = 500;
        $previous_db_debug = $this->db->db_debug;
        $this->db->db_debug = false;

        try {
            if ($this->input->method() !== 'post') {
                $http_status = 405;
                $this->output->set_header('Allow: POST');
                throw new InvalidArgumentException('Esta solicitud debe enviarse mediante POST.');
            }
            $user_id = (int) $this->session->userdata('user_id');
            if (!$this->session->userdata('logged') || $user_id <= 0) {
                $http_status = 401;
                throw new RuntimeException('Es necesario iniciar sesión para solicitar una configuración.');
            }

            $priorizacion_id = filter_var($this->input->post('priorizacion_id'), FILTER_VALIDATE_INT,
                ['options' => ['min_range' => 1]]);
            $prompt = $this->input->post('prompt');
            if ($priorizacion_id === false || !is_string($prompt)
                || !mb_check_encoding($prompt, 'UTF-8') || trim($prompt) === '') {
                $http_status = 400;
                throw new InvalidArgumentException('Envía un priorizacion_id válido y un prompt con contenido.');
            }
            $data['priorizacion_id'] = $priorizacion_id;
            $priorizacion = $this->Db_model->row_id('gf_priorizaciones', $priorizacion_id);
            if (!$priorizacion) {
                $http_status = 404;
                throw new RuntimeException('La priorización solicitada no existe.');
            }
            $is_admin = in_array((int) $this->session->userdata('role'), [1, 2], true);
            if (!$is_admin && (int) $priorizacion->creator_id !== $user_id) {
                $http_status = 403;
                throw new RuntimeException('No tienes permiso para configurar esta priorización.');
            }

            // La capa y los indicadores proceden del servidor, no de campos enviados por el cliente.
            $data['key_capa'] = $priorizacion->key_capa;
            $catalog = $this->Geofocus_model->get_ai_variables_catalog($priorizacion->key_capa);
            $available_ids = [];
            foreach ($catalog as $variable) {
                if ($variable['cantidad_valores'] > 0) $available_ids[] = $variable['id'];
            }
            if (empty($available_ids)) {
                $http_status = 422;
                throw new RuntimeException('La capa no tiene variables activas con mediciones reportadas.');
            }

            // El esquema permite una selección vacía para objetivos que requieren aclaración.
            $schema = [
                'type' => 'object',
                'properties' => [
                    'variables' => [
                        'type' => 'array',
                        'maxItems' => count($available_ids),
                        'items' => [
                            'type' => 'object',
                            'properties' => [
                                'id' => ['type' => 'integer', 'enum' => $available_ids],
                                'tipo_priorizacion' => ['type' => 'integer', 'enum' => [1, -1]],
                                'puntaje' => ['type' => 'integer', 'minimum' => 1, 'maximum' => 100]
                            ],
                            'required' => ['id', 'tipo_priorizacion', 'puntaje'],
                            'additionalProperties' => false
                        ]
                    ],
                    'observaciones' => ['type' => 'string']
                ],
                'required' => ['variables', 'observaciones'],
                'additionalProperties' => false
            ];
            $input_json = json_encode([
                'priorizacion_id' => $priorizacion_id,
                'key_capa' => $priorizacion->key_capa,
                'prompt' => trim($prompt),
                'catalogo_variables' => $catalog
            ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
            if ($input_json === false) {
                throw new RuntimeException('No se pudo preparar el catálogo para la solicitud de IA.');
            }

            $this->load->model('Airequest_model');
            $instruction = $this->Airequest_model->system_instruction('geofocus-variables-config-1');
            if (trim($instruction) === '') {
                throw new RuntimeException('La instrucción de configuración de variables está vacía.');
            }
            $request_settings = [
                'model' => 'gemini-2.5-flash-lite',
                'system_instruction_parts' => [['text' => $instruction]],
                'contents' => [['role' => 'user', 'parts' => [['text' => $input_json]]]],
                'generationConfig' => [
                    'temperature' => 0.2,
                    'maxOutputTokens' => 4096,
                    'responseFormat' => [
                        // responseFormat usa el enum de v1beta para el formato de texto.
                        'text' => ['mimeType' => 'APPLICATION_JSON', 'schema' => $schema]
                    ]
                ]
            ];
            $response = $this->Airequest_model->generate($request_settings, [
                'tool' => 'geofocus',
                'tool_type_request' => 'get_ai_variables_config',
                'response_type' => 'other',
                'creator_id' => $user_id
            ]);
            $data['ai_request_id'] = $response['ai_request_id'];
            if ($response['status'] !== 'success') {
                throw new RuntimeException($response['error_message'] ?: 'No se pudo generar la configuración.');
            }

            // Separar errores de JSON del rechazo de una propuesta que no cumple las reglas de la capa.
            // 2026-10-11: quitar solo el envoltorio externo; conservar el texto original en ai_requests.
            $response_text = trim($response['response_text']);
            if (preg_match('/\A```(?:json)?[ \t]*\R(.*?)\R```[ \t]*\z/is', $response_text, $matches)) {
                $response_text = trim($matches[1]);
            }
            $config = json_decode($response_text);
            if (json_last_error() !== JSON_ERROR_NONE) {
                $http_status = 502;
                throw new RuntimeException('Gemini no devolvió un JSON válido.');
            }
            try {
                $data['configuracion'] = $this->Geofocus_model->validate_ai_variables_config($config, $priorizacion->key_capa);
            } catch (InvalidArgumentException $e) {
                $http_status = 422;
                throw $e;
            }
            $data['configuracion_validada'] = true;
            $data['status'] = 'success';
            $http_status = 200;
        } catch (\Throwable $e) {
            $data['error_message'] = $e->getMessage();
        } finally {
            $this->db->db_debug = $previous_db_debug;
        }

        $this->output->set_status_header($http_status)->set_content_type('application/json')
            ->set_output(json_encode($data, JSON_UNESCAPED_UNICODE | JSON_INVALID_UTF8_SUBSTITUTE));
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
