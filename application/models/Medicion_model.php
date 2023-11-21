<?php
class Medicion_model extends CI_Model{

    function basic($medicion_id)
    {
        $row = $this->Db_model->row_id('med_medicion', $medicion_id);

        $data['row'] = $row;
        $data['head_title'] = $data['row']->nombre_medicion;
        $data['view_a'] = $this->views_folder . 'medicion_v';
        $data['nav_2'] = $this->views_folder . 'menu_v';

        return $data;
    }

// EXPLORE FUNCTIONS - mediciones/explore
//-----------------------------------------------------------------------------
    
    /**
     * Array con los datos para la vista de exploración
     */
    function explore_data($filters, $num_page, $per_page = 10)
    {
        //Data inicial, de la tabla
            $data = $this->get($filters, $num_page, $per_page);
        
        //Elemento de exploración
            $data['controller'] = 'mediciones';                       //Nombre del controlador
            $data['cf'] = 'mediciones/explore/';                      //Nombre del controlador
            $data['views_folder'] = $this->views_folder . 'explore/';      //Carpeta donde están las vistas de exploración
            $data['numPage'] = $num_page;                       //Número de la página
            $data['perPage'] = $per_page;
            
        //Vistas
            $data['head_title'] = 'Mediciones';
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
     * Query con resultados de mediciones filtrados, por página y offset
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
                $this->db->order_by('id', 'DESC');
            }
            
        //Filtros
            $search_condition = $this->search_condition($filters);
            if ( $search_condition ) { $this->db->where($search_condition);}
            
        //Obtener resultados
            $query = $this->db->get('med_medicion', $per_page, $offset); //Resultados por página
        
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
        $words_condition = $this->Search_model->words_condition(
            $filters['q'],
            array('nombre_medicion', 'descripcion', 'palabras_clave', 'notas')
        );
        if ( $words_condition )
        {
            $condition .= $words_condition . ' AND ';
        }
        
        //Otros filtros
        if ( $filters['type'] != '' ) { $condition .= "tipo = {$filters['type']} AND "; }
        if ( $filters['cat_1'] != '' ) { $condition .= "(tematica_1 = {$filters['cat_1']} OR tematica_2 = {$filters['cat_1']}) AND "; }
        if ( $filters['fe1'] != '' ) { $condition .= "unidad_observacion = {$filters['fe1']} AND "; }
        if ( $filters['y'] != '' ) { $condition .= "anio = {$filters['y']} AND "; }
        
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
        $query = $this->db->get('med_medicion'); //Para calcular el total de resultados

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
        $query = $this->db->get('med_medicion', 5000);  //Hasta 5000 productos

        return $query;
    }
    
    /**
     * Devuelve segmento SQL
     */
    function role_filter()
    {
        
        $role = $this->session->userdata('role');
        $condition = 'id = 0';  //Valor por defecto, ningún post, se obtendrían cero mediciones.
        
        if ( $role <= 2 ) 
        {   //Desarrollador, todos los post
            $condition = 'id > 0';
        } else {
            $condition = 'tipo_id IN (6,20)';
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
            'id' => 'ID Medicion',
            'nombre_medicion' => 'Nombre'
        );
        
        return $order_options;
    }

    /**
     * Query para exportar
     * 2021-09-27
     */
    function query_export($filters)
    {
        $this->db->select($this->select('export'));
        $search_condition = $this->search_condition($filters);
        if ( $search_condition ) { $this->db->where($search_condition);}
        $query = $this->db->get('med_medicion', 10000);  //Hasta 10.000 registros

        return $query;
    }

// CRUD
//-----------------------------------------------------------------------------

    /**
     * Objeto registro de un post ID, con un formato específico
     * 2021-01-04
     */
    function row($medicion_id, $format = 'general')
    {
        $row = NULL;    //Valor por defecto

        $this->db->select($this->select($format));
        $this->db->where('id', $medicion_id);
        $query = $this->db->get('med_medicion', 1);

        if ( $query->num_rows() > 0 ) $row = $query->row();

        return $row;
    }

    function save()
    {
        $data['saved_id'] = $this->Db_model->save_id('med_medicion');
        return $data;
    }

// ELIMINACIÓN DE UNA MEDICIÓN
//-----------------------------------------------------------------------------
    
    /**
     * Verifica si el usuario en sesión tiene permiso para eliminar un 
     * registro tabla med_medicion
     * 2020-08-18
     */
    function deleteable($row_id)
    {
        $row = $this->Db_model->row_id('med_medicion', $row_id);

        $deleteable = 0;
        if ( in_array($this->session->userdata('role'), array(1,2,3)) ) $deleteable = 1;    //Es Administrador
        //if ( $row->creator_id = $this->session->userdata('user_id') ) $deleteable = 1;    //Es el creador

        return $deleteable;
    }

    /**
     * Eliminar una medición de la base de datos, se eliminan registros de
     * tablas relacionadas
     * 2020-08-18
     */
    function delete($medicion_id)
    {
        $qty_deleted = 0;

        if ( $this->deleteable($medicion_id) ) 
        {
            
            //Tabla principal
                $this->db->where('id', $medicion_id)->delete('med_medicion');

            $qty_deleted = $this->db->affected_rows();  //De la última consulta, tabla principal
        }

        return $qty_deleted;
    }

// INFORMACIÓN ESPECÍFICA
//-----------------------------------------------------------------------------

    function secciones($medicion_id)
    {
        $this->db->select('*');
        $this->db->where('medicion_id', $medicion_id);
        $this->db->order_by('num_seccion', 'ASC');
        $secciones = $this->db->get('med_seccion');

        return $secciones;
    }

    /**
     * Listado de variables de una medición, con información complementaria de
     * preguntas, secciones y opciones de valor.
     * 2023-04-14
     */
    function preguntas($medicion_id, $num_seccion = null)
    {
        $this->db->select('*');
        $this->db->where('medicion_id', $medicion_id);
        $this->db->order_by('indice_pregunta', 'ASC');
        if ( ! is_null($num_seccion) ) $this->db->where('num_seccion', $num_seccion);
        $preguntas = $this->db->get('med_pregunta');

        return $preguntas;
    }

    /**
     * Listado de variables de una medición, con información complementaria de
     * preguntas, secciones y opciones de valor.
     * 2023-11-21
     */
    function variables($condition = 'id = 0')
    {
        $this->db->select('*');
        $this->db->where($condition);
        $this->db->order_by('etiqueta_orden', 'ASC');
        $variables = $this->db->get('med_variable');

        return $variables;
    }
    /**
     * Opciones de valor de respuesta de las variables de una medición
     * 2023-11-14
     */
    function opciones($condicion = 'id = 0')
    {
        $this->db->select('*');
        $this->db->where($condicion);
        $this->db->order_by('num_nombre', 'ASC');
        $opciones = $this->db->get('med_opcion');

        return $opciones;
    }

    function opciones_agrupadas($condicion = 'id = 0')
    {
        $this->db->select('codigo_opcion, texto_opcion');
        $this->db->where($condicion);
        $this->db->order_by('num_nombre', 'DESC');
        $this->db->group_by('codigo_opcion, texto_opcion');
        $opciones = $this->db->get('med_opcion');

        return $opciones;
    }

    function contenido($medicion_id)
    {
        $this->db->select('*');
        $this->db->where('medicion_id', $medicion_id);
        $secciones = $this->db->get('med_seccion');

        $secciones = $secciones->result_array();

        foreach ($secciones as $i => $seccion) {
            $preguntas = $this->preguntas($medicion_id, $seccion['num_seccion']);
            $secciones[$i]['preguntas'] = $preguntas->result_array();
        }

        return $secciones;
    }

// PROCESOS
//-----------------------------------------------------------------------------

    /**
     * Eliminar los datos de una medición en una tabla determinada
     * 2023-11-14
     */
    function clean_medicion($table, $medicion_id)
    {
        $this->db->where('medicion_id', $medicion_id);
        $this->db->delete($table);
        
        $qty_deleted = $this->db->affected_rows();

        $data['qty_deleted'] = $qty_deleted;

        return $data;
    }

    /**
     * Genera los datos de la tabla opciones a partir de los datos disponibles
     * en med_variable.opciones_json
     * 2023-11-14
     */
    function variables_to_opciones($medicion_id)
    {
        $this->Medicion_model->clean_medicion('med_opcion', $medicion_id);

        $variables = $this->variables($medicion_id);



    }

// CÁLCULOS DE RESULTADOS
//-----------------------------------------------------------------------------

    function sumatoria_encuestados($medicion_id)
    {
        $sumatoria_encuestados = 1; //Valor por defecto para evitar divisiones por cero
        $sql = "SELECT SUM(factor_expansion) AS sumatoria_encuestados
            FROM med_medicion_encuestado
            WHERE medicion_id = {$medicion_id}";
        $query = $this->db->query($sql);
        if ( $query->num_rows() > 0 ) {
            $sumatoria_encuestados = $query->row(0)->sumatoria_encuestados;
        }

        return $sumatoria_encuestados;
    }

    function frecuencias($medicion_id, $pregunta_id)
    {

        $sql = "SELECT o.id, o.codigo_opcion, o.texto_opcion, COUNT(r.id) AS cantidad_respuestas, SUM(me.factor_expansion) AS frecuencia_ponderada
            FROM med_opcion AS o 
            LEFT JOIN med_respuesta AS r ON o.id = r.opcion_id
            LEFT JOIN med_medicion_encuestado AS me ON r.encuestado_id = me.encuestado_id
            WHERE o.pregunta_id = {$pregunta_id}
            GROUP BY o.id, o.codigo_opcion, o.texto_opcion
            ORDER BY o.id ASC, o.codigo_opcion ASC, o.texto_opcion ASC";

        $frecuencias = $this->db->query($sql);

        return $frecuencias;
    }

    function frecuencias_array($frecuencias, $divisor = 1)
    {
        $frecuencias_array = [];
        foreach( $frecuencias->result() as $row )
        {
            $valor = 0;
            if ( is_null($row->frecuencia_ponderada) ) $valor = $row->frecuencia_ponderada;
            $frecuencias_array[] = $row->frecuencia_ponderada / $divisor;
        }

        return $frecuencias_array;
    }

}