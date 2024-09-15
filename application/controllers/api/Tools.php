<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tools extends CI_Controller{

// Variables generales
//-----------------------------------------------------------------------------
    public $url_controller = URL_API . 'tools/';

// Constructor
//-----------------------------------------------------------------------------
    
    function __construct() 
    {
        parent::__construct();
        $this->load->model('App_model');
        date_default_timezone_set("America/Bogota");    //Para definir hora local
    }

// Funciones de lecura
//-----------------------------------------------------------------------------

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

    /**
     * Guarda un archivo JSON a partir de una  tabla de datos de una hoja de cálculo de 
     * googlesheet que sea pública
     * 2023-05-13
     * @param string $fileId ID del archivo en Google Drive
     * @param int $gid ID de la hoja de cálculo dentro del archivo, no es el nombre de la hoja
     * @return array
     */
    function googlesheet_save_json($fileId, $gid = 0, $folder = 'observatorio', $fileName = 'data')
    {
        $this->load->library('google_sheets');
        $array = $this->google_sheets->sheetToArray($fileId, $gid);

        $jsonContent = json_encode($array, JSON_PRETTY_PRINT);
        $filePath = PATH_CONTENT . "json/{$folder}/{$fileName}.json";
        file_put_contents($filePath, $jsonContent);

        $data['status'] = 1;
        $data['array'] = $array;

        //Salida JSON
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }


    /**
     * Guarda un archivo JSON a partir de una  tabla de datos de una hoja de cálculo de 
     * googlesheet que sea pública
     * 2023-05-13
     * @param string $fileId ID del archivo en Google Drive
     * @param int $gid ID de la hoja de cálculo dentro del archivo, no es el nombre de la hoja
     * @return array
     */
    function googlesheet_import_data($fileId, $gid = 0, $table = 'gf_territorios_valor', $importType = 'insert')
    {
        $this->load->library('google_sheets');
        $array = $this->google_sheets->sheetToArray($fileId, $gid);

        $this->db->insert_batch($table, $array);

        $data['status'] = 1;
        $data['qty_rows'] = count($array);
        $data['array'] = $array;

        //Salida JSON
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    /**
     * Guarda un archivo JSON a partir de una  tabla de datos de una hoja de cálculo de 
     * googlesheet que sea pública
     * 2024-09-14
     * @param string $folder :: nombre de la carpeta donde está el archivo CSV
     * @param string $fileName ::  nombre del archivo CSV del cual se importarán datos
     * @param string $table :: nombre de la tabla en la que se insertarán los registros
     * @return array $data :: Respuesta JSON del resultado
     */
    function import_csv($folder, $fileName = 'file', $table = 'gf_territorios')
    {
        $array = $this->pml->csv_json(base_url("content/datos/{$folder}/{$fileName}.csv"));
        $this->db->insert_batch($table, $array);

        $data['status'] = 1;
        $data['qty_rows'] = count($array);
        $data['array'] = $array;

        //Salida JSON
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }
}