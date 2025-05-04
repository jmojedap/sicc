<?php
class Accion_model extends CI_Model{

    function basic($accion_id)
    {
        $row = $this->Db_model->row_id('mecc_acciones', $accion_id);

        $data['row'] = $row;
        $data['head_title'] = $data['row']->nombre_accion;
        $data['view_a'] = $this->views_folder . 'accion_v';
        $data['nav_2'] = $this->views_folder . 'menu_v';

        return $data;
    }

// EXPLORE FUNCTIONS - acciones/explore
//-----------------------------------------------------------------------------
    
    /**
     * Array con los datos para la vista de exploración
     */
    function explore_data($filters, $num_page, $per_page = 10)
    {
        //Data inicial, de la tabla
            $data = $this->get($filters, $num_page, $per_page);
        
        //Elemento de exploración
            $data['controller'] = 'mecc_acciones';                       //Nombre del controlador
            $data['cf'] = 'acciones/explore/';                      //Nombre del controlador
            $data['views_folder'] = 'admin/acciones/explore/';      //Carpeta donde están las vistas de exploración
            $data['numPage'] = $num_page;                       //Número de la página
            
        //Vistas
            $data['head_title'] = 'Posts';
            $data['view_a'] = $data['views_folder'] . 'explore_v';
            $data['nav_2'] = $data['views_folder'] . 'menu_v';
        
        return $data;
    }

    function get($filters, $num_page, $per_page = 10)
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
     * Segmento Select SQL, con diferentes formatos, consulta de acciones
     * 2022-08-23
     */
    function select($format = 'general')
    {
        $arr_select['general'] = '*';
        $arr_select['export'] = '*';

        return $arr_select[$format];
    }
    
    /**
     * Query con resultados de acciones filtrados, por página y offset
     * 2020-07-15
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
                $this->db->order_by('fecha', 'DESC');
            }
            
        //Filtros
            $search_condition = $this->search_condition($filters);
            if ( $search_condition ) { $this->db->where($search_condition);}
            
        //Obtener resultados
            $query = $this->db->get('mecc_acciones', $per_page, $offset); //Resultados por página
        
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
        $arrWords = ['id', 'nombre_accion', 'descripcion', 'nombre_lugar', 'dependencia',
            'equipo_trabajo', 'radicado_orfeo', 'observaciones', 'participantes_equipo'];
        $words_condition = $this->Search_model->words_condition($filters['q'], $arrWords);
        if ( $words_condition )
        {
            $condition .= $words_condition . ' AND ';
        }
        
        //Otros filtros
        if ( $filters['estrategia'] != '' ) { $condition .= "estrategia = {$filters['estrategia']} AND "; }
        if ( $filters['m'] != '' ) { $condition .= "mes = {$filters['m']} AND "; }
        if ( $filters['condition'] != '' ) { $condition .= "{$filters['condition']} AND "; }
        if ( $filters['d1'] != '' ) { $condition .= "fecha >= '{$filters['d1']}' AND "; }
        if ( $filters['d2'] != '' ) { $condition .= "fecha <= '{$filters['d2']}' AND "; }
        if ( $filters['localidad'] != '' ) { $condition .= "cod_localidad = {$filters['localidad']} AND "; }
        
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
        $query = $this->db->get('mecc_acciones'); //Para calcular el total de resultados

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
        $query = $this->db->get('mecc_acciones', 10000);  //Hasta 10.000 registros

        return $query;
    }
    
    /**
     * Devuelve segmento SQL
     */
    function role_filter()
    {
        $role = $this->session->userdata('role');
        $condition = 'id > 0';  //Valor por defecto, ningún post, se obtendrían cero acciones.
        
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
            'id' => 'ID Acción',
            'nombre_accion' => 'Nombre'
        );
        
        return $order_options;
    }

// CRUD
//-----------------------------------------------------------------------------

    /**
     * Objeto registro de un post ID, con un formato específico
     * 2021-01-04
     */
    function row($accion_id, $format = 'general')
    {
        $row = NULL;    //Valor por defecto

        $this->db->select($this->select($format));
        $this->db->where('id', $accion_id);
        $query = $this->db->get('mecc_acciones', 1);

        if ( $query->num_rows() > 0 ) $row = $query->row();

        return $row;
    }

    /**
     * Guardar un registro en la tabla acciones
     * 2022-07-27
     */
    function save($arr_row = null)
    {
        //Verificar si hay array con registro
        if ( is_null($arr_row) ) $arr_row = $this->Db_model->arr_row();

        //Verificar si tiene id definido, insertar o actualizar
        if ( ! isset($arr_row['id']) ) 
        {
            //No existe, insertar
            $this->db->insert('mecc_acciones', $arr_row);
            $accion_id = $this->db->insert_id();
        } else {
            //Ya existe, editar
            $accion_id = $arr_row['id'];
            unset($arr_row['id']);

            $this->db->where('id', $accion_id)->update('mecc_acciones', $arr_row);
        }

        $data['saved_id'] = $accion_id;
        return $data;
    }

// ELIMINACIÓN DE UNA ACCIÓN
//-----------------------------------------------------------------------------
    
    /**
     * Verifica si el usuario en sesión tiene permiso para eliminar un registro
     * tabla mecc_acciones
     * 2023-05-13
     */
    function deleteable($row_id)
    {
        $row = $this->Db_model->row_id('mecc_acciones', $row_id);

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
     * Eliminar una acción de la base de datos, se eliminan registros de tablas
     * relacionadas
     * 2023-05-13
     */
    function delete($accion_id)
    {
        $qty_deleted = 0;

        if ( $this->deleteable($accion_id) ) 
        {
            //Tablas relacionadas
                $this->db->where('accion_id', $accion_id)->delete('mecc_acciones_detalle');
                //$this->db->where('post_id', $accion_id)->delete('post_meta');
            
            //Tabla principal
                $this->db->where('id', $accion_id)->delete('mecc_acciones');

            $qty_deleted = $this->db->affected_rows();  //De la última consulta, tabla principal

            //Eliminar archivos relacionados
            if ( $qty_deleted > 0 ) $this->delete_files($accion_id);
        }

        return $qty_deleted;
    }

    /**
     * Eliminar los archivos relacionados con el post eliminado
     * 2021-02-20
     */
    function delete_files($accion_id)
    {
        //Identificar archivos
        $this->db->select('id');
        $this->db->where("table_id = 121 AND related_1 = {$accion_id}");
        $files = $this->db->get('files');
        
        //Eliminar archivos
        $this->load->model('File_model');
        $session_data = $this->session->userdata();
        foreach ( $files->result() as $file ) {
            $this->File_model->delete($file->id, $session_data);
        }
    }

// IMAGES
//-----------------------------------------------------------------------------

    /**
     * Imágenes asociadas al post
     * 2022-01-11
     */
    function images($accion_id)
    {
        $this->db->select('files.id, files.title, url, url_thumbnail, files.integer_1 AS main, position');
        $this->db->where('is_image', 1);
        $this->db->where('table_id', '121');      //Tabla mecc_acciones
        $this->db->where('related_1', $accion_id);   //Relacionado con el post
        $this->db->order_by('position', 'ASC');
        $images = $this->db->get('files');

        return $images;
    }

    /**
     * Establecer una imagen asociada a una acción como la imagen principal (tabla file)
     * 2020-09-05
     */
    function set_main_image($accion_id, $file_id)
    {
        $data = array('status' => 0);

        $row_file = $this->Db_model->row_id('files', $file_id);
        if ( ! is_null($row_file) )
        {
            //Quitar otro principal
            $this->db->query("UPDATE files SET integer_1 = 0 WHERE table_id = 121 AND related_1 = {$accion_id} AND integer_1 = 1");

            //Poner nuevo principal
            $this->db->query("UPDATE files SET integer_1 = 1 WHERE id = {$file_id} AND related_1 = {$accion_id}");

            //Actualizar registro en tabla post
            $arr_row['image_id'] = $row_file->id;
            $arr_row['url_image'] = $row_file->url;
            $arr_row['url_thumbnail'] = $row_file->url_thumbnail;

            $this->db->where('id', $accion_id);
            $this->db->update('mecc_acciones', $arr_row);

            $data['status'] = 1;
        }

        return $data;
    }

// IMPORTAR
//-----------------------------------------------------------------------------}

    /**
     * Array con configuración de la vista de importación según el tipo de usuario
     * que se va a importar.
     * 2019-11-20
     */
    function import_config($type)
    {
        $data = array();

        if ( $type == 'general' )
        {
            $data['help_note'] = 'Se importarán acciones a la base de datos.';
            $data['help_tips'] = array();
            $data['template_file_name'] = 'f50_acciones.xlsx';
            $data['sheet_name'] = 'mecc_acciones';
            $data['head_subtitle'] = 'Importar';
            $data['destination_form'] = "acciones/import_e/{$type}";
        }

        return $data;
    }

    /**
     * Importa acciones a la base de datos
     * 2020-02-22
     */
    function import($arr_sheet)
    {
        $data = array('qty_imported' => 0, 'results' => array());
        
        foreach ( $arr_sheet as $key => $row_data )
        {
            $data_import = $this->import_row($row_data);
            $data['qty_imported'] += $data_import['status'];
            $data['results'][$key + 2] = $data_import;
        }
        
        return $data;
    }

    /**
     * Realiza la importación de una fila del archivo excel. Valida los campos, crea registro
     * en la tabla post, y agrega al grupo asignado.
     * 2020-02-22
     */
    function import_row($row_data)
    {
        //Validar
            $error_text = '';
                            
            if ( strlen($row_data[0]) == 0 ) { $error_text = 'La casilla Nombre está vacía. '; }
            if ( strlen($row_data[1]) == 0 ) { $error_text .= 'La casilla Cod Tipo está vacía. '; }
            if ( strlen($row_data[2]) == 0 ) { $error_text .= 'La casilla Resumen está vacía. '; }
            if ( strlen($row_data[14]) == 0 ) { $error_text .= 'La casilla Fecha Publicación está vacía. '; }

        //Si no hay error
            if ( $error_text == '' )
            {
                $arr_row['post_name'] = $row_data[0];
                $arr_row['type_id'] = $row_data[1];
                $arr_row['excerpt'] = $row_data[2];
                $arr_row['content'] = $row_data[3];
                $arr_row['content_json'] = $row_data[4];
                $arr_row['keywords'] = $row_data[5];
                $arr_row['code'] = $row_data[6];
                $arr_row['place_id'] = $this->pml->if_strlen($row_data[7], 0);
                $arr_row['related_1'] = $this->pml->if_strlen($row_data[8], 0);
                $arr_row['related_2'] = $this->pml->if_strlen($row_data[9], 0);
                $arr_row['image_id'] = $this->pml->if_strlen($row_data[10], 0);
                $arr_row['text_1'] = $this->pml->if_strlen($row_data[11], '');
                $arr_row['text_2'] = $this->pml->if_strlen($row_data[12], '');
                $arr_row['status'] = $this->pml->if_strlen($row_data[13], 2);
                $arr_row['published_at'] = $this->pml->dexcel_dmysql($row_data[14]);
                $arr_row['slug'] = $this->Db_model->unique_slug($row_data[0], 'mecc_acciones');
                
                $arr_row['creator_id'] = $this->session->userdata('user_id');
                $arr_row['updater_id'] = $this->session->userdata('user_id');

                //Guardar en tabla user
                $data_insert = $this->insert($arr_row);

                $data = array('status' => 1, 'text' => '', 'imported_id' => $data_insert['saved_id']);
            } else {
                $data = array('status' => 0, 'text' => $error_text, 'imported_id' => 0);
            }

        return $data;
    }

// DETALLES DE LAS ACCIONES
//-----------------------------------------------------------------------------

    function get_details($filters)
    {
        $condition = 'id > 0 AND ';
        if ( $filters['prnt'] != '' ) { $condition .= "accion_id = {$filters['prnt']} AND "; }
        if ( $filters['type'] != '' ) { $condition .= "tipo_detalle = {$filters['type']} AND "; }
        //Quitar cadena final de ' AND '
        if ( strlen($condition) > 0 ) { $condition = substr($condition, 0, -5);}

        $this->db->select('*');
        $this->db->where($condition);
        $this->db->limit(10000);
        $details = $this->db->get('mecc_acciones_detalle');
    
        return $details;
    }

    /**
     * Eliminar registros de la tabma mecc_acciones_detalle que cumplan con la $condition SQL
     * 2023-05-02
     */
    function delete_details($condition)
    {
        $this->db->where($condition);
        $this->db->delete('mecc_acciones_detalle');
        
        $qty_deleted = $this->db->affected_rows();

        return $qty_deleted;
    }

// FUNCIONES PARA EXPORTAR DATOS
//-----------------------------------------------------------------------------

    function details_asistentes()
    {
        $select = 'mecc_acciones_detalle.id, accion_id, nombre_accion, mecc_acciones.fecha,
            mecc_acciones_detalle.cod_detalle AS num_documento, nombre';

        $this->db->select($select);
        $this->db->where('tipo_detalle', 110);
        $this->db->order_by('accion_id', 'ASC');
        $this->db->join('mecc_acciones', 'mecc_acciones.id = mecc_acciones_detalle.accion_id', 'left');
        
        $query = $this->db->get('mecc_acciones_detalle');

        return $query;
    }

    function details_asistentes_itinerantes()
    {
        $select = 'mecc_acciones_detalle.id, accion_id, nombre_accion, mecc_acciones.fecha,
            mecc_acciones_detalle.cod_detalle AS num_documento, nombre, items_1.item_name AS identidad_genero,
            items_2.item_name AS grupo_poblacion, cantidad AS edad, mecc_acciones_detalle.descripcion AS telefono';

        $this->db->select($select);
        $this->db->where('tipo_detalle', 140);
        $this->db->order_by('accion_id', 'ASC');
        $this->db->join('mecc_acciones', 'mecc_acciones.id = mecc_acciones_detalle.accion_id', 'left');
        $this->db->join('items AS items_1', 'items_1.cod = mecc_acciones_detalle.relacionado_2 AND items_1.category_id = 111', 'left');
        $this->db->join('items AS items_2', 'items_2.cod = mecc_acciones_detalle.relacionado_1 AND items_2.category_id = 251', 'left');
        
        $query = $this->db->get('mecc_acciones_detalle');

        return $query;
    }
}