<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Repositorio extends CI_Controller{

// Variables generales
//-----------------------------------------------------------------------------
    public $views_folder = 'app/repositorio/';
    public $url_controller = URL_APP . 'repositorio/';

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
            redirect("app/repositorio/explorar/");
        } else {
            redirect("app/repositorio/informacion/{$contenido_id}");
        }
    }
    
//EXPLORE FUNCTIONS
//---------------------------------------------------------------------------------------------------

    /** 
    * Exploración de contenidos
    * 2022-08-23
    * */
    function explorar($num_page = 1, $perPage = 15)
    {
        //Identificar filtros de búsqueda
            $this->load->model('Search_model');
            $filters = $this->Search_model->filters();

        //Datos básicos de la exploración
            $data = $this->Repositorio_model->explore_data($filters, $num_page, $perPage);
            $data['view_a'] = $this->views_folder . 'explorar/explore_v';
            $data['nav_2'] = $this->views_folder . 'menus/explorar_v';
            $data['cf'] = 'repositorio/explorar/';                      //Nombre del controlador
            $data['views_folder'] = 'app/repositorio/explorar/';      //Carpeta donde están las vistas de exploración
            $data['perPage'] = $perPage;      //Carpeta donde están las vistas de exploración
        
        //Opciones de filtros de búsqueda
            $data['arrEstadoPublicacion'] = $this->Item_model->arr_options('category_id = 406');
            $data['arrFormato'] = $this->Item_model->arr_options('category_id = 410');
            $data['arrTipo'] = $this->Item_model->arr_options('category_id = 412');
            $data['arrTema'] = $this->Item_model->arr_options('category_id = 415');
            $data['arrSubtema'] = $this->Item_model->arr_options('category_id = 416');
            $data['arrEntidad'] = $this->Item_model->arr_options('category_id = 213 AND item_group = 1');
            $data['arrSiNoNa'] = $this->Item_model->arr_options('category_id = 55 AND cod <= 1');
            $data['arrArea'] = $this->Item_model->arr_options('category_id = 616');
            
        //Cargar vista
            $this->App_model->view(TPL_FRONT, $data);
    }

    /**
     * Listado de Posts, filtrados por búsqueda, JSON
     * 2022-08-23
     */
    function get($num_page = 1, $per_page = 15)
    {
        if ( $per_page > 250 ) $per_page = 250;

        $this->load->model('Search_model');
        $filters = $this->Search_model->filters();

        $data = $this->Repositorio_model->get($filters, $num_page, $per_page);
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
            $data['qty_deleted'] += $this->Repositorio_model->delete($row_id);
        }

        //Establecer resultado
        if ( $data['qty_deleted'] > 0 ) { $data['status'] = 1; }
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    /**
     * Exportar resultados de búsqueda
     * 2023-03-17
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
    
// INFORMACÍON LECTURA Y APERTURA
//-----------------------------------------------------------------------------

    function inicio()
    {
        $data['head_title'] = 'Contenidos CultuRed_Bogotá';
        $data['view_a'] = $this->views_folder . 'inicio/inicio_v';

        $data['arrFormato'] = $this->Item_model->arr_options('category_id = 410');
        $data['arrSubtema'] = $this->Item_model->arr_options('category_id = 416');
        $data['arrArea'] = $this->Item_model->arr_options('category_id = 616');
        
        $this->load->model('Search_model');
        $filters = $this->Search_model->filters();
        $filters['fe2'] = 1;

        $dataContenidos = $this->Repositorio_model->get($filters, 1, 200);
        $data = array_merge($data,$dataContenidos);

        $this->App_model->view(TPL_FRONT . '_fluid', $data);
    }

    /**
     * Abrir o redireccionar a la vista pública de un post
     */
    function open($contenido_id, $meta_id = 0)
    {
        $row = $this->Db_model->row_id('posts', $contenido_id);
        $row_meta = $this->Db_model->row_id('users_meta', $meta_id); //Registro de asignación
        $destination = "admin/posts/read/{$contenido_id}";

        if ( $row->type_id == 110 ) $destination = "app/contenidos/leer/{$row->id}/{$row->slug}";
        
        redirect($destination);
    }

    /**
     * Mostrar post en vista lectura
     */
    function ver($contenido_id)
    {
        //Datos básicos
        $data = $this->Repositorio_model->basic($contenido_id);
        //unset($data['nav_2']);
        $data['view_a'] = 'app/repositorio/ver_v';
        $data['back_link'] = $this->url_controller . 'explorar/1/';

        $this->App_model->view(TPL_FRONT . '_fluid', $data);
    }

    /**
     * Información general del post
     */
    function informacion($contenido_id)
    {        
        //Datos básicos
        $data = $this->Repositorio_model->basic($contenido_id);
        $data['back_link'] = $this->url_controller . 'explorar/1/';
        $data['view_a'] = $this->views_folder . 'info_v';
        $data['nav_2'] = 'app/repositorio/menu_v';

        $this->App_model->view(TPL_FRONT . '_fluid', $data);
    }

    /**
     * Información detallada del post valores en la base de datos
     * 2020-08-18
     */
    function detalles($contenido_id)
    {        
        //Datos básicos
        $data = $this->Repositorio_model->basic($contenido_id);
        $data['back_link'] = $this->url_controller . 'explorar/1/';
        $data['view_a'] = 'common/row_details_v';
        $data['nav_2'] = 'app/repositorio/menu_v';

        $this->App_model->view(TPL_FRONT . '_fluid', $data);
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
        $data['arrEntidad'] = $this->Item_model->arr_options('category_id = 213');
        $data['arrCampo'] = $this->Item_model->arr_options('category_id = 612');
        $data['arrSubcampo'] = $this->Item_model->arr_options('category_id = 614');
        $data['arrArea'] = $this->Item_model->arr_options('category_id = 616');

        //Variables generales
            $data['head_title'] = 'Nuevo contenido';
            $data['nav_2'] = $this->views_folder . 'menus/explorar_v';
            $data['view_a'] = $this->views_folder . 'add/add_v';

        $this->App_model->view(TPL_FRONT, $data);
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
        $data['arrEtapa'] = $this->Item_model->arr_options('category_id = 420');
        $data['arrPeriodicidad'] = $this->Item_model->arr_options('category_id = 422');
        $data['arrMedioDivulgacion'] = $this->Item_model->arr_options('category_id = 418');
        $data['arrTema'] = $this->Item_model->arr_options('category_id = 415');
        $data['arrSubtema'] = $this->Item_model->arr_options('category_id = 416');
        $data['arrCampo'] = $this->Item_model->arr_options('category_id = 612');
        $data['arrSubcampo'] = $this->Item_model->arr_options('category_id = 614');
        $data['arrArea'] = $this->Item_model->arr_options('category_id = 616');
        $data['arrEntidad'] = $this->Item_model->arr_options('category_id = 213');
        
        //Array data espefícicas
            $data['back_link'] = $this->url_controller . 'explorar/1/';
            $data['view_a'] = $this->views_folder . "edit/{$section}_v";
            $data['nav_2'] = 'app/repositorio/menu_v';
            $data['nav_3'] = $this->views_folder . 'menus/edit_v';
        
        $this->App_model->view(TPL_FRONT . '_fluid', $data);
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

        $data['back_link'] = $this->url_controller . 'explorar/1/';
        $data['view_a'] = 'common/bs5/fast_form_v';
        $data['nav_3'] = $this->views_folder . 'menus/edit_v';
        
        $this->App_model->view(TPL_FRONT . '_fluid', $data);
    }

// SUBIR ARCHIVO
//-----------------------------------------------------------------------------

    function archivo($contenido_id)
    {
        //Datos básicos
        $data = $this->Repositorio_model->basic($contenido_id);

        //Array data espefícicas
            $data['back_link'] = $this->url_controller . 'explorar/1/';
            $data['view_a'] = $this->views_folder . "archivo_v";
            $data['nav_2'] = 'app/repositorio/menu_v';

        $this->App_model->view(TPL_FRONT . '_fluid', $data);
    }

// POST IMAGES
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
        $this->App_model->view(TPL_FRONT, $data);
    }

// COMENTARIOS
//-----------------------------------------------------------------------------

    /**
     * Comentarios del contenido
     */
    function comments($contenido_id)
    {
        $data = $this->Repositorio_model->basic($contenido_id);
        $data['table_id'] = 2000;   //Código tabla posts

        $data['view_a'] = $this->views_folder . 'comments_v';
        $this->App_model->view(TPL_FRONT, $data);
    }

// ESPECIFICACIONES Y DOCUMENTACIÓN
//-----------------------------------------------------------------------------

    function especificaciones($page_slug = 'generalidades', $format = '')
    {
        $indiceJSON = file_get_contents(PATH_CONTENT . "docs/componentes/repositorio/indice.json");
        $data['indice'] = file_get_contents(PATH_CONTENT . "docs/componentes/repositorio/indice.json");

        $data['page_slug'] = $page_slug;
        $data['head_title'] = 'Repositorio';
        $data['view_a'] = $this->views_folder . 'especificaciones/especificaciones_v';
        $this->App_model->view(TPL_FRONT, $data);
    }

    function especificaciones_print()
    {
        $indiceJSON = file_get_contents(PATH_CONTENT . "docs/componentes/repositorio/indice.json");

        $data['indice'] = json_decode($indiceJSON);

        foreach( $data['indice'] as $index => $item ){
            $settings['type'] = $item->type;
            $settings['file_path'] = 'docs/componentes/repositorio/' . $item->pageName;
            $settings['view_path'] = $item->pageName;

            $data['contenidos'][$index] = $this->App_model->get_doc($settings);
        }

        $data['head_title'] = 'Repositorio Especificaciones';
        $data['view_a'] = 'app/repositorio/especificaciones_print_v';

        $this->load->view('templates/print/main', $data);
    }

    function requerimientos()
    {
        $this->load->library('google_sheets');
        $data['requerimientos'] = $this->google_sheets->sheetToArray('1YT843HeicDcFuvMrCXvuDIJlzYjB-qVxxhlK7kDCh5Y');

        $data['head_title'] = 'Requerimientos RepoContenidos';
        $data['view_a'] = $this->views_folder . 'requerimientos/requerimientos_v';
        $this->App_model->view(TPL_FRONT, $data);
    }

// OTROS DESARROLLO Y DOCUMENTACIÓN
//-----------------------------------------------------------------------------

    /**
     * Diccionario de datos, detalle datos de campos de tabla
     * 2023-04-09
     */
    function diccionario_de_datos_ant($table = 'repo_contenidos', $format = '')
    {
        //$data['page_slug'] = $page_slug;
        $this->load->model('Post_model');
        $data['head_title'] = 'Repositorio';
        $data['table'] = $table;
        $data['view_a'] = $this->views_folder . 'especificaciones/diccionario_de_datos_v';
        $this->App_model->view(TPL_FRONT, $data);
    }

    /**
     * Diccionario de datos, detalle datos de campos de tabla
     * 2023-04-09
     */
    function diccionario_de_datos($table = 'contenidos', $format = '')
    {
        $this->load->library('google_sheets');
        $driveFileId = '1cJRxQDfGPj6qDIKxRgekUYEj1kH7CxAcLutbg5DqZ68';
        $data['tables'] = $this->google_sheets->sheetToArray($driveFileId, 0);

        $data['table'] = $table;
        $data['head_title'] = 'Diccionario de datos';
        $data['file_id'] = $driveFileId;

        if ( $format == 'print' ) {
            $data['view_a'] = "app/app/diccionario_print_v";
            $this->App_model->view('templates/print/main', $data);
        } else {
            $data['view_a'] = "app/app/diccionario_v";
            $this->App_model->view(TPL_FRONT, $data);
        }
    }
}