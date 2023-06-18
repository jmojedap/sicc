<?php
class Noticia_model extends CI_Model
{
    public function basic($noticia_id)
    {
        $row = $this->Db_model->row_id('noticias', $noticia_id);

        $data['row'] = $row;
        $data['head_title'] = $data['row']->titular;
        $data['view_a'] = $this->views_folder . 'noticia_v';
        //$data['nav_2'] = $this->views_folder . 'menu_v';

        return $data;
    }

// EXPLORE FUNCTIONS - noticias/explore
//-----------------------------------------------------------------------------

    /**
     * Array con los datos para la vista de exploración
     */
    public function explore_data($filters, $num_page, $per_page = 60)
    {
        //Data inicial, de la tabla
        $data = $this->get($filters, $num_page, $per_page);

        //Elemento de exploración
        $data['controller'] = 'noticias';                       //Nombre del controlador
        $data['cf'] = 'noticias/explore/';                      //Nombre del controlador
        $data['views_folder'] = $this->views_folder . 'explorar/';      //Carpeta donde están las vistas de exploración
        $data['num_page'] = $num_page;                       //Número de la página

        //Vistas
        $data['head_title'] = 'Mediciones';
        $data['view_a'] = $data['views_folder'] . 'explore_v';
        $data['nav_2'] = $data['views_folder'] . 'menu_v';

        return $data;
    }

    public function get($filters, $num_page, $per_page = 60)
    {
        //Load
        $this->load->model('Search_model');

        //Búsqueda y Resultados
        $data['filters'] = $filters;
        $offset = ($num_page - 1) * $per_page;      //Número de la página de datos que se está consultado
        $elements = $this->search($filters, $per_page, $offset);    //Resultados para página

        //Cargar datos
        $data['list'] = $elements->result();
        $data['str_filters'] = $this->Search_model->str_filters($filters);
        $data['search_num_rows'] = $this->search_num_rows($filters);
        $data['max_page'] = ceil($this->pml->if_zero($data['search_num_rows'], 1) / $per_page);   //Cantidad de páginas

        return $data;
    }

    /**
     * Segmento Select SQL, con diferentes formatos, consulta de products
     * 2020-12-12
     */
    public function select($format = 'general')
    {
        $arr_select['general'] = '*';
        $arr_select['export'] = 'id, titular, epigrafe, fecha_publicacion, anio_publicacion,
            url_publicacion, url_thumbnail, status, 
            cat_1 AS cod_categoria, clasificacion, compartible,
            actualizado_por, updated_at AS fecha_actualizado';

        return $arr_select[$format];
    }

    /**
     * Query con resultados de noticias filtrados, por página y offset
     * 2020-07-15
     */
    public function search($filters, $per_page = null, $offset = null)
    {
        //Segmento SELECT
        $select_format = 'general';
        if ($filters['sf'] != '') {
            $select_format = $filters['sf'];
        }
        $this->db->select($this->select($select_format));

        //Orden
        if ($filters['o'] != '') {
            $order_type = $this->pml->if_strlen($filters['ot'], 'ASC');
            $this->db->order_by($filters['o'], $order_type);
        } else {
            $this->db->order_by('aleatorio', 'ASC');
        }

        //Filtros
        $search_condition = $this->search_condition($filters);
        if ($search_condition) {
            $this->db->where($search_condition);
        }

        //Obtener resultados
        $query = $this->db->get('noticias', $per_page, $offset); //Resultados por página

        return $query;
    }

    /**
     * String con condición WHERE SQL para filtrar noticia
     * 2020-08-01
     */
    public function search_condition($filters)
    {
        $condition = null;
        //$condition = 'fecha_publicacion >= "2021-07-01 00:00:00" AND ';

        $condition .= $this->role_filter() . ' AND ';

        //q words condition
        $words_condition = $this->Search_model->words_condition(
            $filters['q'],
            array('titular', 'epigrafe', 'palabras_clave')
        );
        if ($words_condition) {
            $condition .= $words_condition . ' AND ';
        }

        //Otros filtros
        if ($filters['cat_1'] != '') {
            $condition .= "cat_1 = {$filters['cat_1']} AND ";
        }
        if ($filters['fe1'] != '') {
            $condition .= "status = {$filters['fe1']} AND ";
        }
        if ($filters['fe2'] != '') {
            $condition .= "actualizado_por = '{$filters['fe2']}' AND ";
        }

        //Quitar cadena final de ' AND '
        if (strlen($condition) > 0) {
            $condition = substr($condition, 0, -5);
        }

        return $condition;
    }

    /**
     * Devuelve la cantidad de registros encontrados en la tabla con los filtros
     * establecidos en la búsqueda
     */
    public function search_num_rows($filters)
    {
        $this->db->select('id');
        $search_condition = $this->search_condition($filters);
        if ($search_condition) {
            $this->db->where($search_condition);
        }
        $query = $this->db->get('noticias'); //Para calcular el total de resultados

        return $query->num_rows();
    }

    /**
     * Query para exportar
     * 2020-12-12
     */
    public function export($filters)
    {
        $this->db->select($this->select('export'));
        $search_condition = $this->search_condition($filters);
        if ($search_condition) {
            $this->db->where($search_condition);
        }
        $query = $this->db->get('noticias', 5000);  //Hasta 5000 productos

        return $query;
    }

    /**
     * Devuelve segmento SQL
     */
    public function role_filter()
    {
        $role = $this->session->userdata('role');
        $condition = 'id > 0';  //Valor por defecto, ningún noticia, se obtendrían cero noticias.

        if ($role <= 2) {   //Desarrollador, todos las noticias
            $condition = 'id > 0';
        }

        return $condition;
    }

    /**
     * Array con options para ordenar el listado de noticia en la vista de
     * exploración
     */
    public function order_options()
    {
        $order_options = array(
            '' => '[ Ordenar por ]',
            'id' => 'ID Medicion',
            'titular' => 'Nombre'
        );

        return $order_options;
    }

    /**
     * Query para exportar
     * 2022-09-01
     */
    public function query_export($filters)
    {
        $this->db->select($this->select('export'));
        $search_condition = $this->search_condition($filters);
        if ($search_condition) {
            $this->db->where($search_condition);
        }
        $query = $this->db->get('noticias', 10000);  //Hasta 10.000 registros

        return $query;
    }

// CRUD
//-----------------------------------------------------------------------------

    /**
     * Objeto registro de un noticia ID, con un formato específico
     * 2021-01-04
     */
    public function row($noticia_id, $format = 'general')
    {
        $row = null;    //Valor por defecto

        $this->db->select($this->select($format));
        $this->db->where('id', $noticia_id);
        $query = $this->db->get('noticias', 1);

        if ($query->num_rows() > 0) {
            $row = $query->row();
        }

        return $row;
    }

    public function save()
    {
        $data['saved_id'] = $this->Db_model->save_id('noticias');
        return $data;
    }

// DATOS
//-----------------------------------------------------------------------------

    public function classification_summary()
    {
    }

    /**
     * 2022-07-26
     */
    public function options_cat_1()
    {
        $options_cat_1 = [
            ["id"=>110, "name"=>"Seguridad", "short_name"=>"Seguridad"],
            ["id"=>115, "name"=>"Convivencia", "short_name"=>"Convivencia"],
            ["id"=>120, "name"=>"Movilidad", "short_name"=>"Movilidad"],
            ["id"=>130, "name"=>"Gobierno y Política", "short_name"=>"Gobierno y Política"],
            ["id"=>140, "name"=>"Economía", "short_name"=>"Economía"],
            ["id"=>150, "name"=>"Educación", "short_name"=>"Educación"],
            ["id"=>160, "name"=>"Cultura", "short_name"=>"Cultura"],
            ["id"=>170, "name"=>"Deporte", "short_name"=>"Deporte"],
            ["id"=>190, "name"=>"Servicios Públicos", "short_name"=>"Serv. Públicos"],
            ["id"=>200, "name"=>"Salud", "short_name"=>"Salud"],
            ["id"=>210, "name"=>"Medio Ambiente", "short_name"=>"M. Ambiente"],
            ["id"=>220, "name"=>"Justicia", "short_name"=>"Justicia"],
            ["id"=>990, "name"=>"Otro", "short_name"=>"Otro"],
        ];

        return $options_cat_1;
    }

    public function options_clasificacion()
    {
        $options_clasificacion = [
            ['value'=>1, 'name'=> 'Negativa', 'class'=> 'btn-danger', 'emptyClass'=> 'btn-outline-danger', 'infoClass' => 'bg-danger'],
            ['value'=>5, 'name'=> 'Neutral', 'class'=> 'btn-secondary', 'emptyClass'=> 'btn-outline-secondary', 'infoClass' => 'bg-secondary'],
            ['value'=>10, 'name'=> 'Positiva', 'class'=> 'btn-success', 'emptyClass'=> 'btn-outline-success', 'infoClass' => 'bg-success'],
        ];

        return $options_clasificacion;
    }

// PREPARACIÓN
//-----------------------------------------------------------------------------

    /**
     * Reiniciar datos de noticias para clasificación
     */
    public function reset_noticias()
    {
        $affected_rows = 0;
        $queries[] = 'UPDATE noticias
            SET clasificacion = 0, cat_1 = 0, status = 0, actualizado_por = "", 
            compartible = 0, muestra = 0';

        foreach ($queries as $query) {
            $this->db->query($query);
            $affected_rows +=  $this->db->affected_rows();
        }

        return $affected_rows;
    }

    /**
     * Marcar las noticias que harán parte de la muestra
     */
    public function set_noticias_samples()
    {
        //Reiniciar muestras
        $this->db->query('UPDATE noticias SET muestra = 0');

        $sample_sizes = $this->year_sample_size();

        foreach ($sample_sizes as $year => $qty_noticias) {
            $sql = "UPDATE noticias
                SET muestra = 1
                WHERE anio_publicacion = {$year}
                ORDER BY id ASC
                LIMIT {$qty_noticias}";
            $this->db->query($sql);
        }

        return 'Muestras actualizadas';
    }

    /**
     * Array tamaños de muestra por año
     * 2022-08-29
     */
    public function year_sample_size()
    {
        $sample_sizes = [
            2016 => 165,
            2017 => 175,
            2018 => 174,
            2019 => 176,
            2020 => 176,
            2021 => 179,
            2022 => 174
        ];

        return $sample_sizes;
    }

// RESULTADOS
//-----------------------------------------------------------------------------

    /**
     * 
     */
    function resultados_clasificacion()
    {
        $this->db->select('clasificacion, COUNT(id) AS qty_noticias');
        $this->db->where('status > 0');
        $this->db->group_by('clasificacion');
        $resultados_clasificacion = $this->db->get('noticias');

        return $resultados_clasificacion;
    }

    /**
     * 
     */
    function resultados_clasificador()
    {
        $this->db->select('actualizado_por, COUNT(id) AS qty_noticias');
        $this->db->where('status > 0');
        $this->db->group_by('actualizado_por');
        $resultados_clasificacion = $this->db->get('noticias');

        //$resultados_clasificacion = $query->result_array();

        return $resultados_clasificacion;
    }

    /**
     * 
     */
    function resultados_anio()
    {
        $this->db->select('anio_publicacion, COUNT(id) AS qty_noticias');
        $this->db->where('status > 0');
        $this->db->group_by('anio_publicacion');
        $this->db->order_by('anio_publicacion');
        $resultados_anio = $this->db->get('noticias');

        return $resultados_anio;
    }

    /**
     * Resultados por clasificación y año
     * 2022-09-01
     */
    function resultados_ca()
    {
        $this->db->select('anio_publicacion, clasificacion, COUNT(id) AS qty_noticias');
        $this->db->where('status > 0');
        $this->db->group_by('anio_publicacion, clasificacion');
        $this->db->order_by('anio_publicacion, clasificacion');
        $resultados_anio = $this->db->get('noticias');

        return $resultados_anio;
    }

// RESULTADOS SERIE DE TIEMPO
//-----------------------------------------------------------------------------

    function get_series($cat_1 = 0, $compartible = 0)
    {
        //$this->db->select('unixtime, pct_negativa, pct_neutral, pct_positiva');
        $this->db->where('cat_1', $cat_1);
        $this->db->where('compartible', $compartible);
        $this->db->where('day', 1);
        $series = $this->db->get('noticias_series');
        $cant_negativa = [];
        $cant_neutral = [];
        $cant_positiva = [];
        $pct_negativa = [];
        $pct_neutral = [];
        $pct_positiva = [];

        foreach($series->result() as $row)
        {
            $unixtime = intval($row->unixtime);
            $cant_negativa[] = [$unixtime,floatval($row->cant_negativa)];
            $cant_neutral[] = [$unixtime,floatval($row->cant_neutral)];
            $cant_positiva[] = [$unixtime,floatval($row->cant_positiva)];
            $pct_negativa[] = [$unixtime,floatval($row->pct_negativa)];
            $pct_neutral[] = [$unixtime,floatval($row->pct_neutral)];
            $pct_positiva[] = [$unixtime,floatval($row->pct_positiva)];
        }

        $data['cant_negativa'] = $cant_negativa;
        $data['cant_neutral'] = $cant_neutral;
        $data['cant_positiva'] = $cant_positiva;
        $data['pct_negativa'] = $pct_negativa;
        $data['pct_neutral'] = $pct_neutral;
        $data['pct_positiva'] = $pct_positiva;

        return $data;
    }

    function update_series($cat_1 = 0, $compartible = 0)
    {
        //Recorrer fila
        $this->db->select('id, end, year, month, day, week_day, unixtime');
        $this->db->where('type_id', 9);
        $this->db->where('id >=', 20161201);
        $this->db->where('id <=', 20220731);
        $this->db->where('day', 1); //Primer día de cada mes
        $periods = $this->db->get('periods');

        $rows = [];
        foreach ($periods->result() as $period) {
            $aRow = $this->serie_row($period, $cat_1, $compartible);
            $rows[] = $aRow;
            $condition = "period_id = {$aRow['period_id']} AND cat_1 = {$aRow['cat_1']} AND compartible = {$aRow['compartible']}";
            $this->Db_model->save('noticias_series', $condition, $aRow);
        }

        return $rows;
    }

    function serie_row($period, $cat_1, $compartible)
    {
        $date_end = $period->end;
        $date_start = $this->pml->date_add($date_end, '-1 year');

        $clasificacion = $this->query_clasificacion_fecha($date_start, $date_end, $cat_1, $compartible);

        $aRow['period_id'] = $period->id;
        $aRow['year'] = $period->year;
        $aRow['month'] = $period->month;
        $aRow['day'] = $period->day;
        $aRow['week_day'] = $period->week_day;
        $aRow['unixtime'] = $period->unixtime;
        $aRow['updated_at'] = date('Y-m-d H:i:s');
        $aRow['cat_1'] = $cat_1;
        $aRow['compartible'] = $compartible;
        $aRow['cant_negativa'] = 0;
        $aRow['cant_neutral'] = 0;
        $aRow['cant_positiva'] = 0;
        $aRow['pct_negativa'] = 0;
        $aRow['pct_neutral'] = 0;
        $aRow['pct_positiva'] = 0;
        foreach($clasificacion->result() as $row){
            if ( $row->clasificacion == 1 ) $aRow['cant_negativa'] = intval($row->cant_noticias);
            if ( $row->clasificacion == 5 ) $aRow['cant_neutral'] = intval($row->cant_noticias);
            if ( $row->clasificacion == 10 ) $aRow['cant_positiva'] = intval($row->cant_noticias);
        }
        $aRow['cant_noticias'] = $aRow['cant_negativa'] + $aRow['cant_positiva'] + $aRow['cant_neutral'];
        if ( $aRow['cant_noticias'] > 0 ) {
            $aRow['pct_negativa'] = 100 * $aRow['cant_negativa'] / $aRow['cant_noticias'];
            $aRow['pct_neutral'] = 100 * $aRow['cant_neutral'] / $aRow['cant_noticias'];
            $aRow['pct_positiva'] = 100 * $aRow['cant_positiva'] / $aRow['cant_noticias'];
        }

        return $aRow;
    }

    function query_clasificacion_fecha($date_start, $date_end, $cat_1, $compartible)
    {
        $this->db->select('clasificacion, COUNT(id) AS cant_noticias');
        $this->db->where('status', 1);
        $this->db->where('fecha_publicacion >', $date_start);
        $this->db->where('fecha_publicacion <=', $date_end);
        if ( $cat_1 > 0 ) { $this->db->where('cat_1', $cat_1); }
        if ( $compartible > 0 ) { $this->db->where('compartible', $compartible); }
        $this->db->group_by('clasificacion');
        $clasificacion = $this->db->get('noticias');

        return $clasificacion;
    }

// RESULTADOS POR CATEGORÍA
//-----------------------------------------------------------------------------

    function get_resultados_categoria($year)
    {
        $arrCat1 = $this->options_cat_1();

        $pct_negativa = [];
        $pct_positiva = [];
        $cant_noticias = [];

        $listCat1 = [];
        foreach ($arrCat1 as $cat1) {
            $condition = "cat_1 = {$cat1['id']}";
            if ( $year > 0 ) $condition .= " AND anio_publicacion = {$year}";
            $catSummary = $this->category_summary($condition);
            $pct_negativa[] = -1 * $catSummary['pct_negativa'];
            $pct_positiva[] = $catSummary['pct_positiva'];
            $cant_noticias[] = $catSummary['cant_noticias'];
            $listCat1[] = $cat1['name'];
        }

        $data['pct_negativa'] = $pct_negativa;
        $data['pct_positiva'] = $pct_positiva;
        $data['cant_noticias'] = $cant_noticias;

        return $data;
    }

    function category_summary($condition)
    {
        $clasificacion = $this->query_clasificacion($condition);

        $summary['cant_negativa'] = 0;
        $summary['cant_neutral'] = 0;
        $summary['cant_positiva'] = 0;
        $summary['pct_negativa'] = 0;
        $summary['pct_neutral'] = 0;
        $summary['pct_positiva'] = 0;

        foreach($clasificacion->result() as $row){
            if ( $row->clasificacion == 1 ) $summary['cant_negativa'] = intval($row->cant_noticias);
            if ( $row->clasificacion == 5 ) $summary['cant_neutral'] = intval($row->cant_noticias);
            if ( $row->clasificacion == 10 ) $summary['cant_positiva'] = intval($row->cant_noticias);
        }
        $summary['cant_noticias'] = $summary['cant_negativa'] + $summary['cant_positiva'] + $summary['cant_neutral'];
        if ( $summary['cant_noticias'] > 0 ) {
            $summary['pct_negativa'] = floatval(number_format(100 * $summary['cant_negativa'] / $summary['cant_noticias'],2));
            $summary['pct_neutral'] = 100 * $summary['cant_neutral'] / $summary['cant_noticias'];
            $summary['pct_positiva'] = floatval(number_format(100 * $summary['cant_positiva'] / $summary['cant_noticias'],2));
        }

        return $summary;
    }

    function query_clasificacion($condition)
    {
        $this->db->select('clasificacion, COUNT(id) AS cant_noticias');
        if ( strlen($condition) > 0 ) { $this->db->where($condition); }
        $this->db->where('status', 1);
        $this->db->group_by('clasificacion');
        $clasificacion = $this->db->get('noticias');

        return $clasificacion;
    }


// ELIMINACIÓN DE UNA NOTICIA
//-----------------------------------------------------------------------------
    
    /**
     * Verifica si el usuario en sesión tiene permiso para eliminar un registro
     * tabla noticia
     * 2020-08-18
     */
    function deleteable($row_id)
    {
        $row = $this->Db_model->row_id('noticias', $row_id);

        $deleteable = 0;
        if ( in_array($this->session->userdata('role'), array(1,2,3)) ) $deleteable = 1;    //Es Administrador
        if ( $row->creator_id = $this->session->userdata('user_id') ) $deleteable = 1;      //Es el creador

        return $deleteable;
    }

    /**
     * Eliminar un noticia de la base de datos, se eliminan registros de tablas relacionadas
     * 2020-08-18
     */
    function delete($noticia_id)
    {
        $qty_deleted = 0;

        if ( $this->deleteable($noticia_id) ) 
        {
            //Tabla principal
                $this->db->where('id', $noticia_id)->delete('noticias');

            $qty_deleted = $this->db->affected_rows();  //De la última consulta, tabla principal
        }

        return $qty_deleted;
    }
}