<?php
class Rci_model extends CI_Model{

    /**
     * Estado de seguimiento a un usuario por parte del usuario en sesión
     * 2025-07-14
     */
    function following_status($user_id)
    {
        $this->db->select('*');
        $this->db->where('user_id', $user_id);
        $this->db->where('type_id', 1011);
        $this->db->where('related_1', $this->session->userdata('user_id'));
        $row_meta = $this->db->get('users_meta');

        if ( $row_meta->num_rows() > 0 ) {
            return 1;
        }

        return 0;
    }

    function followed($user_id)
    {
        $this->db->select('user_id, users.username');
        $this->db->where('users_meta.related_1', $user_id);   //Seguidor
        $this->db->where('users_meta.type_id', 1011);
        $this->db->join('users', 'users.id = users_meta.user_id', 'left');
        
        $followed = $this->db->get('users_meta');
        
        return $followed;
    
    }

// IMPORTAR INVITADOS
//-----------------------------------------------------------------------------

    /**
     * Importa invitados a la base de datos
     * 2021-06-01
     */
    function import($arr_sheet)
    {
        $data = array('qty_imported' => 0, 'results' => array());

        $this->load->model('Account_model');
        
        foreach ( $arr_sheet as $key => $row_data )
        {
            $data_import = $this->import_user($row_data);
            $data['qty_imported'] += $data_import['status'];
            $data['results'][$key + 2] = $data_import;
        }
        
        return $data;
    }

    /**
     * Realiza la importación de una fila del archivo excel. Valida los campos, crea registro
     * en la tabla users.
     * 2021-06-01
     */
    function import_user($row_data)
    {
        //Validar
            $error_text = '';

            if ( strlen($row_data[1]) == 0 ) { $error_text .= 'El nombre está vacío. '; }        //Debe tener apellido
            if ( strlen($row_data[2]) == 0 ) { $error_text .= 'El e-mail está vacío. '; }          //Debe tener email
            if ( strlen($row_data[9]) == 0 ) { $error_text .= 'El username está vacío. '; }          //Debe tener username
            if ( $row_data[15] <= 1 ) { $error_text .= 'El código del rol no es válido.'; } //No rol de administrador o desarrollador

        //Si no hay error
            $this->load->helper('string');
            if ( $error_text == '' )
            {
                $arr_row['created_at'] = $row_data[0];
                $arr_row['display_name'] = $row_data[1];
                $arr_row['email'] = $row_data[2];
                $arr_row['text_1'] = $row_data[3];
                $arr_row['city_name'] = $row_data[4];
                $arr_row['team_1'] = $row_data[5];
                $arr_row['about'] = $row_data[6];
                $arr_row['text_2'] = $row_data[7];
                $arr_row['text_3'] = $row_data[8];
                $arr_row['username'] = $row_data[9];
                $arr_row['admin_notes'] = $row_data[10];
                $arr_row['tags'] = $row_data[11];
                $arr_row['integer_1'] = $row_data[12];
                $arr_row['job_role'] = $row_data[13];
                $arr_row['gender'] = $row_data[14];
                $arr_row['role'] = $row_data[15];
                $arr_row['status'] = $row_data[16];

                $arr_row['updated_at'] = date('Y-m-d H:i:s');
                $arr_row['password'] = $this->Account_model->crypt_pw(random_string('alnum', 12));

                $arr_row['updater_id'] = $row_data[17];
                $arr_row['creator_id'] = $row_data[19];
                if ( $this->session->userdata('logged') ) {
                    $arr_row['creator_id'] = $this->session->userdata('user_id');
                    $arr_row['updater_id'] = $this->session->userdata('user_id');
                }

                //Guardar en tabla user
                $condition = "email = '{$arr_row['email']}'";
                $saved_id = $this->Db_model->save('users', $condition, $arr_row);

                $data = array('status' => 1, 'text' => 'Se guardó con el ID: ' . $saved_id, 'imported_id' => $saved_id);
            } else {
                $data = array('status' => 0, 'text' => $error_text, 'imported_id' => 0);
            }

        return $data;
    }

    function visitas()
    {
        $this->db->select('events.text_1 AS username, users.display_name, users.id AS user_id, COUNT(events.id) AS qty_events');
        $this->db->where('events.type_id', 52);    //Visita pefil
        $this->db->where('events.related_1 > 2');    //Que no sea de usuarios internos
        $this->db->group_by('events.text_1');
        $this->db->join('users', 'users.id = events.element_id');
        $this->db->order_by('COUNT(events.id)', 'desc');
        
        $visitas = $this->db->get('events');

        //$this->output->enable_profiler(TRUE);
    
        return $visitas;
    }

    /**
     * Listado de registro de seguimientos entre usuarios
     */
    function intereses()
    {
        $this->db->select('u.display_name, u.username, u.text_1 AS pais, u.job_role AS actitidad_rol, users_meta.text_1 AS seguidor,
            s.username AS seguidor_username, users_meta.related_1 as seguidor_id, users_meta.created_at');
        $this->db->where('users_meta.type_id', 1011);    //Seguidor
        $this->db->where('u.role > 2');    //Que no sea de usuarios administradores
        $this->db->join('users u', 'u.id = users_meta.user_id');
        $this->db->join('users s', 's.id = users_meta.related_1');
        $this->db->order_by('users_meta.created_at', 'desc');
        
        $intereses = $this->db->get('users_meta');
    
        return $intereses;
    }

    function contenidos_ia()
    {
        $this->db->select('posts.*, users.display_name, users.id AS user_id');
        $this->db->where('posts.type_id', 401);    //Generado IA Descrubre
        $this->db->join('users', 'users.id = posts.creator_id');
        $this->db->order_by('posts.id', 'desc');
        
        $contenidos = $this->db->get('posts');

        //$this->output->enable_profiler(TRUE);
    
        return $contenidos;
    }

// IMPORTAR METADATOS DE INVITADOS
//-----------------------------------------------------------------------------

    /**
     * Importa metadatos de invitados a la base de datos
     * 2025-08-07
     */
    function import_users_meta($arr_sheet)
    {
        $data = array('qty_imported' => 0, 'results' => array());

        $this->load->model('Account_model');
        
        foreach ( $arr_sheet as $key => $row_data )
        {
            $data_import = $this->import_user_meta($row_data);
            $data['qty_imported'] += $data_import['status'];
            $data['results'][$key + 2] = $data_import;
        }
        
        return $data;
    }

    /**
     * Realiza la importación de una fila del archivo excel. Valida los campos, crea registro
     * en la tabla users_meta.
     * 2025-08-07
     */
    function import_user_meta($row_data)
    {
        //Validar
            $error_text = '';

            if ( strlen($row_data[0]) == 0 ) { $error_text .= 'El username está vació. '; }        //Debe username
            if ( strlen($row_data[1]) == 0 ) { $error_text .= 'El type está vacío. '; }          //Debe tener email
            if ( strlen($row_data[2]) == 0 ) { $error_text .= 'El meta value está vacío. '; }          //Debe tener valor

            $user = $this->Db_model->row('users', "username = '{$row_data[0]}'");
            if ( is_null($user) ) { $error_text .= "No existe un usuario con username '{$row_data[0]}'. "; }


        //Si no hay error
            if ( $error_text == '' )
            {
                $arr_row['user_id'] = $user->id;
                $arr_row['type'] = $row_data[1];
                $arr_row['text_1'] = $row_data[2];
                $arr_row['type_id'] = $row_data[3];
                
                $arr_row['updated_at'] = date('Y-m-d H:i:s');
                $arr_row['created_at'] = date('Y-m-d H:i:s');

                $arr_row['updater_id'] = $row_data[4];
                $arr_row['creator_id'] = $row_data[6];
                if ( $this->session->userdata('logged') ) {
                    $arr_row['creator_id'] = $this->session->userdata('user_id');
                    $arr_row['updater_id'] = $this->session->userdata('user_id');
                }

                //Guardar en tabla user
                $condition = "user_id = '{$arr_row['user_id']}' AND type_id = {$arr_row['type_id']}";
                $saved_id = $this->Db_model->save('users_meta', $condition, $arr_row);

                $data = array('status' => 1, 'text' => 'Se guardó con users_meta.id: ' . $saved_id, 'imported_id' => $saved_id);
            } else {
                $data = array('status' => 0, 'text' => $error_text, 'imported_id' => 0);
            }

        return $data;
    }

    /**
     * Construye la solicitud de Descubre con contexto validado y configuración propia.
     * Compacta el JSON y excluye correos y campos vacíos, conservando todos los invitados.
     * @param string $user_input :: Consulta validada por el controlador.
     * @return array :: Parámetros para Airequest_model::generate().
     * 2026-10-10
     */
    public function build_ai_request($user_input)
    {
        $json_path = PATH_CONTENT . 'redcultural/data/ecci_invitados.json';
        if (!is_file($json_path) || !is_readable($json_path)) {
            throw new RuntimeException('No se pudo leer la información de los invitados.');
        }
        $json_content = file_get_contents($json_path);
        if ($json_content === false) {
            throw new RuntimeException('No se pudo leer la información de los invitados.');
        }
        $guests = json_decode($json_content, true);
        if (json_last_error() !== JSON_ERROR_NONE || !is_array($guests) || empty($guests)) {
            throw new RuntimeException('La información de los invitados no contiene un JSON válido.');
        }
        foreach ($guests as &$guest) {
            if (!is_array($guest)) {
                throw new RuntimeException('La información de los invitados tiene una estructura inválida.');
            }
            unset($guest['Correo electrónico']);
            $guest = array_filter($guest, function ($value) {
                return $value !== null && (!is_string($value) || trim($value) !== '');
            });
        }
        unset($guest);
        $json_content = json_encode(array_values($guests), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        if ($json_content === false) {
            throw new RuntimeException('No se pudo preparar el contexto de los invitados.');
        }

        $instruction_path = PATH_CONTENT . 'ai_system_instructions/invitados-ecci.md';
        if (!is_file($instruction_path) || !is_readable($instruction_path)) {
            throw new RuntimeException('No se pudo leer la instrucción de generación.');
        }
        $this->load->library('Gemini_client');
        $instruction = $this->gemini_client->system_instruction('invitados-ecci');
        if (trim($instruction) === '') {
            throw new RuntimeException('La instrucción de generación está vacía.');
        }

        return [
            'model' => 'gemini-2.5-flash-lite',
            'system_instruction_parts' => [
                ['text' => $instruction],
                ['text' => 'Mi nombre es: ' . $this->session->userdata('display_name')],
                ['text' => 'Entrega la respuesta en Markdown. Usa los datos disponibles y no inventes información.']
            ],
            'contents' => [[
                'role' => 'user',
                'parts' => [
                    ['text' => 'Los datos de los invitados son: ' . $json_content],
                    ['text' => $user_input]
                ]
            ]],
            'generationConfig' => [
                'temperature' => 0.5,
                'maxOutputTokens' => 2048,
                'responseMimeType' => 'text/plain'
            ]
        ];
    }

    /**
     * Guarda una generación exitosa como post de tipo 401 en estado borrador.
     * Conserva los campos de tokens usados por el historial y detecta errores de guardado.
     * @param string $user_input :: Consulta que originó el contenido.
     * @param array $result :: Resultado exitoso de Airequest_model::generate().
     * @return int :: ID del post guardado.
     * 2026-10-10
     */
    public function save_ai_content($user_input, array $result)
    {
        if (($result['status'] ?? '') !== 'success' || trim($result['response_text'] ?? '') === '') {
            throw new InvalidArgumentException('Solo se pueden guardar generaciones exitosas.');
        }
        $content_json = json_encode($result['response_details'], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        if ($content_json === false) {
            throw new RuntimeException('No se pudo serializar la respuesta para guardar el contenido.');
        }
        $this->load->model('Post_model');
        $arr_row = $this->Db_model->arr_row(false);
        $usage = $result['response_details']['usageMetadata'] ?? [];
        $arr_row['content'] = $result['response_text'];
        $arr_row['content_json'] = $content_json;
        $arr_row['type_id'] = 401;
        $arr_row['post_name'] = 'Solicitud de ' . $this->session->userdata('display_name');
        $arr_row['text_1'] = $this->session->userdata('username');
        $arr_row['text_2'] = $this->session->userdata('display_name');
        $arr_row['status'] = 2; // Borrador; la publicación sigue siendo una acción del usuario.
        $arr_row['excerpt'] = $user_input;
        $arr_row['integer_1'] = $usage['promptTokenCount'] ?? 0;
        $arr_row['integer_2'] = $usage['candidatesTokenCount'] ?? 0;
        $arr_row['integer_3'] = $arr_row['integer_1'] + $arr_row['integer_2'];

        $previous_db_debug = $this->db->db_debug;
        $this->db->db_debug = false;
        try {
            $saved = $this->Post_model->save($arr_row);
        } finally {
            $this->db->db_debug = $previous_db_debug;
        }
        if (empty($saved['saved_id'])) {
            throw new RuntimeException('La respuesta fue generada, pero no se pudo guardar el contenido.');
        }
        return (int) $saved['saved_id'];
    }

    /**
     * Devuelve el límite de Descubre y el consumo de posts 401 del usuario actual.
     * Para roles mayores a 3 considera las últimas 24 horas, como en la interfaz existente.
     * @return array :: max y usage, compartidos por la vista y el endpoint.
     * 2026-10-10
     */
    public function generation_usage()
    {
        $user_id = (int) $this->session->userdata('user_id');
        $condition = "creator_id = {$user_id} AND type_id = 401";
        if ((int) $this->session->userdata('role') > 3) {
            $condition .= ' AND created_at >= NOW() - INTERVAL 24 HOUR';
        }
        return [
            'max' => 350000,
            'usage' => $user_id > 0 ? $this->used_tokens($condition) : 0
        ];
    }

    /**
     * Cantidad de tokens utilizados en la generación de contenidos AI
     * 2025-09-02
     */
    function used_tokens($condition):int
    {        
        $this->db->select('SUM(integer_3) AS sum_used_token');
        $this->db->where($condition);
        $query = $this->db->get('posts');
        $used_tokens = $query->row(0)->sum_used_token ?? 0;

        return $used_tokens;
    }
}
