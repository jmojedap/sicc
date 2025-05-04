<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Experimentos extends CI_Controller {
        
// Variables generales
//-----------------------------------------------------------------------------
    public $views_folder = 'app/experimentos/';
    public $url_controller = URL_APP . 'experimentos/';

// Constructor
//-----------------------------------------------------------------------------
    
    function __construct()
    {
        parent::__construct();

        //$this->load->model('Noticia_model');
        
        //Local time set
        date_default_timezone_set("America/Bogota");
    }

    /**
     * Primera función
     */
    function index()
    {
        $this->inicio();
    }

// Mediciones especiales
//-----------------------------------------------------------------------------

    function somos_asociacion()
    {
        $data['head_title'] = 'SOMOS - Asociación';
        $data['view_a'] = 'app/experimentos/somos_asociacion/somos_asociacion_v';
        $this->App_model->view('templates/easypml/empty', $data);
    }

    function puntos()
    {
        $data['head_title'] = 'Puntos';
        $data['view_a'] = 'app/experimentos/puntos/puntos_v';
        $this->App_model->view('templates/easypml/empty', $data);
    }

    function mapas()
    {
        $data['head_title'] = 'Mapas';
        $data['view_a'] = 'app/experimentos/mapas/mapas_v';
        $this->App_model->view('templates/easypml/empty', $data);
    }

    function read_geojson()
    {
        $ruta_geojson = PATH_CONTENT . 'maps/barrios_bogota.geojson';

        // 1. Leer el archivo GeoJSON
        $geojson_str = file_get_contents($ruta_geojson);
        $geojson = json_decode($geojson_str, true);

        // 2. Verificar si se pudo decodificar correctamente
        if (json_last_error() !== JSON_ERROR_NONE) {
            return ['status' => 0, 'message' => 'Error al leer el GeoJSON'];
        }

        //Salida JSON
        $this->output->set_content_type('application/json')->set_output(json_encode($geojson ));

        /*// 3. Recorrer los polígonos y agregar la columna "habitantes"
        foreach ($geojson['features'] as &$feature) {
            // Obtener el ID del polígono
            $id_poligono = $feature['properties']['id']; // Asegúrate de que el campo se llame "id"

            // Verificar si el ID está en el array de habitantes
            if (isset($datos_habitantes[$id_poligono])) {
                $feature['properties']['habitantes'] = $datos_habitantes[$id_poligono];
            } else {
                $feature['properties']['habitantes'] = 0; // Si no hay datos, asigna 0
            }
        }

        // 4. Guardar el nuevo archivo GeoJSON con la información agregada
        $nuevo_geojson = json_encode($geojson, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        file_put_contents($ruta_salida, $nuevo_geojson);

        return ['status' => 1, 'message' => 'GeoJSON actualizado correctamente', 'file' => $ruta_salida];*/
    }
}