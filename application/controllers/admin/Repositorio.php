<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Repositorio extends CI_Controller{

// Variables generales
//-----------------------------------------------------------------------------
    public $views_folder = 'admin/repositorio/';
    public $url_controller = URL_ADMIN . 'repositorio/';

// Constructor
//-----------------------------------------------------------------------------
    
    function __construct() 
    {
        parent::__construct();
        $this->load->model('Repositorio_model');
        date_default_timezone_set("America/Bogota");    //Para definir hora local
    }
    
    function index($contenido_id = NULL)
    {
        if ( is_null($contenido_id) ) {
            redirect("admin/repositorio/explore/");
        } else {
            redirect("admin/repositorio/info/{$contenido_id}");
        }
    }
    
//EXPLORE FUNCTIONS
//---------------------------------------------------------------------------------------------------

    /** 
    * Exploración de contenidos
    * 2022-08-23
    * */
    function explore($num_page = 1)
    {
        //Identificar filtros de búsqueda
            $this->load->model('Search_model');
            $filters = $this->Search_model->filters();

        //Datos básicos de la exploración
            $data = $this->Repositorio_model->explore_data($filters, $num_page, 100);
        
        //Opciones de filtros de búsqueda
            $data['arrEstadoPublicacion'] = $this->Item_model->arr_options('category_id = 406');
            $data['arrFormato'] = $this->Item_model->arr_options('category_id = 410');
            $data['arrTipo'] = $this->Item_model->arr_options('category_id = 412');
            $data['arrArea'] = $this->Item_model->arr_options('category_id = 616');
            
        //Cargar vista
            $this->App_model->view(TPL_ADMIN, $data);
    }

    /**
     * Listado de Contenidos, filtrados por búsqueda, JSON
     * 2022-08-23
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
     * Eliminar un conjunto de Contenidos seleccionados
     */
    function delete_selected()
    {
        $selected = explode(',', $this->input->post('selected'));
        $data['qty_deleted'] = 0;
        
        foreach ( $selected as $row_id ) 
        {
            $data['qty_deleted'] += $this->Repositorio_model->delete($row_id);
        }

        //Establecer resultado
        if ( $data['qty_deleted'] > 0 ) { $data['status'] = 1; }
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    /**
     * Exportar resultados de búsqueda
     * 2023-04-04
     */
    function export($element_name = 'contenidos')
    {
        set_time_limit(120);    //120 segundos, 2 minutos para el proceso

        //Identificar filtros y búsqueda
        $this->load->model('Search_model');
        $filters = $this->Search_model->filters();

        $data['query'] = $this->Repositorio_model->query_export($filters);

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

    /**
     * Listado de Contenidos, filtrados por búsqueda, JSON
     * 2022-08-23
     */
    function list($num_page = 1, $per_page = 2000)
    {
        $this->load->model('Search_model');
        $filters = $this->Search_model->filters();
        $filters['sf'] = 'id';
        if ( $this->input->get('sf') != null ) {
            $filters['sf'] = $this->input->get('sf');
        }

        $data = $this->Repositorio_model->get($filters, $num_page, $per_page);
        $this->output->set_content_type('application/json')->set_output(json_encode($data['list']));
    }
    
// INFORMACÍON LECTURA Y APERTURA
//-----------------------------------------------------------------------------

    /**
     * Abrir o redireccionar a la vista pública de un contenido
     */
    function open($contenido_id, $meta_id = 0)
    {
        $row = $this->Db_model->row_id('Contenidos', $contenido_id);
        $row_meta = $this->Db_model->row_id('users_meta', $meta_id); //Registro de asignación
        $destination = "admin/Contenidos/read/{$contenido_id}";

        if ( $row->type_id == 110 ) $destination = "app/contenidos/leer/{$row->id}/{$row->slug}";
        
        redirect($destination);
    }

    /**
     * Mostrar contenido en vista lectura
     */
    function read($contenido_id)
    {
        //Datos básicos
        $data = $this->Repositorio_model->basic($contenido_id);
        //unset($data['nav_2']);
        $data['view_a'] = $this->views_folder . 'read_v';
        $data['back_link'] = $this->url_controller . 'explore';

        $this->App_model->view(TPL_ADMIN, $data);
    }

    /**
     * Información general del contenido
     */
    function info($contenido_id)
    {        
        //Datos básicos
        $data = $this->Repositorio_model->basic($contenido_id);
        $data['back_link'] = $this->url_controller . 'explore';
        $data['view_a'] = $this->views_folder . 'info_v';

        $this->App_model->view(TPL_ADMIN, $data);
    }

    /**
     * Información detallada del contenido valores en la base de datos
     * 2020-08-18
     */
    function details($contenido_id)
    {        
        //Datos básicos
        $data = $this->Repositorio_model->basic($contenido_id);
        $data['back_link'] = $this->url_controller . 'explore';
        $data['view_a'] = 'common/row_details_v';

        $this->App_model->view(TPL_ADMIN, $data);
    }

    function get_info($contenido_id)
    {
        $data = $this->Repositorio_model->basic($contenido_id);
        //Salida JSON
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

// CREACIÓN DE UN CONTENIDO
//-----------------------------------------------------------------------------

    /**
     * Vista Formulario para la creación de un nuevo contenido
     */
    function add()
    {
        $data['arrTipoArchivo'] = $this->Item_model->arr_options('category_id = 408');
        $data['arrFormato'] = $this->Item_model->arr_options('category_id = 410');
        $data['arrTema'] = $this->Item_model->arr_options('category_id = 415');
        $data['arrSubtema'] = $this->Item_model->arr_options('category_id = 416');
        $data['arrCampo'] = $this->Item_model->arr_options('category_id = 612');
        $data['arrSubcampo'] = $this->Item_model->arr_options('category_id = 614');
        $data['arrArea'] = $this->Item_model->arr_options('category_id = 616');

        //Variables generales
            $data['head_title'] = 'Nuevo contenido';
            $data['nav_2'] = $this->views_folder . 'explore/menu_v';
            $data['view_a'] = $this->views_folder . 'add/add_v';

        $this->App_model->view(TPL_ADMIN, $data);
    }

    /**
     * AJAX JSON
     * Crear o actualizar el registro de un contenido
     */
    function save()
    {
        $data = $this->Repositorio_model->save();
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }
    
    
// EDICIÓN Y ACTUALIZACIÓN
//-----------------------------------------------------------------------------

    /**
     * Formulario para la edición de los datos de un contenido.
     */
    function edit($contenido_id, $section = 'basic')
    {
        //Datos básicos
        $data = $this->Repositorio_model->basic($contenido_id);

        $data['arrEstadoPublicacion'] = $this->Item_model->arr_options('category_id = 406');
        $data['arrTipoArchivo'] = $this->Item_model->arr_options('category_id = 408');
        $data['arrFormato'] = $this->Item_model->arr_options('category_id = 410');
        $data['arrTipo'] = $this->Item_model->arr_options('category_id = 412');
        $data['arrCategoriaContenido'] = $this->Item_model->arr_options('category_id = 413');
        $data['arrMetodologia'] = $this->Item_model->arr_options('category_id = 414');
        $data['arrTema'] = $this->Item_model->arr_options('category_id = 415');
        $data['arrSubtema'] = $this->Item_model->arr_options('category_id = 416');
        
        //Array data espefícicas
            $data['back_link'] = $this->url_controller . 'explore';
            $data['view_a'] = $this->views_folder . "edit/{$section}_v";
        
        $this->App_model->view(TPL_ADMIN, $data);
    }


    /**
     * Formulario para la edición detallada de un registro en la tabla
     * repo_contenidos
     * 2023-02-07
     */
    function edit_details($contenido_id)
    {
        //Datos básicos
        $data = $this->Repositorio_model->basic($contenido_id);
        
        //Configuración del formulario
        $data['formDestination'] = URL_API . 'repositorio/save';
        
        //Campos a editar
        $data['fields'] = $this->db->field_data('repo_contenidos');
        $data['hiddenFields'] = ['id', 'created_at', 'updated_at', 'updater_id', 'creator_id'];

        //Configuración de campos
        $data['requiredFields'] = ['titulo', 'slug', 'descripcion', 'palabras_clave'];

        $data['back_link'] = $this->url_controller . 'explore';
        $data['view_a'] = 'common/bs5/fast_form_v';
        
        $this->App_model->view(TPL_ADMIN, $data);
    }

// CONTENIDO IMAGES
//-----------------------------------------------------------------------------

    /**
     * Vista, gestión de imágenes de un contenido
     * 2020-07-14
     */
    function images($contenido_id)
    {
        $data = $this->Repositorio_model->basic($contenido_id);

        $data['images'] = $this->Repositorio_model->images($contenido_id);

        $data['view_a'] = $this->views_folder . 'images/images_v';
        $data['back_link'] = $this->url_controller . 'explore';
        $this->App_model->view(TPL_ADMIN, $data);
    }

    /**
     * AJAX JSON
     * Imágenes de un contenido
     * 2020-07-07
     */
    function get_images($contenido_id)
    {
        $images = $this->Repositorio_model->images($contenido_id);
        $data['images'] = $images->result();

        //Salida JSON
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    /**
     * Establecer imagen principal de un contenido
     * 2020-07-07
     */
    function set_main_image($contenido_id, $file_id)
    {
        $data = $this->Repositorio_model->set_main_image($contenido_id, $file_id);
        //Salida JSON
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

// COMENTARIOS
//-----------------------------------------------------------------------------

    /**
     * Comentarios del contenido
     */
    function comments($contenido_id)
    {
        $data = $this->Repositorio_model->basic($contenido_id);
        $data['table_id'] = 2000;   //Código tabla Contenidos

        $data['view_a'] = $this->views_folder . 'comments_v';
        $this->App_model->view(TPL_ADMIN, $data);
    }

// ACTUALIZACIONES MASIVAS
//-----------------------------------------------------------------------------

    /**
     * Actualización del campo Contenidos.qty_read, masiva, todos los Contenidos
     * 2020-11-11
     */
    function update_qty_read()
    {
        $this->db->select('id');
        $contenidos = $this->db->get('Contenidos');

        foreach ($contenidos->result() as $post)
        {
            $this->Repositorio_model->update_qty_read($post->id);
        }

        $data['qty_updated'] = $contenidos->num_rows();

        //Salida JSON
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    /**
     * Actualiza el campo Contenidos.slug, para Contenidos donde slug está vacío
     * 2022-07-27
     */
    function update_slugs()
    {
        $data = array('status' => 0, 'message' => 'No se actualizaron registros');

        $this->db->select('id, titulo');
        $this->db->where('LENGTH(slug) = 0');
        $contenidos = $this->db->get('repo_contenidos');

        $qty_updated = 0;

        $this->load->helper('string');

        foreach ($contenidos->result() as $contenido)
        {
            $slug = random_string('numeric',16);
            if ( strlen($contenido->titulo) > 0 ) {
                $slug = $this->Db_model->unique_slug($contenido->titulo, 'repo_contenidos');
            }

            $arr_row['id'] = $contenido->id;
            $arr_row['slug'] = $slug;
            $this->Repositorio_model->save($arr_row);
            $qty_updated++;
        }

        
        if ( $qty_updated > 0 ) {
            $data = array('status' => 1, 'message' => 'Registros actualizados: ' . $qty_updated);
        }

        //Salida JSON
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    /**
     * Actualizar masivamente campo repo_contenidos.extension_archivo
     * 2023-0730
     */
    function update_extension_archivo()
    {
        $data = array('status' => 0, 'message' => 'No se actualizaron registros');

        $this->db->select('id, titulo, url_contenido, tipo_archivo');
        $this->db->where('LENGTH(url_contenido) > 0');
        $contenidos = $this->db->get('repo_contenidos');

        $qty_updated = 0;

        $this->load->helper('string');

        foreach ($contenidos->result() as $contenido)
        {
            $path = parse_url($contenido->url_contenido, PHP_URL_PATH);
            $extension = pathinfo($path, PATHINFO_EXTENSION);

            $arr_row['id'] = $contenido->id;
            $arr_row['tipo_archivo'] = $contenido->tipo_archivo;
            
            //Construyendo array actualizado
            $arr_row['extension_archivo'] = $extension;
            if ( $extension == 'pdf' ) $arr_row['tipo_archivo'] = 10;
            if ( in_array($extension,['mp4']) ) {
                $arr_row['tipo_archivo'] = 20;
            }
            if ( in_array($extension,['doc','docx','csv','html','ods','odt','pptx','rtf','xlk','xls','xslx','zip']) ) {
                $arr_row['tipo_archivo'] = 50;
            }
            $this->Repositorio_model->save($arr_row);
            $qty_updated++;
        }
        
        if ( $qty_updated > 0 ) {
            $data = array('status' => 1, 'message' => 'Registros actualizados: ' . $qty_updated);
        }

        //Salida JSON
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }
}