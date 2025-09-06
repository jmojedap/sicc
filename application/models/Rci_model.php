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
        $this->db->where('related_1', $this->session->userdata('user_id'));
        $row_meta = $this->db->get('users_meta');

        if ( $row_meta->num_rows() > 0 ) {
            return 1;
        }

        return 0;
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