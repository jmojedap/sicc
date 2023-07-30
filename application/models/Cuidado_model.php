<?php
class Cuidado_model extends CI_Model{

    /**
     * Crear el archivo JSON de acciones leído desde Google Drive
     * 2023-07-09
     */
    function create_acciones_json($readDrive = 0)
    {
        $filePath = PATH_CONTENT . 'mecc/ehc_acciones.json';

        if ($readDrive == 1) {
            $fileId = '1GvmA_N6BJ3wjfsRMCocIHj3HoXGM_L4K7xH1foWk0RU';
            $gid = '1891263053';    //Hoja export
            $this->load->library('Google_sheets');
            $arrAcciones = $this->google_sheets->sheetToArray($fileId, $gid);
    
            $jsonAcciones = json_encode($arrAcciones, JSON_PRETTY_PRINT);
            file_put_contents($filePath, $jsonAcciones);
        }

        return $filePath;
    }
}