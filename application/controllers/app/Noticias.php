<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Noticias extends CI_Controller {
        
// Variables generales
//-----------------------------------------------------------------------------
    public $views_folder = 'app/noticias/';
    public $url_controller = URL_APP . 'noticias/';

// Constructor
//-----------------------------------------------------------------------------
    
    function __construct()
    {
        parent::__construct();

        $this->load->model('Noticia_model');
        
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

    /** Exploración de noticias */
    function explorar($num_page = 1)
    {
        //Identificar filtros de búsqueda
        $this->load->model('Search_model');
        $filters = $this->Search_model->filters();
        $filters['sf'] = 'general';  //Select format

        //Datos básicos de la exploración
            $data = $this->Noticia_model->explore_data($filters, $num_page, 60);
            $data['cf'] = 'noticias/explorar/';
            $data['controller'] = 'noticias/';
            $data['views_folder'] = $this->views_folder . 'explorar/';      //Carpeta donde están las vistas de exploración
            $data['head_title'] = 'Noticias';
            $data['view_a'] = $data['views_folder'] . 'explore_v';
        
        //Opciones de filtros de búsqueda
            $data['options_cat_1'] = $this->Noticia_model->options_cat_1();
            $data['options_clasificacion'] = $this->Noticia_model->options_clasificacion();
            
        //Arrays con valores para contenido en lista
            //$data['arr_cat'] = $this->Item_model->arr_cod('category_id = 21');
            
        //Cargar vista
            $this->App_model->view('templates/easypml/main', $data);
    }

    /**
     * Listado de noticias, filtrados por búsqueda, JSON
     */
    function get($num_page = 1, $per_page = 60)
    {
        $this->load->model('Search_model');
        $filters = $this->Search_model->filters();
        //$filters['sf'] = 'general';  //Select format

        $data = $this->Noticia_model->get($filters, $num_page, $per_page);
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

        $data['query'] = $this->Noticia_model->query_export($filters);

        if ( $data['query']->num_rows() > 0 ) {
            //Preparar datos
                $data['sheet_name'] = 'noticias';

            //Objeto para generar archivo excel
                $this->load->library('Excel');
                $file_data['obj_writer'] = $this->excel->file_query($data);

            //Nombre de archivo
                $file_data['file_name'] = date('Ymd_His') . '_' . $data['sheet_name'];

            $this->load->view('common/download_excel_file_v', $file_data);
        } else {
            $data = array('message' => 'No se encontraron noticias para exportar');
            //Salida JSON
            $this->output->set_content_type('application/json')->set_output(json_encode($data));
        }
    }

// FUNCIONES HERRAMIENTA
//-----------------------------------------------------------------------------

    /**
     * Form login de users se ingresa con nombre de user y 
     * contraseña. Los datos se envían vía ajax a accounts/validate_login
     */
    function inicio()
    {
        $data['head_title'] = 'ClasiNoti';
        $data['view_a'] = 'app/noticias/inicio_v';
        $this->App_model->view('templates/easypml/noticias', $data);
    }

    /**
     * AJAX JSON
     * 
     * Recibe los datos POST del form en accounts/signup. Si se validan los 
     * datos, se registra el user. Se devuelve $data, con resultados de registro
     * o de validación (si falló).
     * 2021-04-15
     */
    function crear_sesion()
    {
        $data = array('status' => 0, 'message' => 'Datos no válidos');  //Initial result values
        
        $this->load->model('Validation_model');
        $data['recaptcha'] = $this->Validation_model->recaptcha(); //Validación Google ReCaptcha V3
            
        if ( $data['recaptcha'] == 1 )
        {
            $email_parts = explode('@',$this->input->post('email'));
            //Construir registro del nuevo user
                $userdata['username'] = $email_parts[0];
                $this->session->set_userdata($userdata);
                $data['username'] = $userdata['username'];
                $data['status'] = 1;
                $data['message'] = 'Sesión iniciada';
        } else {
            $data['message'] = 'Recaptcha no válido';
        }

        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    function salir()
    {
        $this->session->sess_destroy();
        redirect('app/noticias/inicio/');
    }

// PREPARACIÓN
//-----------------------------------------------------------------------------

    /**
     * AJAX JSON
     * Actualizar campo notiicas.muestra, para marcar las noticias que harán parte
     * de la muestra para la clasificación de noticias
     * 2022-08-29
     */
    function marcar_noticias_muestra()
    {
        $data['message'] = $this->Noticia_model->set_noticias_samples();

        //Salida JSON
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    /**
     * Reiniciar datos de noticias
     */
    function reset_noticias()
    {
        $data['affected_rows'] = $this->Noticia_model->reset_noticias();
        //Salida JSON
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

// FUNCIONES DE REVISIÓN
//-----------------------------------------------------------------------------

    /**
     * REDIRECT
     * Ir a la siguiente noticia aleatoria sin revisión
     * 2022-07-14
     */
    function siguiente($check_goal = 20)
    {
        $this->db->select('id, aleatorio');
        $this->db->where('status', 0);
        $this->db->where('anio_publicacion IN (2017)');    //Temporal, pruebas 2022-09-01
        $this->db->where('grupo_1',1);    //ClasiNoti Bogotá
        $this->db->order_by('id', 'ASC');
        $noticias = $this->db->get('noticias', 1500);

        if ( $noticias->num_rows() > 0 ) {
            $index = random_int(0,$noticias->num_rows() - 1);
            $noticia_id = $noticias->row($index)->id;
            $next_aleatorio = $noticias->row($index)->aleatorio;
            redirect("app/noticias/clasificar/{$noticia_id}/{$next_aleatorio}/{$check_goal}/");
        } else {
            redirect('app/noticias/resumen');
        }
        
    }

    /**
     * Vista formulario para clasificar una noticia
     * 2022-08-17
     */
    function clasificar($noticia_id = 1, $aleatorio = 100019, $check_goal = 20)
    {
        if ( strlen($this->session->userdata('username')) > 0 ) {
            $noticia = $this->Db_model->row('noticias', "id = {$noticia_id} AND aleatorio = {$aleatorio}");
            $data = $this->Noticia_model->basic($noticia->id);
            $data['head_title'] = 'Revisar noticia';
    
            $username = $this->session->userdata('username');
            
            $data['qtyUserChecked'] = $this->Db_model->num_rows('noticias', "actualizado_por = '{$username}'");
            $data['checkGoal'] = $check_goal;
            if ( $data['qtyUserChecked'] > $data['checkGoal'] ) { $data['checkGoal'] = $data['qtyUserChecked']; }
    
            $data['options_cat_1'] = $this->Noticia_model->options_cat_1();
            $data['options_clasificacion'] = $this->Noticia_model->options_clasificacion();
            
            $data['head_title'] = 'Clasificar';
            $data['view_a'] = $this->views_folder . 'clasificar/clasificar_v';
            $data['nav_2'] = $this->views_folder . 'explorar/menu_v';
    
            $this->App_model->view('templates/easypml/noticias', $data);
        } else {
            redirect('app/noticias/inicio');
        }
    }

    /**
     * AJAX JSON
     * Guardar los datos de clafificación de la noticia
     * 2022-08-19
     */
    function actualizar($noticia_id)
    {
        $data['saved_id'] = 0;
        if ( strlen($this->session->userdata('username')) > 0 )
        {
            $arr_row['actualizado_por'] = $this->session->userdata('username');
            $arr_row['status'] = 1; //Clasificado
            $arr_row['clasificacion'] = $this->input->post('clasificacion');
            $arr_row['cat_1'] = $this->input->post('cat_1');
            $arr_row['compartible'] = $this->input->post('compartible');
            $arr_row['updated_at'] = date('Y-m-d H:i:s');
    
            $data['saved_id'] = $this->Db_model->save('noticias', "id = {$noticia_id}", $arr_row);
        }

        //Salida JSON
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    /**
     * HTML
     * Vista resumen estadístico de clasificación
     * 2022-08-31
     */
    function resumen()
    {
        $data['head_title'] = 'ClasiNoti :: Resumen';
        $data['view_a'] = $this->views_folder . 'resumen/resumen_v';
        $data['nav_2'] = $this->views_folder . 'explorar/menu_v';

        $resultadosClasificacion = $this->Noticia_model->resultados_clasificacion();
        $data['resultadosClasificacion'] = $resultadosClasificacion;
        $data['resultadosClasificacionSummary'] = $this->pml->field_summary($resultadosClasificacion, 'qty_noticias');
        $data['arrClasificacion'] = $this->Noticia_model->options_clasificacion();
        
        $resultadosClasificador = $this->Noticia_model->resultados_clasificador();
        $data['resultadosClasificador'] = $resultadosClasificador;
        $data['resultadosClasificadorSummary'] = $this->pml->field_summary($resultadosClasificador, 'qty_noticias');
        
        $resultadosAnio = $this->Noticia_model->resultados_anio();
        $data['resultadosAnio'] = $resultadosAnio;
        $data['resultadosAnioSummary'] = $this->pml->field_summary($resultadosAnio, 'qty_noticias');

        $this->App_model->view(TPL_FRONT, $data);
    }

// RESULTADOS LÍNEA DE TIEMPO
//-----------------------------------------------------------------------------

    function resultados_linea($cat_1 = 0, $compartible = 0)
    {
        $data['cat_1'] = $cat_1;
        $data['arrCat1'] = $this->Noticia_model->options_cat_1();

        $data['head_title'] = 'ClasiNoti :: Resultados';
        $data['view_a'] = $this->views_folder . 'resultados/linea/linea_v';
        $data['nav_2'] = $this->views_folder . 'explorar/menu_v';

        $data['series'] = $this->Noticia_model->get_series($cat_1);

        $this->App_model->view(TPL_FRONT, $data);
    }

    function get_qty_rows_series($cat_1 = 0)
    {
        $data['qty_rows_series'] = $this->Db_model->num_rows('noticias_series', "cat_1 = {$cat_1}");
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    function update_series($cat_1 = 0, $compartible = 0)
    {
        $rows = $this->Noticia_model->update_series($cat_1, $compartible);
        $data['qty_updated'] = count($rows);

        //Salida JSON
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    function get_series($cat_1 = 0)
    {
        $data = $this->Noticia_model->get_series($cat_1);
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

// Resultados categoría
//-----------------------------------------------------------------------------

    function resultados_categoria($year = 0)
    {
        $data['head_title'] = 'ClasiNoti :: Resultados por categoría';
        $data['view_a'] = $this->views_folder . 'resultados/categoria/categoria_v';
        $data['nav_2'] = $this->views_folder . 'explorar/menu_v';
        $data['year'] = $year;

        $data['arrCat1'] = $this->Noticia_model->options_cat_1();

        $this->App_model->view(TPL_FRONT, $data);
    }

    function get_resultados_categoria($year = 0)
    {
        $data = $this->Noticia_model->get_resultados_categoria($year);

        //Salida JSON
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

// Otros gráficos
//-----------------------------------------------------------------------------

    function resultados($tipo ='linea_cantidad')
    {
        $data['cat_1'] = 0;
        $data['arrCat1'] = $this->Noticia_model->options_cat_1();

        $data['head_title'] = 'ClasiNoti :: Resultados';
        $data['view_a'] = $this->views_folder . "resultados/{$tipo}/resultados_v";
        $data['nav_2'] = $this->views_folder . 'explorar/menu_v';

        $this->App_model->view(TPL_FRONT, $data);
    }

// PROCESOS MASIVOS
//-----------------------------------------------------------------------------

    /**
     * Actualizar noticias.status, para coincidir con tamaño de muestra
     * requerido
     * 2022-10-08
     */
    function set_muestra()
    {
        $samples_size = [
            2016 => 250, 2017 => 290, 2018 => 290, 2019 => 290, 2020 => 290, 2021 => 300, 2022 => 290
        ];

        $queries = [];
        $qty_affected = [];

        foreach ($samples_size as $year => $size) {
            $this->db->order_by('id','ASC');
            $this->db->where('anio_publicacion',$year);
            $this->db->where('status', 1);
            $noticias = $this->db->get('noticias', $size);
            
            $index = $size - 1;

            $max_id = $noticias->row($index)->id;

            $sql = "UPDATE noticias
                SET status = 2
                WHERE id > {$max_id} AND anio_publicacion = {$year} AND status = 1";

            $queries[$year] = $sql;

            $this->db->query($sql);            

            $qty_affected[$year] = $this->db->affected_rows();
        }

        $data['queries'] = $queries;
        $data['qty_affected'] = $qty_affected;

        //Salida JSON
        $this->output->set_content_type('application/json')->set_output(json_encode($data));

    }
}