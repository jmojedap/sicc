<?php
class Cuidado_model extends CI_Model{

    function basic($post_id)
    {
        $row = $this->Db_model->row_id('ehc_actividades', $post_id);

        $data['row'] = $row;
        $data['head_title'] = 'Actividad ' . $data['row']->id;
        $data['view_a'] = $this->views_folder . 'post_v';
        $data['nav_2'] = $this->views_folder . 'menu_v';

        return $data;
    }

// EXPLORE FUNCTIONS - posts/explore
//-----------------------------------------------------------------------------
    
    /**
     * Array con los datos para la vista de exploración
     */
    function explore_data($filters, $num_page, $per_page = 10)
    {
        //Data inicial, de la tabla
            $data = $this->get($filters, $num_page, $per_page);
        
        //Elemento de exploración
            $data['controller'] = 'cuidado';                       //Nombre del controlador
            $data['cf'] = 'cuidado/explore/';                      //Nombre del controlador
            $data['views_folder'] = 'admin/cuidado/actividades/explore/';      //Carpeta donde están las vistas de exploración
            $data['numPage'] = $num_page;                       //Número de la página
            
        //Vistas
            $data['head_title'] = 'Actividades EHC';
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
     * Segmento Select SQL, con diferentes formatos, consulta de posts
     * 2022-08-23
     */
    function select($format = 'general')
    {
        $arr_select['general'] = '*';
        $arr_select['export'] = '*';

        return $arr_select[$format];
    }
    
    /**
     * Query con resultados de posts filtrados, por página y offset
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
                //$this->db->order_by('id', 'DESC');
                $this->db->order_by('inicio', 'DESC');
            }
            
        //Filtros
            $search_condition = $this->search_condition($filters);
            if ( $search_condition ) { $this->db->where($search_condition);}
            
        //Obtener resultados
            $query = $this->db->get('ehc_actividades', $per_page, $offset); //Resultados por página
        
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
        $qFields = ['nombre_actividad','descripcion','tipo_actividad','nombre_lugar',
            'direccion','facilitadores','contacto_espacio'
        ];
        $words_condition = $this->Search_model->words_condition($filters['q'], $qFields);
        if ( $words_condition )
        {
            $condition .= $words_condition . ' AND ';
        }
        
        //Otros filtros
        if ( $filters['localidad'] != '' ) { $condition .= "localidad_cod = {$filters['localidad']} AND "; }

        //Mes
        if ( $filters['m'] != '' ) {
            $month = $this->Db_model->row_id('periods', intval($filters['m']));
            $condition .= "inicio >= '{$month->start} 00:00:00' AND inicio <= '{$month->end} 23:59:59' AND ";
        }
        if ( $filters['condition'] != '' ) { $condition .= "{$filters['condition']} AND "; }
        
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
        $query = $this->db->get('ehc_actividades'); //Para calcular el total de resultados

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
        $query = $this->db->get('ehc_actividades', 10000);  //Hasta 10.000 registros

        return $query;
    }
    
    /**
     * Devuelve segmento SQL
     */
    function role_filter()
    {
        $role = $this->session->userdata('role');
        $condition = 'id > 0';  //Todas laas actividades
        
        if ( $role <= 2 ) 
        {   //Desarrollador, todos las actividades
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
            'id' => 'ID Actividad',
            'inicio' => 'Fecha'
        );
        
        return $order_options;
    }

// CRUD
//-----------------------------------------------------------------------------

    /**
     * Guardar un registro en la tabla ehc_actividades
     * 2022-11-13
     */
    function save($arr_row = null)
    {
        //Verificar si hay array con registro
        if ( is_null($arr_row) ) $arr_row = $this->Db_model->arr_row();

        //Verificar si tiene id definido, insertar o actualizar
        if ( ! isset($arr_row['id']) ) 
        {
            //No existe, insertar
            $this->db->insert('ehc_actividades', $arr_row);
            $row_id = $this->db->insert_id();
        } else {
            //Ya existe, editar
            $row_id = $arr_row['id'];
            unset($arr_row['id']);

            $this->db->where('id', $row_id)->update('ehc_actividades', $arr_row);
        }

        $data['saved_id'] = $row_id;
        return $data;
    }

// ELIMINACIÓN DE UNA ACTIVIDAD
//-----------------------------------------------------------------------------
    
    /**
     * Verifica si el usuario en sesión tiene permiso para eliminar un registro
     * tabla ehc_actividades
     * 2020-08-18
     */
    function deleteable($row_id)
    {
        $row = $this->Db_model->row_id('ehc_actividades', $row_id);

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
     * Eliminar una actividad de la base de datos, se eliminan registros de tablas
     * relacionadas
     * 2022-08-20
     */
    function delete($post_id)
    {
        $qty_deleted = 0;

        if ( $this->deleteable($post_id) ) 
        {
            //Tablas relacionadas
                //$this->db->where('parent_id', $post_id)->delete('posts');
            
            //Tabla principal
                $this->db->where('id', $post_id)->delete('ehc_actividades');

            $qty_deleted = $this->db->affected_rows();  //De la última consulta, tabla principal
        }

        return $qty_deleted;
    }

// EXPORTAR DATOS
//-----------------------------------------------------------------------------

    /**
     * Query de la tabla details para exportar
     * 2022-11-10
     */
    function query_export_asistencia($condition)
    {
        //Select
        $select = 'ehc_actividades.id AS actividad_id, ehc_actividades.inicio,
            ehc_actividades.nombre_actividad, ehc_actividades.tipo_actividad,
            users.id AS user_id, users.display_name AS nombre_estudiante, 
            document_number AS numero_documento';
        $this->db->select($select);
        $this->db->where('details.table_id = 151 AND details.type_id = 15110');
        $this->db->join('ehc_actividades', 'details.row_id = ehc_actividades.id');
        $this->db->join('users', 'details.related_1 = users.id');
        $this->db->order_by('ehc_actividades.inicio','DESC');
        
        if ( strlen($condition) > 0 ) {
             $this->db->where($condition);
        }
        
        //Hasta 10.000 registros
        $query = $this->db->get('details', 10000);

        return $query;
    }

    /**
     * Query de la tabla details para exportar
     * 151 el el código de la tabla ehc_actividades
     * 515 es la categoría de los items módulos
     * 15112 es el código para el tipo de detalle: módulos/sesiones
     * 2022-11-14
     */
    function query_export_actividades_sesiones($condition)
    {
        //Select
        $select = 'ehc_actividades.id AS actividad_id, ehc_actividades.inicio,
            ehc_actividades.nombre_actividad, ehc_actividades.tipo_actividad,
            items.cod AS num_modulo, items.item_name AS modulo, 
            integer_1 AS numero_sesion';
        $this->db->select($select);
        $this->db->where('details.table_id = 151 AND details.type_id = 15112');
        $this->db->join('ehc_actividades', 'details.row_id = ehc_actividades.id');
        $this->db->join('items', 'details.related_1 = items.cod AND items.category_id = 515');
        $this->db->order_by('ehc_actividades.inicio','DESC');
        
        if ( strlen($condition) > 0 ) {
             $this->db->where($condition);
        }
        
        //Hasta 10.000 registros
        $query = $this->db->get('details', 10000);

        return $query;
    }

    /**
     * Query de la tabla users_meta para exportar
     * 2022-11-10
     */
    function query_export_manzanas($condition)
    {
        //Select
        $select = 'posts.id AS manzana_id, post_name AS nombre, related_1 AS cod_localidad,
            items.item_name AS localidad,
            text_1 AS direccion, excerpt AS notas';
            
        $this->db->select($select);
        $this->db->join('items', 'posts.related_1 = items.cod AND items.category_id = 121');
        $this->db->where('posts.type_id', 311);
        
        if ( strlen($condition) > 0 ) {
             $this->db->where($condition);
        }
        
        //Hasta 10.000 registros
        $query = $this->db->get('posts', 10000);

        return $query;
    }

    /**
     * Query de la tabla users_meta para exportar
     * 2022-11-10
     */
    function query_export_sesiones($condition)
    {
        //Select
        $select = 'posts.id AS sesion_id, posts.post_name AS nombre_sesion, posts.related_2 AS manzana_id,
            manzanas.post_name AS nombre_manzana,
            posts.date_1 AS fecha_hora, posts.text_1 AS modalidad, posts.integer_1 AS num_modulo,
            posts.integer_2 AS num_sesion';
            
        $this->db->select($select);
        $this->db->join('posts AS manzanas', 'posts.related_2 = manzanas.id');
        $this->db->where('posts.type_id', 312);
        
        if ( strlen($condition) > 0 ) {
             $this->db->where($condition);
        }
        
        //Hasta 10.000 registros
        $query = $this->db->get('posts', 10000);

        return $query;
    }
}