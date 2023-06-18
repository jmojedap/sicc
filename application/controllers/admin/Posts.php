<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Posts extends CI_Controller{

// Variables generales
//-----------------------------------------------------------------------------
    public $views_folder = 'admin/posts/';
    public $url_controller = URL_ADMIN . 'posts/';

// Constructor
//-----------------------------------------------------------------------------
    
    function __construct() 
    {
        parent::__construct();
        $this->load->model('Post_model');
        date_default_timezone_set("America/Bogota");    //Para definir hora local
    }
    
    function index($post_id = NULL)
    {
        if ( is_null($post_id) ) {
            redirect("admin/posts/explore/");
        } else {
            redirect("admin/posts/info/{$post_id}");
        }
    }
    
//EXPLORE FUNCTIONS
//---------------------------------------------------------------------------------------------------

    /** 
    * Exploración de Posts
    * 2022-08-23
    * */
    function explore($num_page = 1)
    {
        //Identificar filtros de búsqueda
            $this->load->model('Search_model');
            $filters = $this->Search_model->filters();

        //Datos básicos de la exploración
            $data = $this->Post_model->explore_data($filters, $num_page);
        
        //Opciones de filtros de búsqueda
            $data['arr_type'] = $this->Item_model->arr_options('category_id = 33');
            
        //Cargar vista
            $this->App_model->view(TPL_ADMIN, $data);
    }

    /**
     * Listado de Posts, filtrados por búsqueda, JSON
     * 2022-08-23
     */
    function get($num_page = 1, $per_page = 10)
    {
        if ( $per_page > 250 ) $per_page = 250;

        $this->load->model('Search_model');
        $filters = $this->Search_model->filters();

        $data = $this->Post_model->get($filters, $num_page, $per_page);
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }
    
    /**
     * AJAX JSON
     * Eliminar un conjunto de posts seleccionados
     */
    function delete_selected()
    {
        $selected = explode(',', $this->input->post('selected'));
        $data['qty_deleted'] = 0;
        
        foreach ( $selected as $row_id ) 
        {
            $data['qty_deleted'] += $this->Post_model->delete($row_id);
        }

        //Establecer resultado
        if ( $data['qty_deleted'] > 0 ) { $data['status'] = 1; }
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    /**
     * Exportar resultados de búsqueda
     * 2022-08-17
     */
    function export($element_name = 'posts')
    {
        set_time_limit(120);    //120 segundos, 2 minutos para el proceso

        //Identificar filtros y búsqueda
        $this->load->model('Search_model');
        $filters = $this->Search_model->filters();

        $data['query'] = $this->Post_model->query_export($filters);

        if ( $data['query']->num_rows() > 0 ) {
            //Preparar datos
                $data['sheet_name'] = $element_name;

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
    function open($post_id, $meta_id = 0)
    {
        $row = $this->Db_model->row_id('posts', $post_id);
        $row_meta = $this->Db_model->row_id('users_meta', $meta_id); //Registro de asignación
        $destination = "admin/posts/read/{$post_id}";

        if ( $row->type_id == 110 ) $destination = "app/contenidos/leer/{$row->id}/{$row->slug}";
        
        redirect($destination);
    }

    /**
     * Mostrar post en vista lectura
     */
    function read($post_id)
    {
        //Datos básicos
        $data = $this->Post_model->basic($post_id);
        unset($data['nav_2']);
        $data['view_a'] = $this->Post_model->type_folder($data['row']) . 'read_v';

        $this->App_model->view(TPL_ADMIN, $data);
    }

    /**
     * Información general del post
     */
    function info($post_id)
    {        
        //Datos básicos
        $data = $this->Post_model->basic($post_id);
        $data['back_link'] = $this->url_controller . 'explore';
        $data['view_a'] = $data['type_folder'] . 'info_v';

        $this->App_model->view(TPL_ADMIN, $data);
    }

    /**
     * Información detallada del post valores en la base de datos
     * 2020-08-18
     */
    function details($post_id)
    {        
        //Datos básicos
        $data = $this->Post_model->basic($post_id);
        $data['back_link'] = $this->url_controller . 'explore';
        $data['view_a'] = 'common/row_details_v';

        $this->App_model->view(TPL_ADMIN, $data);
    }

    function get_info($post_id)
    {
        $data = $this->Post_model->basic($post_id);
        //Salida JSON
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

// CREACIÓN DE UN POST
//-----------------------------------------------------------------------------

    /**
     * Vista Formulario para la creación de un nuevo post
     */
    function add()
    {
        $data['options_type'] = $this->Item_model->arr_options('category_id = 33');

        //Variables generales
            $data['head_title'] = 'Posts';
            $data['nav_2'] = $this->views_folder . 'explore/menu_v';
            $data['view_a'] = $this->views_folder . 'add/add_v';

        $this->App_model->view(TPL_ADMIN, $data);
    }

    /**
     * AJAX JSON
     * Crear o actualizar el registro de un post
     */
    function save()
    {
        $data = $this->Post_model->save();
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }
    
    
// EDICIÓN Y ACTUALIZACIÓN
//-----------------------------------------------------------------------------

    /**
     * Formulario para la edición de los datos de un user. Los datos que se
     * editan dependen de la $section elegida.
     */
    function edit($post_id)
    {
        //Datos básicos
        $data = $this->Post_model->basic($post_id);

        $data['options_type'] = $this->Item_model->options('category_id = 33');
        $data['options_status'] = $this->Item_model->options('category_id = 42');
        $data['options_cat_1'] = $this->Item_model->options('category_id = 21 AND level = 0', 'Categoría');

        //Opciones categoría 2
        $condition = 'category_id = 21 AND level = 1';
        if ( $data['row']->cat_1 > 0 ) $condition .= " AND parent_id = {$data['row']->cat_1}";
        $data['options_cat_2'] = $this->Item_model->options($condition, 'Subcategoría');
        
        //Array data espefícicas
            $data['back_link'] = $this->url_controller . 'explore';
            $data['view_a'] = $data['type_folder'] . 'edit_v';
        
        $this->App_model->view(TPL_ADMIN, $data);
    }

// POST IMAGES
//-----------------------------------------------------------------------------

    /**
     * Vista, gestión de imágenes de un post
     * 2020-07-14
     */
    function images($post_id)
    {
        $data = $this->Post_model->basic($post_id);

        $data['images'] = $this->Post_model->images($post_id);

        $data['view_a'] = $this->views_folder . 'images/images_v';
        $data['back_link'] = $this->url_controller . 'explore';
        $this->App_model->view(TPL_ADMIN, $data);
    }

    /**
     * AJAX JSON
     * Imágenes de un post
     * 2020-07-07
     */
    function get_images($post_id)
    {
        $images = $this->Post_model->images($post_id);
        $data['images'] = $images->result();

        //Salida JSON
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    /**
     * Establecer imagen principal de un post
     * 2020-07-07
     */
    function set_main_image($post_id, $file_id)
    {
        $data = $this->Post_model->set_main_image($post_id, $file_id);
        //Salida JSON
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

// COMENTARIOS
//-----------------------------------------------------------------------------

    /**
     * Comentarios del post
     */
    function comments($post_id)
    {
        $data = $this->Post_model->basic($post_id);
        $data['table_id'] = 2000;   //Código tabla posts

        $data['view_a'] = $this->views_folder . 'comments_v';
        $this->App_model->view(TPL_ADMIN, $data);
    }

// IMPORTACIÓN DE POSTS
//-----------------------------------------------------------------------------

    /**
     * Mostrar formulario de importación de posts
     * con archivo Excel. El resultado del formulario se envía a 
     * 'posts/import_e'
     */
    function import($type = 'general')
    {
        $data = $this->Post_model->import_config($type);

        $data['url_file'] = URL_RESOURCES . 'import_templates/' . $data['template_file_name'];

        $data['head_title'] = 'Posts';
        $data['nav_2'] = $this->views_folder . 'explore/menu_v';
        $data['view_a'] = 'common/import_v';
        
        $this->App_model->view(TPL_ADMIN, $data);
    }

    //Ejecuta la importación de posts con archivo Excel
    function import_e()
    {
        //Proceso
        $this->load->library('excel');            
        $imported_data = $this->excel->arr_sheet_default($this->input->post('sheet_name'));
        
        if ( $imported_data['status'] == 1 )
        {
            $data = $this->Post_model->import($imported_data['arr_sheet']);
        }

        //Cargue de variables
            $data['status'] = $imported_data['status'];
            $data['message'] = $imported_data['message'];
            $data['arr_sheet'] = $imported_data['arr_sheet'];
            $data['sheet_name'] = $this->input->post('sheet_name');
            $data['back_destination'] = "posts/explore/";
        
        //Cargar vista
            $data['head_title'] = 'Posts';
            $data['head_subtitle'] = 'Resultado importación';
            $data['view_a'] = 'common/import_result_v';
            $data['nav_2'] = $this->views_folder . 'explore/menu_v';

        $this->App_model->view(TPL_ADMIN, $data);
    }

// Asignación a usuario
//-----------------------------------------------------------------------------

    /**
     * Asigna un contenido digital a un usuario
     */
    function add_to_user($post_id, $user_id)
    {
        $data = $this->Post_model->add_to_user($post_id, $user_id);

        //Salida JSON
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    /**
     * Retira un contenido digital a un usuario
     * 2020-04-30
     */
    function remove_to_user($post_id, $meta_id)
    {
        $data = $this->Post_model->remove_to_user($post_id, $meta_id);

        //Salida JSON
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    /**
     * ESPECIAL
     * Asigna un contenido digital a un usuario descontando del saldo o crédito disponible
     * 2020-08-20
     */
    function add_to_user_payed($post_id, $user_id)
    {
        $data = array('status' => 0, 'message' => 'Saldo insuficiente');
        $price = $this->Db_model->field_id('posts', $post_id, 'integer_2');

        $this->load->model('Order_model');
        $credit = $this->Order_model->credit($user_id);

        if ( $price <= $credit )
        {
            $data = $this->Post_model->add_to_user_payed($post_id, $user_id, $price);
        }

        //Salida JSON
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

// INTERACCIÓN DE USUARIO
//-----------------------------------------------------------------------------

    /**
     * Alternar like and unlike a un post por parte del usuario en sesión
     * 2020-07-17
     */
    function alt_like($post_id)
    {
        $data = $this->Post_model->alt_like($post_id);
        //Salida JSON
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

// ACTUALIZACIONES MASIVAS
//-----------------------------------------------------------------------------

    /**
     * Actualización del campo posts.qty_read, masiva, todos los posts
     * 2020-11-11
     */
    function update_qty_read()
    {
        $this->db->select('id');
        $posts = $this->db->get('posts');

        foreach ($posts->result() as $post)
        {
            $this->Post_model->update_qty_read($post->id);
        }

        $data['qty_updated'] = $posts->num_rows();

        //Salida JSON
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    /**
     * Actualiza el campo posts.slug, para posts donde slug está vacío
     * 2022-07-27
     */
    function update_slugs()
    {
        $data = array('status' => 0, 'message' => 'No se actualizaron registros');

        $this->db->select('id, post_name');
        $this->db->where('LENGTH(slug) = 0');
        $posts = $this->db->get('posts');

        $qty_updated = 0;

        $this->load->helper('string');

        foreach ($posts->result() as $post)
        {
            $slug = random_string('numeric',16);
            if ( strlen($post->post_name) > 0 ) {
                $slug = $this->Db_model->unique_slug($post->post_name, 'posts');
            }

            $arr_row['id'] = $post->id;
            $arr_row['slug'] = $slug;
            $this->Post_model->save($arr_row);
            $qty_updated++;
        }

        
        if ( $qty_updated > 0 ) {
            $data = array('status' => 1, 'message' => 'Registros actualizados: ' . $qty_updated);
        }

        //Salida JSON
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }
}