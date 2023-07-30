<?php
class Account_model extends CI_Model{
    
    
    /**
     * Realiza la validación de login, user y password. Valida coincidencia
     * de password, y status del users.
     */
    function validate_login($userlogin, $password)
    {
        $data = array('status' => 0, 'messages' => array());
        $conditions = 0;   //Initial value
        
        //Validation de password (Condición 1)
            $password_validation = $this->validate_password($userlogin, $password);
            $data['messages'][] = $password_validation['message'];

            if ( $password_validation['status'] ) { $conditions++; }
            
        //Verificar que el user esté activo (Condición 2)
            $user_status = $this->user_status($userlogin);
            if ( $user_status['status'] < 1 ) { $data['messages'][] = $user_status['message']; }
            
            if ( $user_status['status'] >= 1 ) { $conditions++; }   //Usuario activo o registrado
            
        //Se valida el login si se cumplen las conditions
        if ( $conditions == 2 ) 
        {
            $data['status'] = 1;    //General login validation
        }
            
        return $data;
    }

    /**
     * Guardar evento final de sesión, eliminar cookie y destruir sesión
     */
    function logout()
    {
        //Editar, evento de inicio de sesión
        if ( strlen($this->session->userdata('login_id')) > 0 ) 
        {
            $row_event = $this->Db_model->row_id('events', $this->session->userdata('login_id'));

            $arr_row['end'] = date('Y-m-d H:i:s');
            $arr_row['status'] = 2;    //Cerrado
            $arr_row['seconds'] = $this->pml->seconds($row_event->start, date('Y-m-d H:i:s'));

            if ( ! is_null($row_event) ) 
            {
                //Si el evento existe
                $this->Db_model->save('events', "id = {$row_event->id}", $arr_row);
            }
        }
            
        //Destruir sesión existente
            $this->session->sess_destroy();
    }
    
// SESSION CONSTRUCT
//-----------------------------------------------------------------------------
    
    function create_session($username, $register_login = TRUE)
    {
        $data = $this->session_data($username);
        $this->session->set_userdata($data);

        //Registrar evento de login en la tabla [evento]
        if ( $register_login )
        {
            $this->load->model('Event_model');
            $this->Event_model->save_ev_login();
        }
        
        //Actualizar users.ultimo_login
            $this->update_last_login($username);
        
        //Si el user solicitó ser recordardo en el equipo
            //if ( $this->input->post('rememberme') ) { $this->rememberme(); }
    }
    
    /**
     * Actualiza el campo users.last_login, con la última fecha en la que el usuario
     * hizo login
     */
    function update_last_login($username)
    {
        $arr_row['last_login'] = date('Y-m-d H:i:s');

        $this->db->where('username', $username);
        $this->db->update('users', $arr_row);
    }

    /**
     * Array con datos de sesión.
     * 2019-06-23
     */
    function session_data($username)
    {
        $this->load->helper('text');
        $row_user = $this->Db_model->row('users', "username = '{$username}' OR email='{$username}' OR document_number='{$username}'");

        //$data general
            $data = array(
                'logged' => TRUE,
                'username' => $row_user->username,
                'email' => $row_user->email,
                'display_name' => $row_user->display_name,
                'short_name' => explode(' ', $row_user->display_name)[0],
                'user_id' => $row_user->id,
                'userkey' => $row_user->userkey,
                'role' => $row_user->role,
                'last_login' => $row_user->last_login,
                'picture' => $row_user->url_thumbnail
            );
                
        //Datos específicos para la aplicación
            $app_session_data = $this->App_model->app_session_data($row_user);
            $data = array_merge($data, $app_session_data);
        
        //Devolver array
            return $data;
    }

// REGISTER VALIDATION
//-----------------------------------------------------------------------------

    /**
     * Valida datos de un user nuevo o existente, verificando validez respecto
     * a users ya existentes en la base de datos.
     * 2020-10-28
     */
    function validate_form($user_id = NULL)
    {
        $data['status'] = 1;
        $validation = array();
        
        $this->load->model('Validation_model');
        if ( strlen($this->input->post('email')) > 0 ) {
            $email_validation = $this->Validation_model->email($user_id);
            $validation = array_merge($validation, $email_validation);
        }
        
        //Si se registró número de documento
        if ( strlen($this->input->post('document_number')) > 0 ) {
            $document_number_validation = $this->Validation_model->document_number($user_id);
            $validation = array_merge($validation, $document_number_validation);
        }
        
        $data['validation'] = $validation;
        
        //Verificar cada condición
        foreach ( $validation as $value )
        {
            if ( $value != 1 ) { $data['status'] = 0; } //Si algún valor NO es 1, todo se invalida
        }

        return $data;
    }

    /* Esta función genera un string con el username para un registro en la tabla user
    * Se forma: la primera letra del primer nombre + la primera letra del segundo nombre +
    * el primer apellido + la primera letra del segundo apellido.
    * Se verifica que el username construido no exista
    */
    function generate_username()
    {
        $this->load->model('User_model');
        
        //Sin espacios iniciales o finales
        $first_name = trim($this->input->post('first_name'));
        $last_name = trim($this->input->post('last_name'));
        
        //Without accents
        $this->load->helper('text');
        $first_name = convert_accented_characters($first_name);
        $last_name = convert_accented_characters($last_name);
        
        $arr_last_name = explode(" ", $last_name);
        $arr_first_name =  explode(" ", $first_name);
        
        //Construyendo por partes
            $username = $arr_first_name[0];
            if ( isset($arr_first_name[1]) ){ $username .= substr($arr_first_name[1], 0, 2); }
            
            $username .= '.' . $arr_last_name[0];
            
            if ( isset($arr_last_name[1]) ){
                $username .= substr($arr_last_name[1], 0, 2);
            }    
        
        //Reemplazando caracteres
            $username = str_replace (' ', '', $username); //Quitando espacios en blanco
            $username = strtolower($username); //Se convierte a minúsculas    
        
        //Verificar, si el username requiere un sufix numérico para hacerlo único
            $sufix = $this->username_sufix($username);
            $username .= $sufix;
        
        return $username;
    }

    /**
     * Establece un código de activación o restauración de contraseña (users.activation_key)
     * 2020-07-20
     */
    function activation_key($user_id)
    {
        $this->load->helper('string');
        $arr_row['activation_key'] = strtolower(random_string('alpha', 32));
        
        $this->db->where('id', $user_id);
        $this->db->update('users', $arr_row);

        return $arr_row['activation_key'];
    }

    /**
     * Activar cuenta de usuario
     * 2022-08-08
     */
    function activate($activation_key)
    {
        //Identificar usuario
        $user = $this->Db_model->row('users', "activation_key = '{$activation_key}'");

        //Actualizar
        if ( ! is_null($user) )
        {
            $arr_row['status'] = 1;
            $arr_row['updated_at'] = date('Y-m-d H:i:s');
        
            //Update
            $this->db->where('id', $user->id);
            $this->db->update('users', $arr_row);
        }
            
        return $user;
    }

// PASSWORDS
//---------------------------------------------------------------------------------------------------

    /**
     * Encripta y cambia la contraseña de un usuario, status 1 => Activo
     * 2020-08-21
     */
    function change_password($user_id, $password)
    {
        $arr_row = array(
            'status' => 1,           //Activar usuario
            'activation_key' => '',  //Quitar clave de activación reciente
            'password'  => $this->crypt_pw($password)
        );
        
        $this->db->where('id', $user_id);
        $this->db->update('users', $arr_row);
    }

    /**
     * Verificar la contraseña de users. Verifica que la combinación de
     * user y contraseña existan en un mismo registro en la tabla users.
     * 
     * @param type $userlogin
     * @param type $password
     * @return boolean
     */
    function validate_password($userlogin, $password)
    {
        //Valor por defecto
            $data['status'] = 0;
            $data['message'] = 'Contraseña no válida para el usuario "'. $userlogin .'"' ;
         
        //Buscar user con username o correo electrónico
            $condition = "username = '{$userlogin}' OR email = '{$userlogin}'";
            $row_user = $this->Db_model->row('users', $condition);
        
        if ( ! is_null($row_user) )
        {    
            //Crypting
                $cpw = crypt($password, $row_user->password);
                $pw_compare = $row_user->password;
            
            if ( $pw_compare == $cpw )
            {
                $data['status'] = 1;    //Contraseña válida
                $data['message'] = 'Contraseña válida para el usuario';
            }
        }
        
        return $data;
    }

    /**
     * Devuelve password encriptado
     * 
     * @param type $input
     * @param type $rounds
     * @return type
     */
    function crypt_pw($input, $rounds = 7)
    {
        $salt = '';
        $salt_chars = array_merge(range('A','Z'), range('a','z'), range(0,9));
        for($i=0; $i < 22; $i++) {
          $salt .= $salt_chars[array_rand($salt_chars)];
        }
        
        return crypt($input, sprintf('$2a$%02d$', $rounds) . $salt);
    }

    /**
     * Array con: valor del campo users.estado, y un mensaje explicando 
     * el estado
     * 
     * @param type $userlogin
     * @return string
     */
    function user_status($userlogin)
    {
        $data['status'] = -1;     //Valor inicial, -1 => inexistente
        $data['message'] = 'No existe un user identificado con "'. $userlogin .'"';
        
        $this->db->where("username = '{$userlogin}' OR email = '{$userlogin}'");
        $query = $this->db->get('users');
        
        if ( $query->num_rows() > 0 )
        {
            $data['status'] = $query->row()->status;
            $data['message'] = 'Usuario activo';
            
            if ( $data['status'] == 0 ) { $data['message'] = "El user '{$userlogin}' está inactivo, comuníquese con servicio al cliente"; }
            if ( $data['status'] == 2 ) { $data['message'] = "El user '{$userlogin}' está registrado, e-mail no confirmado"; }
        }
        
        return $data;
        
    }

// RECUPERACIÓN DE CUENTA DE USUARIO Y CONTRASEÑA
//---------------------------------------------------------------------------------------------------

    /**
     * Envía un email de para restauración de la contraseña de user
     */
    function recover($email)
    {
        $data = array('status' => 0, 'message' => 'El proceso no fue ejecutado');
        
        //Identificar user
        $row_user = $this->Db_model->row('users', "email = '{$email}'");

        if ( ! is_null($row_user) ) 
        {
            //$this->email_activation($row_user->id, 'recovery');
            $data = array('status' => 1, 'message' => 'El mensaje de correo electrónico fue enviado');
        } else {
            $data['status'] = 2;    //Usuario inexistente
            $data['message'] = "No existe ningún user con el correo electrónico: '{$email}'";
        }
        
        return $data;
    }
    
// LOGIN AND REGISTRATION WITH GOOGLE ACCOUNT
//-----------------------------------------------------------------------------
    
    /**
     * Prepara un objeto Google_Client, para solicitar la autorización  de una
     * autenticación de un user de google y obtener información de su cuenta
     * 
     * Credenciales de Cliente para la aplicación Legalink, creadas con 
     * la cuenta google pacarinamedialab@gmail.com
     * 
     * @return \Google_Client
     */
    public function g_client()
    {
        $g_client = new Google_Client();
        $g_client->setClientId('71811056897-mttc0c3ft09bjpu3rp9j75m8p6o2cu7k.apps.googleusercontent.com');
        $g_client->setClientSecret('iIZy8AISJtlo-mqi48n-8DG3');
        $g_client->setApplicationName(APP_NAME);
        $g_client->setRedirectUri(base_url() . 'accounts/g_callback');
        $g_client->setScopes('https://www.googleapis.com/auth/userinfo.profile https://www.googleapis.com/auth/userinfo.email');
        
        return $g_client;
    }
    
    /**
     * Teniendo como entrada el objeto Google_Cliente autorizado, se solicita
     * y se obtiene la información de la cuenta de un user mediante 
     * Google_Service_Oauth2 y se carga en el array g_userinfo
     * 
     * @param type $g_client
     * @return type
     */
    public function g_userinfo($g_client)
    {
        $oAuth = new Google_Service_Oauth2($g_client);
        $g_userinfo = $oAuth->userinfo_v2_me->get();
        
        return $g_userinfo;
    }

    function g_register($g_userinfo)
    {
        //Construir registro del nuevo user
            $arr_row['first_name'] = $g_userinfo['given_name'];
            $arr_row['last_name'] = $g_userinfo['family_name'];
            $arr_row['display_name'] = $g_userinfo['given_name'] . ' ' . $g_userinfo['family_name'];
            $arr_row['username'] = str_replace('@gmail.com', '', $g_userinfo['email']);
            $arr_row['email'] = $g_userinfo['email'];
            $arr_row['role'] = 21;         //21: Cliente
            $arr_row['status'] = 1;        //Activo

        //Insert in table "user"
            $this->load->model('User_model');
            $data = $this->User_model->save(NULL, $arr_row);

        //Create user session
            if ( $data['user_id'] > 0 )
            {
                $this->create_session($arr_row['username']);
                //$this->Account_model->g_save_account($result['user_id']);
            }

        return $data;
    }
}