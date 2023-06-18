<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require 'vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Excel {

    /** ACTUALIZADA 2021-09-27 */

    /**
     * Convierte un listado de una hoja de cálculo en un array
     * Desde la columna A y la fila 2
     * 
     * @param type $file
     * @param type $sheet_name
     * @return array
     */
    public function get_array($file, $sheet_name)
    {
        //Valor inicial
        $data = array('status' => 0, 'arr_sheet' => array(), 'message' => 'Se presentó un error al leer el archivo');
        
        //Cargando archivo
        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        $reader->setLoadSheetsOnly($sheet_name);
        $spreadsheet = $reader->load($file);
        $worksheet = $spreadsheet->getActiveSheet();
        
        if ( ! is_null($worksheet) )
        {
            $data['status'] = 1;
            
            $end_column = $worksheet->getHighestColumn();  //Última columna con datos
            $end_row = $worksheet->getHighestRow();        //Última fila con datos
            $range = "A2:{$end_column}{$end_row}";
            
            $data['arr_sheet'] = $worksheet->rangeToArray($range, NULL, TRUE, FALSE);
            $data['message'] = 'Filas encontradas: ' . intval($end_row - 1);
        }
        
        return $data;
    }

    /**
     * Listado de columnas de una hoja de cálculo
     * Desde la columna A y en la fila 1
     * 2022-06-14
     */
    public function get_columns($sheet_name)
    {
        $file = $_FILES['file']['tmp_name'];             //Se crea un archivo temporal, no se sube al servidor, se toma el nombre temporal
        //Valor inicial
        $columns = array();
        
        //Cargando archivo
        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        $reader->setLoadSheetsOnly($sheet_name);
        $spreadsheet = $reader->load($file);
        $worksheet = $spreadsheet->getActiveSheet();
        
        if ( ! is_null($worksheet) )
        {
            $end_column = $worksheet->getHighestColumn();  //Última columna con datos
            $range = "A1:{$end_column}1";
            $array = $worksheet->rangeToArray($range, NULL, TRUE, FALSE);
            if ( count($array) > 0 ) {
                $columns = $array[0];
            }
        }
        
        return $columns;
    }

    public function arr_sheet_default($sheet_name)
    {
        $file = $_FILES['file']['tmp_name'];             //Se crea un archivo temporal, no se sube al servidor, se toma el nombre temporal
        $data = $this->get_array($file, $sheet_name);
        
        return $data;
    }
    
    /**
     * Genera un objeto excel file, a partir de un query CodeIgniter
     * 2021-09-27
     */
    public function file_query($data)
    {
        $spreadsheet = new Spreadsheet();

        // Establecer propiedadess del documento
        $spreadsheet->getProperties()
            ->setCreator('Pacarina Media Lab')
            ->setLastModifiedBy('Pacarina Media Lab')
            ->setTitle($data['sheet_name']);

        //Encabezados
            $fields = $data['query']->list_fields();
            foreach ( $fields as $key => $field ) 
            {
                $field_title = str_replace('_',' ',$field);
                $spreadsheet->getActiveSheet()->setCellValueByColumnAndRow($key + 1, 1, $field_title);
            }
        
        //Valores
            $row_number = 2;
            foreach ( $data['query']->result() as $row ) 
            {
                foreach ( $fields as $key => $field ) {
                    $spreadsheet->getActiveSheet()->setCellValueByColumnAndRow($key + 1, $row_number, $row->$field);
                }
                $row_number++;
            }

        // Establecer nombre a worksheet
        $spreadsheet->getActiveSheet()
            ->setTitle($data['sheet_name']);

        // Objeto para crear archivo y guardar
        $writer = new Xlsx($spreadsheet);
        
        return $writer;
    }
}