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
}