<?php
class Validation_model extends CI_Model{

// General
//-----------------------------------------------------------------------------

    /**
     * Validación de Google Recaptcha V3, la validación se realiza considerando el valor de
     * $recaptcha->score, que va de 0 a 1.
     * 2021-10-31
     */
    function recaptcha()
    {
        $recaptcha = -1;

        $secret = K_RCSC;   //Ver config/constants.php
        $response = $this->input->post('g-recaptcha-response');
        $json_recaptcha = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret={$secret}&response={$response}");
        $recaptcha_content = json_decode($json_recaptcha);
        if ( $recaptcha_content->success ) {
            $recaptcha = 0;
            if ( $recaptcha_content->score > 0.5 ) $recaptcha = 1;
            //$recaptcha = $recaptcha_content->score;
        }
        
        return $recaptcha;
    }

// Usuarios
//-----------------------------------------------------------------------------

    /**
     * Valida que username sea único, si se incluye un ID User existente
     * lo excluye de la comparación cuando se realiza edición
     * 2021-02-18
     */
    function username($user_id = null)
    {
        $validation['username_unique'] = -1;    //Por defecto

        if ( strlen($this->input->post('username')) > 0 ) {
            $validation['username_unique'] = $this->Db_model->is_unique('users', 'username', $this->input->post('username'), $user_id);
        }
        return $validation;
    }

    /**
     * Valida que username sea único, si se incluye un ID User existente
     * lo excluye de la comparación cuando se realiza edición
     * 2021-02-18
     */
    function email($user_id = null)
    {
        $validation['email_unique'] = -1;   //Indeterminado
        //$validation['email_unique'] = FALSE;   //Indeterminado

        if ( strlen($this->input->post('email')) > 0 ) {
            $validation['email_unique'] = $this->Db_model->is_unique('users', 'email', $this->input->post('email'), $user_id);
        }

        return $validation;
    }

    /**
     * Valida que número de identificacion (document_number) sea único, si se 
     * incluye un ID User existentelo excluye de la comparación cuando se 
     * realiza edición
     * 2021-02-18
     */
    function document_number($user_id = null)
    {
        $validation['document_number_unique'] = -1;

        //Si tiene algún valor escrito
        if ( strlen($this->input->post('document_number')) > 0 ) {
            $validation['document_number_unique'] = $this->Db_model->is_unique('users', 'document_number', $this->input->post('document_number'), $user_id);
        }
        return $validation;
    }
}