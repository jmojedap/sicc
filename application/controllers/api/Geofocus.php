<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Geofocus extends CI_Controller{

// Variables generales
//-----------------------------------------------------------------------------
    public $url_controller = URL_API . 'geofocus/';

// Constructor
//-----------------------------------------------------------------------------
    
    function __construct() 
    {
        parent::__construct();
        $this->load->model('Geofocus_model');
        date_default_timezone_set("America/Bogota");    //Para definir hora local
    }
    
    function index($accion_id = NULL)
    {
        if ( is_null($accion_id) ) {
            redirect("app/geofocus/explorar/");
        } else {
            redirect("app/geofocus/info/{$accion_id}");
        }
    }

// CRUD Priorizaciones
//-----------------------------------------------------------------------------

    /**
     * Listado de priorizaciones
     * 2024-10-17
     */
    function get_priorizaciones()
    {
        $priorizaciones = $this->Geofocus_model->get_priorizaciones();
        $data['list'] = $priorizaciones->result();
        //Salida JSON
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    /**
     * AJAX JSON
     * Crear o actualizar el registro de una priorización
     * 2024-10-17
     */
    function save_priorizacion()
    {
        $data = $this->Geofocus_model->save_priorizacion();
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    /**
     * AJAX
     * Eliminar un registro, devuelve la cantidad de registros eliminados
     * 2024-10-28
     */
    function delete_priorizacion($priorizacionId, $creatorId)
    {
        $condition = "id = {$priorizacionId} AND creator_id = {$creatorId}";
        
        $this->db->where($condition);
        $rows = $this->db->get('gf_priorizaciones');
        $data['qty_deleted'] = 0;

        foreach ($rows->result() as $row) {
            $data['qty_deleted'] += $this->Geofocus_model->delete($row->id);
        }
        
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }


// Ejecución de cálculos
//-----------------------------------------------------------------------------

    /**
     * Calcular la priorización
     * 2024-09-14
     */
    function calcular_priorizacion()
    {
        // Obtener el contenido JSON enviado por POST
        $jsonData = file_get_contents("php://input");
        $settings = json_decode($jsonData, true);

        // Verificar si la decodificación fue exitosa
        if (json_last_error() === JSON_ERROR_NONE) {
            // Proceso para organizar los datos
            $data = $this->Geofocus_model->calcularPriorizacion($settings);

            // Preparar los datos para insertar en la base de datos
            $aRow['configuracion'] = json_encode($settings['variables']); // Guardar el JSON original
            $aRow['id'] = $settings['priorizacion']['id'];
            $data['saved_id'] = $this->Geofocus_model->save_priorizacion($aRow);
        } else {
            $data = [
                'status' => 'error',
                'message' => 'Datos JSON inválidos'
            ];
        }
    
        //Salida JSON
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    /**
     * Actualizar el valor de la gf_territorios_valor.valor_normalizado
     * En una escala estandarizada mediante el método Z-score
     * 2024-10-12
     */
    function normalizar_variable($variableId)
    {
        $data = $this->Geofocus_model->normalizarValores('variable_id', $variableId);

        //Salida JSON
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    function get_variable_valores($field = 'priorizacion_id', $fieldValue = 1)
    {
        $this->db->select('gf_territorios.poligono_id AS code, gf_territorios.nombre AS name, gf_territorios_valor.valor AS value');
        $this->db->join('gf_territorios', 'gf_territorios.poligono_id = gf_territorios_valor.poligono_id', 'left');
        $this->db->where($field, $fieldValue);
        $this->db->order_by('gf_territorios_valor.valor', 'DESC');
        $this->db->limit(1200);
        $valores = $this->db->get('gf_territorios_valor');

        $data['valores'] = $valores->result();
        $data['summary'] = $this->pml->field_summary($valores, 'value');
        //Salida JSON
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

    /**
     * Obtener descripción en texto de la parametrización de la priorización del usuario
     * 2024-11-23
     */
    function get_descripcion($priorizacionId) {
        $apiKey = 'AIzaSyCcelTenQpGgFCzbY66kI7st8qk-Sc_J0A';
        $model_id = "gemini-2.0-flash-lite";
        $url = "https://generativelanguage.googleapis.com/v1beta/models/{$model_id}:generateContent?key=" . $apiKey;

        $instruction = 'Genera un párrafo que describa y resuma en lenguaje natural la parametrización 
            que un usuario realizó de una herramienta web de datos que realiza priorización geográfica 
            de barrios de bogotá mediante la ponderación de diferentes variables culturales, demográficas 
            y sociales. A cada variable se le asigna uno de dos tipo de priorización
            (1. priorizar valores altos, 2. Priorizar valores bajos), 
            y también se le asigna un peso o ponderación a cada variable, que va de 0 a 100.\nPara generar 
            el texto ordena la descripción mencionando primero las variables a las que se les asignó mayor puntaje. 
            Cuando se mencionen las ponderaciones no deben hacerse como porcentaje sino como puntos.
            El párrafo será utilizado en un informe o reporte de análisis geográfico y debe iniciar 
            con \"La priorización geográfica realizada ...\"\nDatos de cada variable:\n
            1. Nombre de la variable\n
            2. Descripción de la variable\n
            3. Tipo de priorización seleccionada (Valores altos o valores bajos)\n
            4. Peso/Ponderación asignada a la variable.';
        $inputUser = $this->input->post('texto_parametrizacion');
    
        $payload = [
            "contents" => [
                [
                    "role" => "user",
                    "parts" => [
                        [
                            "text" => "Parametrización realizada por el usuario:\n{$inputUser}"
                        ]
                    ]
                ]
            ],
            "systemInstruction" => [
                "role" => "user",
                "parts" => [
                    [
                        "text" => $instruction
                    ]
                ]
            ],
            "generationConfig" => [
                "temperature" => 1,
                "topK" => 40,
                "topP" => 0.95,
                "maxOutputTokens" => 8192,
                "responseMimeType" => "text/plain"
            ]
        ];
    
        $jsonData = json_encode($payload);
    
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
        ]);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    
        $response = curl_exec($ch);
        $arrResponse = json_decode($response,true);
        $descripcion = $arrResponse['candidates'][0]['content']['parts'][0]['text'] ?? '(Descripción no disponible)';
        
        /* PARA PRUEBAS */
        //$descripcion = 'La priorización geográfica realizada se basó en la ponderación de siete variables. Las variables con mayor peso fueron: "Coeficiente expansión" (83 puntos), que prioriza barrios con mayor proporción de área de expansión; "Distancia a estaciones de Transmilenio" (80 puntos), priorizando barrios con menor distancia a estaciones; y "Subíndice de espacio público 2023" (75 puntos), priorizando barrios con valores altos en este subíndice. "Conteo homicidios por barrio" (67 puntos) también tuvo una ponderación importante, priorizando barrios con mayor número de homicidios. Las variables restantes fueron: "Área" (50 puntos), priorizando barrios con menor área; "Equipamientos Culturales" (50 puntos), priorizando barrios con mayor cantidad de equipamientos; y "Subíndice de cultura política y ciudadanía 2023" (21 puntos), priorizando barrios con valores bajos en este subíndice. (TTT)';
        //$arrResponse = ['status' => 'Respuesta dummy'];
        /** FIN PARA PRUEBAS */

        $aRow['descripcion_generada'] = $descripcion;

        curl_close($ch);

        $aRow['updater_id'] = $this->session->userdata('user_id');
        $aRow['updated_at'] = date('Y-m-d H:i:s');

        $data['descripcion_generada'] = $aRow['descripcion_generada'];
        $data['saved_id'] = $this->Db_model->save('gf_priorizaciones', "id = {$priorizacionId}", $aRow);
        $data['response'] = $arrResponse;
    
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }

}