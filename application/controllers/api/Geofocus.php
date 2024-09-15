<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Geofocus extends CI_Controller{

// Variables generales
//-----------------------------------------------------------------------------
    public $url_controller = URL_API . 'geofocus/';

// Constructor
//-----------------------------------------------------------------------------
    
    function __construct() 
    {
        parent::__construct();
        $this->load->model('Geofocus_model');
        date_default_timezone_set("America/Bogota");    //Para definir hora local
    }
    
    function index($accion_id = NULL)
    {
        if ( is_null($accion_id) ) {
            redirect("app/geofocus/explorar/");
        } else {
            redirect("app/geofocus/info/{$accion_id}");
        }
    }

// Ejecución de cálculos
//-----------------------------------------------------------------------------

    function calcular_priorizacion()
    {
        // Obtener el contenido JSON enviado por POST
        $jsonData = file_get_contents("php://input");
        $settings = json_decode($jsonData, true);

        // Verificar si la decodificación fue exitosa
        if (json_last_error() === JSON_ERROR_NONE) {
            // Proceso para organizar los datos
            $data = $this->Geofocus_model->calcularPriorizacion($settings);
        } else {
            $data = [
                'status' => 'error',
                'message' => 'Datos JSON inválidos'
            ];
        }
    
        //Salida JSON
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

}