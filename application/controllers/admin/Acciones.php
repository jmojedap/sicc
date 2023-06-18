<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Acciones extends CI_Controller {
        
// Variables generales
//-----------------------------------------------------------------------------
    public $views_folder = 'app/acciones/';
    public $url_controller = URL_APP . 'acciones/';

// Constructor
//-----------------------------------------------------------------------------
    
    function __construct()
    {
        parent::__construct();

        $this->load->model('Accion_model');
        
        //Local time set
        date_default_timezone_set("America/Bogota");
    }

    /**
     * Primera función
     */
    function index()
    {
        $this->inicio();
    }

// EXPLORACIÓN
//-----------------------------------------------------------------------------

    /** Exploración de acciones */
    function explorar($num_page = 1)
    {
        //Identificar filtros de búsqueda
        $this->load->model('Search_model');
        $filters = $this->Search_model->filters();
        $filters['sf'] = 'general';  //Select format

        //Datos básicos de la exploración
            $data = $this->Accion_model->explore_data($filters, $num_page, 60);
            $data['cf'] = 'acciones/explorar/';
            $data['controller'] = 'acciones/';
            $data['views_folder'] = $this->views_folder . 'explorar/';      //Carpeta donde están las vistas de exploración
            $data['head_title'] = 'Acciones CC';
            $data['view_a'] = $data['views_folder'] . 'explore_v';
            $data['nav_2'] = $data['views_folder'] . 'menu_v';
        
        //Opciones de filtros de búsqueda
            $data['arrEstrategia'] = $this->Item_model->arr_options('category_id = 221');
            $data['arrLineaEstrategica'] = $this->Item_model->arr_options('category_id = 222');
            
        //Cargar vista
            $this->App_model->view('templates/easypml/main', $data);
    }

    /**
     * Listado de acciones, filtrados por búsqueda, JSON
     */
    function get($num_page = 1, $per_page = 60)
    {
        $this->load->model('Search_model');
        $filters = $this->Search_model->filters();
        //$filters['sf'] = 'general';  //Select format

        $data = $this->Accion_model->get($filters, $num_page, $per_page);
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    /**
     * Exportar resultados de búsqueda
     * 2021-09-27
     */
    function export()
    {
        set_time_limit(120);    //120 segundos, 2 minutos para el proceso

        //Identificar filtros y búsqueda
        $this->load->model('Search_model');
        $filters = $this->Search_model->filters();

        $data['query'] = $this->Accion_model->query_export($filters);

        if ( $data['query']->num_rows() > 0 ) {
            //Preparar datos
                $data['sheet_name'] = 'acciones';

            //Objeto para generar archivo excel
                $this->load->library('Excel');
                $file_data['obj_writer'] = $this->excel->file_query($data);

            //Nombre de archivo
                $file_data['file_name'] = date('Ymd_His') . '_' . $data['sheet_name'];

            $this->load->view('common/download_excel_file_v', $file_data);
        } else {
            $data = array('message' => 'No se encontraron acciones para exportar');
            //Salida JSON
            $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }
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

// INFORMACIÓN
//-----------------------------------------------------------------------------

    function info($accion_id)
    {
        $data = $this->Accion_model->basic($accion_id);
        $data['view_a'] = $this->views_folder . 'info_v';
        $data['back_link'] = $this->url_controller . 'explorar';

        $this->App_model->view(TPL_ADMIN, $data);
    }

// CREACIÓN DE UNA ACCIÓN
//-----------------------------------------------------------------------------

    /**
     * Vista Formulario para la creación de una nueva acción de cultura
     * ciudadana
     * 2022-09-13
     */
    function add()
    {
        $data['arrEstrategia'] = $this->Item_model->arr_options('category_id = 221');
        $data['arrLineaEstrategica'] = $this->Item_model->arr_options('category_id = 222');
        $data['arrCumplimientoObjetivo'] = $this->Item_model->arr_options('category_id = 236');
        $data['arrGrupoValor'] = $this->Item_model->arr_options('category_id = 251');

        //Variables generales
            $data['head_title'] = 'Acciones CC';
            $data['nav_2'] = $this->views_folder . 'explorar/menu_v';
            $data['view_a'] = $this->views_folder . 'add/add_v';

        $this->App_model->view(TPL_ADMIN, $data);
    }

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

// EDICIÓN ACCIONES
//-----------------------------------------------------------------------------

    function edit($accion_id, $section = 'basic')
    {
        $data = $this->Accion_model->basic($accion_id);

        $data['arrEstrategia'] = $this->Item_model->arr_options('category_id = 221');
        $data['arrLineaEstrategica'] = $this->Item_model->arr_options('category_id = 222');
        $data['arrCumplimientoObjetivo'] = $this->Item_model->arr_options('category_id = 236');
        $data['arrLocalidad'] = $this->Item_model->arr_options('category_id = 121');
        $data['arrMeta'] = $this->Item_model->arr_options('category_id = 218');
        $data['arrGrupoValor'] = $this->Item_model->arr_options('category_id = 251');

        /*$data['head_title'] = '';
        $data['nav_2'] = '';*/
        $data['back_link'] = $this->url_controller . 'explorar';
        $data['view_a'] = $this->views_folder . "edit/{$section}_v";
        $data['nav_3'] = $this->views_folder . 'edit/menu_v';

        $this->App_model->view(TPL_ADMIN, $data);
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
                $arr_row['display_name'] = $this->input->post('first_name') . ' ' . $this->input->post('last_name');
                $arr_row['username'] = $this->User_model->email_to_username($this->input->post('email'));
                /*$arr_row['first_name'] = $this->input->post('first_name');
                $arr_row['last_name'] = $this->input->post('last_name');
                $arr_row['email'] = $this->input->post('email');
                $arr_row['document_number'] = $this->input->post('document_number');
                $arr_row['document_type'] = $this->input->post('document_type');
                $arr_row['phone_number'] = $this->input->post('phone_number');*/
                $arr_row['role'] = 22; //Estudiante

            //Crerar usuario, tabla users
                $data['saved_id'] = $this->User_model->save($arr_row);
                if ( $data['saved_id'] > 0 ) {
                    $data['username'] = $arr_row['username'];
                }
        }

        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

}