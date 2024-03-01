<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Observatorio extends CI_Controller {
        
// Variables generales
//-----------------------------------------------------------------------------
    public $views_folder = 'admin/observatorio/';
    public $url_controller = URL_APP . 'observatorio/';

// Constructor
//-----------------------------------------------------------------------------
    
    function __construct()
    {
        parent::__construct();

        //$this->load->model('Observatorio_model');
        
        //Local time set
        date_default_timezone_set("America/Bogota");
    }

    /**
     * Primera función
     */
    function index()
    {
        //$this->inicio();
    }

// LINKS
//-----------------------------------------------------------------------------

    /**
     * Actualización masiva de datos de los links de contenidos del observatorio
     * Leyendo archivo links observatorio
     * 2024-02-05
     */
    function update_rows_links($readDrive = 0)
    {
        $updatedRows = [];
        $qtyUpdated = 0;
        
        $this->load->model('Observatorio_model');
        $filePath = $this->Observatorio_model->create_links_json($readDrive);

        // Verificar si el archivo existe
        if (file_exists($filePath)) {
            $jsonLinks = file_get_contents($filePath);
            $arrLinks = json_decode($jsonLinks, true);
            
            if ($arrLinks != null) {
                foreach ($arrLinks as $key => $link) {
                    $aRow = $this->Observatorio_model->link_to_post_row($link);

                    $condition = "code = {$aRow['code']} AND type_id = 138";
                    $savedId = $this->Db_model->save('posts', $condition, $aRow);
                    
                    $updatedRows[$savedId] = $savedId;
                    $qtyUpdated += 1;
                }
            }
        }

        $data['file_path'] = $filePath;
        $data['qty_updated'] = $qtyUpdated;
        $data['results'] = $updatedRows;
        $data['status'] = 1;
        $data['message'] = 'Registros actualizados en posts:links ' . $qtyUpdated;

        //Salida JSON
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }
}