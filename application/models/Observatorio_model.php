<?php
class Observatorio_model extends CI_Model{

    function basic($accion_id)
    {
        $row = $this->Db_model->row_id('posts', $accion_id);

        $data['row'] = $row;
        $data['head_title'] = $data['row']->post_name;
        $data['view_a'] = $this->views_folder . 'contenido_v';
        $data['nav_2'] = $this->views_folder . 'menu_v';

        return $data;
    }

    /**
     * Crear el archivo JSON de links leído desde Google Drive
     * 2024-02-05
     */
    function create_links_json($readDrive = 0)
    {
        $filePath = PATH_CONTENT . 'observatorio/otros/links.json';

        if ($readDrive == 1) {
            $fileId = '1xULiZYp1bBlnPY9m5AdXefbom311MNxC0M-VQ8rNhd0';
            $gid = '0';    //Hoja export
            $this->load->library('Google_sheets');
            $arrLinks = $this->google_sheets->sheetToArray($fileId, $gid);
    
            $jsonLinks = json_encode($arrLinks, JSON_PRETTY_PRINT);
            file_put_contents($filePath, $jsonLinks);
        }

        return $filePath;
    }

    /**
     * Convertir registro de observatorio.links a post
     * 2024-02-05
     */
    function link_to_post_row($link)
    {
        $aRow['type_id'] = 138;
        $aRow['code'] = $link['num'];
        $aRow['post_name'] = $link['nombre'];
        $aRow['excerpt'] = $link['descripcion'];
        $aRow['keywords'] = $link['palabras_clave'];
        $aRow['date_1'] = $link['fecha_actualizacion'];
        $aRow['text_1'] = $link['link'];
        $aRow['text_2'] = $link['proyecto'];
        $aRow['text_3'] = $link['tipo'];
        $aRow['text_4'] = $link['tema'];
        $aRow['integer_1'] = $link['periodo'];
        $aRow['updater_id'] = 202341;
        $aRow['creator_id'] = 202341;
        $aRow['created_at'] = date('Y-m-d H:i:s');
        $aRow['updated_at'] = date('Y-m-d H:i:s');

        return $aRow;
    }
}