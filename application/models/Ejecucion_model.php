<?php
class Ejecucion_model extends CI_Model{

    function obligaciones()
    {
        $this->db->select($this->Post_model->select('71_obligaciones'));
        $this->db->order_by('integer_1', 'ASC');
        $this->db->where('type_id', 71);
        $obligaciones = $this->db->get('posts');

        return $obligaciones;
    }

    function actividades()
    {
        $this->db->select($this->Post_model->select('72_actividades'));
        $this->db->order_by('code', 'ASC');
        $this->db->where('type_id', 72);
        $actividades = $this->db->get('posts');

        return $actividades;
    }

    /**
     * Query bitácora de actividades
     */
    function bitacora()
    {
        $this->db->select($this->Post_model->select('73_bitacora'));
        $this->db->order_by('integer_3', 'DESC');
        $this->db->where('type_id', 73);
        $bitacora = $this->db->get('posts');

        return $bitacora;
    }
}