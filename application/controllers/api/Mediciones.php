<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mediciones extends CI_Controller{

// Variables generales
//-----------------------------------------------------------------------------
    public $views_folder = 'api/mediciones/mediciones/';
    public $url_controller = URL_API . 'mediciones/';

// Constructor
//-----------------------------------------------------------------------------
    
    function __construct() 
    {
        parent::__construct();
        $this->load->model('Medicion_model');
        date_default_timezone_set("America/Bogota");    //Para definir hora local
    }
    
    function index($medicion_id = NULL)
    {
        if ( is_null($medicion_id) ) {
            redirect("admin/mediciones/explore/");
        } else {
            redirect("admin/mediciones/info/{$medicion_id}");
        }
    }
    
//EXPLORE FUNCTIONS
//---------------------------------------------------------------------------------------------------

    /**
     * Listado de mediciones, filtrados por búsqueda, JSON
     */
    function get($num_page = 1, $per_page = 30)
    {
        $this->load->model('Search_model');
        $filters = $this->Search_model->filters();

        $data = $this->Medicion_model->get($filters, $num_page, $per_page);
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }
    
    /**
     * AJAX JSON
     * Eliminar un conjunto de mediciones seleccionados
     */
    function delete_selected()
    {
        $selected = explode(',', $this->input->post('selected'));
        $data['qty_deleted'] = 0;
        
        foreach ( $selected as $row_id ) 
        {
            $data['qty_deleted'] += $this->Medicion_model->delete($row_id);
        }

        //Establecer resultado
        if ( $data['qty_deleted'] > 0 ) { $data['status'] = 1; }
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    
    /**
     * Exportar resultados de búsqueda
     * 2022-04-14
     */
    function export()
    {
        set_time_limit(120);    //120 segundos, 2 minutos para el proceso

        //Identificar filtros y búsqueda
        $this->load->model('Search_model');
        $filters = $this->Search_model->filters();

        $data['query'] = $this->Medicion_model->query_export($filters);

        if ( $data['query']->num_rows() > 0 ) {
            //Preparar datos
                $data['sheet_name'] = 'mediciones';

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
    
// INFORMACÍON LECTURA Y APERTURA
//-----------------------------------------------------------------------------

    /**
     * Abrir o redireccionar a la vista pública de un post
     */
    function open($medicion_id, $meta_id = 0)
    {
        $row = $this->Db_model->row_id('med_medicion', $medicion_id);
        $row_meta = $this->Db_model->row_id('users_meta', $meta_id); //Registro de asignación
        $destination = "admin/mediciones/read/{$medicion_id}";

        if ( $row->type_id == 2 ) $destination = "app/mediciones/ver/{$row->id}/{$row->slug}";
        
        redirect($destination);
    }

    

    function get_info($medicion_id)
    {
        $data = $this->Medicion_model->basic($medicion_id);
        //Salida JSON
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

// CREACIÓN DE UNA MEDICIÓN
//-----------------------------------------------------------------------------

    

    /**
     * AJAX JSON
     * Crear o actualizar un registro de una medición
     * 2022-08-16
     */
    function save()
    {
        $data = $this->Medicion_model->save();
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }
    
// PROCESOS
//-----------------------------------------------------------------------------

    /**
     * Eliminar los datos de una medición en una tabla determinada
     * 2023-11-14
     */
    function clean_medicion($table, $medicion_id)
    {
        $data = $this->Medicion_model->clean_medicion($table, $medicion_id);

        //Salida JSON
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    /**
     * Genera los datos de la tabla opciones a partir de los datos disponibles
     * en med_variable.opciones_json
     * 2023-11-14
     */
    function generar_opciones($medicion_id)
    {
        $this->Medicion_model->clean_medicion('med_opcion', $medicion_id);

        $variables = $this->Medicion_model->variables($medicion_id);

        $rows = [];
        foreach ($variables->result() as $variable) {
            //Es de selección multiple
            if ( strlen($variable->opciones_json) > 0 ) {
                $opciones = json_decode($variable->opciones_json,true);
                if ( ! is_null($opciones) ) {
                    foreach ($opciones as $codigo_opcion => $texto_opcion) {
                        $aRow['id'] = $variable->id * 1000 + intval($codigo_opcion);
                        $aRow['medicion_id'] = $medicion_id;
                        $aRow['pregunta_id'] = $variable->pregunta_id;
                        $aRow['variable_id'] = $variable->id;
                        $aRow['codigo_opcion'] = $codigo_opcion;
                        $aRow['num_nombre'] = substr('000' . $codigo_opcion,-2) . ') ' . $texto_opcion;
                        $aRow['texto_opcion'] = $texto_opcion;

                        $this->db->insert('med_opcion', $aRow);
                        
                        $aRow['saved_id'] = $this->db->insert_id();

                        $rows[] = $aRow;
                        unset($aRow['saved_id']);
                    }
                    
                    
                    
                }
            }
        }

        $data['rows'] = $rows;

        //Salida JSON
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

// RESULTADOS
//-----------------------------------------------------------------------------

    /**
     * AJAX JSON
     * Contenido de una medición, detalle del formulario
     * 2023-11-21
     */
    function get_contenido($medicion_id)
    {
        $data['secciones'] = $this->Medicion_model->secciones($medicion_id)->result();
        $data['preguntas'] = $this->Medicion_model->preguntas($medicion_id)->result();
        //$data['variables'] = $this->Medicion_model->variables("medicion_id = {$medicion_id}")->result();
        //$data['opciones'] = $this->Medicion_model->opciones("medicion_id = {$medicion_id}")->result();

        //Salida JSON
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    /**
     * Frecuencias de respuesta
     */
    function frecuencias($medicion_id, $pregunta_id)
    {
        $sumatoria_encuestados = $this->Medicion_model->sumatoria_encuestados($medicion_id);
        $frecuencias = $this->Medicion_model->frecuencias($medicion_id, $pregunta_id);
        $frecuencias_array = $this->Medicion_model->frecuencias_array($frecuencias, $sumatoria_encuestados);

        $data['sumatoria_encuestados'] = $sumatoria_encuestados;
        $data['frecuencias'] = $frecuencias->result();
        $data['frecuencias_array'] = $frecuencias_array;

        //Salida JSON
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }
}