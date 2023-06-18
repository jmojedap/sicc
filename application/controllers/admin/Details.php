<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Details extends CI_Controller{

// Constructor
//-----------------------------------------------------------------------------
    
function __construct() 
{        
    parent::__construct();

    $this->load->model('Detail_model');
    
    //Para definir hora local
    date_default_timezone_set("America/Bogota");
}

// CRUD
//-----------------------------------------------------------------------------

    /**
     * Guardar registro en tabla details
     * 2022-11-13
     */
    function save($key = '', $key_2 = '')
    {
        $aRow = $this->Db_model->arr_row();
        $condition = "type_id = {$aRow['type_id']} AND 
            table_id = {$aRow['table_id']} AND
            row_id = {$aRow['row_id']}";

        if ( $key != '' ) {
            $condition .= " AND {$key} = $aRow[$key]";
        }
        if ( $key_2 != '' ) {
            $condition .= " AND {$key_2} = $aRow[$key_2]";
        }

        $data['saved_id'] = $this->Db_model->save('details', $condition, $aRow);
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    /**
     * Eliminar registro 
     * 2022-11-05
     */
    function delete($detail_id, $row_id)
    {
        $data = $this->Detail_model->delete($detail_id, $row_id);
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    /**
     * Query details
     * 2022-11-05
     */
    function get_list()
    {
        $filters = $this->input->post();
        $query = $this->Detail_model->get_list($filters);
        $data['list'] = $query->result();
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    /**
     * Exportar metadatos de elemento a excel, tabla details
     * 2022-11-10
     */
    function export($meta_type)
    {
        set_time_limit(120);    //120 segundos, 2 minutos para el proceso

        //Identificar filtros y búsqueda
        $condition = $this->input->post('condition');

        $data['query'] = $this->Detail_model->query_export_meta($meta_type, $condition);

        if ( $data['query']->num_rows() > 0 ) {
            //Preparar datos
                $data['sheet_name'] = "users_{$meta_type}";

            //Objeto para generar archivo excel
                $this->load->library('Excel');
                $file_data['obj_writer'] = $this->excel->file_query($data);

            //Nombre de archivo
                $file_data['file_name'] = date('Ymd_His') . '_' . $data['sheet_name'];

            $this->load->view('common/download_excel_file_v', $file_data);
        } else {
            $data = array('message' => 'No se encontraron registros para exportar');
            //Salida JSON
            $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }
    }

}