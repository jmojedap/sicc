<?php
class Geofocus_model extends CI_Model{

    function basic($priorizacion_id)
    {
        $row = $this->Db_model->row_id('priorizaciones', $priorizacion_id);

        $data['row'] = $row;
        $data['type_folder'] = $this->type_folder($row->type_id);
        $data['head_title'] = $data['row']->post_name;
        $data['view_a'] = $this->views_folder . 'post_v';
        $data['nav_2'] = $data['type_folder'] . 'menu_v';

        return $data;
    }
}