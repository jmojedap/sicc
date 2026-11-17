<?php
class Airequest_model extends CI_Model{

    // 2026-11-17: cupo compartido por las herramientas de IA, por usuario y ventana móvil de días.
    private $max_tokens = 250000;
    private $token_window_days = 7;

    /**
     * Prepara los datos y las vistas para consultar un registro de ai_requests.
     */
    function basic($airequest_id)
    {
        $data['airequest_id'] = $airequest_id;
        $data['row'] = $this->Db_model->row_id('ai_requests', $airequest_id);
        $data['head_title'] = $data['row']->id;
        $data['view_a'] = 'admin/ai_requests/ai_request_v';
        $data['nav_2'] = 'admin/ai_requests/menus/ai_request_v';

        return $data;
    }

    /**
     * Suma los tokens del usuario desde hace $days días hasta la fecha y hora actuales de la BD.
     * Usa creator_id, el mismo campo utilizado al registrar las solicitudes, y filtra por created_at.
     * Incluye todos los estados; los tokens NULL y los períodos sin solicitudes se contabilizan como cero.
     * Cuenta también los registros del período, aunque no tengan tokens registrados.
     *
     * @param int $user_id :: ID positivo del usuario que creó las solicitudes.
     * @param int $days :: Cantidad positiva de días hacia atrás (ventana móvil, no días calendario).
     * @return array :: input_tokens, output_tokens, total_tokens y total_requests, expresados como enteros.
     * @throws InvalidArgumentException :: Si el usuario o la cantidad de días no son enteros positivos.
     * @throws RuntimeException :: Si falla la consulta del consumo.
     * 2026-11-17
     */
    public function get_token_totals($user_id, $days)
    {
        $options = ['options' => ['min_range' => 1]];
        $user_id = filter_var($user_id, FILTER_VALIDATE_INT, $options);
        $days = filter_var($days, FILTER_VALIDATE_INT, $options);
        if ($user_id === false || $days === false) {
            throw new InvalidArgumentException('El usuario y la cantidad de días deben ser enteros positivos.');
        }

        $query = $this->db
            ->select('COALESCE(SUM(input_tokens), 0) AS input_tokens, COALESCE(SUM(output_tokens), 0) AS output_tokens, COUNT(*) AS total_requests', false)
            ->where('creator_id', $user_id)
            ->where('created_at >=', 'DATE_SUB(NOW(), INTERVAL ' . $days . ' DAY)', false)
            ->where('created_at <=', 'NOW()', false)
            ->get('ai_requests');
        if ($query === false) {
            throw new RuntimeException('No se pudo consultar el consumo de tokens.');
        }
        $totals = $query->row_array();

        $input_tokens = (int) $totals['input_tokens'];
        $output_tokens = (int) $totals['output_tokens'];
        return [
            'input_tokens' => $input_tokens,
            'output_tokens' => $output_tokens,
            'total_tokens' => $input_tokens + $output_tokens,
            'total_requests' => (int) $totals['total_requests']
        ];
    }

    /**
     * Obtiene el consumo y el permiso de IA del usuario durante los últimos siete días.
     * Agrega el cupo centralizado, porcentaje real (puede superar 100%) y saldo nunca negativo.
     * Un cupo cero deshabilita la generación; no se reserva saldo entre solicitudes concurrentes.
     * @param int $user_id :: Usuario cuyo consumo se consulta.
     * @return array :: Totales, max_tokens, usage_percentage, remaining_tokens y can_generate.
     * 2026-11-17
     */
    public function get_token_usage($user_id)
    {
        $usage = $this->get_token_totals($user_id, $this->token_window_days);
        $usage['max_tokens'] = $this->max_tokens;
        $usage['usage_percentage'] = $this->max_tokens > 0
            ? round($usage['total_tokens'] / $this->max_tokens * 100, 1) : 0;
        $usage['remaining_tokens'] = max(0, $this->max_tokens - $usage['total_tokens']);
        $usage['can_generate'] = $this->max_tokens > 0 && $usage['total_tokens'] < $this->max_tokens;
        return $usage;
    }

    /**
     * Indica si el usuario puede iniciar otra petición de IA según su consumo registrado.
     * Una petición iniciada con saldo puede superar el cupo al finalizar y bloquear las siguientes.
     * Si también se necesitan los totales, usar directamente can_generate de get_token_usage().
     * @param int $user_id :: Usuario cuyo permiso se consulta.
     * @return bool :: true si el consumo es menor que el cupo y este es positivo.
     * 2026-11-17
     */
    public function can_generate($user_id)
    {
        return $this->get_token_usage($user_id)['can_generate'];
    }

    /**
     * Lee una instrucción Markdown y devuelve el texto sin saltos de línea ni espacios en los extremos.
     * Permite preparar instrucciones desde el modelo sin cargar una biblioteca de proveedor.
     * @param string $key :: Nombre del archivo de instrucción, sin la extensión .md.
     * @param string $folder :: Carpeta relativa a PATH_CONTENT que contiene las instrucciones.
     * @return string :: Instrucción normalizada para incluirla en la solicitud de IA.
     * 2026-10-10
     */
    public function system_instruction($key = 'ayudante', $folder = 'ai_system_instructions')
    {
        $path = PATH_CONTENT . $folder . '/' . $key . '.md';
        if (!is_file($path) || !is_readable($path)) {
            throw new RuntimeException('No se pudo leer la instrucción del sistema.');
        }
        $file_content = file_get_contents($path);
        if ($file_content === false) {
            throw new RuntimeException('No se pudo leer la instrucción del sistema.');
        }

        $file_content = str_replace("\r\n", "\n", $file_content);
        $file_content = str_replace("\n", ' ', $file_content);
        return trim($file_content);
    }

    /**
     * Coordina el registro y la generación con Gemini, y devuelve su resultado.
     * Valida los parámetros, maneja las excepciones y delega el guardado a los métodos privados.
     * Comprueba el cupo del creador antes de registrar o enviar la petición a cualquier herramienta.
     *
     * @param array $request_settings :: Instrucciones, contenidos y configuración de Gemini.
     * @param array $context :: tool y tool_type_request son obligatorios;
     *                         response_type y creator_id son opcionales.
     * @return array :: Estado, ai_request_id, texto, detalles y mensaje de error.
     * 2026-11-17
     */
    public function generate(array $request_settings, array $context)
    {
        foreach (['tool', 'tool_type_request'] as $field) {
            if (empty($context[$field])) {
                throw new InvalidArgumentException("Falta el campo {$field}");
            }
        }

        $context['response_type'] = $context['response_type'] ?? 'plain_text';
        if (!in_array($context['response_type'], ['plain_text', 'markdown', 'html', 'other'], true)) {
            throw new InvalidArgumentException('response_type no permitido');
        }

        $request_settings['model'] = $request_settings['model'] ?? 'gemini-2.5-flash-lite';

        $result = [
            'status' => 'error',
            'ai_request_id' => null,
            'model_id' => $request_settings['model'],
            'response_text' => null,
            'response_details' => [],
            'error_message' => null
        ];

        // Manejar los errores de BD sin interrumpir la salida JSON del controlador.
        $previous_db_debug = $this->db->db_debug;
        $this->db->db_debug = false;

        try {
            // 2026-11-17: comprobar y registrar el mismo usuario; no crear intentos bloqueados por cupo.
            $context['creator_id'] = $context['creator_id'] ?? $this->session->userdata('user_id');
            if (!$this->can_generate($context['creator_id'])) {
                $result['error_message'] = 'Has alcanzado el límite de tokens de los últimos '
                    . $this->token_window_days . ' días. Podrás volver a usar la IA cuando se libere consumo de ese período.';
                return $result;
            }

            $this->load->library('Gemini_client');
            $request_settings['generationConfig'] = $request_settings['generationConfig']
                ?? $this->gemini_client->generation_config_default();
            $request_id = $this->create_request($request_settings, $context);

            // No ejecutar la API si no se pudo registrar la solicitud.
            if ($request_id === false) {
                $result['error_message'] = 'No se pudo registrar la solicitud de IA.';
                return $result;
            }

            $result['ai_request_id'] = $request_id;
            $response_json = null;

            try {
                $api_response = $this->gemini_client->generate($request_settings);
                $details = $api_response['response_details'] ?? [];
                $result['response_details'] = $details;

                // Usar el texto real para no guardar el mensaje genérico de la biblioteca.
                $result['response_text'] = $details['candidates'][0]['content']['parts'][0]['text']
                    ?? null;
                $error = !empty($api_response['error'])
                    ? $api_response['error']
                    : ($details['error']['message'] ?? null);

                if (!$error && trim($result['response_text'] ?? '') === '') {
                    $error = 'Gemini no devolvió contenido de texto.';
                }

                $response_json = json_encode($details, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
                if ($response_json === false) {
                    $response_json = null;
                    throw new RuntimeException('Respuesta JSON inválida: ' . json_last_error_msg());
                }

                $result['status'] = $error ? 'error' : 'success';
                $result['error_message'] = $error;
            } catch (\Throwable $e) {
                $result['status'] = 'error';
                $result['error_message'] = $e->getMessage();
            }

            return $this->complete_request($request_id, $result, $response_json);
        } catch (\Throwable $e) {
            // 2026-11-17: conservar la respuesta de error ante fallos de consulta, preparación o registro.
            $result['status'] = 'error';
            $result['error_message'] = $e->getMessage();
            return $result;
        } finally {
            $this->db->db_debug = $previous_db_debug;
        }
    }

    /**
     * Serializa el cuerpo de Gemini y crea el registro pending antes de ejecutar la API.
     * Excluye la URL y la clave de API del JSON guardado.
     *
     * @param array $request_settings :: Configuración con los valores por defecto ya aplicados.
     * @param array $context :: Contexto validado de la herramienta y del usuario.
     * @return int|string|false :: ID del registro creado o false si falla la inserción.
     */
    private function create_request(array $request_settings, array $context)
    {
        $request_json = json_encode([
            'contents' => $request_settings['contents'],
            'system_instruction' => ['parts' => $request_settings['system_instruction_parts']],
            'generationConfig' => $request_settings['generationConfig']
        ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

        if ($request_json === false) {
            throw new InvalidArgumentException('Solicitud JSON inválida: ' . json_last_error_msg());
        }

        $inserted = $this->db->insert('ai_requests', [
            'tool' => $context['tool'],
            'tool_type_request' => $context['tool_type_request'],
            'proveedor' => 'gemini',
            'modelo' => $request_settings['model'],
            'request_json' => $request_json,
            'response_type' => $context['response_type'],
            'status' => 'pending',
            'creator_id' => $context['creator_id']
                ?? $this->session->userdata('user_id')
                ?? null
        ]);

        return $inserted ? $this->db->insert_id() : false;
    }

    /**
     * Guarda la respuesta, los tokens y el estado success o error en el registro existente.
     * Si falla la actualización, devuelve un error para que el controlador no guarde la descripción.
     *
     * @param int|string $request_id :: ID de la solicitud registrada.
     * @param array $result :: Resultado de la generación, incluidas las excepciones capturadas.
     * @param string|null $response_json :: Respuesta serializada o NULL si no pudo obtenerse.
     * @return array :: Resultado final, con el error de guardado cuando corresponda.
     */
    private function complete_request($request_id, array $result, $response_json)
    {
        // Los tokens ausentes permanecen en NULL; conservar también el JSON de errores.
        $usage = $result['response_details']['usageMetadata'] ?? [];
        $updated = $this->db->where('id', $request_id)->update('ai_requests', [
            'input_tokens' => $usage['promptTokenCount'] ?? null,
            'output_tokens' => $usage['candidatesTokenCount'] ?? null,
            'response_json' => $response_json,
            'response_content' => $result['response_text'],
            'status' => $result['status'],
            'error_message' => $result['error_message']
        ]);

        if (!$updated) {
            $result['status'] = 'error';
            $result['error_message'] = trim(($result['error_message'] ?? '')
                . ' No se pudo actualizar el registro de la solicitud de IA.');
        }

        return $result;
    }
}
