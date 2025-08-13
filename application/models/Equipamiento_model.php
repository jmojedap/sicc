<?php
class Equipamiento_model extends CI_Model{

    function basic($equipamientoId)
    {
        $row = $this->Db_model->row_id('sie_equipamientos', $equipamientoId);

        $data['row'] = $row;
        $data['head_title'] = "{$equipamientoId}) {$data['row']->nombre}";
        $data['view_a'] = $this->views_folder . 'equipamiento_v';
        $data['nav_2'] = $this->views_folder . 'menu_v';

        return $data;
    }

// EXPLORE FUNCTIONS - equipamientos/explore
//-----------------------------------------------------------------------------
    
    /**
     * Array con los datos para la vista de exploración
     */
    function explore_data($filters, $num_page, $perPage = 10)
    {
        //Data inicial, de la tabla
            $data = $this->get($filters, $num_page, $perPage);
        
        //Elemento de exploración
            $data['controller'] = 'sie_equipamientos';                       //Nombre del controlador
            $data['cf'] = 'equipamientos/explore/';                      //Nombre del controlador
            $data['views_folder'] = 'admin/equipamientos/explore/';      //Carpeta donde están las vistas de exploración
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
     * Segmento Select SQL, con diferentes formatos, consulta de equipamientos
     * 2022-08-23
     */
    function select($format = 'general')
    {
        $arr_select['general'] = 'sie_equipamientos.*, users.username AS updater_username, users.display_name AS updater_display_name';
        $arr_select['export'] = '*';

        return $arr_select[$format];
    }
    
    /**
     * Query con resultados de equipamientos filtrados, por página y offset
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
                $this->db->order_by('id', 'ASC');
            }

        $this->db->join('users', 'sie_equipamientos.updater_id = users.id', 'left'); //Unir con tabla de usuarios
            
        //Filtros
            $search_condition = $this->search_condition($filters);
            if ( $search_condition ) { $this->db->where($search_condition);}
            
        //Obtener resultados
            $query = $this->db->get('sie_equipamientos', $perPage, $offset); //Resultados por página
        
        return $query;
        
    }

    /**
     * String con condición WHERE SQL para filtrar equipamientos
     * 2025-04-17
     */
    function search_condition($filters)
    {
        $condition = NULL;

        $condition .= $this->role_filter() . ' AND ';

        //q words condition
        $arrWords = ['sie_equipamientos.id', 'nombre', 'descripcion', 'sie_equipamientos.tags', 'palabras_clave', 'localidad',
                'upz', 'sector', 'barrio', 'tipo_equipamiento', 'categoria_equipamiento'
            ];
        $words_condition = $this->Search_model->words_condition($filters['q'], $arrWords);
        if ( $words_condition )
        {
            $condition .= $words_condition . ' AND ';
        }
        
        //Otros filtros
        if ( $filters['type'] != '' ) { $condition .= "tipo_equipamiento_cod = '{$filters['type']}' AND "; }
        if ( $filters['cat'] != '' ) { $condition .= "categoria_equipamiento = '{$filters['cat']}' AND "; }
        
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
        $query = $this->db->get('sie_equipamientos'); //Para calcular el total de resultados

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
        $query = $this->db->get('sie_equipamientos', 10000);  //Hasta 10.000 registros

        return $query;
    }
    
    /**
     * Devuelve segmento SQL
     */
    function role_filter()
    {
        $role = $this->session->userdata('role');
        $condition = 'sie_equipamientos.id > 0';  //Valor por defecto, ningún post, se obtendrían cero equipamientos.
        
        if ( $role <= 2 ) 
        {   //Desarrollador, todos los post
            $condition = 'sie_equipamientos.id > 0';
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
            'id' => 'ID equipamiento',
            'nombre_accion' => 'Nombre'
        );
        
        return $order_options;
    }

// CRUD
//-----------------------------------------------------------------------------

    /**
     * Objeto registro de un equipamiento ID, con un formato específico
     * 2025-04-17
     */
    function row($equipamientoId, $format = 'general')
    {
        $row = NULL;    //Valor por defecto

        $this->db->select($this->select($format));
        $this->db->where('id', $equipamientoId);
        $query = $this->db->get('sie_equipamientos', 1);

        if ( $query->num_rows() > 0 ) $row = $query->row();

        return $row;
    }

    /**
     * Guardar un registro en la tabla equipamientos
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
            $this->db->insert('sie_equipamientos', $aRow);
            $equipamientoId = $this->db->insert_id();
        } else {
            //Ya existe, editar
            $equipamientoId = $aRow['id'];
            unset($aRow['id']);

            $this->db->where('id', $equipamientoId)->update('sie_equipamientos', $aRow);
        }

        $this->update_dependent_fields($equipamientoId, $aRow); //Actualizar campos dependientes
        $data['saved_id'] = $equipamientoId;
        return $data;
    }

    /**
     * Guardar un registro en la tabla equipamientos, con un formato específico
     * 2025-04-17
     */
    function update_dependent_fields($equipamientoId, $aRow)
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

        // Actualizar dependencia líder del equipamiento
        if ( isset($aRow['direccion_lider_sigla']) ) {
            $dependencia = $this->Db_model->row('items', "abbreviation = '{$aRow['direccion_lider_sigla']}' AND category_id = 215");
            if ( ! is_null($dependencia) ) {
                $aRow['direccion_lider'] = $dependencia->item_name;
            } else {
                $aRow['direccion_lider'] = '';
            }
        }

        $this->db->where('id', $equipamientoId)->update('sie_equipamientos', $aRow);
    }

// ELIMINACIÓN DE UN EQUIPAMIENTO
//-----------------------------------------------------------------------------
    
    /**
     * Verifica si el usuario en sesión tiene permiso para eliminar un registro
     * tabla sie_equipamientos
     * 2023-05-13
     */
    function deleteable($row_id)
    {
        $row = $this->Db_model->row_id('sie_equipamientos', $row_id);

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
    function delete($equipamientoId)
    {
        $qty_deleted = 0;

        if ( $this->deleteable($equipamientoId) ) 
        {
            //Tablas relacionadas
                //$this->db->where('equipamiento_id', $equipamientoId)->delete('sie_equipamientos_detalle');
                //$this->db->where('post_id', $equipamientoId)->delete('post_meta');
            
            //Tabla principal
                $this->db->where('id', $equipamientoId)->delete('sie_equipamientos');

            $qty_deleted = $this->db->affected_rows();  //De la última consulta, tabla principal

            //Eliminar archivos relacionados
            if ( $qty_deleted > 0 ) $this->delete_files($equipamientoId);
        }

        return $qty_deleted;
    }

    /**
     * Eliminar los archivos relacionados con el equipamiento eliminado
     * 2021-02-20
     */
    function delete_files($equipamientoId)
    {
        //Identificar archivos
        $this->db->select('id');
        $this->db->where("table_id = 121 AND related_1 = {$equipamientoId}");
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
    function images($equipamientoId)
    {
        $this->db->select('files.id, files.title, url, url_thumbnail, files.integer_1 AS main, position');
        $this->db->where('is_image', 1);
        $this->db->where('table_id', '121');      //Tabla sie_equipamientos
        $this->db->where('related_1', $equipamientoId);   //Relacionado con el post
        $this->db->order_by('position', 'ASC');
        $images = $this->db->get('files');

        return $images;
    }

    /**
     * Establecer una imagen asociada a una acción como la imagen principal (tabla file)
     * 2020-09-05
     */
    function set_main_image($equipamientoId, $file_id)
    {
        $data = array('status' => 0);

        $row_file = $this->Db_model->row_id('files', $file_id);
        if ( ! is_null($row_file) )
        {
            //Quitar otro principal
            $this->db->query("UPDATE files SET integer_1 = 0 WHERE table_id = 121 AND related_1 = {$equipamientoId} AND integer_1 = 1");

            //Poner nuevo principal
            $this->db->query("UPDATE files SET integer_1 = 1 WHERE id = {$file_id} AND related_1 = {$equipamientoId}");

            //Actualizar registro en tabla post
            $arr_row['image_id'] = $row_file->id;
            $arr_row['url_image'] = $row_file->url;
            $arr_row['url_thumbnail'] = $row_file->url_thumbnail;

            $this->db->where('id', $equipamientoId);
            $this->db->update('sie_equipamientos', $arr_row);

            $data['status'] = 1;
        }

        return $data;
    }



// DETALLES DE LOS EQUIPAMIENTOS
//-----------------------------------------------------------------------------

    /**
     * Segmento Select SQL, con diferentes formatos, consulta de detalles de equipamientos
     * 2025-06-10
     */
    function select_details($format = 'general')
    {
        $arr_select['general'] = '*';
        $arr_select['export'] = '*';

        return $arr_select[$format];
    }

    function get_details($filters)
    {
        $condition = 'sie_equipamientos_detalles.id > 0 AND ';
        if ( $filters['prnt'] != '' ) { $condition .= "equipamiento_id = {$filters['prnt']} AND "; }
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
        $this->db->join('users', 'sie_equipamientos_detalles.updater_id = users.id', 'left'); //Unir con tabla de usuarios
        $this->db->join('sie_equipamientos', 'sie_equipamientos_detalles.equipamiento_id = sie_equipamientos.id', 'left'); //Unir con tabla de equipamientos
        if ( $filters['o'] != '' ) { $this->db->order_by($filters['o'], $filters['ot']); }
        $details = $this->db->get('sie_equipamientos_detalles');
    
        return $details;
    }

    /**
     * Eliminar registros de la tabla sie_equipamientos_detalles que cumplan con la $condition SQL
     * 2025-04-19
     */
    function delete_details($condition)
    {
        $this->db->where($condition);
        $this->db->delete('sie_equipamientos_detalles');
        
        $qty_deleted = $this->db->affected_rows();

        return $qty_deleted;
    }
}