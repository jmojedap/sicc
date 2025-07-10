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
            display_name, first_name, last_name, email, organization';


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
    function send_login_link($user_id)
    {   
        //Asignar nueva user.activation_key 
            $activation_key = $this->activation_key($user_id);
            $user = $this->Db_model->row_id('nc_users', $user_id);

        if ( ENV == 'production') {
            //Enviar Email
            $this->load->library('Mail_pml');
            $settings['to'] = $user->email;
            $settings['subject'] = 'Ingresa a ' . APP_NAME;
            $settings['html_message'] = $this->login_link_message($user);
            $data = $this->mail_pml->send($settings);
        } else {
            $data['status'] = 1;
            $data['link'] =  URL_APP . "nominations/validate_login_link/{$activation_key}";
            $data['message'] = 'Mensaje no enviado - Versión local';
            $data['activation_key'] = $activation_key;
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
     * Devuelve texto de la vista que se envía por email a un usuario para
     * activación o restauración de su cuenta
     * 2025-07-05
     */
    function login_link_message($user, $type = 'html')
    {
        $data['user'] = $user ;
        
        if ( $type == 'html' ) {
            $data['styles'] = $this->email_styles();
            $data['view_a'] = 'admin/notifications/login_link_message_v';
            $message = $this->load->view('templates/email/main', $data, TRUE);
        } else {
            $data['view_a'] = 'admin/notifications/login_link_message_text_v';
            $message = $this->load->view('templates/email/text', $data, TRUE);
        }
        
        
        return $message;
    }


}