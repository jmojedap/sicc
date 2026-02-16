<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Utils extends CI_Controller{

// Variables generales
//-----------------------------------------------------------------------------
    public $views_folder = 'admin/utils/';
    public $url_controller = URL_API . 'utils/';

// Constructor
//-----------------------------------------------------------------------------
    
    function __construct() 
    {        
        parent::__construct();

        //$this->load->model('User_model');
        
        //Para definir hora local
        date_default_timezone_set("America/Bogota");
    }
    
//EXPLORE
//---------------------------------------------------------------------------------------------------

    /**
     * Devuelve tabla de datos de una hoja de cálculo de googlesheet que sea pública
     * 2023-05-19
     * @param string $fileId ID del archivo en Google Drive
     * @param int $gid ID de la hoja de cálculo dentro del archivo, no es el nombre de la hoja
     * @return array
     */
    function googlesheet_array($fileId, $gid = 0)
    {
        $this->load->library('google_sheets');
        $data = $this->google_sheets->sheetToArray($fileId, $gid);

        //Salida JSON
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }
}