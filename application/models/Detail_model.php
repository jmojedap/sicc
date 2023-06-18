<?php
class Detail_model extends CI_Model{

// CRUD
//-----------------------------------------------------------------------------

    /**
     * Query con detalles, según condición y fltros por keys
     * 2022-11-05
     */
    function get_list($filters)
    {
        //Claves de la tabla
        $keys = ['type_id','table_id','row_id'];
        foreach ($keys as $key) {
            if ( isset($filters[$key]) ) {
                $this->db->where($key,$filters[$key]);
            }
        }

        //Condición especial
        if ( isset($filters['condition']) ) {
            $this->db->where($filters['condition']);
        }

        $this->db->select('*');
        $details = $this->db->get('details');

        return $details;
    }

    /**
     * Insertar o actualizar registro en la tabla details
     * 2022-11-13
     */
    function save($aRow, $condition)
    {
        $data['saved_id'] = $this->Db_model->save('details', $condition, $aRow);
        return $data;
    }

    /**
     * Eliminar registro de details
     * 2022-11-05
     */
    function delete($detail_id, $row_id)
    {
        $this->db->where('id', $detail_id);
        $this->db->where('row_id', $row_id);
        $this->db->delete('details');
        
        $data['qty_deleted'] = $this->db->affected_rows();
    
        return $data;
    }

    /**
     * Query de la tabla details para exportar
     * 2022-11-10
     */
    function query_export_meta($meta_type, $condition)
    {
        //Select
        $select = $this->meta_select($meta_type);
        $this->db->select($select);

        //Establecer type_id
        if ( $meta_type == 'personas_hogar' ) {
            $this->db->where('details.type_id', '100021');
        }
        
        if ( strlen($condition) > 0 ) {
             $this->db->where($condition);
        }
        
        //Hasta 10.000 registros
        $query = $this->db->get('details', 10000);

        return $query;
    }

    /**
     * Segmento SELECT SQL para exportar tabla details
     * 2022-10-11
     */
    function meta_select($meta_type = 'export')
    {
        $arrSelect['export'] = 'details.*';
        $arrSelect['personas_hogar'] = '';

        return $arrSelect[$meta_type];
    }
}