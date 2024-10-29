<?php
class Geofocus_model extends CI_Model{

    /**
     * Datos básicos de una priorización
     */
    function basic($priorizacionId)
    {
        $row = $this->Db_model->row_id('priorizaciones', $priorizacionId);

        $data['row'] = $row;
        $data['type_folder'] = $this->type_folder($row->type_id);
        $data['head_title'] = $data['row']->post_name;
        $data['view_a'] = $this->views_folder . 'post_v';
        $data['nav_2'] = $data['type_folder'] . 'menu_v';

        return $data;
    }

// CRUD PRIORIZACIONES
//-----------------------------------------------------------------------------

    /**
     * Devolver listado de priorizaciones en la base de datos
     * 2024-10-17
     */
    function get_priorizaciones()
    {
        $this->db->select('gf_priorizaciones.*, users.username AS creator_username');
        $this->db->limit(150);
        $this->db->order_by('updated_at', 'DESC');
        $this->db->join('users', 'gf_priorizaciones.creator_id = users.id', 'left');
        
        $priorizaciones = $this->db->get('gf_priorizaciones');
        return $priorizaciones;
    }

    /**
     * Guardar un registro en la tabla gf_priorizaciones
     * 2024-10-28
     */
    function save_priorizacion($arr_row = null)
    {
        //Verificar si hay array con registro
        if ( is_null($arr_row) ) $arr_row = $this->Db_model->arr_row();

        //Verificar si tiene id definido, insertar o actualizar
        if ( ! isset($arr_row['id']) || $arr_row['id'] == 0 ) 
        {
            //No existe, insertar
            $this->db->insert('gf_priorizaciones', $arr_row);
            $priorizacionId = $this->db->insert_id();
        } else {
            //Ya existe, editar
            $priorizacionId = $arr_row['id'];
            unset($arr_row['id']);

            $this->db->where('id', $priorizacionId)->update('gf_priorizaciones', $arr_row);
        }

        $data['saved_id'] = $priorizacionId;
        return $data;
    }

// ELIMINAR PRIORIZACIÓN
//-----------------------------------------------------------------------------
    
    /**
     * Verifica si el usuario en sesión tiene permiso para eliminar un registro
     * tabla gf_priorización
     * 2024-10-28
     */
    function deleteable($priorizacionId)
    {
        $row = $this->Db_model->row_id('gf_priorizaciones', $priorizacionId);

        $deleteable = 0;    //Valor por defecto

        //Es Administrador
        if ( in_array($this->session->userdata('role'), [1,2,3]) ) {
            $deleteable = 1;
        }

        //Es el creador
        if ( $row->creator_id = $this->session->userdata('user_id') ) {
            $deleteable = 1;
        }

        return $deleteable;
    }

    /**
     * Eliminar un post de la base de datos, se eliminan registros de tablas
     * relacionadas
     * 2022-08-20
     */
    function delete($priorizacionId)
    {
        $qty_deleted = 0;

        if ( $this->deleteable($priorizacionId) ) 
        {
            //Tablas relacionadas
                $this->db->where('priorizacion_id', $priorizacionId)->delete('gf_territorios_valor');
                //$this->db->where('priorizacionId', $priorizacionId)->delete('post_meta');
            
            //Tabla principal
                $this->db->where('id', $priorizacionId)->delete('gf_priorizaciones');

            $qty_deleted = $this->db->affected_rows();  //De la última consulta, tabla principal
        }

        return $qty_deleted;
    }

// CÁLCULOS DE PRIORIZACION
//-----------------------------------------------------------------------------

    /**
     * Calcular la priorización
     * @param array $settings :: Variables de configuración de la solicitud de cálculo
     * @return array $data :: Detalles de resultado del procesamiendo
     * 2024-09-14
     */
    function calcularPriorizacion($settings)
    {
        $variables = $settings['variables'];

        $params['puntajes'] = $this->getArrayPuntajes($variables);  //Simplificar variable_id => puntaje
        $params['tipos_priorizacion'] = array_column($variables, 'tipo_priorizacion', 'id');    //Directa o inversa
        $params['sum_puntajes'] = array_sum($params['puntajes']);   //Para hacer división final
        $params['condition'] = $this->getVariablesCondition($variables);

        //Identificar territorios para procesar
        $this->db->select('id, poligono_id, nombre');
        $this->db->where('key_capa', 'barrios_planeacion_2023'); //PENDIENTE AJUSTE
        $territorios = $this->db->get('gf_territorios');

        $arrTerritorios = [];
        $aRow = $this->getRowBaseTerritoriosValor($settings['priorizacion']);

        // Recorrer cada territorio, calcular valor ponderado y guardar.
        foreach ($territorios->result_array() as $rowTerritorio) {
            $valorPonderado = $this->calcularValorPonderado($rowTerritorio, $params);
            $rowTerritorio['valor'] = $valorPonderado;

            //Completar row y guardar en DB
            $aRow['poligono_id'] = $rowTerritorio['poligono_id'];
            $aRow['valor'] = $valorPonderado;
            $condition = "poligono_id = {$aRow['poligono_id']} AND priorizacion_id = {$aRow['priorizacion_id']}";
            $rowTerritorio['row_id'] = $this->Db_model->save('gf_territorios_valor',$condition, $aRow);

            $arrTerritorios[] = $rowTerritorio;
        }

        //Resumen para dar respuesta
        $data['cantidad_ordenados'] = $this->actualizarOrden('priorizacion_id', $aRow['priorizacion_id']);
        $data['variables'] = $variables;
        $data['params'] = $params;
        
        $territoriosPriorizados = $this->getPriorizacion($aRow['priorizacion_id']);
        $data['territorios'] = $territoriosPriorizados->result();

        return $data;
    }

    /**
     * Para un territorio específico calcula el valor de combinación
     * de los valores de las variables ponderadas (puntajes)
     * 2024-09-14
     * @param object $rowTerritorio :: fila del territorio que se va a procesar
     * @param array $variables :: listado de variables
     * @return float $valorCalculado :: Valor calculado tras combiación de valores, pesos y tipos
     */
    function calcularValorPonderado($rowTerritorio, $params)
    {
        $this->db->where($params['condition']);
        $this->db->where('poligono_id', $rowTerritorio['poligono_id']);
        $valores = $this->db->get('gf_territorios_valor');

        $puntajes = $params['puntajes'];
        $tiposPriorizacion = $params['tipos_priorizacion'];

        $valorCalculado = 0;
        foreach ($valores->result() as $rowValor) {
            $valorCalculado += $rowValor->valor_normalizado * $puntajes[$rowValor->variable_id]
                * $tiposPriorizacion[$rowValor->variable_id];
        }

        if ( $params['sum_puntajes'] != 0) {
            $valorCalculado = $valorCalculado / $params['sum_puntajes'];
        }

        return $valorCalculado;
    }

    /**
     * Array con los puntajes seleccionados para ponderar cada variable
     * con llave variable_id y valor puntaje
     * @param array $variables :: array completo de una variable
     * @return array $puntajes :: array simplificado solo id => puntaje
     * 2024-09-14
     */
    function getArrayPuntajes($variables)
    {
        $puntajes = array_column($variables, 'puntaje', 'id');
        return $puntajes;
    }

    function getVariablesCondition($variables)
    {
        $ids = array_column($variables, 'id');
        $condition = 'variable_id IN (' . implode(',', $ids) . ')';

        return $condition;
    }

    /**
     * Array base del registro para la tabla gf_territorios_valor
     * 2024-10-15
     */
    function getRowBaseTerritoriosValor($priorizacion)
    {
        $aRow['variable_id'] = 0;
        $aRow['variable_key'] = $priorizacion['slug'];
        $aRow['priorizacion_id'] = $priorizacion['id'];

        return $aRow;
    }

    /**
     * Actualiza masivamente la columna gf_territorios_valor.orden con valores
     * consecutivos de 1 a la cantidad de registros
     * @param string $campo :: Nombre del campo por el cual se hará el orden,
     *  puede ser por variable o priorización
     * @param int $valor :: valor del ID de variable o ID de priorización que
     * se requiere ordenar
     * @return int $affected_rows :: Cantidad de registros que se actualizan
     * 
     * 2024-09-14
     */
    function actualizarOrden($campo, $valor)
    {
        $this->db->select('id');
        $this->db->where($campo, $valor);
        $this->db->order_by('valor', 'DESC');
        $this->db->order_by('poligono_id', 'ASC');
        $valores = $this->db->get('gf_territorios_valor');

        $orden = 1;
        $rows = [];
        foreach ($valores->result() as $rowValor) {
            $aRow['id'] = $rowValor->id;
            $aRow['orden'] = $orden;
            $rows[] = $aRow;
            $orden++;
        }

        $this->db->update_batch('gf_territorios_valor', $rows, 'id');

        return $this->db->affected_rows();
    }

    function getPriorizacion($priorizacionId)
    {
        $this->db->select('gf_territorios.*, 
            gf_territorios_valor.variable_id, variable_key, priorizacion_id, valor, valor_normalizado, orden');
        $this->db->where('priorizacion_id', $priorizacionId);
        $this->db->join('gf_territorios', 'gf_territorios.poligono_id = gf_territorios_valor.poligono_id', 'left');
        $this->db->order_by('orden', 'ASC');
        $this->db->order_by('valor', 'DESC');
        $this->db->limit(10);
        $territorios = $this->db->get('gf_territorios_valor');

        return $territorios;
    }

    /**
     * Actualizar el valor de la gf_territorios_valor.valor_normalizado
     * En una escala estandarizada mediante el método Z-score
     * 2024-10-12
     */
    function normalizarVariable($variableId)
    {
        //Valor por defecto
        $data = ['status' => 0, 'message' => 'No se ejecutó la normalización'];

        //Seleccionar valores
        $this->db->where('variable_id', $variableId);
        $valores = $this->db->get('gf_territorios_valor');

        //Estadísticos de la variable
        $valorSummary = $this->pml->field_summary($valores, 'valor');

        //Array inicial vacío para calcular
        $valoresCalculados = [];
        
        if ( $valorSummary['std_dev'] > 0 )
        {
            //Recorrer valores y calcular valores estándar
            foreach ($valores->result() as $rowValor) {
                $aRow['id'] = $rowValor->id;
                //Estandarización Z-Score
                $aRow['valor_normalizado'] = ($rowValor->valor - $valorSummary['avg']) / $valorSummary['std_dev'];
                $valoresCalculados[] =$aRow;
            }
    
            // Actualización batch
            $this->db->update_batch('gf_territorios_valor', $valoresCalculados, 'id');

            //Preparación de respuesta
            $data = [
                'status' => 1,
                'message' => 'Variable normalizada',
                'valorSummary' => $valorSummary,
                'affectedRows' => $this->db->affected_rows(),
                'valoresCalculados' => $valoresCalculados
            ];
        }
    
        return $data;
    }
}