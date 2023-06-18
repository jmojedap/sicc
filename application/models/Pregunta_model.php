<?php
class Pregunta_model extends CI_Model{

    function basic($pregunta_id)
    {
        $row = $this->Db_model->row_id('med_pregunta', $pregunta_id);

        $data['row'] = $row;
        $data['head_title'] = $data['row']->nombre;
        $data['view_a'] = $this->views_folder . 'pregunta_v';
        $data['nav_2'] = $this->views_folder . 'menu_v';

        return $data;
    }

// EXPLORE FUNCTIONS - preguntas/explore
//-----------------------------------------------------------------------------
    
    /**
     * Array con los datos para la vista de exploración
     */
    function explore_data($filters, $num_page, $per_page = 10)
    {
        //Data inicial, de la tabla
            $data = $this->get($filters, $num_page, $per_page);
        
        //Elemento de exploración
            $data['controller'] = 'preguntas';                       //Nombre del controlador
            $data['cf'] = 'preguntas/explore/';                      //Nombre del controlador
            $data['views_folder'] = $this->views_folder . 'explore/';      //Carpeta donde están las vistas de exploración
            $data['numPage'] = $num_page;                       //Número de la página
            $data['perPage'] = $per_page;                       //Número de la página
            
        //Vistas
            $data['head_title'] = 'Preguntas';
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
            $data['strFilters'] = $this->Search_model->str_filters($filters);
            $data['qtyResults'] = $this->search_num_rows($filters);
            $data['maxPage'] = ceil($this->pml->if_zero($data['qtyResults'],1) / $per_page);   //Cantidad de páginas

            

        return $data;
    }

    /**
     * Segmento Select SQL, con diferentes formatos, consulta de products
     * 2020-12-12
     */
    function select($format = 'general')
    {
        $arr_select['general'] = '*';
        $arr_select['export'] = '*';

        return $arr_select[$format];
    }
    
    /**
     * Query con resultados de preguntas filtrados, por página y offset
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
                $this->db->order_by('id', 'ASC');
            }
            
        //Filtros
            $search_condition = $this->search_condition($filters);
            if ( $search_condition ) { $this->db->where($search_condition);}
            
        //Obtener resultados
            $query = $this->db->get('med_pregunta', $per_page, $offset); //Resultados por página
        
        return $query;
        
    }

    /**
     * String con condición WHERE SQL para filtrar post
     * 2020-08-01
     */
    function search_condition($filters)
    {
        $condition = NULL;

        $condition .= $this->role_filter() . ' AND ';

        //q words condition
        $qFields = ['codigo', 'enunciado_1', 'descripcion', 'palabras_clave', 'instruccion'];
        $words_condition = $this->Search_model->words_condition($filters['q'], $qFields);
        if ( $words_condition )
        {
            $condition .= $words_condition . ' AND ';
        }
        
        //Otros filtros
        if ( $filters['type'] != '' ) { $condition .= "tipo = {$filters['type']} AND "; }
        if ( $filters['med'] != '' ) { $condition .= "medicion_id = {$filters['med']} AND "; }
        if ( $filters['role'] != '' ) { $condition .= "rol = {$filters['role']} AND "; }
        if ( $filters['condition'] != '' ) { $condition .= "{$filters['condition']} AND "; }
        
        //Quitar cadena final de ' AND '
        if ( strlen($condition) > 0 ) { $condition = substr($condition, 0, -5);}
        
        return $condition;
    }
    
    /**
     * Devuelve la cantidad de registros encontrados en la tabla con los filtros
     * establecidos en la búsqueda
     */
    function search_num_rows($filters)
    {
        $this->db->select('id');
        $search_condition = $this->search_condition($filters);
        if ( $search_condition ) { $this->db->where($search_condition);}
        $query = $this->db->get('med_pregunta'); //Para calcular el total de resultados

        return $query->num_rows();
    }

    /**
     * Query para exportar
     * 2020-12-12
     */
    function export($filters)
    {
        $this->db->select($this->select('export'));
        $search_condition = $this->search_condition($filters);
        if ( $search_condition ) { $this->db->where($search_condition);}
        $query = $this->db->get('med_pregunta', 5000);  //Hasta 5000 productos

        return $query;
    }
    
    /**
     * Devuelve segmento SQL
     */
    function role_filter()
    {
        
        $role = $this->session->userdata('role');
        $condition = 'id > 0';  //Valor por defecto, ningún post, se obtendrían cero preguntas.
        
        /*if ( $role <= 2 ) 
        {   //Desarrollador, todos los post
            $condition = 'id > 0';
        }*/
        
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
            'id' => 'ID Pregunta',
            'codigo' => 'Código'
        );
        
        return $order_options;
    }

// CRUD
//-----------------------------------------------------------------------------

    /**
     * Objeto registro de un post ID, con un formato específico
     * 2021-01-04
     */
    function row($pregunta_id, $format = 'general')
    {
        $row = NULL;    //Valor por defecto

        $this->db->select($this->select($format));
        $this->db->where('id', $pregunta_id);
        $query = $this->db->get('med_pregunta', 1);

        if ( $query->num_rows() > 0 ) $row = $query->row();

        return $row;
    }

    function save()
    {
        $data['saved_id'] = $this->Db_model->save_id('med_pregunta');
        return $data;
    }

// ELIMINACIÓN DE UNA PREGUNTA
//-----------------------------------------------------------------------------
    
    /**
     * Verifica si el usuario en sesión tiene permiso para eliminar un registro tabla post
     * 2020-08-18
     */
    function deleteable($row_id)
    {
        $row = $this->Db_model->row_id('med_pregunta', $row_id);

        $deleteable = 0;
        if ( in_array($this->session->userdata('role'), [1,2,3]) ) $deleteable = 1;     //Es Administrador
        if ( $row->creator_id = $this->session->userdata('user_id') ) $deleteable = 1;  //Es el creador

        return $deleteable;
    }

    /**
     * Eliminar un post de la base de datos, se eliminan registros de tablas relacionadas
     * 2020-08-18
     */
    function delete($pregunta_id)
    {
        $qty_deleted = 0;

        if ( $this->deleteable($pregunta_id) ) 
        {
            
            //Tabla principal
                $this->db->where('id', $pregunta_id)->delete('med_pregunta');

            $qty_deleted = $this->db->affected_rows();  //De la última consulta, tabla principal
        }

        return $qty_deleted;
    }

// DATOS
//-----------------------------------------------------------------------------

    /**
     * Opciones de respuesta de una pregunta de selección única
     * 2022-04-24
     */
    function opciones($pregunta_id)
    {
        $this->db->order_by('codigo_opcion', 'ASC');
        $this->db->where('pregunta_id', $pregunta_id);
        $opciones = $this->db->get('med_opcion');

        return $opciones;
        
    }

// VALIDATION
//-----------------------------------------------------------------------------

    function arr_row($process = 'update')
    {
        $arr_row = $this->input->post();
        $arr_row['updater_id'] = $this->session->userdata('user_id');
        
        if ( $process == 'insert' )
        {
            $arr_row['slug'] = $this->Db_model->unique_slug($arr_row['post_name'], 'med_pregunta');
            $arr_row['creator_id'] = $this->session->userdata('user_id');
        }
        
        return $arr_row;
    }

// PROCESOS DE DATOS
//-----------------------------------------------------------------------------

    /**
     * Actualiza los campos dependientes de med_pregunta.codigo
     * 2022-06-17
     */
    function update_codigo_parts()
    {
        $this->db->select('id, codigo');
        $this->db->where('LENGTH(codigo) >= 11');
        $preguntas  = $this->db->get('med_pregunta');

        $qty_updated = 0;

        foreach ($preguntas->result() as $pregunta) {
            $arr_row_edit = $this->Db_model->arr_row_edit(FALSE);
            $codigo_parts = $this->explode_codigo($pregunta->codigo);
            $arr_row = array_merge($arr_row_edit, $codigo_parts);
            $arr_row['id'] = $pregunta->id;

            $saved_id = $this->Db_model->save_id('med_pregunta', $arr_row);

            if ( $saved_id > 0 ) $qty_updated += 1;
        }

        $data['status'] = 1;
        $data['message'] = 'Preguntas actualizadas: ' . $qty_updated;

        return $data;
        
    }

    /**
     * Devuelve array con valores de campos que componen el código de pregunta
     * 2022-06-17
     */
    function explode_codigo($codigo)
    {
        //Valor por defecto
        $arr_row = [
            'tematica_id' => '',
            'subtematica_id' => '',
        ];

        if ( strlen($codigo) >= 11 ) {
            $arr_row['tematica_id'] = substr($codigo,1,2);
            $arr_row['subtematica_id'] = substr($codigo,3,2);
            $arr_row['consecutivo_tematica'] = substr($codigo,5,3);
            $arr_row['consecutivo_grupo'] = substr($codigo,8,3);
        }

        return $arr_row;
    }
}