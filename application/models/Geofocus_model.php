<?php
class Geofocus_model extends CI_Model{

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

    /**
     * Calcular la priorización
     * @param array $settings :: Variables de configuración de la solicitud de cálculo
     * @return array $data :: Detalles de resultado del procesamiendo
     * 2024-09-14
     */
    function calcularPriorizacion($settings)
    {
        $variables = $settings['variables'];

        $params['puntajes'] = $this->getArrayPuntajes($variables);
        $params['sum_puntajes'] = array_sum($params['puntajes']);
        $params['condition'] = $this->getVariablesCondition($variables);

        //Identificar terrotorios para procesar
        $this->db->select('id, poligono_id, nombre');
        $this->db->where('key_capa', 'barrios_planeacion_2023');
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
     * @param object $rowTerritorio :: fila del territorio de se va a procesar
     * @param array $variables :: listado de variables
     */
    function calcularValorPonderado($rowTerritorio, $params)
    {
        $this->db->where($params['condition']);
        $this->db->where('poligono_id', $rowTerritorio['poligono_id']);
        $valores = $this->db->get('gf_territorios_valor');

        $puntajes = $params['puntajes'];

        $valorCalculado = 0;
        foreach ($valores->result() as $rowValor) {
            $valorCalculado += $rowValor->valor_normalizado * $puntajes[$rowValor->variable_id];
        }

        if ( $params['sum_puntajes'] != 0) {
            $valorCalculado = $valorCalculado / $params['sum_puntajes'];
        }

        return $valorCalculado;
    }

    /**
     * Array con los puntajes seleccionados para cada variable
     * 2024-09-14
     */
    function getArrayPuntajes($variables)
    {
        $puntajes = [];
        foreach ($variables as $item) {
            $puntajes[$item['id']] = $item['puntaje'];
        }

        return $puntajes;
    }

    function getVariablesCondition($variables)
    {
        $ids = array_column($variables, 'id');
        $condition = 'variable_id IN (' . implode(',', $ids) . ')';

        return $condition;
    }


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
}