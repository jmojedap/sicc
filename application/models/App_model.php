<?php
class App_model extends CI_Model{
    
    /* Application model,
     * Functions to SICC Application
     * 
     */
    
    function __construct(){
        parent::__construct();
        
    }
    
//SYSTEM
//---------------------------------------------------------------------------------------------------------

    /**
     * Devuelve array con configuración de la app
     * 2025-07-15
     */
    function app_info($app_name = 'main')
    {
        $this->config->load('apps_info');
        $apps = $this->config->item('apps');
        $app_info = $apps[$app_name];
        return $app_info;
    }
    
    /**
     * Carga la view solicitada, si por get se solicita una view específica
     * se devuelve por secciones el html de la view, por JSON.
     * 
     * @param type $view
     * @param type $data
     */
    function view($view, $data)
    {
        if ( $this->input->get('json') )
        {
            //Sende sections JSON
            $result['head_title'] = $data['head_title'];
            $result['head_subtitle'] = '';
            $result['nav_2'] = '';
            $result['nav_3'] = '';
            $result['view_a'] = '';
            
            if ( isset($data['head_subtitle']) ) { $result['head_subtitle'] = $data['head_subtitle']; }
            if ( isset($data['view_a']) ) { $result['view_a'] = $this->load->view($data['view_a'], $data, TRUE); }
            if ( isset($data['nav_2']) ) { $result['nav_2'] = $this->load->view($data['nav_2'], $data, TRUE); }
            if ( isset($data['nav_3']) ) { $result['nav_3'] = $this->load->view($data['nav_3'], $data, TRUE); }
            
            $this->output->set_content_type('application/json')->set_output(json_encode($result));
            //echo trim(json_encode($result));
        } else {
            //Cargar view completa de forma normal
            $this->load->view($view, $data);
        }
    }
    
    /**
     * Devuelve el valor del campo sis_option.valor
     * @param type $option_id
     * @return type
     */
    function option_value($option_id)
    {
        $option_value = $this->Db_model->field_id('sis_option', $option_id, 'value');
        return $option_value;
    }

    /**
     * Array con datos de sesión adicionales específicos para la aplicación actual.
     * 2019-06-23
     */
    function app_session_data($row_user)
    {
        //$this->load->model('Order_model');
        //$data['credit'] = $this->Order_model->credit($row_user->id);
        //$data['credit'] = 38000;
        $data = array();

        return $data;
    }

    /**
     * Devuelve row User si se descifra del elemento ik enviado la URL por GET
     * 2021-10-16
     */
    function user_request()
    {
        $user = null;   //Valor por defecto
        
        $arr_ik = explode('-', $this->input->get('ik'));
        if ( count($arr_ik) == 2 ) {
            $user_id = $arr_ik[0];  //Id
            $userkey = $arr_ik[1];  //Key
            $user = $this->Db_model->row('users', "id = {$user_id} AND userkey = {$userkey}");
        }

        return $user;
    }

    //Resumen para dashboard
    function summary()
    {
        $summary = array();

        $summary['users']['num_rows'] = $this->db->count_all('users');
        $summary['users']['qty_deportistas'] = $this->Db_model->num_rows('users', 'role = 21');
        $summary['posts']['num_rows'] = $this->db->count_all('posts');
        
        //Lapso próxima semana
        $today = date('Y-m-d') . ' 00:00:00';
        $one_week_time = strtotime($today . ' +6 days');
        $one_week = date('Y-m-d',$one_week_time);

        //Lapso semana pasada
        $last_week_time = strtotime($today . ' -6 days');
        $last_week = date('Y-m-d',$last_week_time);
    
        return $summary;
    }

// NOMBRES
//-----------------------------------------------------------------------------

    /**
     * Devuelve el nombre de un user ($user_id) en un format específico ($format)
     */
    function name_user($user_id, $format = 'd')
    {
        $name_user = 'ND';
        $row = $this->Db_model->row_id('users', $user_id);

        if ( ! is_null($row) ) 
        {
            $name_user = $row->username;

            if ($format == 'u') {
                $name_user = $row->username;
            } elseif ($format == 'FL') {
                $name_user = "{$row->first_name} {$row->last_name}";
            } elseif ($format == 'LF') {
                $name_user = "{$row->last_name} {$row->first_name}";
            } elseif ($format == 'FLU') {
                $name_user = "{$row->first_name} {$row->last_name} | {$row->username}";
            } elseif ($format == 'd') {
                $name_user = $row->display_name;
            }
        }

        return $name_user;
    }

    /**
     * Devuelve el nombre de una registro ($place_id) en un format específico ($format)
     */
    function place_name($place_id, $format = 1)
    {
        
        $place_name = 'ND';
        
        if ( strlen($place_id) > 0 )
        {
            $this->db->select("places.id, places.place_name, region, country"); 
            $this->db->where('places.id', $place_id);
            $row = $this->db->get('places')->row();

            if ( $format == 1 ){
                $place_name = $row->place_name;
            } elseif ( $format == 'CR' ) {
                $place_name = $row->place_name . ', ' . $row->region;
            } elseif ( $format == 'CRP' ) {
                $place_name = $row->place_name . ' - ' . $row->region . ' - ' . $row->country;
            }
        }
        
        return $place_name;
    }

// OPCIONES
//-----------------------------------------------------------------------------

    /** Devuelve un array con las opciones de la tabla place, limitadas por una condición definida
    * en un formato ($format) definido    
    */
    function options_place($condition, $value_field = 'full_name', $empty_text = 'Lugar')
    {
        
        $this->db->select("CONCAT('0', places.id) AS place_id, place_name, full_name, CONCAT((place_name), ', ', (region)) AS cr", FALSE); 
        $this->db->where($condition);
        $this->db->order_by('places.place_name', 'ASC');
        $query = $this->db->get('places');
        
        $options_place = array_merge(array('' => '[ ' . $empty_text . ' ]'), $this->pml->query_to_array($query, $value_field, 'place_id'));
        
        return $options_place;
    }

    /** Devuelve un array con las opciones de la tabla user, limitadas por una 
    * condición definida en un format ($format) definido
    * 2021-11-26
    */
    function options_user($condition, $empty_value = NULL, $value_field = 'display_name')
    {
        $select = "CONCAT('0', users.id) AS user_id, display_name, username,
            CONCAT((first_name), ' ', (last_name)) AS document_name";
        
        $this->db->select($select, FALSE); 
        $this->db->where($condition);
        $this->db->order_by('users.first_name', 'ASC');
        $query = $this->db->get('users');

        $options_pre = $this->pml->query_to_array($query, $value_field, 'user_id');

        if ( ! is_null($empty_value) ) 
        {
            $options = array_merge(array('' => '[ ' . $empty_value . ' ]'), $options_pre);
        } else {
            $options = $options_pre;
        }
        
        return $options;
    }

    /* Devuelve un array con las opciones de la tabla post, limitadas por una condición definida
    * en un formato ($formato) definido
    */
    function options_post($condition, $format = 'n', $empty_text = 'posts')
    {
        
        $this->db->select("CONCAT('0', posts.id) AS post_id, post_name", FALSE); 
        $this->db->where($condition);
        $this->db->order_by('posts.id', 'ASC');
        $query = $this->db->get('posts');
        
        $index_field = 'post_id';
        
        if ( $format == 'n' )
        {
            $value_field = 'post_name';
        }

        $options_post = $this->pml->query_to_array($query, $value_field, $index_field);

        /*if ( $empty_text != '' ) {
            $options_post = array_merge(array('' => '[ ' . $empty_text . ' ]'), $options_post);
        }*/
        
        
        return $options_post;
    }

    /**
     * Array con posts, especificando código y nombre. Filtrados por condición
     * 2022-11-05
     * 
     * @param string $condition
     * @return array $options
     */
    function arr_options_post($condition)
    {
        $select = 'CONCAT("0", (id)) AS str_cod, id, post_name AS name, related_1';

        $query = $this->db->select($select)
            ->where($condition)
            ->order_by('post_name', 'ASC')
            ->get('posts');
        
        $options = $query->result_array();
        
        return $options;
    }

    /**
     * Array con periodos
     * 2022-11-11
     * 
     * @param string $condition
     * @return array $options
     */
    function arr_periods($condition, $orderType = 'ASC')
    {
        $select = 'CONCAT("0", (id)) AS str_cod, id, period_name AS name';

        $query = $this->db->select($select)
            ->where($condition)
            ->order_by('id', $orderType)
            ->get('periods');
        
        $options = $query->result_array();
        
        return $options;
    }

    /**
     * Lee un archivo json en la carpeta content y devuelve un array php con
     * su contenido
     * 2024-05-13
     * @param string $filePath
     * @return array $arrayContent
     */
    function getJsonContent($filePath)
    {
        $arrayContent = [];
        if ( file_exists($filePath) ) {
            $jsonContent = file_get_contents($filePath);
            $arrayContent = json_decode($jsonContent, true);
        }

        return $arrayContent;
    }
    
// Específicas de la aplicación
//-----------------------------------------------------------------------------

    /**
     * Array con los valores de posts.type_id, que tiene un formato especial
     * para menú, edición, y lectura en el administrador
     * 2022-08-20
     */
    function posts_special_types()
    {
        $special_types = [6,7,12,18,22,110,130,311,312];
        return $special_types;
    }

    /**
     * Devolver JSON con contenido HTML de un contenido o documento de especificaciones técnicas
     * 2023-04-04
     */
    function get_doc($settings)
    {
        $this->load->model('Post_model');

        $content = '';
        
        if ( $settings['type'] == 'markdown' ) {
            $filePath = $settings['file_path'];
            $this->load->library('markdown_parser');
            $content = $this->markdown_parser->parse_file(PATH_CONTENT . "{$filePath}.md");
        } elseif ( $settings['type'] == 'view' ) {
            $viewPath = $settings['view_path'];
            $content = $this->load->view($viewPath, [], true);
        }

        return $content;
    }

}