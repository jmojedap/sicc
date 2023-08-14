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

    /**
     * Actualiza los datos en la tabla mecc_acciones_detalles registrando los usuarios
     * participantes en una acción tomando los valores del campo mecc_acciones.participantes_equipo
     * 2023-08-12
     */
    function update_acciones_staff()
    {
        //Eliminar detalles actuales
            $this->db->where('tipo_detalle', 150);
            $this->db->delete('mecc_acciones_detalle');
            $qty_deleted = $this->db->affected_rows();

        //Leer acciones de escuela
            $this->db->select('id, participantes_equipo, fecha');
            $this->db->where('estrategia', 102);    //Escuela de cuidado
            $acciones = $this->db->get('mecc_acciones');

        //Recorrer acciones
            $qty_rows_staff = 0;
            foreach ($acciones->result() as $accion) {
                $personas = explode(',',$accion->participantes_equipo);
                foreach ($personas as $persona) {
                    if ( strlen($persona) > 3 ) {
                        $aRow['nombre'] = strtolower(trim($persona));
                        $aRow['accion_id'] = $accion->id;
                        $aRow['created_at'] = $accion->fecha;
                        $saved_id = $this->save_accion_staff($aRow);
                        if ( $saved_id > 0 ) $qty_rows_staff++;
                    }
                }
            }

        $qty_rows = $acciones->num_rows();

        $data['status'] = 1;
        $data['message'] = "Participaciones registradas del Staff: {$qty_rows_staff}";

        return $data;
    }

    /**
     * Guardar registro de participación de un integrante del staff de la estrategia
     * en una acción
     * 2023-08-12
     */
    function save_accion_staff($aRow)
    {
        $aRow['tipo_detalle'] = 150;
        $aRow['updated_at'] = date('Y-m-d H:i:s');
        $this->db->insert('mecc_acciones_detalle', $aRow);
        $saved_id = $this->db->insert_id();
        return $saved_id;
        
    }
}