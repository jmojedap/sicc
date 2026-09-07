<?php
class Geofocus_model extends CI_Model{

    /**
     * Datos básicos de una priorización
     */
    function basic($priorizacionId)
    {
        $row = $this->Db_model->row_id('gf_priorizaciones', $priorizacionId);

        $data['row'] = $row;
        //$data['type_folder'] = $this->type_folder($row->type_id);
        $data['head_title'] = $data['row']->nombre;
        $data['view_a'] = $this->views_folder . 'priorizacion_v';
        //$data['nav_2'] = $data['type_folder'] . 'menu_v';

        return $data;
    }

// EXPLORE FUNCTIONS
//-----------------------------------------------------------------------------

    /**
     * Segmento Select SQL, con diferentes formatos, consulta de tablas
     * 2025-03-13
     */
    function select($format = 'general')
    {
        $arr_select['export_territorios_valor'] = 'orden, gf_territorios.poligono_id, gf_territorios.nombre,
            tipo_territorio, descripcion, localidad, cod_localidad, upz,
            variable_key AS clave_priorizacion, valor_normalizado AS puntaje_calculado, ';
        $arr_select['export_territorios_variable'] = 'orden, gf_territorios.poligono_id, gf_territorios.nombre,
            tipo_territorio, descripcion, localidad, cod_localidad, upz,
            variable_key AS variable, valor, valor_normalizado, ';

        return $arr_select[$format];
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
            $arr_row['creator_id'] = $this->session->userdata('user_id');
            $arr_row['created_at'] = date('Y-m-d H:i:s');
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
        // Obtener las variables seleccionadas para esta solicitud de priorización.
        $variables = $settings['variables'];

        // Organizar los parámetros de cálculo por ID de variable para poder
        // consultar rápidamente su peso y el sentido de la priorización.
        $params['puntajes'] = array_column($variables, 'puntaje', 'id');
        $params['tipos_priorizacion'] = array_column($variables, 'tipo_priorizacion', 'id');    //Directa o inversa
        $params['sum_puntajes'] = array_sum($params['puntajes']);   //Para hacer división final
        // Construir el filtro SQL que limita el cálculo a las variables seleccionadas.
        $params['condition'] = $this->getVariablesCondition($variables);

        //Identificar territorios para procesar
        $this->db->select('id, poligono_id, nombre');
        $this->db->where('key_capa', $settings['priorizacion']['key_capa']);
        $territorios = $this->db->get('gf_territorios');

        $arrTerritorios = [];
        // Preparar los datos comunes que tendrán todos los resultados de la priorización.
        $aRow = $this->getRowBaseTerritoriosValor($settings['priorizacion']);

        // Recorrer cada territorio, calcular valor ponderado y guardar.
        foreach ($territorios->result_array() as $rowTerritorio) {
            $valorPonderado = $this->calcularValorPonderado($rowTerritorio, $params);
            $rowTerritorio['valor'] = $valorPonderado;

            //Completar row y guardar en DB
            $aRow['territorio_id'] = $rowTerritorio['id'];
            $aRow['poligono_id'] = $rowTerritorio['poligono_id'];
            $aRow['valor'] = $valorPonderado;
            // Si ya existe un resultado para el territorio y la priorización, se
            // actualiza; en caso contrario, el método save crea el registro.
            $condition = "territorio_id = {$aRow['territorio_id']} AND priorizacion_id = {$aRow['priorizacion_id']}";
            $rowTerritorio['row_id'] = $this->Db_model->save('gf_territorios_valor', $condition, $aRow);

            $arrTerritorios[] = $rowTerritorio;
        }

        // Estandarizar los resultados calculados para que puedan compararse entre sí.
        $this->normalizarValores('priorizacion_id', $settings['priorizacion']['id']);

        //Resumen para dar respuesta
        // Asignar la posición de cada territorio según su valor de priorización.
        $data['cantidad_ordenados'] = $this->actualizarOrden('priorizacion_id', $aRow['priorizacion_id']);
        $data['variables'] = $variables;
        $data['params'] = $params;
        
        // Recuperar el resultado final ya normalizado y ordenado para la respuesta.
        $territoriosPriorizados = $this->getPriorizacion($aRow['priorizacion_id']);
        $data['territorios'] = $territoriosPriorizados->result();

        return $data;
    }

    /**
     * Para un territorio específico calcula el valor de combinación
     * de los valores de las variables ponderadas (puntajes)
     * 2024-09-14
     * @param object $rowTerritorio :: fila del territorio que se va a procesar
     * @param array $params :: listado de parámetros y variables de cálculo
     * @return float $valorCalculado :: Valor calculado tras combiación de valores, pesos y tipos
     */
    function calcularValorPonderado($rowTerritorio, $params)
    {
        // Consultar únicamente los valores de las variables seleccionadas que
        // pertenecen al territorio que se está procesando.
        $this->db->where($params['condition']);
        $this->db->where('territorio_id', $rowTerritorio['id']);
        $valores = $this->db->get('gf_territorios_valor');

        // Obtener los pesos y el sentido directo o inverso indexados por variable.
        $puntajes = $params['puntajes'];
        $tiposPriorizacion = $params['tipos_priorizacion'];

        // Acumular para cada variable: valor normalizado × peso × sentido de
        // priorización. El sentido permite aumentar o invertir su aporte al total.
        $valorCalculado = 0;
        foreach ($valores->result() as $rowValor) {
            $valorCalculado += $rowValor->valor_normalizado * $puntajes[$rowValor->variable_id]
                * $tiposPriorizacion[$rowValor->variable_id];
        }

        // Dividir por la suma de los pesos para obtener el promedio ponderado.
        // La validación evita una división por cero cuando no hay puntajes acumulados.
        if ( $params['sum_puntajes'] != 0) {
            $valorCalculado = $valorCalculado / $params['sum_puntajes'];
        }

        return $valorCalculado;
    }

    /**
     * Devuelve condición SQL para filtrar las variables que se selecionarios para
     * la priorización
     * @param array $variables :: Variables y sus valores seleccionados
     * @return string $condition :: Condición WHERE SQL
     */
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
        $aRow['variable_key'] = $priorizacion['clave'];
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
        $this->db->order_by('territorio_id', 'ASC');
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

    /**
     * Devuelve listado de territorios con sus valores calculados para una priorización específica
     * @param int $priorizacionId :: ID de la priorización a consultar
     * @param int $limit :: Límite de registros a devolver
     * @return object :: Resultado de la consulta
     * 2024-12-15
     */
    function getPriorizacion($priorizacionId, $limit = 15)
    {
        $this->db->select('gf_territorios.*, 
            gf_territorios_valor.variable_id, variable_key, priorizacion_id, valor, valor_normalizado, orden');
        $this->db->where('priorizacion_id', $priorizacionId);
        $this->db->join('gf_territorios', 'gf_territorios.id = gf_territorios_valor.territorio_id', 'left');
        $this->db->order_by('orden', 'ASC');
        $this->db->order_by('valor', 'DESC');
        $this->db->limit($limit);
        $territorios = $this->db->get('gf_territorios_valor');

        return $territorios;
    }

    /**
     * Actualizar el valor del campo gf_territorios_valor.valor_normalizado
     * En una escala estandarizada mediante el método Z-score
     * 2024-10-12
     */
    function normalizarValores($field, $fieldValue = 0)
    {
        //Valor por defecto
        $data = ['status' => 0, 'message' => 'No se ejecutó la normalización'];

        //Seleccionar valores
        $this->db->where($field, $fieldValue);
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
                'message' => 'Valores normalizados',
                'valorSummary' => $valorSummary,
                'affectedRows' => $this->db->affected_rows(),
                'valoresCalculados' => $valoresCalculados
            ];
        }
    
        return $data;
    }

// EXPORT
//-----------------------------------------------------------------------------

    /**
     * Query para exportar
     * 2024-11-18
     */
    function query_export_territorios_valor($condition)
    {
        //Select
        $select = $this->select('export_territorios_valor');
        $this->db->select($select);
        $this->db->where($condition);
        $this->db->join('gf_territorios', 'gf_territorios.poligono_id = gf_territorios_valor.poligono_id', 'left');
        $this->db->order_by('orden', 'ASC');
        $query = $this->db->get('gf_territorios_valor', 10000);  //Hasta 10.000 registros

        return $query;
    }

    /**
     * Query para exportar una variable
     * 2025-03-13
     */
    function query_export_territorios_variable($condition)
    {
        //Select
        $select = $this->select('export_territorios_variable');
        $this->db->select($select);
        $this->db->where($condition);
        $this->db->join('gf_territorios', 'gf_territorios.poligono_id = gf_territorios_valor.poligono_id', 'left');
        $this->db->order_by('orden', 'ASC');
        $query = $this->db->get('gf_territorios_valor', 10000);  //Hasta 10.000 registros

        return $query;
    }

// GESTIÓN DE VARIABLES
//-----------------------------------------------------------------------------

    /**
     * Devuelve listado de capas base disponibles para la priorización
     * 2026-08-31
     * @return array $gf_capas_base :: Listado de capas base disponibles
     */
    function capas_base()
    {
        $gf_capas_base = [
            [
                'id' => 2,
                'nombre' => 'Barrios Bogotá 2025',
                'key_capa' => 'sector_catastral_0526',
                'cantidad_poligonos' => 1230,
                'archivo_mapa' => 'sector_catastral_0526.json',
                'property_key' => 'poligono_id',
            ],
            [   'id' => 1,
                'nombre' => 'Barrios Bogotá 2023',
                'key_capa' => 'barrios_planeacion_2023',
                'cantidad_poligonos' => 1169,
                'archivo_mapa' => 'barrios_bogota_geofocus_urbano.json',
                'property_key' => 'ID_BARRIO',
            ],
        ];
        return $gf_capas_base;
    }

    /**
     * Devuelve listado de variables y cantidad de registros en la tabla gf_territorios_valor
     * @return object $variables :: Listado de variables con cantidad de registros
     * 2026-08-31
     */
    function get_variables($condition = NULL)
    {
        $this->db->select('gf_variables.*');
        if ( ! is_null($condition) ) {
            $this->db->where($condition);
        }
        $this->db->order_by('gf_variables.id', 'ASC');
        $variables = $this->db->get('gf_variables');

        return $variables;
    }

    /**
     * Actualizar el resumen estadístico de una variable en la tabla gf_variables
     * @param int $variable_id :: ID de la variable a actualizar
     * @return array $data :: Detalles de la actualización
     * 2026-08-31
     */
    function update_variable_summary($variable_id)
    {
        // Valor inicial por defecto
        $data = ['status' => 0, 'message' => 'No se actualizó el resumen estadístico', 'saved_id' => 0];

        $this->db->select('gf_territorios_valor.valor AS value');
        $this->db->where('variable_id', $variable_id);
        $valores = $this->db->get('gf_territorios_valor');

        $summary = $this->pml->field_summary($valores, 'value');

        $arr_row['minimo'] = $summary['min'];
        $arr_row['maximo'] = $summary['max'];
        $arr_row['media'] = $summary['avg'];
        $arr_row['desviacion_estandar'] = $summary['std_dev'];
        $arr_row['cantidad_valores'] = $summary['count'];

        // Actualizar valores
        $data['saved_id'] = $this->Db_model->save('gf_variables', "id = {$variable_id}", $arr_row);

        if ( $data['saved_id'] > 0 ) {
            $data['status'] = 1;
            $data['message'] = 'Resumen estadístico actualizado';
        }

        return $data;
    }

    /**
     * Importa valores de variable a la base de datos
     * @param array $arr_sheet :: Datos de la hoja de excel con valores de variable
     * @param object $row_variable :: Fila de la variable a la que se importan los valores
     * @return array $data :: Detalles del resultado de la importación
     * 2026-09-01
     */
    function import_variable_values($arr_sheet, $row_variable)
    {
        $data = array('qty_imported' => 0, 'results' => array());
        
        foreach ( $arr_sheet as $key => $row_data )
        {
            $data_import = $this->import_variable_values_row($row_data, $row_variable);
            $data['qty_imported'] += $data_import['status'];
            $data['results'][$key + 2] = $data_import;
        }
        
        return $data;
    }

    /**
     * Realiza la importación de una fila del archivo excel. Valida los campos, crea registro
     * en la tabla gf_territorios_valor y devuelve el resultado de la operación
     * @param array $row_data :: Datos de la fila del archivo excel
     * 2026-09-01
     */
    function import_variable_values_row($row_data, $row_variable)
    {
        //Validar
            $error_text = '';
                            
            if ( strlen($row_data[0]) == 0 ) { $error_text = 'La casilla `poligono_id` está vacía. '; }
            if ( strlen($row_data[1]) == 0 ) { $error_text = 'La casilla `valor` está vacía. '; }

        //Si no hay error
            if ( $error_text == '' )
            {                
                $arr_row['territorio_id'] = $row_data[0];
                $arr_row['variable_id'] = $row_variable->id;
                $arr_row['variable_key'] = $row_variable->clave;
                $arr_row['priorizacion_id'] = 0;
                $arr_row['valor'] = $row_data[1];
                $arr_row['valor_normalizado'] = $row_data[1];
                $arr_row['orden'] = 1;

                //Guardar en tabla gf_territorios_valor, si ya existe, se actualiza
                $condition = "territorio_id = {$arr_row['territorio_id']} AND variable_id = {$arr_row['variable_id']}";
                $imported_id = $this->Db_model->save('gf_territorios_valor', $condition, $arr_row);

                $data = array('status' => 1, 'text' => 'Fila importada: ' . $imported_id, 'imported_id' => $imported_id);
            } else {
                $data = array('status' => 0, 'text' => $error_text, 'imported_id' => 0);
            }

        return $data;
    }

    /**
     * Función para actualizar el campo gf_territorios_valor.poligono_id de la tabla gf_territorios_valor, a partir del campo gf_territorios.poligono_id
     * Esta actualización es necesaria para relacionar el polígono en la base de datos con el polígono en el mapa geojson, ya que el polígono puede cambiar de ID en la tabla gf_territorios, y se requiere mantener la relación con los valores de la variable.
     * 2026-09-04
     * @param int $variable_id :: ID de la variable a actualizar    
     * @return int $affected_rows :: Cantidad de registros actualizados
     */
    function actualizar_poligono_id($variable_id)
    {
        $sql = "UPDATE gf_territorios_valor AS v
            JOIN gf_territorios AS t ON v.territorio_id = t.id
            SET v.poligono_id = t.poligono_id
            WHERE v.variable_id = {$variable_id}";

        $this->db->query($sql);
        return $this->db->affected_rows();
    }   
}