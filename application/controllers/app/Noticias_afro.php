<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Noticias_afro extends CI_Controller {
        
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
        $this->siguiente();
    }

// FUNCIONES DE CLASIFICACIÓN
//-----------------------------------------------------------------------------

    /**
     * REDIRECT
     * Ir a la siguiente noticia aleatoria sin revisión
     * 2022-07-14
     */
    function siguiente($check_goal = 373)
    {
        $this->db->select('id, aleatorio');
        $this->db->where('id NOT IN (SELECT noticia_id FROM noticias_clasificacion WHERE grupo = 2)');
        $this->db->where('grupo_2',1);    //ClasiNoti Afro
        $this->db->order_by('aleatorio', 'ASC');
        $noticias = $this->db->get('noticias', 2000);

        if ( $noticias->num_rows() > 0 ) {
            $index = random_int(0,$noticias->num_rows() - 1);
            $noticia_id = $noticias->row($index)->id;
            $next_aleatorio = $noticias->row($index)->aleatorio;
            redirect("app/noticias_afro/clasificar/{$noticia_id}/{$next_aleatorio}/{$check_goal}/");
        } else {
            redirect('app/noticias_afro/resumen');
        }
        
    }

    /**
     * Vista formulario para clasificar una noticia
     * 2022-08-17
     */
    function clasificar($noticia_id = 1, $aleatorio = 100019, $check_goal = 373)
    {
        if ( strlen($this->session->userdata('username')) > 0 ) {
            $noticia = $this->Db_model->row('noticias', "id = {$noticia_id} AND aleatorio = {$aleatorio}");
            $data = $this->Noticia_model->basic($noticia->id);
    
            $username = $this->session->userdata('username');
            
            $data['qtyUserChecked'] = $this->Db_model->num_rows('noticias_clasificacion', "grupo = 2");
            $data['checkGoal'] = $check_goal;
            if ( $data['qtyUserChecked'] > $data['checkGoal'] ) { $data['checkGoal'] = $data['qtyUserChecked']; }
    
            $data['options_cat_1'] = $this->Noticia_model->options_cat_1();
            $data['options_clasificacion'] = $this->Noticia_model->options_clasificacion();

            $data['rowClasificacion'] = $this->Db_model->row('noticias_clasificacion', "noticia_id = {$noticia_id} AND grupo = 2");
            
            $data['head_title'] = 'Clasificar';
            $data['view_a'] = $this->views_folder . 'clasificar_afro/clasificar_v';
    
            $this->App_model->view('templates/easypml/noticias', $data);
            //$this->output->enable_profiler(TRUE);
        } else {
            redirect('app/noticias/inicio');
        }
    }

    /**
     * AJAX JSON
     * Guardar los datos de clafificación de la noticia
     * 2022-08-19
     */
    function guardar_clasificacion($noticia_id)
    {
        $data['saved_id'] = 0;
        if ( strlen($this->session->userdata('username')) > 0 )
        {
            $arr_row = $this->input->post();
            $arr_row['noticia_id'] = $noticia_id;
            $arr_row['grupo'] = 2; //NotiAfro
            $arr_row['actualizado_por'] = $this->session->userdata('username');
            $arr_row['updated_at'] = date('Y-m-d H:i:s');
    
            $condition = "noticia_id = {$noticia_id} AND grupo = 2";
            $data['saved_id'] = $this->Db_model->save('noticias_clasificacion', $condition, $arr_row);
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
}