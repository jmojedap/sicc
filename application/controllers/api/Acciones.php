<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Acciones extends CI_Controller{

// Variables generales
//-----------------------------------------------------------------------------
    public $url_controller = URL_API . 'acciones/';

// Constructor
//-----------------------------------------------------------------------------
    
    function __construct() 
    {
        parent::__construct();
        $this->load->model('Accion_model');
        date_default_timezone_set("America/Bogota");    //Para definir hora local
    }
    
    function index($accion_id = NULL)
    {
        if ( is_null($accion_id) ) {
            redirect("app/acciones/explorar/");
        } else {
            redirect("app/acciones/info/{$accion_id}");
        }
    }
    
//EXPLORE FUNCTIONS
//---------------------------------------------------------------------------------------------------

    /**
     * Listado de acciones, filtrados por búsqueda, JSON
     * 2024-05-28
     */
    function get($num_page = 1, $per_page = 100)
    {
        if ( $per_page > 250 ) $per_page = 250;

        $this->load->model('Search_model');
        $filters = $this->Search_model->filters();

        $data = $this->Repositorio_model->get($filters, $num_page, $per_page);
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    /**
     * AJAX JSON
     * Eliminar un conjunto de acciones seleccionadas
     * 2023-05-13
     */
    function delete_selected()
    {
        $selected = explode(',', $this->input->post('selected'));
        $data['qty_deleted'] = 0;
        
        foreach ( $selected as $row_id ) {
            $data['qty_deleted'] += $this->Accion_model->delete($row_id);
        }
        
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    /**
     * Listado de acciones, filtrados por búsqueda, JSON
     */
    function list($num_page = 1, $per_page = 1000)
    {
        $this->load->model('Search_model');
        $filters = $this->Search_model->filters();
        $filters['condition'] = 'fecha <> "0000-00-00"';

        $data = $this->Accion_model->get($filters, $num_page, $per_page);
        $this->output->set_content_type('application/json')->set_output(json_encode($data['list']));
    }

// CREACIÓN DE UNA ACCIÓN
//-----------------------------------------------------------------------------

    /**
     * AJAX JSON
     * Crear o actualizar el registro de una acción CC
     * 2022-09-03
     */
    function save()
    {
        $data = $this->Accion_model->save();
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

// DETALLES DE ACCIONES
//-----------------------------------------------------------------------------

    /**
     * AJAX JSON
     * Guardar un registro en la tabla mecc_acciones_detalles
     * 2023-05-01
     */
    function save_detail()
    {
        $aRow = $this->Db_model->arr_row();

        $condition = "accion_id = {$aRow['accion_id']} AND tipo_detalle = {$aRow['tipo_detalle']} AND cod_detalle = '{$aRow['cod_detalle']}'";
        $data['saved_id'] = $this->Db_model->save('mecc_acciones_detalle', $condition, $aRow);

        //Salida JSON
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    /**
     * AJAX JSON
     * Eliminar un detalle de una acción
     * 2023-05-02
     */
    function delete_detail($accion_id, $detalle_id)
    {
        $condition = "accion_id = {$accion_id} AND id = {$detalle_id}";
        $data['qty_deleted'] = $this->Accion_model->delete_details($condition);

        //Salida JSON
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    /**
     * AJAX JSON
     * Listado de datalles de acciones filtradas
     * 2023-05-02
     */
    function get_details()
    {
        $this->load->model('Search_model');
        $filters = $this->Search_model->filters();

        $details = $this->Accion_model->get_details($filters);
        $data['details'] = $details->result();
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

// REGISTRO DE USUARIOS
//-----------------------------------------------------------------------------

    /**
     * AJAX JSON
     * 
     * Recibe los datos POST del form en accounts/signup. Si se validan los 
     * datos, se registra el user. Se devuelve $data, con resultados de registro
     * o de validación (si falló).
     * 2023-05-10
     */
    function create_user()
    {
        //Validar Recaptcha
        $this->load->model('Validation_model');
        $recaptcha = $this->Validation_model->recaptcha(); //Validación Google ReCaptcha V3

        $this->load->model('Account_model');
        $this->load->model('User_model');

        //Validar Formulario
        $res_validation = $this->Account_model->validate_form();

        //Resultado inicial por defecto
        $data = [
            'saved_id' => 0,
            'recaptcha' => $recaptcha,
            'validation' => $res_validation['validation']
        ];
        
        //Comprobar 2 validaciones
        if ( $res_validation['status'] && $recaptcha == 1 )
        {
            //Construir registro del nuevo user
                $arr_row = $this->input->post();
                unset($arr_row['g-recaptcha-response']);
                //$arr_row['display_name'] = $this->input->post('first_name') . ' ' . $this->input->post('last_name');
                $arr_row['username'] = $this->User_model->email_to_username($this->input->post('email'));
                $arr_row['role'] = 22; //Estudiante

            //Crerar usuario, tabla users
                $data['saved_id'] = $this->User_model->save($arr_row);
                if ( $data['saved_id'] > 0 ) {
                    $data['username'] = $arr_row['username'];
                    $data['activation_key'] = $this->Account_model->activation_key($data['saved_id']);
                }
        }

        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    /**
     * AJAX JSON
     * Actualizar los datos de un usuario rol estudiante
     * 2023-07-20
     */
    function update_user()
    {
        $aRow = $this->input->post();
        $aRow['display_name'] = $this->input->post('first_name') . ' ' . $this->input->post('last_name');
        $condition = "id = {$aRow['id']} AND activation_key = '{$aRow['activation_key']}' AND role = 22";
        $data['saved_id'] = $this->Db_model->save('users', $condition, $aRow);

        if ( $data['saved_id'] > 0 ) {
            //Si se guardó, resetear activation_key
            $this->load->model('Account_model');
            $this->Account_model->activation_key($data['saved_id']);
        }

        //Salida JSON
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    /**
     * Actualización masiva de datos de las acciones de la escuela de cuidado
     * Leyendo archivo cronograma de la escuela
     * 2023-07-29
     */
    function update_rows_ehc($readDrive = 0)
    {
        $updatedRows = [];
        $qtyUpdated = 0;
        
        $this->load->model('Cuidado_model');
        $filePath = $this->Cuidado_model->create_acciones_json($readDrive);

        // Verificar si el archivo existe
        if (file_exists($filePath)) {
            $jsonAcciones = file_get_contents($filePath);
            $arrAcciones = json_decode($jsonAcciones, true);
            
            if ($arrAcciones != null) {
                foreach ($arrAcciones as $key => $accion) {
                    $aRow = $accion;
                    unset($aRow['id']);
                    
                    //Convertirl el separador de decimales de coma (G-Drive) a punto MySQL
                    $aRow['latitud'] = str_replace(',','.',$aRow['latitud']);
                    $aRow['longitud'] = str_replace(',','.',$aRow['longitud']);

                    $this->db->where('id', $accion['id']);
                    $this->db->update('mecc_acciones', $aRow);
                    $affectedRows = $this->db->affected_rows();
                    
                    $updatedRows[$accion['id']] = $affectedRows;
                    $qtyUpdated += $affectedRows;
                }
            }
        }

        $data['file_path'] = $filePath;
        $data['qty_updated'] = $qtyUpdated;
        $data['results'] = $updatedRows;
        $data['status'] = 1;
        $data['message'] = 'Registros actualizados en mecc_acciones: ' . $qtyUpdated;

        //Salida JSON
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    /**
     * Actualiza los datos en la tabla mecc_acciones_detalles registrando los usuarios
     * participantes en una acción tomando los valores del campo mecc_acciones.participantes_equipo
     * 2023-08-12
     */
    function update_acciones_staff()
    {
        $this->load->model('Cuidado_model');
        $data = $this->Cuidado_model->update_acciones_staff();

        //Salida JSON
        $this->output->set_content_type('application/json')->set_output(json_encode($data));

    }
}