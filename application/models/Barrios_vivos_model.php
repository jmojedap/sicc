<?php
class Barrios_vivos_model extends CI_Model{

    function basic($laboratorioId)
    {
        $row = $this->Db_model->row_id('bv_laboratorios', $laboratorioId);

        $data['row'] = $row;
        $data['head_title'] = "BV {$laboratorioId}) {$data['row']->nombre_corto}";
        $data['view_a'] = $this->views_folder . 'laboratorio_v';
        $data['nav_2'] = $this->views_folder . 'menu_v';

        return $data;
    }

// EXPLORE FUNCTIONS - laboratorios/explore
//-----------------------------------------------------------------------------
    
    /**
     * Array con los datos para la vista de exploración
     */
    function explore_data($filters, $num_page, $perPage = 10)
    {
        //Data inicial, de la tabla
            $data = $this->get($filters, $num_page, $perPage);
        
        //Elemento de exploración
            $data['controller'] = 'bv_laboratorios';                       //Nombre del controlador
            $data['cf'] = 'laboratorios/explore/';                      //Nombre del controlador
            $data['views_folder'] = 'admin/laboratorios/explore/';      //Carpeta donde están las vistas de exploración
            $data['numPage'] = $num_page;                       //Número de la página
            $data['perPage'] = $perPage;                       //Número de la página
            
        //Vistas
            $data['head_title'] = 'Posts';
            $data['view_a'] = $data['views_folder'] . 'explore_v';
            $data['nav_2'] = $data['views_folder'] . 'menu_v';
        
        return $data;
    }

    function get($filters, $num_page, $perPage = 10)
    {
        //Load
            $this->load->model('Search_model');

        //Búsqueda y Resultados
            $data['filters'] = $filters;
            $offset = ($num_page - 1) * $perPage;      //Número de la página de datos que se está consultado
            $elements = $this->search($filters, $perPage, $offset);    //Resultados para página
        
        //Cargar datos
            $data['list'] = $elements->result();
            $data['strFilters'] = $this->Search_model->str_filters($filters, TRUE);
            $data['qtyResults'] = $this->qty_results($filters);
            $data['maxPage'] = ceil($this->pml->if_zero($data['qtyResults'],1) / $perPage);   //Cantidad de páginas

        return $data;
    }

    /**
     * Segmento Select SQL, con diferentes formatos, consulta de laboratorios
     * 2022-08-23
     */
    function select($format = 'general')
    {
        $arr_select['general'] = 'bv_laboratorios.*, users.username AS updater_username, users.display_name AS updater_display_name';
        $arr_select['export'] = '*';

        return $arr_select[$format];
    }
    
    /**
     * Query con resultados de laboratorios filtrados, por página y offset
     * 2025-04-20
     */
    function search($filters, $perPage = NULL, $offset = NULL)
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
                $this->db->order_by('vigencia', 'DESC');
                $this->db->order_by('id', 'ASC');
            }

        $this->db->join('users', 'bv_laboratorios.updater_id = users.id', 'left'); //Unir con tabla de usuarios
            
        //Filtros
            $search_condition = $this->search_condition($filters);
            if ( $search_condition ) { $this->db->where($search_condition);}
            
        //Obtener resultados
            $query = $this->db->get('bv_laboratorios', $perPage, $offset); //Resultados por página
        
        return $query;
        
    }

    /**
     * String con condición WHERE SQL para filtrar laboratorios
     * 2025-04-17
     */
    function search_condition($filters)
    {
        $condition = NULL;

        $condition .= $this->role_filter() . ' AND ';

        //q words condition
        $arrWords = ['bv_laboratorios.id', 'nombre_laboratorio', 'descripcion', 
            'direccion_lider', 'duplas','gerente', 'bv_laboratorios.tags', 'palabras_clave'];
        $words_condition = $this->Search_model->words_condition($filters['q'], $arrWords);
        if ( $words_condition )
        {
            $condition .= $words_condition . ' AND ';
        }
        
        //Otros filtros
        if ( $filters['type'] != '' ) { $condition .= "tipo_laboratorio = '{$filters['type']}' AND "; }
        if ( $filters['cat'] != '' ) { $condition .= "categoria_laboratorio = '{$filters['cat']}' AND "; }
        if ( $filters['cat_1'] != '' ) { $condition .= "direccion_lider_sigla = '{$filters['cat_1']}' AND "; }
        if ( $filters['y'] != '' ) { $condition .= "vigencia = '{$filters['y']}' AND "; }
        
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
        $query = $this->db->get('bv_laboratorios'); //Para calcular el total de resultados

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
        $query = $this->db->get('bv_laboratorios', 10000);  //Hasta 10.000 registros

        return $query;
    }

    /**
     * Query para exportar
     * 2022-08-17
     */
    function query_export_actividades($filters)
    {
        //Select
        $select = $this->select_details('actividades');
        if ( $filters['sf'] != '' ) { $select = $this->select($filters['sf']); }
        $this->db->select($select);
        $this->db->join('users', 'bv_laboratorios_detalles.updater_id = users.id', 'left'); //Unir con tabla de usuarios
        $this->db->join('bv_laboratorios', 'bv_laboratorios_detalles.laboratorio_id = bv_laboratorios.id', 'left'); //Unir con tabla de laboratorios

        //Condición Where
        //$search_condition = $this->search_condition($filters);
        //if ( $search_condition ) { $this->db->where($search_condition);}

        //Get
        $query = $this->db->get('bv_laboratorios_detalles', 10000);  //Hasta 10.000 registros

        return $query;
    }
    
    /**
     * Devuelve segmento SQL
     */
    function role_filter()
    {
        $role = $this->session->userdata('role');
        $condition = 'bv_laboratorios.id > 0';  //Valor por defecto, ningún post, se obtendrían cero laboratorios.
        
        if ( $role <= 2 ) 
        {   //Desarrollador, todos los post
            $condition = 'bv_laboratorios.id > 0';
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
            'id' => 'ID laboratorio',
            'nombre_accion' => 'Nombre'
        );
        
        return $order_options;
    }

// CRUD
//-----------------------------------------------------------------------------

    /**
     * Objeto registro de un laboratorio ID, con un formato específico
     * 2025-04-17
     */
    function row($laboratorioId, $format = 'general')
    {
        $row = NULL;    //Valor por defecto

        $this->db->select($this->select($format));
        $this->db->where('id', $laboratorioId);
        $query = $this->db->get('bv_laboratorios', 1);

        if ( $query->num_rows() > 0 ) $row = $query->row();

        return $row;
    }

    /**
     * Guardar un registro en la tabla laboratorios
     * 2025-04-17
     */
    function save($aRow = null)
    {
        //Verificar si hay array con registro
        if ( is_null($aRow) ) $aRow = $this->Db_model->arr_row();

        //Verificar si tiene id definido, insertar o actualizar
        if ( ! isset($aRow['id']) ) 
        {
            //No existe, insertar
            $this->db->insert('bv_laboratorios', $aRow);
            $laboratorioId = $this->db->insert_id();
        } else {
            //Ya existe, editar
            $laboratorioId = $aRow['id'];
            unset($aRow['id']);

            $this->db->where('id', $laboratorioId)->update('bv_laboratorios', $aRow);
        }

        $this->update_dependent_fields($laboratorioId, $aRow); //Actualizar campos dependientes
        $data['saved_id'] = $laboratorioId;
        return $data;
    }

    /**
     * Guardar un registro en la tabla laboratorios, con un formato específico
     * 2025-04-17
     */
    function update_dependent_fields($laboratorioId, $aRow)
    {
        // Actualizar datos del barrio
        if ( isset($aRow['barrio_id']) && $aRow['barrio_id'] > 0 ) {
            $rowBarrio = $this->Db_model->row_id('gf_territorios', $aRow['barrio_id']);
            $aRow['barrio_ancla'] = $rowBarrio->nombre . ' / ' . $rowBarrio->upz . ' / ' . $rowBarrio->localidad;
            $aRow['localidad'] = $rowBarrio->localidad;
            $aRow['localidad_cod'] = $rowBarrio->cod_localidad;
            $aRow['latitud'] = $rowBarrio->latitud;
            $aRow['longitud'] = $rowBarrio->longitud;
        }

        // Actualizar dependencia líder del laboratorio
        if ( isset($aRow['direccion_lider_sigla']) ) {
            $dependencia = $this->Db_model->row('items', "abbreviation = '{$aRow['direccion_lider_sigla']}' AND category_id = 215");
            if ( ! is_null($dependencia) ) {
                $aRow['direccion_lider'] = $dependencia->item_name;
            } else {
                $aRow['direccion_lider'] = '';
            }
        }

        $this->db->where('id', $laboratorioId)->update('bv_laboratorios', $aRow);
    }

// ELIMINACIÓN DE UNA ACCIÓN
//-----------------------------------------------------------------------------
    
    /**
     * Verifica si el usuario en sesión tiene permiso para eliminar un registro
     * tabla bv_laboratorios
     * 2023-05-13
     */
    function deleteable($row_id)
    {
        $row = $this->Db_model->row_id('bv_laboratorios', $row_id);

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
     * 2025-04-17
     */
    function delete($laboratorioId)
    {
        $qty_deleted = 0;

        if ( $this->deleteable($laboratorioId) ) 
        {
            //Tablas relacionadas
                //$this->db->where('laboratorio_id', $laboratorioId)->delete('bv_laboratorios_detalle');
                //$this->db->where('post_id', $laboratorioId)->delete('post_meta');
            
            //Tabla principal
                $this->db->where('id', $laboratorioId)->delete('bv_laboratorios');

            $qty_deleted = $this->db->affected_rows();  //De la última consulta, tabla principal

            //Eliminar archivos relacionados
            if ( $qty_deleted > 0 ) $this->delete_files($laboratorioId);
        }

        return $qty_deleted;
    }

    /**
     * Eliminar los archivos relacionados con el post eliminado
     * 2021-02-20
     */
    function delete_files($laboratorioId)
    {
        //Identificar archivos
        $this->db->select('id');
        $this->db->where("table_id = 121 AND related_1 = {$laboratorioId}");
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
    function images($laboratorioId)
    {
        $this->db->select('files.id, files.title, url, url_thumbnail, files.integer_1 AS main, position');
        $this->db->where('is_image', 1);
        $this->db->where('table_id', '121');      //Tabla bv_laboratorios
        $this->db->where('related_1', $laboratorioId);   //Relacionado con el post
        $this->db->order_by('position', 'ASC');
        $images = $this->db->get('files');

        return $images;
    }

    /**
     * Establecer una imagen asociada a una acción como la imagen principal (tabla file)
     * 2020-09-05
     */
    function set_main_image($laboratorioId, $file_id)
    {
        $data = array('status' => 0);

        $row_file = $this->Db_model->row_id('files', $file_id);
        if ( ! is_null($row_file) )
        {
            //Quitar otro principal
            $this->db->query("UPDATE files SET integer_1 = 0 WHERE table_id = 121 AND related_1 = {$laboratorioId} AND integer_1 = 1");

            //Poner nuevo principal
            $this->db->query("UPDATE files SET integer_1 = 1 WHERE id = {$file_id} AND related_1 = {$laboratorioId}");

            //Actualizar registro en tabla post
            $arr_row['image_id'] = $row_file->id;
            $arr_row['url_image'] = $row_file->url;
            $arr_row['url_thumbnail'] = $row_file->url_thumbnail;

            $this->db->where('id', $laboratorioId);
            $this->db->update('bv_laboratorios', $arr_row);

            $data['status'] = 1;
        }

        return $data;
    }



// DETALLES DE LOS LABORATORIOS
//-----------------------------------------------------------------------------

    /**
     * Segmento Select SQL, con diferentes formatos, consulta de detalles de laboratorios
     * 2025-06-10
     */
    function select_details($format = 'general')
    {
        $arr_select['general'] = 'bv_laboratorios_detalles.*, bv_laboratorios.nombre_laboratorio AS laboratorio_nombre, 
            bv_laboratorios.nombre_corto AS lab_nombre_corto, bv_laboratorios.estado_registro AS lab_estado_registro,
            bv_laboratorios.vigencia AS lab_vigencia,
            users.username AS updater_username, users.display_name AS updater_display_name';
        $arr_select['actividades'] = 'bv_laboratorios_detalles.nombre, bv_laboratorios_detalles.descripcion,
            bv_laboratorios_detalles.titulo_2 AS fase_laboratorio, bv_laboratorios_detalles.titulo_3 AS numero_actividad,
            bv_laboratorios_detalles.texto_2 AS lugar, bv_laboratorios_detalles.texto_3 AS direccion,
            bv_laboratorios_detalles.fecha_1 AS fecha, bv_laboratorios_detalles.hora_1 AS hora_inicio,
            bv_laboratorios_detalles.hora_2 AS hora_fin, bv_laboratorios_detalles.categoria_1 AS fase_barrios_vivos,
            bv_laboratorios_detalles.entero_1 AS cantidad_hombres, bv_laboratorios_detalles.entero_2 AS cantidad_mujeres,
            bv_laboratorios_detalles.entero_3 AS cantidad_sexo_nd,
            (entero_1 + entero_2 + entero_3) AS total_participantes,
            bv_laboratorios_detalles.url_1, bv_laboratorios_detalles.url_2, bv_laboratorios_detalles.url_3, bv_laboratorios_detalles.url_4,
            bv_laboratorios_detalles.num_radicacion, bv_laboratorios_detalles.notas,
            bv_laboratorios_detalles.updated_at, bv_laboratorios_detalles.created_at,
            bv_laboratorios.id AS laboratorio_id, 
            bv_laboratorios.nombre_laboratorio AS laboratorio_nombre, 
            bv_laboratorios.nombre_corto AS lab_nombre_corto, bv_laboratorios.estado_registro AS lab_estado_registro,
            bv_laboratorios.vigencia AS lab_vigencia,
            users.username AS updater_username, users.display_name AS updater_display_name';
        $arr_select['export'] = '*';

        return $arr_select[$format];
    }

    function get_details($filters)
    {
        $condition = 'bv_laboratorios_detalles.id > 0 AND ';
        if ( $filters['prnt'] != '' ) { $condition .= "laboratorio_id = {$filters['prnt']} AND "; }
        if ( $filters['type'] != '' ) { $condition .= "tipo_detalle = {$filters['type']} AND "; }
        if ( $filters['y'] != '' ) { $condition .= "vigencia = {$filters['y']} AND "; }
        
        //Quitar cadena final de ' AND '
        if ( strlen($condition) > 0 ) { $condition = substr($condition, 0, -5);}

        //Segmento SELECT
        $select_format = 'general';
        if ( $filters['sf'] != '' ) { $select_format = $filters['sf']; }

        $this->db->select($this->select_details($select_format));
        $this->db->where($condition);
        $this->db->limit(10000);
        $this->db->join('users', 'bv_laboratorios_detalles.updater_id = users.id', 'left'); //Unir con tabla de usuarios
        $this->db->join('bv_laboratorios', 'bv_laboratorios_detalles.laboratorio_id = bv_laboratorios.id', 'left'); //Unir con tabla de laboratorios
        if ( $filters['o'] != '' ) { $this->db->order_by($filters['o'], $filters['ot']); }
        $details = $this->db->get('bv_laboratorios_detalles');
    
        return $details;
    }

    /**
     * Eliminar registros de la tabla bv_laboratorios_detalles que cumplan con la $condition SQL
     * 2025-04-19
     */
    function delete_details($condition)
    {
        $this->db->where($condition);
        $this->db->delete('bv_laboratorios_detalles');
        
        $qty_deleted = $this->db->affected_rows();

        return $qty_deleted;
    }

    function events()
    {
        $this->db->select($this->select_details('actividades'));
        //$this->db->where($condition);
        $this->db->limit(10000);
        $this->db->join('users', 'bv_laboratorios_detalles.updater_id = users.id', 'left'); //Unir con tabla de usuarios
        $this->db->join('bv_laboratorios', 'bv_laboratorios_detalles.laboratorio_id = bv_laboratorios.id', 'left'); //Unir con tabla de laboratorios
        $details = $this->db->get('bv_laboratorios_detalles');

        //echo $this->db->last_query();

        return $details;
    }
}