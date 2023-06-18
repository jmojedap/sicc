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
    function index($accion_id = 0)
    {
        if ( $accion_id > 0 ) {
            if ( $this->session->userdata('user_id') > 0 ) {
                redirect("app/acciones/asistentes/{$accion_id}");
            } else {
                redirect("app/acciones/info/{$accion_id}");
            }
        } else {
            redirect("app/acciones/explorar");
        }
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
            $data['arrPrograma'] = $this->Item_model->arr_options('category_id = 221');
            $data['arrEstrategia'] = $this->Item_model->arr_options('category_id = 222');
            $data['arrPeriodo'] = $this->App_model->arr_periods('year = 2023 AND type_id = 7');
            $data['arrLocalidad'] = $this->Item_model->arr_options('category_id = 121');
            
        //Cargar vista
            $this->App_model->view('templates/easypml/main_fluid', $data);
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

// REPORTES
//-----------------------------------------------------------------------------

    /**
     * Visualización de datos relacionadas con las estrategias
     * 2023-06-14
     */
    function balance($visualizacion = 'escuela-de-cuidado')
    {
        $data['head_title'] = 'Balance de actividades';
        $data['view_a'] = $this->views_folder . 'balance_v';
        $data['visualizacion'] = $visualizacion;
        $data['nav_2'] = $this->views_folder . 'explorar/menu_v';
        $this->App_model->view(TPL_FRONT . '_fluid', $data);
    }

// INFORMACIÓN
//-----------------------------------------------------------------------------

    function info($accion_id)
    {
        $data = $this->Accion_model->basic($accion_id);
        //$data['view_a'] = $this->views_folder . 'info_v';
        $data['view_a'] = 'common/row_details_v';
        $data['back_link'] = $this->url_controller . 'explorar';

        $this->App_model->view(TPL_FRONT, $data);
    }

// CREACIÓN DE UNA ACCIÓN
//-----------------------------------------------------------------------------

    /**
     * Vista formulario para la creación de una nueva acción de cultura
     * ciudadana
     * 2023-06-03
     */
    function add()
    {
        $data['arrPrograma'] = $this->Item_model->arr_options('category_id = 221');
        $data['arrEstrategia'] = $this->Item_model->arr_options('category_id = 222');
        $data['arrDependencia'] = $this->Item_model->arr_options('category_id = 215');
        $data['arrEquipoTrabajo'] = $this->Item_model->arr_options('category_id = 216');
        $data['arrLocalidad'] = $this->Item_model->arr_options('category_id = 121');
        $data['arrModalidad'] = $this->Item_model->arr_options('category_id = 510');

        //Variables generales
            $data['head_title'] = 'Acciones CC';
            $data['nav_2'] = $this->views_folder . 'explorar/menu_v';
            $data['view_a'] = $this->views_folder . 'add/add_v';

        $this->App_model->view(TPL_FRONT . '_fluid', $data);
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

    /**
     * Formulario de edición de las acciones de CC
     * 2023-06-03
     */
    function edit($accion_id, $section = 'basic')
    {
        $data = $this->Accion_model->basic($accion_id);

        $data['page_title'] = "Acción {$accion_id}) {$data['row']->nombre_accion}";
        $data['arrPrograma'] = $this->Item_model->arr_options('category_id = 221');
        $data['arrEstrategia'] = $this->Item_model->arr_options('category_id = 222');
        $data['arrDependencia'] = $this->Item_model->arr_options('category_id = 215');
        $data['arrEquipoTrabajo'] = $this->Item_model->arr_options('category_id = 216');
        $data['arrLocalidad'] = $this->Item_model->arr_options('category_id = 121');
        $data['arrModalidad'] = $this->Item_model->arr_options('category_id = 510');
        $data['arrSiNoNa'] = $this->Item_model->arr_options('category_id = 55');

        /*$data['head_title'] = '';
        $data['nav_2'] = '';*/
        $data['back_link'] = $this->url_controller . 'explorar';
        $data['view_a'] = $this->views_folder . "edit/{$section}_v";
        //$data['nav_3'] = $this->views_folder . 'edit/menu_v';

        $this->App_model->view('templates/easypml/main_fluid', $data);
    }

    /**
     * Geovisor y geolocalizador de la actividad
     * 2023-06-03
     */
    function localizacion($accion_id)
    {
        $data = $this->Accion_model->basic($accion_id);
        $data['page_title'] = "Acción {$accion_id}) {$data['row']->nombre_accion}";
        $data['back_link'] = $this->url_controller . 'explorar';
        $data['view_a'] = $this->views_folder . 'localizacion/localizacion_v';        
        $this->App_model->view('templates/easypml/main_fluid', $data);
    }

// MAPA
//-----------------------------------------------------------------------------

function mapa()
{
    $data['head_title'] = 'Mapa de actividades';
    $data['view_a'] = $this->views_folder . 'mapa/mapa_v_arcgis';
    //$data['nav_2'] = $this->views_folder . 'explorar/menu_v';
    
    $this->load->model('Search_model');
    $filters = $this->Search_model->filters();
    $data['acciones'] = $this->Accion_model->get($filters,1,500);

    //$this->App_model->view('templates/easypml/main_fluid', $data);
    $this->App_model->view(TPL_ADMIN, $data);
}

// DETALLES
//-----------------------------------------------------------------------------

    function get_detalles()
    {
        $data = $this->Accion_model->get_detalles();
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

// REGISTRO DE USUARIOS
//-----------------------------------------------------------------------------

    function usuarios()
    {
        $data['head_title'] = 'Usuarios';
        $data['view_a'] = $this->views_folder . 'usuarios_v';
        $data['nav_2'] = $this->views_folder . 'explorar/menu_v';
        $data['arrSexos'] = $this->Item_model->arr_options('category_id = 59');

        $this->load->model('User_model');
        $this->load->model('Search_model');
        $filters = $this->Search_model->filters();
        $filters['role'] = 22;
        $filters['sf'] = 'cuidado_estudiantes';
        $data['users'] = $this->User_model->get($filters, 1, 1000);

        $this->App_model->view('templates/easypml/main_fluid', $data);
    }


    /**
     * HTML VIEW
     * Formulario para registro de usuarios beneficiarios
     * 2023-05-10
     */
    function registro_usuario()
    {
        $data['head_title'] = 'Registro de usuarios';
        $data['view_a'] = $this->views_folder . "registro_usuario/registro_usuario_v";
        $data['arrDocumentTypes'] = $this->Item_model->arr_options('category_id = 53');
        $data['arrSexos'] = $this->Item_model->arr_options('category_id = 59');
        $data['arrGenders'] = $this->Item_model->arr_options('category_id = 111');
        $data['arrSexualOrientation'] = $this->Item_model->arr_options('category_id = 112');
        $data['arrOcupaciones'] = $this->Item_model->arr_options('category_id = 517');
        $data['arrLocalidades'] = $this->Item_model->arr_options('category_id = 121');

        $this->App_model->view(TPL_FRONT, $data);
    }

    /**
     * HTML VIEW
     * Vista y formulario para registro de asistentes a una acción cc
     * 2023-05-01
     */
    function asistentes($accion_id)
    {
        $data = $this->Accion_model->basic($accion_id);
        $data['head_title'] = 'Acción ' . $accion_id . ' Asistentes';
        $data['page_title'] = "Acción {$accion_id}) {$data['row']->nombre_accion}";
        $data['view_a'] = $this->views_folder . 'asistentes_v';
        $data['nav_2'] = $this->views_folder . 'menu_v';
        $data['back_link'] = $this->url_controller . 'explorar';
        $this->App_model->view('templates/easypml/main_fluid', $data);
    }

    /**
     * HTML VIEW
     * Vista y formulario para registro de asistentes a una acción cc
     * 2023-05-01
     */
    function acciones_asistentes()
    {
        $data['head_title'] = 'Acciónes y asistentes';
        $data['view_a'] = $this->views_folder . 'acciones_asistentes_v';
        $data['nav_2'] = $this->views_folder . 'explorar/menu_v';
        $this->App_model->view(TPL_FRONT . '_fluid', $data);
    }

    /**
     * HTML VIEW
     * Vista y formulario para registro de asistentes itinerantes a una acción
     * 2023-06-15
     */
    function asistentes_itinerantes($accion_id)
    {
        $data = $this->Accion_model->basic($accion_id);

        $data['arrGrupoPoblacion'] = $this->Item_model->arr_options('category_id = 251 AND cod IN (1005,1520,1550,1560)');
        $data['arrIdentidadGenero'] = $this->Item_model->arr_options('category_id = 111');
        $data['arrTipoDocumento'] = $this->Item_model->arr_options('category_id = 53');
        
        $data['head_title'] .= ' - Asistentes';
        $data['page_title'] = "Acción {$accion_id}: {$data['row']->nombre_accion}";
        $data['view_a'] = $this->views_folder . 'asistentes_itinerantes/asistentes_itinerantes_v';
        $data['nav_2'] = $this->views_folder . 'menu_v';
        $data['back_link'] = $this->url_controller . 'explorar';

        $this->App_model->view(TPL_FRONT . '_fluid', $data);
    }

// POBLACIÓN BENEFICIARIA DE LAS ACCIONES
//-----------------------------------------------------------------------------

    function poblacion_beneficiaria($accion_id)
    {
        $data = $this->Accion_model->basic($accion_id);

        $data['arrGrupoPoblacion'] = $this->Item_model->arr_options('category_id = 251');
        $data['arrSexo'] = $this->Item_model->arr_options('category_id = 59 AND cod <= 2');
        
        $data['head_title'] .= ' - Beneficiarios';
        $data['page_title'] = "Acción {$accion_id}: {$data['row']->nombre_accion}";
        $data['view_a'] = $this->views_folder . 'poblacion_beneficiaria/poblacion_beneficiaria_v';
        $data['nav_2'] = $this->views_folder . 'menu_v';
        $data['back_link'] = $this->url_controller . 'explorar';

        $this->App_model->view(TPL_FRONT, $data);
    }

// ENTIDADES PARTICIPANTES DE LAS ACCIONES
//-----------------------------------------------------------------------------

    /**
     * HTML View
     * Listado y formulario de entidaes participantes o relacionadas con la
     * acción
     * 2023-06-03
     */
    function entidades_participantes($accion_id)
    {
        $data = $this->Accion_model->basic($accion_id);

        $data['arrTipoEntidad'] = $this->Item_model->arr_options('category_id = 256');
        
        $data['head_title'] .= ' - Entidades';
        $data['page_title'] = "Acción {$accion_id}: {$data['row']->nombre_accion}";
        $data['view_a'] = $this->views_folder . 'entidades_participantes/entidades_participantes_v';
        $data['nav_2'] = $this->views_folder . 'menu_v';
        $data['back_link'] = $this->url_controller . 'explorar';

        $this->App_model->view(TPL_FRONT . '_fluid', $data);
    }

// OTROS DESARROLLO Y DOCUMENTACIÓN
//-----------------------------------------------------------------------------

    /**
     * Diccionario de datos, detalle datos de campos de tabla
     * 2023-04-09
     */
    function diccionario_de_datos($table = 'acciones', $format = '')
    {
        $this->load->library('google_sheets');
        $data['tables'] = $this->google_sheets->sheetToArray('1-MKsqbEVi9EH8v0ZVOUmZ4V0EQN1EDFGrKguoDIfH4I', 113998780);

        //$data['diccionario'] = file_get_contents(PATH_CONTENT . "json/diccionarios/{$table}.json");

        $data['table'] = $table;
        $data['head_title'] = 'Diccionario de datos';
        $data['file_id'] = '1-MKsqbEVi9EH8v0ZVOUmZ4V0EQN1EDFGrKguoDIfH4I';

        if ( $format == 'print' ) {
            $data['view_a'] = $this->views_folder . "diccionario_print_v";
            $this->App_model->view('templates/print/main', $data);
        } else {
            $data['view_a'] = $this->views_folder . "diccionario_v";
            $this->App_model->view(TPL_FRONT, $data);
        }
    }
}