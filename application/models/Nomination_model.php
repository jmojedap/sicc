<?php
class Nomination_model extends CI_Model{


// USER EXPLORE FUNCTIONS
//-----------------------------------------------------------------------------

    /**
     * Array con listado de users, filtrados por búsqueda y num página, más datos adicionales sobre
     * la búsqueda, filtros aplicados, total resultados, página máxima.
     * 2020-08-01
     */
    function get_users($filters, $numPage, $perPage)
    {
        //Referencia
            $offset = ($numPage - 1) * $perPage;      //Número de la página de datos que se está consultado

        //Búsqueda y Resultados
            $elements = $this->search($filters, $perPage, $offset);    //Resultados para página
        
        //Cargar datos
            $data['filters'] = $filters;
            $data['list'] = $this->list($filters, $perPage, $offset);    //Resultados para página
            $data['strFilters'] = $this->Search_model->str_filters();      //String con filtros en formato GET de URL
            $data['qtyResults'] = $this->qtyResults($data['filters']);
            $data['perPage'] = $perPage;   //Cantidad de resultados por página
            $data['maxPage'] = ceil($this->pml->if_zero($data['qtyResults'],1) / $perPage);   //Cantidad de páginas

        return $data;
    }

    /**
     * Segmento Select SQL, con diferentes formatos, consulta de usuarios
     * 2025-07-05
     */
    function select($format = 'general')
    {
        $arr_select['general'] = 'id,  
            display_name, first_name, last_name, email, organization, survey_status';


        return $arr_select[$format];
    }
    
    /**
     * Query de users, filtrados según búsqueda, limitados por página
     * 2020-08-01
     */
    function search($filters, $perPage = NULL, $offset = NULL)
    {
        //Construir consulta
            $select_format = 'general';
            if ( $filters['sf'] != '' ) { $select_format = $filters['sf']; }
            $this->db->select($this->select($select_format));
            
        //Orden
            if ( $filters['o'] != '' )
            {
                $order_type = $this->pml->if_strlen($filters['ot'], 'ASC');
                $this->db->order_by($filters['o'], $order_type);
            } else {
                $this->db->order_by('display_name', 'ASC');
            }
            
        //Filtros
            $search_condition = $this->search_condition($filters);
            if ( $search_condition ) { $this->db->where($search_condition);}
            
        //Obtener resultados
            $query = $this->db->get('nc_users', $perPage, $offset); //Resultados por página
        
        return $query;
    }

    /**
     * String con condición WHERE SQL para filtrar users
     * 2023-05-18
     */
    function search_condition($filters)
    {
        $condition = NULL;

        //$condition .= $this->role_filter() . ' AND ';

        //q words condition
        $q_search_fields = [
            'first_name', 'last_name', 'display_name', 'email'
        ];
        $words_condition = $this->Search_model->words_condition($filters['q'], $q_search_fields);
        if ( $words_condition )
        {
            $condition .= $words_condition . ' AND ';
        }
        
        //Otros filtros
        if ( $filters['org'] != '' ) { $condition .= "organization = '{$filters['org']}' AND "; }
        
        //Quitar cadena final de ' AND '
        if ( strlen($condition) > 0 ) { $condition = substr($condition, 0, -5);}
        
        return $condition;
    }

    /**
     * Array Listado elemento resultado de la búsqueda (filtros).
     * 2020-06-19
     */
    function list($filters, $perPage = NULL, $offset = NULL)
    {
        $query = $this->search($filters, $perPage, $offset);
        $list = array();

        foreach ($query->result() as $row)
        {
            /*$row->qty_students = $this->Db_model->num_rows('group_user', "group_id = {$row->id}");  //Cantidad de estudiantes*/
            /*if ( $row->image_id == 0 )
            {
                $first_image = $this->first_image($row->id);
                $row->url_image = $first_image['url'];
                $row->url_thumbnail = $first_image['url_thumbnail'];
            }*/
            $list[] = $row;
        }

        return $list;
    }
    
    /**
     * Devuelve la cantidad de registros encontrados en la tabla con los filtros
     * establecidos en la búsqueda
     */
    function qtyResults($filters)
    {
        $this->db->select('id');
        $search_condition = $this->search_condition($filters);
        if ( $search_condition ) { $this->db->where($search_condition);}
        $query = $this->db->get('nc_users'); //Para calcular el total de resultados

        return $query->num_rows();
    }

// Acceso
//-----------------------------------------------------------------------------

    /**
     * Envia un mensaje al correo electrónico del usuario con un link
     * para iniciar sesión en la aplicación
     * 2024-07-27
     */
    function send_login_link($user_id, $app_name = 'nominations')
    {   
        $this->load->model('Notification_model');
        //Identificar información de la APP
            $app_info = $this->App_model->app_info($app_name);

        //Asignar nueva user.activation_key 
            $activation_key = $this->activation_key($user_id);
            $user = $this->Db_model->row_id('nc_users', $user_id);

        //Enviar Email
            if ( ENV == 'production') {
                $this->load->library('Mail_pml');
                $settings['from_name'] = $app_info['email_from_name'];
                $settings['to'] = $user->email;
                $settings['subject'] = 'Ingresa a ' . $app_info['title'];
                $settings['html_message'] = $this->Notification_model->login_link_message($user, 'html', $app_info['email_template']);
                $data = $this->mail_pml->send($settings);
                if ( $data['status'] == 1 ) {
                    $data['message'] = "El link fue enviado a el correo electrónico {$user->email}";
                }
            } else {
                $data['status'] = 1;
                $data['link'] =  "accounts/validate_login_link/{$activation_key}";
                $data['message'] = 'Se simula envío - Versión local';
            }

        return $data;
    }

    /**
     * Establece un código de activación o restauración de contraseña (nc_users.activation_key)
     * 2025-07-05
     */
    function activation_key($user_id)
    {
        $this->load->helper('string');
        $arr_row['activation_key'] = random_string('alnum', 32);
        
        $this->db->where('id', $user_id);
        $this->db->update('nc_users', $arr_row);

        return $arr_row['activation_key'];
    }

    /**
     * EN CONSTRUCCIÓN, POSIBLEMENTE NO SE USE
     */
    function save_responses($input_data)
    {
        $data = array('status' => 0, 'message' => 'Las respuestas no se guardaron');
        $data['user_id'] = $input_data['user_id'];
        $data['message'] = 'Se guardó la respuesta para el usuario ' . $input_data['user_id'];

        //Actualizar estado de respuesta del usuario
        $aRow['survey_status'] = 1; //Se respondió la encuesta
        $data['saved_id'] = $this->Db_model->save('nc_users', "id = {$input_data['user_id']}", $aRow);
    
        /*if (  )
        {
            $data = array('status' => 1, 'message' => 'texto_exito');
        }*/
    
        return $data;
    }

    /**
     * 2025-07-23
     * @return array $user :: Datos del usuario en sesión con token jwt
     */
    function user_info()
    {
        $headers = $this->input->request_headers();
        $user = NULL; //Valor por defecto
        
        if (isset($headers['Authorization'])) {
            $token = str_replace('Bearer ', '', $headers['Authorization']);
            $this->load->library('jwt');
            $payload = $this->jwt->validate($token);
            $email = $payload->email;
            
            $user = $this->Db_model->row('nc_users', "email = '{$email}'");
        }

        return $user;
    }

    /**
     * Devuelve array con registro base para guardar datos en la tabla nc_users_meta
     * 2025-07-23
     */
    function arr_row_meta($user, $updater_id)
    {
        $aRow['user_id'] = $user->id;
        $aRow['updater_id'] = $updater_id;
        $aRow['creator_id'] = $updater_id;
        $aRow['updated_at'] = date('Y-m-d H:i:s');
        $aRow['created_at'] = date('Y-m-d H:i:s');

        return $aRow;
    }

    /**
     * Guarda el detalle de la nominación en la tabla nc_users_meta
     * 2025-07-24
     */
    function nominate($nomination)
    {
        $data = ['status' => 0, 'message' => 'No se guardó la nominación'];
        $nominator = $this->user_info();
        if (!$nominator) {
            $data['message'] = 'Usuario no identificado';
            return $data;
        }

        //Nominado y nominador deben ser de la misma organización
        $nominated_condition = "email = '{$nomination['nominated_email']}' AND organization = '{$nominator->organization}'";
        $nominated = $this->Db_model->row('nc_users', $nominated_condition);
        if (!$nominated) {
            $data['message'] = 'El usuario nominado no existe o no pertenece a la misma organización';
            return $data;
        }

        //Identificar cualidades
        $qualities = $nomination['qualities'];
        if ( !is_array($qualities) || count($qualities) == 0 ) {
            $data['message'] = 'No se especificaron cualidades para la nominación';
            return $data;
        }

        //Primero se eliminan nominaciones previas
        $this->delete_nomination($nominator->id, $nominated->id);
        //Guardar cualidades de la nominación
        $saved = $this->save_nomination_qualities($nominator, $nominated, $qualities);
        
        //Si se guardó al menos una nominación
        if (count($saved) > 0) {
            $data['status'] = 1;
            $data['message'] = 'Nominación guardada con ' . count($saved) . ' cualidades';
            $data['saved'] = $saved;
        }

        return $data;
    }

    /**
     * Guarda cada una de las las cualidades de la nominación en la tabla nc_users_meta
     * 2025-07-24
     */
    function save_nomination_qualities($nominator, $nominated, $qualities)
    {
        $saved = [];

        //Preparar registro para la tabla nc_users_meta
        $aRow = $this->arr_row_meta($nominator, $nominator->id);
        $aRow['type_id'] = 20;  //nominación
        $aRow['type'] = 'nominacion';  //nominación
        $aRow['related_1'] = $nominated->id;  //ID del nominado
        $aRow['text_1'] = $nominated->email;     //Nombre de la persona nominada

        //Recorrer las cualidades, se crea un registro por cada cualidad
        foreach ($qualities as $quality) {
            $aRow["text_2"] = $quality;

            //Condición para guardar y no repetir nominaciones de un mismo nominado por el mismo nominador
            $condition = "user_id = {$nominator->id} AND type_id = 20 AND related_1 = {$nominated->id}
                AND text_2 = '{$quality}'";
            $saved_id = $this->Db_model->save('nc_users_meta', $condition, $aRow); 
            $saved[$saved_id] = $quality;
        }

        return $saved;
    }

    /**
     * Elimina los registros de cualidades de la tabla nc_users_meta, basándose
     * en el ID del nominador y del nominado
     * 2025-07-24
     */
    function delete_nomination($nominator_id, $nominated_id)
    {
        $data = ['status' => 0, 'message' => 'No se eliminó la nominación'];
        $nominator = $this->user_info();

        //Buscar nominación
        $condition = "user_id = {$nominator->id} AND type_id = 20 AND related_1 = {$nominated_id}";
        $this->db->where($condition);
        $this->db->delete('nc_users_meta');
        
        $qty_deleted = $this->db->affected_rows();

        return $qty_deleted;
    }
}