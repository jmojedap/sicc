<?php

class Pcc {
    
    //Pcc, hace referencia al punto del hook, Post Controller Constructor
    
    /**
     * 2025-07-11
     */
    function index()
    {
        //Crea instancia para obtener acceso a las librerías de codeigniter, basado en el id
            $this->CI = &get_instance();
        
        // Identificar módulo consultado en el request
        $module = $this->CI->uri->segment(1);
        // Obtener información del usuario que hace el request
        $userdata = $this->get_userdata($module);

        //Identificar mfc: module/controlador/función, y allow
        $mcf = $module . '/' . $this->CI->uri->segment(2) . '/' . $this->CI->uri->segment(3);
        $allow_mcf = $this->allow_mcf($mcf, $userdata);    //Permisos de acceso al recurso module/controlador/función

        $data['module'] = $module;
        $data['userdata'] = $userdata;
        $data['allow_mcf'] = $allow_mcf;
        $data['mcf'] = $mcf;

        //Verificar allow
        if ( $allow_mcf == FALSE )
        {
            //Salida JSON
            //No tiene autorización
            if ( in_array($module, ['api', 'apir']) ) {
                // Código HTTP 403 (Forbidden)
                http_response_code(403); // o 401 si es por falta de autenticación

                // Salida JSON
                echo json_encode([
                    'status' => false,
                    'message' => 'Acceso denegado: no tiene permisos para ' . $mcf
                ]);
                exit; // Detener la ejecución
            } else {
                //http_response_code(403); // o 401 si es por falta de autenticación
                //echo $mcf;

                // Salida JSON
                /*echo json_encode([
                    'status' => false,
                    'message' => 'Acceso denegado: no tiene permisos para ' . $mcf
                ]);*/
                //exit;
                redirect("{$module}/app/denied/{$mcf}");
            }
        }     
    }

    /**
     * Devuelve array con información básica del usuario que hace el request
     * 2025-07-11
     * @return array $userdata
     */
    function get_userdata($module)
    {
        //Variables de sesión por defecto
        $userdata = ['logged' => FALSE, 'role' => 99];

        //Verificar si está ingresando internamente
        if ( $module == 'apir' ) {
            //Request externo, validar token
            $payload = $this->get_payload();
            $userdata['role'] = 98;
            $userdata['payload'] = $payload;
            if ( $payload ) {
                $userdata['logged'] = TRUE;
                $userdata['role'] = $payload->role;
                $userdata['module'] = $module;
            }
        } else {
            //Request interno, validar sesión
            $userdata['logged'] = $this->CI->session->userdata('logged');
            $userdata['role'] = $this->CI->session->userdata('role');
        }

        return $userdata;
    }
    
    /**
     * Control de acceso de usuarios basado en el archivo config/acl.php
     * CF > Ruta Controller/Function
     * 2025-07-11
     */
    function allow_mcf($mcf, $userdata)
    {
        //Cargando lista de control de acceso, application/config/acl.php
        $this->CI->config->load('acl', TRUE);
        $acl = $this->CI->config->item('acl');
        
        //Verificar en funciones públicas
        if ( in_array($mcf, $acl['public_functions']) ) return TRUE;
        
        //Si inició sesión
        if ( $userdata['logged'] == TRUE )
        {
            //Es administrador, todos los permisos
            if ( in_array($userdata['role'], [1,2]) ) return TRUE;
            //Funciones para todos los usuarios con sesión iniciada
            if ( in_array($mcf, $acl['logged_functions']) ) return TRUE;
        }

        //Funciones para el rol actual
        if ( array_key_exists($mcf, $acl['function_roles']) )
        {
            $roles = $acl['function_roles'][$mcf];
            if ( in_array($userdata['role'], $roles) ) return TRUE;
        }

        return FALSE;
    }

    function get_payload()
    {
        $payload = [];
        $headers = $this->CI->input->request_headers();
        
        if (isset($headers['Authorization'])) {
            $token = str_replace('Bearer ', '', $headers['Authorization']);
            $this->CI->load->library('jwt');
            $payload = $this->CI->jwt->validate($token);
        }

        return $payload;
    }
    
    /**
     * Antes de cada acceso, actualiza la variable de sesión de cantidad de mensajes sin leer
     */
    function qty_unread()
    {
        $this->CI = &get_instance();
        
        //Consulta
            $this->CI->db->where('status', 0);  //No leído
            $this->CI->db->where('user_id', $this->CI->session->userdata('user_id'));  //No leído
            $messages = $this->CI->db->get('message_user');
            
        //Establecer valor
            $qty_unread = 0;
            if ( $messages->num_rows() > 0 ) { $qty_unread = $messages->num_rows(); }
        
        //Actualizar variable de sesión
            $this->CI->session->set_userdata('qty_unread', $qty_unread);
    }

    /**
     * Row usuario que hace el request por la API
     * 2021-10-16
     */
    function user_request()
    {
        $this->CI = &get_instance();

        $user = null;   //Valor por defecto
        
        $arr_ik = explode('-', $this->CI->input->get('ik'));
        if ( count($arr_ik) == 2 ) {
            $user_id = $arr_ik[0];
            $userkey = $arr_ik[1];

            $condition = "id = '{$user_id}' AND userkey = '{$userkey}'";
            $this->CI->db->where($condition);
            $users = $this->CI->db->get('users', 1);
            
            if ( $users->num_rows() > 0 ) $user = $users->row();            
        }

        return $user;
    }
}