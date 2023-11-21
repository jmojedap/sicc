<?php
class Repositorio_model extends CI_Model{

    function basic($contenido_id)
    {
        $row = $this->Db_model->row_id('repo_contenidos', $contenido_id);

        $data['row'] = $row;
        $data['head_title'] = substr($data['row']->titulo,0,70);
        $data['view_a'] = $this->views_folder . 'post_v';
        $data['nav_2'] = $this->views_folder . 'menu_v';

        return $data;
    }

// EXPLORE FUNCTIONS - repositorio/explore
//-----------------------------------------------------------------------------
    
    /**
     * Array con los datos para la vista de exploración
     */
    function explore_data($filters, $num_page, $per_page = 100)
    {
        //Data inicial, de la tabla
            $data = $this->get($filters, $num_page, $per_page);
        
        //Elemento de exploración
            $data['controller'] = 'repositorio';                       //Nombre del controlador
            $data['cf'] = 'repositorio/explore/';                      //Nombre del controlador
            $data['views_folder'] = 'admin/repositorio/explore/';      //Carpeta donde están las vistas de exploración
            $data['numPage'] = $num_page;                       //Número de la página
            
        //Vistas
            $data['head_title'] = 'Repositorio';
            $data['view_a'] = $data['views_folder'] . 'explore_v';
            $data['nav_2'] = $data['views_folder'] . 'menu_v';
        
        return $data;
    }

    function get($filters, $num_page, $per_page = 100)
    {
        //Load
            $this->load->model('Search_model');

        //Búsqueda y Resultados
            $data['filters'] = $filters;
            $offset = ($num_page - 1) * $per_page;      //Número de la página de datos que se está consultado
            $elements = $this->search($filters, $per_page, $offset);    //Resultados para página
        
        //Cargar datos
            $data['list'] = $elements->result();
            $data['strFilters'] = $this->Search_model->str_filters($filters, TRUE);
            $data['qtyResults'] = $this->qty_results($filters);
            $data['maxPage'] = ceil($this->pml->if_zero($data['qtyResults'],1) / $per_page);   //Cantidad de páginas

        return $data;
    }

    /**
     * Segmento Select SQL, con diferentes formatos, consulta de posts
     * 2022-08-23
     */
    function select($format = 'general')
    {
        $arr_select['general'] = 'id, titulo, descripcion, slug, anio_publicacion, url_thumbnail, url_image,
            estado_publicacion, revision_ruta_disco, palabras_clave, url_contenido_externo, entidad,
            entidad_sigla, tipo_contenido, subtema_1, subtema_2, formato_cod, sector_area';
        $arr_select['export'] = '*';
        $arr_select['dataviz'] = 'id, titulo, descripcion, estado_publicacion, anio_publicacion, entidad_sigla,
            dependencia, tema_cod, subtema_1, tipo_archivo, tipo_formato_cod, formato_cod, tipo_contenido,
            metodologia_cod, updated_at';
        $arr_select['id'] = 'id';

        return $arr_select[$format];
    }
    
    /**
     * Query con resultados de posts filtrados, por página y offset
     * 2023-10-10
     */
    function search($filters, $per_page = NULL, $offset = NULL)
    {
        //Segmento SELECT
            $select_format = 'general';
            if ( $filters['sf'] != '' ) { $select_format = $filters['sf']; }
            $this->db->select($this->select($select_format));
        
        //Orden
            if ( $filters['o'] != '' )
            {
                $order_type = $this->pml->if_strlen($filters['ot'], 'ASC');
                $this->db->order_by($filters['o'], $order_type);
            } else {
                $this->db->order_by('anio_publicacion', 'DESC');
                $this->db->order_by('estado_publicacion', 'ASC');
            }
            
        //Filtros
            $search_condition = $this->search_condition($filters);
            if ( $search_condition ) { $this->db->where($search_condition);}
            
        //Obtener resultados
            $query = $this->db->get('repo_contenidos', $per_page, $offset); //Resultados por página
        
        return $query;
        
    }

    /**
     * String con condición WHERE SQL para filtrar post
     * 2022-05-02
     */
    function search_condition($filters)
    {
        $condition = NULL;

        $condition .= $this->role_filter() . ' AND ';

        //q words condition
        $qFields = ['titulo', 'palabras_clave', 'revision_ruta_disco', 'descripcion', 'investigadores', 'radicado_orfeo'];
        $words_condition = $this->Search_model->words_condition($filters['q'], $qFields);
        if ( $words_condition )
        {
            $condition .= $words_condition . ' AND ';
        }
        
        //Otros filtros
        if ( $filters['status'] != '' ) { $condition .= "estado_publicacion = {$filters['status']} AND "; }
        if ( $filters['y'] != '' ) { $condition .= "anio_publicacion = {$filters['y']} AND "; }
        if ( $filters['repo_formato'] != '' ) { $condition .= "formato_cod = {$filters['repo_formato']} AND "; }
        if ( $filters['repo_tipo'] != '' ) { $condition .= "tipo_contenido = {$filters['repo_tipo']} AND "; }
        if ( $filters['repo_tema'] != '' ) { $condition .= "tema_cod = {$filters['repo_tema']} AND "; }
        if ( $filters['repo_subtema'] != '' ) {
            $condition .= "(subtema_1 = {$filters['repo_subtema']} OR subtema_2 = {$filters['repo_subtema']}) AND ";
        }
        if ( $filters['repo_area'] != '' ) { $condition .= "sector_area = {$filters['repo_area']} AND "; }
        if ( $filters['fe1'] != '' ) { $condition .= "entidad_sigla = '{$filters['fe1']}' AND "; }

        if ( $filters['u'] != '' ) { $condition .= "creator_id = {$filters['u']} AND "; }
        if ( $filters['condition'] != '' ) { $condition .= "{$filters['condition']} AND "; }
        if ( $filters['fe2'] != '' ) { $condition .= "contenido_disponible = '{$filters['fe2']}' AND "; }
        
        //Quitar cadena final de ' AND '
        if ( strlen($condition) > 0 ) { $condition = substr($condition, 0, -5);}
        
        return $condition;
    }
    
    /**
     * Devuelve la cantidad de registros encontrados en la tabla con los filtros
     * establecidos en la búsqueda
     */
    function qty_results($filters)
    {
        $this->db->select('id');
        $search_condition = $this->search_condition($filters);
        if ( $search_condition ) { $this->db->where($search_condition);}
        $query = $this->db->get('repo_contenidos'); //Para calcular el total de resultados

        return $query->num_rows();
    }

    /**
     * Query para exportar
     * 2022-08-17
     */
    function query_export($filters)
    {
        //Select
        $select = $this->select('export');
        if ( $filters['sf'] != '' ) { $select = $this->select($filters['sf']); }
        $this->db->select($select);

        //Condición Where
        $search_condition = $this->search_condition($filters);
        if ( $search_condition ) { $this->db->where($search_condition);}

        //Get
        $query = $this->db->get('repo_contenidos', 10000);  //Hasta 10.000 registros

        return $query;
    }
    
    /**
     * Devuelve segmento SQL
     */
    function role_filter()
    {
        $role = $this->session->userdata('role');
        $condition = 'id > 0';  //Valor por defecto, todos los contenidos
        
        if ( $role <= 2 ) 
        {   //Desarrollador, todos los post
            $condition = 'id > 0';
        }
        
        return $condition;
    }
    
    /**
     * Array con options para ordenar el listado de post en la vista de
     * exploración
     */
    function order_options()
    {
        $order_options = array(
            '' => '[ Ordenar por ]',
            'id' => 'ID Contenido',
            'titulo' => 'Título'
        );
        
        return $order_options;
    }

// CRUD
//-----------------------------------------------------------------------------

    /**
     * Objeto registro de un post ID, con un formato específico
     * 2021-01-04
     */
    function row($contenido_id, $format = 'general')
    {
        $row = NULL;    //Valor por defecto

        $this->db->select($this->select($format));
        $this->db->where('id', $contenido_id);
        $query = $this->db->get('repo_contenidos', 1);

        if ( $query->num_rows() > 0 ) $row = $query->row();

        return $row;
    }

    /**
     * Guardar un registro en la tabla repo_contenidos
     * 2022-07-27
     */
    function save($arr_row = null)
    {
        $contenido_id = 0;
        //Verificar si hay array con registro
        if ( is_null($arr_row) ) $arr_row = $this->Db_model->arr_row();

        //Verificar si tiene id definido, insertar o actualizar
        if ( ! isset($arr_row['id']) ) 
        {
            //No existe, insertar
            $arr_row['slug'] = $this->Db_model->unique_slug($arr_row['titulo'],'repo_contenidos');
            $this->db->insert('repo_contenidos', $arr_row);
            $contenido_id = $this->db->insert_id();
            $this->update_dependents($contenido_id);
        } else {
            //Ya existe, editar
            $contenido_id = $arr_row['id'];
            unset($arr_row['id']);

            $this->db->where('id', $contenido_id)->update('repo_contenidos', $arr_row);
        }        

        $data['saved_id'] = $contenido_id;
        return $data;
    }

    /**
     * Actualizar campos dependientes de la tabla repo_contenidos
     * valores por defecto
     * 2023-10-16
     */
    function update_dependents($contenido_id)
    {
        $contenido = $this->Db_model->row_id('repo_contenidos', $contenido_id);

        $aRow['url_contenido'] = URL_REPO_STORAGE . 'contenidos/' . $contenido->anio_publicacion . '/' . $contenido->id . '-' . $contenido->slug . '.pdf';
        $aRow['url_image'] = URL_REPO_STORAGE . 'portadas/' . $contenido->anio_publicacion . '/' . $contenido->id . '.jpg';
        $aRow['url_thumbnail'] = URL_REPO_STORAGE . 'miniaturas/' . $contenido->anio_publicacion . '/' . $contenido->id . '.jpg';

        $save_id = $this->Db_model->save('repo_contenidos', "id = {$contenido_id}", $aRow);

        return $save_id;
        
    }

// GESTIÓN DEL ARCHIVO
//-----------------------------------------------------------------------------

    /**
     * Realiza el upload de un file al servidor, asocia el archivo cargado en
     * repo_contenidos.url_contenido
     * 2023-10-16
     */
    function upload_file($contenido_id)
    {
        $contenido = $this->Db_model->row_id('repo_contenidos', $contenido_id);
        $config_upload = $this->config_upload($contenido);
        $this->load->library('upload', $config_upload);
        

        if ( $this->upload->do_upload('file_field') )  //Campo "file_field" del formulario
        {
            
            $upload_data = $this->upload->data();
            $aRow['extension_archivo'] = str_replace(".", "", strtolower($upload_data['file_ext']));
            $aRow['url_contenido'] = $this->contenido_path($contenido, 'url_folder') . $this->contenido_path($contenido, 'raw_name') . $upload_data['file_ext'];
            $aRow['contenido_disponible'] = 1;
            
            $data['saved_id'] = $this->Db_model->save('repo_contenidos', "id = {$contenido->id}", $aRow);
            
            //Array resultado
                $data = ['status' => 1,
                    'upload_data' => $upload_data,
                    'contenido' => $aRow
                ];
        } else {
            //No se cargó
            $data = ['status' => 0];
            $data['html'] = $this->upload->display_errors('<div role="alert" class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><i class="fa fa-warning"></i> ', '</div>');
            $data['config_upload'] = $config_upload;
        }
        
        return $data;
    }
    
    /**
     * Configuración para cargue de files, algunas propiedades solo se aplican
     * para files de imagen.
     * 2023-10-16
     */
    function config_upload($contenido)
    {
        $config['upload_path'] = $this->contenido_path($contenido,'folder');    //Carpeta año y mes
        $config['allowed_types'] = 'pdf|doc|docx|csv|xls|xlsx';
        $config['max_size']	= '50000';       //Tamaño máximo en Kilobytes
        $config['max_width']  = '10000';     //Ancho máxima en pixeles
        $config['max_height']  = '10000';    //Altura máxima en pixeles
        $config['file_name']  = $this->contenido_path($contenido, 'raw_name');
        
        return $config;
    }

    /**
     * Eliminar archivo de un contenido
     * 2023-10-16
     */
    function delete_file($contenido_id, $slug)
    {
        $condition = "id = {$contenido_id} AND slug = '{$slug}'";
        $contenido = $this->Db_model->row('repo_contenidos', $condition);

        $contenidoPath = $this->contenido_path($contenido, 'full');
        $qtyUnlinked = 0;
        if ( ! is_null($contenido) ) {
            if ( file_exists($contenidoPath) ) 
            {
                unlink($contenidoPath);
                $qtyUnlinked = 1;
            }
        }

        //Actualizar registro
        $aRow['contenido_disponible'] = 0;
        $aRow['url_contenido'] = '';
        $aRow['extension_archivo'] = '';
        $data['saved_id'] = $this->Db_model->save('repo_contenidos', "id = {$contenido_id}", $aRow);

        $data['qty_unlinked'] = $qtyUnlinked;

        return $data;
    }

    /**
     * Ruta del archivo del contenido en diferenes posibles formatos
     * 2023-10-16
     */
    function contenido_path($contenido, $format = 'full')
    {
        $fileName = $contenido->id . '-' . $contenido->slug . '.' . $contenido->extension_archivo;
        $folderPath = PATH_CONTENT . 'repositorio/contenidos/' . $contenido->anio_publicacion . '/';
        $contenidoPath = $folderPath . $fileName;   //Full path
        if ( $format == 'file_name' ) {
            $contenidoPath = $fileName;
        } else if ( $format == 'folder' ) {
            $contenidoPath = $folderPath;
        } else if ( $format == 'url' ) {
            $contenidoPath = URL_REPO_STORAGE . 'contenidos/' . $contenido->anio_publicacion . '/' . $fileName;
        } else if ( $format == 'url_folder' ) {
            $contenidoPath = URL_REPO_STORAGE . 'contenidos/' . $contenido->anio_publicacion . '/';
        } else if ( $format == 'raw_name' ) {
            $contenidoPath = $contenido->id . '-' . $contenido->slug;
        }

        return $contenidoPath;
    }

// ELIMINACIÓN DE UN CONTENIDO
//-----------------------------------------------------------------------------
    
    /**
     * Verifica si el usuario en sesión tiene permiso para eliminar un registro
     * tabla post
     * 2020-08-18
     */
    function deleteable($row_id)
    {
        $row = $this->Db_model->row_id('repo_contenidos', $row_id);

        $deleteable = 0;    //Valor por defecto

        //Es Administrador
        if ( in_array($this->session->userdata('role'), [1,2,3]) ) {
            $deleteable = 1;
        }

        //Es el creador
        if ( $row->creator_id = $this->session->userdata('user_id') ) {
            $deleteable = 1;
        }

        return $deleteable;
    }

    /**
     * Eliminar un post de la base de datos, se eliminan registros de tablas
     * relacionadas
     * 2022-08-20
     */
    function delete($contenido_id)
    {
        $qty_deleted = 0;

        if ( $this->deleteable($contenido_id) ) 
        {
            //Tablas relacionadas
                //$this->db->where('parent_id', $contenido_id)->delete('repo_contenidos');
                //$this->db->where('contenido_id', $contenido_id)->delete('post_meta');
            
            //Tabla principal
                $this->db->where('id', $contenido_id)->delete('repo_contenidos');

            $qty_deleted = $this->db->affected_rows();  //De la última consulta, tabla principal

            //Eliminar archivos relacionados
            if ( $qty_deleted > 0 ) $this->delete_files($contenido_id);
        }

        return $qty_deleted;
    }

    /**
     * Eliminar los archivos relacionados con el contenido eliminado
     * 2021-02-20
     */
    function delete_files($contenido_id)
    {
        //Identificar archivos
        $this->db->select('id');
        $this->db->where("table_id = 141 AND related_1 = {$contenido_id}");
        $files = $this->db->get('files');
        
        //Eliminar archivos
        $this->load->model('File_model');
        $session_data = $this->session->userdata();
        foreach ( $files->result() as $file ) {
            $this->File_model->delete($file->id, $session_data);
        }
    }

// VALIDATION
//-----------------------------------------------------------------------------

    function arr_row($process = 'update')
    {
        $arr_row = $this->input->post();
        $arr_row['updater_id'] = $this->session->userdata('user_id');
        
        if ( $process == 'insert' )
        {
            //$arr_row['slug'] = $this->Db_model->unique_slug($arr_row['post_name'], 'repo_contenidos');
            $arr_row['creator_id'] = $this->session->userdata('user_id');
        }
        
        return $arr_row;
    }

// IMAGES
//-----------------------------------------------------------------------------

    /**
     * Imágenes asociadas al post
     * 2022-01-11
     */
    function images($contenido_id)
    {
        $this->db->select('files.id, files.title, url, url_thumbnail, files.integer_1 AS main, position');
        $this->db->where('is_image', 1);
        $this->db->where('table_id', '141');      //Tabla repo_contenidos
        $this->db->where('related_1', $contenido_id);   //Relacionado con el contenido
        $this->db->order_by('position', 'ASC');
        $images = $this->db->get('files');

        return $images;
    }

    /**
     * Establecer una imagen asociada a un post como la imagen principal (tabla file)
     * 2020-09-05
     */
    function set_main_image($contenido_id, $file_id)
    {
        $data = array('status' => 0);

        $row_file = $this->Db_model->row_id('files', $file_id);
        if ( ! is_null($row_file) )
        {
            //Quitar otro principal
            $this->db->query("UPDATE files SET integer_1 = 0 WHERE table_id = 141 AND related_1 = {$contenido_id} AND integer_1 = 1");

            //Poner nuevo principal
            $this->db->query("UPDATE files SET integer_1 = 1 WHERE id = {$file_id} AND related_1 = {$contenido_id}");

            //Actualizar registro en tabla post
            $arr_row['image_id'] = $row_file->id;
            $arr_row['url_image'] = $row_file->url;
            $arr_row['url_thumbnail'] = $row_file->url_thumbnail;

            $this->db->where('id', $contenido_id);
            $this->db->update('repo_contenidos', $arr_row);

            $data['status'] = 1;
        }

        return $data;
    }

// CONTENIDO INFO
//-----------------------------------------------------------------------------

    /**
     * Array con datos del autor o creador de un post
     */
    function author($row_contenido)
    {
        $author = array(
            'id' => '', 'username' => 'ND', 'display_name' => 'ND', 'url_thumbnail' => '',
        );

        $user = $this->Db_model->row_id('users', $row_contenido->creator_id);
        if ( ! is_null($user) ) {
            $author = array(
                'id' => $user->id,
                'username' => $user->username,
                'display_name' => $user->display_name,
                'url_thumbnail' => $user->url_thumbnail,
            );              
        }

        return $author;
        
    }

// INTERACCIÓN DE USUARIOS
//-----------------------------------------------------------------------------

    /**
     * Proceso alternado, like or unlike un post, registro type 10 en la tabla users_meta
     * 2020-12-22
     */
    function alt_like($contenido_id)
    {
        //Condición
        $condition = "related_1 = {$contenido_id} AND type_id = 10 AND user_id = {$this->session->userdata('user_id')}";

        $row_meta = $this->Db_model->row('users_meta', $condition);

        $data = array('status' => 0);

        if ( is_null($row_meta) )
        {
            //No existe: like
            $arr_row['user_id'] = $this->session->userdata('user_id');
            $arr_row['related_1'] = $contenido_id;
            $arr_row['type_id'] = 10; //Like de un post
            $arr_row['updater_id'] = $this->session->userdata('user_id');
            $arr_row['creator_id'] = $this->session->userdata('user_id');

            $this->db->insert('users_meta', $arr_row);
            
            $data['saved_id'] = $this->db->insert_id();
            $data['status'] = 1;

            //$this->db->query("UPDATE post SET ");
        } else {
            //Existe, eliminar (Unlike)
            $this->db->where('id', $row_meta->id);
            $this->db->delete('users_meta');
            
            $data['qty_deleted'] = $this->db->affected_rows();
            $data['status'] = 2;
        }

        return $data;
    }

// Seguimiento
//-----------------------------------------------------------------------------
    /**
     * Guardar evento de apertura de post
     * 2020-04-26
     */
    function save_open_event($contenido_id)
    {
        $arr_row['type_id'] = 51;   //Apertura de post
        $arr_row['start'] = date('Y-m-d H:i:s');
        $arr_row['end'] = date('Y-m-d H:i:s');
        $arr_row['created_at'] = date('Y-m-d H:i:s');
        $arr_row['ip_address'] = $this->input->ip_address();
        $arr_row['element_id'] = $contenido_id;

        if( ! is_null($this->session->userdata('user_id')) )
        {
            $arr_row['user_id'] = $this->session->userdata('user_id');
            $arr_row['creator_id'] = $this->session->userdata('user_id');
        }

        $event_id = $this->Db_model->save('events', 'id = 0', $arr_row);

        if ( $event_id > 0 ) $this->update_qty_read($contenido_id);

        return $event_id;
    }

    function update_qty_read($contenido_id)
    {
        $arr_row['qty_read'] = $this->Db_model->num_rows('events', "type_id = 51 AND element_id = {$contenido_id}");

        $this->db->where('id', $contenido_id);
        $this->db->update('repo_contenidos', $arr_row);
        
        return $this->db->affected_rows();
    }
}