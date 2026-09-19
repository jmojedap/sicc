<?php
    // Estado actual de las variables de la priorización
    $variablesParametrizadas = '[]';
    if ( strlen($row->configuracion) > 0 ) {
        $variablesParametrizadas = $row->configuracion;
    }
?>

<script>
let priorizacionMap = null
let priorizacionMapReady = null
let priorizacionMapRequestId = 0
let priorizacionMapPopup = null
let priorizacionMapInteractionsReady = false
const priorizacionMapDataCache = new Map()
const priorizacionMapSourceId = 'priorizacion-base-source'
const priorizacionMapFillLayerId = 'priorizacion-base-fill'
const priorizacionMapLineLayerId = 'priorizacion-base-line'

// VueApp
//-----------------------------------------------------------------------------
var priorizacionApp = createApp({
    data(){
        return{
            section: 'variables',
            //section: 'mapa',
            loading: false,
            capaBase: <?= json_encode($capa_base) ?>,
            priorizacion: <?= json_encode($row) ?>,
            display: {
                descripcion: false
            },
            variablesParametrizadas: <?= $variablesParametrizadas ?>,
            currentTema: 'Cultura',
            currentVariableId: 0,
            currentVariable: {
                'id': 0, 'nombre': '',
                'descripcion': '',
                'tema': '',
                'anio_valores': '',
                'descripcion_calculo': '',
                'entidad': '',
                'unidad_medida': '',
                'minimo': '',
                'media': '',
                'desviacion_estandar': '',
                'color': ''
            },
            mapMode: 'priorizacion',
            mapLoading: false,
            mapError: '',
            mapScale: null,
            mapRotationKey: '',
            mapContentUrl: <?= json_encode(URL_CONTENT, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>,
            rotaciones: <?= json_encode(
                $rotaciones,
                JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT
            ) ?>,
            variables: <?= json_encode($variables) ?>,
            allSelected: false,
            territorios: <?= json_encode($territorios->result()) ?>,
            localidades: <?= json_encode($localidades) ?>,
            descripcion: {
                texto: `<?= $row->descripcion_generada ?>`,
                active: false,
                loading: false
            },
            arrTemas: <?= json_encode($arrTemas) ?>,
            userRole: APP_RID,
            userId: APP_UID,
        }
    },
    methods: {
        validateSubmit: function(){
            if ( this.validateForm() ) {
                this.submitForm()
            } else {
                toastr['warning']('No se pudo procesar la petición')
            }
        },
        submitForm: function(){
            this.loading = true
            this.section = 'territorios'
            this.territorios = []
            this.descripcion.texto = ''
            var payload = {
                'priorizacion': this.priorizacion,
                'variables': this.variablesActivas
            }
            axios.post(URL_API + 'geofocus/calcular_priorizacion/', payload, {
                headers: { 'Content-Type': 'application/json'}
            })
            .then(response => {
                this.loading = false
                this.mostrarTerritorios(response.data.territorios)
                this.actualizarMapa('territorios')
                this.descripcion.active = true //Para poder generar descripción
            })
            .catch( function(error) {console.log(error)} )
        },
        // Al recibir los territorios mostrarlos secuencialmente en tabla
        mostrarTerritorios: function(newTerritorios){
            // Índice para seguir el progreso de los elementos agregados
            let index = 0;

            // Utilizamos setInterval para agregar un elemento cada 250 ms
            const intervalo = setInterval(() => {
                // Verifica si hay elementos restantes en newTerritorios
                if (index < newTerritorios.length) {
                    // Agrega el elemento actual al array territorios
                    this.territorios.push(newTerritorios[index]);
                    console.log(`Agregado: ${JSON.stringify(newTerritorios[index])}`);
                    
                    // Incrementa el índice
                    index++;
                } else {
                    // Si se han agregado todos los elementos, detén el intervalo
                    clearInterval(intervalo);
                    console.log('Todos los elementos han sido agregados.');
                }
            }, 250); // Intervalo de 250 ms
        },
        updateVariable: function(){
            if ( this.currentVariableId ) this.setVariable(this.currentVariableId)
        },
        setVariable: function(variableId, newSection = 'mapa'){
            const selectedVariable = this.variables.find(variable => variable.id == variableId)
            if ( ! selectedVariable ) return

            this.currentVariableId = String(selectedVariable.id)
            this.currentVariable = selectedVariable
            this.currentTema = this.currentVariable.tema
            if ( newSection == 'mapa' ) {
                this.mapMode = 'variable'
                this.section = 'mapa'
                this.$nextTick(() => this.loadPrioritizationMap())
            } else {
                this.section = newSection
            }
        },
        startVariables: function(){
            this.variables.forEach(variable => {
                //Valores por defecto
                variable.active = false;
                variable.tipo_priorizacion = 1;

                //Buscar variable por clave en configuración guardada (variableParametrizda)
                //Si la encuentra asignarle los valores de configuración guardados.
                variableConfig = this.variablesParametrizadas.find(variableParametrizada => variableParametrizada['clave'] == variable['clave'])
                if ( variableConfig != undefined ) {
                    variable.puntaje = variableConfig.puntaje
                    variable.active = variableConfig.active
                    variable.tipo_priorizacion = variableConfig.tipo_priorizacion
                }
            });
        },
        validateForm: function(){
            if ( this.variablesActivas.length == 0 ) return false
            return true
        },
        toggleSelectAll: function(){
            this.variables.forEach(variable => {
                variable.active = this.allSelected;
            });
        },
        toggleActivateVariable: function(index){
            this.variables[index].active = !this.variables[index].active
        },
        setTipoPriorizacion: function(index, newValue){
            this.variables[index].tipo_priorizacion = newValue
        },
        recalcularVariable: function(variable){
            axios.get(URL_API + 'geofocus/recalcular_variable/' + variable.id)
            .then(response => {
                toastr['info']('Variable recalculada')
                console.log(response.data)
            })
            .catch(function(error) { console.log(error) })
        },
        setSection: function(newSection){
            if ( newSection == 'mapa' ) {
                this.showPrioritizationMap()
            } else {
                priorizacionMapRequestId += 1
                this.mapLoading = false
                this.section = newSection
            }
        },
        localidadValor: function(codLocalidad = '', field = 'nombre'){
            var localidadValor = '-'
            var localidad = this.localidades.find(row => row.cod_localidad == codLocalidad)
            if ( localidad != undefined ) localidadValor = localidad[field]
            return localidadValor
        },
        classSector: function(codLocalidad = ''){
            var sector = this.localidadValor(codLocalidad, field = 'sector')
            return this.textToClass(sector, 'sector')
        },
        variableClass: function(variable){
            if ( variable.active == true ) {
                if ( variable.tipo_priorizacion == 1 ) return 'table-info'
                if ( variable.tipo_priorizacion == -1 ) return 'table-warning'
            }
            return ''
        },
        textToClass: function(text, prefix = null){
            if ( prefix == null) {
                return Pcrn.textToClass(text)
            }
            return prefix + '-' + Pcrn.textToClass(text)
        },
        // Mapas
        //-----------------------------------------------------------------------------
        actualizarMapa: function(newSection){
            priorizacionMapRequestId += 1
            this.mapLoading = false
            this.mapMode = 'priorizacion'
            this.section = newSection
            if ( newSection == 'mapa' ) {
                this.$nextTick(() => this.loadPrioritizationMap())
            }
        },
        // Abre el resultado consolidado de la priorizacion en el mapa.
        showPrioritizationMap: function(){
            this.actualizarMapa('mapa')
        },
        // Abre la variable seleccionada en el mapa.
        actualizarCapa: function(){
            if ( ! this.currentVariable || ! this.currentVariable.id ) return
            this.mapMode = 'variable'
            this.section = 'mapa'
            this.$nextTick(() => this.loadPrioritizationMap())
        },
        // Selecciona la primera variable disponible para el tema elegido.
        setTema: function(){
            const selectedVariable = this.mapVariablesFiltradas[0]
            if ( selectedVariable ) {
                this.setVariable(selectedVariable.id)
            } else {
                this.currentVariableId = ''
                this.currentVariable = null
            }
        },
        // Normaliza las llaves para asociar API y GeoJSON sin depender del tipo.
        normalizeMapJoinKey: function(value){
            if ( value === null || value === undefined ) return ''
            return String(value).trim()
        },
        // Obtiene las llaves territoriales disponibles en el GeoJSON.
        mapGeometryKeys: function(mapData, propertyKey){
            const keys = new Set()
            if ( ! mapData || ! Array.isArray(mapData.features) || ! propertyKey ) return keys

            mapData.features.forEach(feature => {
                const value = this.normalizeMapJoinKey(
                    feature.properties ? feature.properties[propertyKey] : null
                )
                if ( value !== '' ) keys.add(value)
            })
            return keys
        },
        // Convierte y filtra los valores que corresponden a la capa base.
        mapValues: function(payload, mapData, propertyKey){
            const geometryKeys = this.mapGeometryKeys(mapData, propertyKey)
            return (payload.valores || []).map(row => {
                const numericValue = row.value === null || row.value === ''
                    ? NaN
                    : Number(row.value)
                return {
                    code: row.code,
                    name: row.name,
                    value: Number.isFinite(numericValue) ? numericValue : null
                }
            }).filter(row => geometryKeys.has(this.normalizeMapJoinKey(row.code)))
        },
        // Oscurece el color principal para el extremo superior de la escala.
        darkenMapColor: function(color){
            const value = color.replace('#', '')
            const red = Math.max(0, Math.round(parseInt(value.substring(0, 2), 16) * 0.78))
            const green = Math.max(0, Math.round(parseInt(value.substring(2, 4), 16) * 0.78))
            const blue = Math.max(0, Math.round(parseInt(value.substring(4, 6), 16) * 0.78))
            return '#' + [red, green, blue]
                .map(channel => channel.toString(16).padStart(2, '0'))
                .join('')
        },
        // Construye la escala de color a partir de los valores visibles.
        mapColorScale: function(payload, mapDataValues, requestedColor){
            const values = mapDataValues
                .map(row => row.value)
                .filter(value => Number.isFinite(value))
            const summary = payload.summary || {}
            let min = Number(summary.min)
            let max = Number(summary.max)

            if ( values.length ) {
                min = Math.min(...values)
                max = Math.max(...values)
            }
            if ( ! Number.isFinite(min) ) min = 0
            if ( ! Number.isFinite(max) ) max = 1
            if ( min === max ) max = min + 1

            const baseColor = /^#[0-9a-f]{6}$/i.test(requestedColor || '')
                ? requestedColor
                : '#AA0066'
            return {
                min: min,
                max: max,
                baseColor: baseColor,
                darkColor: this.darkenMapColor(baseColor)
            }
        },
        // Construye la expresion que colorea los poligonos con datos.
        mapFillExpression: function(scale){
            const middle = scale.min + ((scale.max - scale.min) * 0.65)
            return [
                'case',
                ['==', ['get', '__priority_has_value'], 1],
                [
                    'interpolate',
                    ['linear'],
                    ['to-number', ['get', '__priority_value'], 0],
                    scale.min, '#f4f6f9',
                    middle, scale.baseColor,
                    scale.max, scale.darkColor
                ],
                '#dfe4e8'
            ]
        },
        // Carga y conserva en memoria el GeoJSON optimizado de la capa base.
        getPrioritizationMapData: async function(){
            if ( ! this.capaBase || ! this.capaBase.archivo_mapa ) {
                throw new Error('La priorizacion no tiene una capa base valida')
            }
            const dataKey = this.capaBase.archivo_mapa
            if ( priorizacionMapDataCache.has(dataKey) ) {
                return priorizacionMapDataCache.get(dataKey)
            }

            const response = await fetch(
                this.mapContentUrl + 'geofocus/capas_base/' + dataKey
            )
            if ( ! response.ok ) {
                throw new Error('No fue posible cargar la geometria de la capa base')
            }
            const mapData = await response.json()
            priorizacionMapDataCache.set(dataKey, mapData)
            return mapData
        },
        // Consulta los valores de una priorizacion o de una variable.
        getPrioritizationMapValues: async function(field, id){
            const response = await axios.get(
                URL_API + 'geofocus/get_variable_valores/' + field + '/' + id
            )
            return response.data
        },
        // Busca una rotacion del catalogo por su clave.
        findMapRotation: function(rotationKey){
            return this.rotaciones.find(rotation =>
                String(rotation.key) === String(rotationKey)
            ) || null
        },
        // Obtiene la rotacion predeterminada de la capa base.
        defaultMapRotation: function(){
            const rotation = this.capaBase
                ? this.findMapRotation(this.capaBase.rotacion)
                : null
            return rotation ||
                this.rotaciones.find(option => Number(option.value) === 0) ||
                this.rotaciones[0] || null
        },
        // Devuelve los grados de la rotacion seleccionada.
        selectedMapBearing: function(){
            const rotation = this.findMapRotation(this.mapRotationKey) ||
                this.defaultMapRotation()
            const bearing = rotation ? Number(rotation.value) : 0
            return Number.isFinite(bearing) ? bearing : 0
        },
        // Configura en el selector la rotacion propia de la capa base.
        syncMapRotation: function(){
            const rotation = this.defaultMapRotation()
            this.mapRotationKey = rotation ? String(rotation.key) : ''
        },
        // Obtiene el centro configurado para la capa base.
        mapCenter: function(){
            const center = this.capaBase && Array.isArray(this.capaBase.center)
                ? this.capaBase.center.map(value => Number(value))
                : []
            return center.length === 2 && center.every(Number.isFinite)
                ? center
                : [-74.10, 4.65]
        },
        // Obtiene el zoom configurado para la capa base.
        mapZoom: function(){
            const zoom = this.capaBase ? Number(this.capaBase.zoom) : NaN
            return Number.isFinite(zoom) ? zoom : 10
        },
        // Aplica dinamicamente la orientacion elegida.
        changeMapRotation: function(){
            if ( priorizacionMap ) {
                priorizacionMap.easeTo({
                    bearing: this.selectedMapBearing(),
                    duration: 300
                })
            }
        },
        // Inicializa una unica instancia de MapLibre.
        initializePrioritizationMap: async function(){
            if ( priorizacionMap ) return priorizacionMapReady

            priorizacionMap = new maplibregl.Map({
                container: 'priorizacion-map-container',
                style: {
                    version: 8,
                    sources: {},
                    layers: [{
                        id: 'priorizacion-background',
                        type: 'background',
                        paint: { 'background-color': '#eef1f4' }
                    }]
                },
                center: this.mapCenter(),
                zoom: this.mapZoom(),
                bearing: this.selectedMapBearing(),
                pitch: 0,
                dragRotate: true,
                attributionControl: true
            })
            priorizacionMap.addControl(
                new maplibregl.NavigationControl({ showCompass: true }),
                'top-right'
            )
            priorizacionMapPopup = new maplibregl.Popup({
                closeButton: false,
                closeOnClick: false,
                offset: 8
            })

            priorizacionMapReady = new Promise((resolve, reject) => {
                priorizacionMap.once('load', resolve)
                priorizacionMap.once('error', event => {
                    reject(event.error || new Error('No fue posible inicializar MapLibre'))
                })
            })

            try {
                await priorizacionMapReady
                return priorizacionMap
            } catch (error) {
                priorizacionMap.remove()
                priorizacionMap = null
                priorizacionMapReady = null
                throw error
            }
        },
        // Escapa texto antes de incluirlo en el popup.
        escapeMapHtml: function(value){
            return String(value === null || value === undefined ? '' : value)
                .replace(/[&<>"']/g, character => ({
                    '&': '&amp;',
                    '<': '&lt;',
                    '>': '&gt;',
                    '"': '&quot;',
                    "'": '&#039;'
                })[character])
        },
        // Muestra el valor del territorio bajo el cursor.
        showMapFeaturePopup: function(event){
            if ( ! priorizacionMap || ! event.features || ! event.features.length ) return

            const properties = event.features[0].properties || {}
            const hasValue = Number(properties.__priority_has_value) === 1
            const value = hasValue ? this.formatNumber(properties.__priority_value) : 'Sin dato'
            const unit = this.mapMode == 'variable' && this.currentVariable &&
                this.currentVariable.unidad_medida
                ? ' ' + this.escapeMapHtml(this.currentVariable.unidad_medida)
                : ''
            const name = properties.nombre || properties.name ||
                properties.__priority_name || 'Territorio'

            priorizacionMapPopup
                .setLngLat(event.lngLat)
                .setHTML(
                    '<strong>' + this.escapeMapHtml(name) + '</strong><br>' +
                    this.escapeMapHtml(value) + unit
                )
                .addTo(priorizacionMap)
        },
        // Vincula una sola vez los eventos de interaccion del mapa.
        bindMapInteractions: function(){
            if ( priorizacionMapInteractionsReady ) return
            priorizacionMap.on('mouseenter', priorizacionMapFillLayerId, () => {
                priorizacionMap.getCanvas().style.cursor = 'pointer'
            })
            priorizacionMap.on('mouseleave', priorizacionMapFillLayerId, () => {
                priorizacionMap.getCanvas().style.cursor = ''
                if ( priorizacionMapPopup ) priorizacionMapPopup.remove()
            })
            priorizacionMap.on('mousemove', priorizacionMapFillLayerId, event => {
                this.showMapFeaturePopup(event)
            })
            priorizacionMapInteractionsReady = true
        },
        // Integra los valores como propiedades de cada poligono del GeoJSON.
        mapDataWithValues: function(mapData, mapDataValues, propertyKey){
            const valuesByKey = new Map()
            mapDataValues.forEach(row => {
                const key = this.normalizeMapJoinKey(row.code)
                if ( key !== '' && ! valuesByKey.has(key) ) valuesByKey.set(key, row)
            })

            return {
                ...mapData,
                features: mapData.features.map(feature => {
                    const properties = feature.properties || {}
                    const key = this.normalizeMapJoinKey(properties[propertyKey])
                    const row = valuesByKey.get(key)
                    const hasValue = !!row && Number.isFinite(row.value)
                    return {
                        ...feature,
                        properties: {
                            ...properties,
                            __priority_has_value: hasValue ? 1 : 0,
                            __priority_value: hasValue ? row.value : 0,
                            __priority_name: row && row.name ? row.name : ''
                        }
                    }
                })
            }
        },
        // Crea la fuente y las capas o actualiza sus datos atomicamente.
        ensurePrioritizationMapLayers: function(mapData){
            const source = priorizacionMap.getSource(priorizacionMapSourceId)
            if ( source ) {
                source.setData(mapData)
            } else {
                priorizacionMap.addSource(priorizacionMapSourceId, {
                    type: 'geojson',
                    data: mapData
                })
                priorizacionMap.addLayer({
                    id: priorizacionMapFillLayerId,
                    type: 'fill',
                    source: priorizacionMapSourceId,
                    paint: {
                        'fill-color': '#dfe4e8',
                        'fill-opacity': 0.84
                    }
                })
                priorizacionMap.addLayer({
                    id: priorizacionMapLineLayerId,
                    type: 'line',
                    source: priorizacionMapSourceId,
                    paint: {
                        'line-color': '#59636f',
                        'line-width': 0.6,
                        'line-opacity': 0.9
                    }
                })
            }
            this.bindMapInteractions()
        },
        // Dibuja los valores y actualiza la escala de color del mapa.
        renderPrioritizationMap: async function(mapData, payload, requestedColor){
            const propertyKey = this.capaBase.property_key || 'poligono_id'
            if ( ! mapData || ! Array.isArray(mapData.features) ) {
                throw new Error('La capa base no contiene poligonos validos')
            }
            if ( this.mapGeometryKeys(mapData, propertyKey).size === 0 ) {
                throw new Error('La capa no contiene la propiedad de asociacion configurada')
            }

            const mapDataValues = this.mapValues(payload, mapData, propertyKey)
            if ( mapDataValues.length === 0 ) {
                throw new Error('No hay valores asociados a los poligonos de esta capa')
            }
            const scale = this.mapColorScale(payload, mapDataValues, requestedColor)
            const mapDataReady = this.mapDataWithValues(mapData, mapDataValues, propertyKey)

            await this.initializePrioritizationMap()
            this.ensurePrioritizationMapLayers(mapDataReady)
            priorizacionMap.setPaintProperty(
                priorizacionMapFillLayerId,
                'fill-color',
                this.mapFillExpression(scale)
            )
            priorizacionMap.resize()
            this.mapScale = scale
        },
        // Carga el resultado general o la variable seleccionada en el mapa.
        loadPrioritizationMap: async function(){
            if ( ! this.capaBase ) {
                this.mapError = 'La priorizacion no tiene una capa base configurada'
                return
            }

            const mode = this.mapMode
            const variable = mode == 'variable' ? this.currentVariable : null
            if ( mode == 'variable' && (!variable || !variable.id) ) {
                this.mapError = 'Seleccione una variable para visualizar'
                return
            }

            const field = mode == 'priorizacion' ? 'priorizacion_id' : 'variable_id'
            const id = mode == 'priorizacion' ? this.priorizacion.id : variable.id
            const color = mode == 'priorizacion' ? '#AA0066' : variable.color
            const requestId = ++priorizacionMapRequestId
            this.mapLoading = true
            this.mapError = ''

            try {
                const [mapData, payload] = await Promise.all([
                    this.getPrioritizationMapData(),
                    this.getPrioritizationMapValues(field, id)
                ])
                if ( requestId !== priorizacionMapRequestId ) return
                await this.renderPrioritizationMap(mapData, payload, color)
            } catch (error) {
                console.error(error)
                this.mapError = error.message || 'No fue posible cargar el mapa'
            } finally {
                if ( requestId === priorizacionMapRequestId ) this.mapLoading = false
            }
        },
        // Descripción de la priorización
        //-----------------------------------------------------------------------------
        getDescripcionPriorizacion: function(){
            this.loading = true
            var formValues = new FormData()
            formValues.append('texto_parametrizacion', this.textoParametrizacion)
            axios.post(URL_API + 'geofocus/get_descripcion/' + this.priorizacion.id, formValues)
            .then(response => {
                //this.descripcion.texto = response.data.candidates[0].content.parts[0].text
                console.log(response.data.descripcion_generada)
                this.descripcion.texto = response.data.descripcion_generada
                this.descripcion.active = false
                typeText(this.descripcion.texto, 10);
                this.loading = false
            })
            .catch( function(error) {console.log(error)} )
        },
        formatNumber: function(value){
            if ( value === null || value === undefined || value === '' ) return '-'
            const numericValue = Number(value)
            return Number.isFinite(numericValue) ? Pcrn.round(numericValue) : value
        },
        isEditable: function(){
            console.log(this.userRole)
            console.log(this.priorizacion.creator_id)
            if ( this.userRole <= 2 ) return true
            if ( this.priorizacion.creator_id == this.userId ) return true
            return false
        },

    },
    computed: {
        variablesActivas: function(){
            return this.variables.filter(variable => variable.active == true)
        },
        // Devuelve los temas que tienen variables activas en esta capa.
        mapTemas: function(){
            const availableThemes = new Set(
                this.variables
                    .filter(variable => String(variable.estado) === '1')
                    .map(variable => (variable.tema || '').trim())
                    .filter(theme => theme.length > 0)
            )
            return this.arrTemas
                .map(option => option.name)
                .filter(theme => availableThemes.has(theme))
        },
        // Filtra las variables disponibles en el selector del mapa.
        mapVariablesFiltradas: function(){
            return this.variables.filter(variable =>
                String(variable.estado) === '1' &&
                (!this.currentTema || (variable.tema || '').trim() === this.currentTema)
            )
        },
        textoParametrizacion: function(){
            var texto = 'Variables: '
            this.variables.forEach((variable,index) => {
                if ( variable.active ) {
                    texto += "Variable " + (index+1) + ': '
                    texto += `Nombre: ${variable.nombre}. `;
                    texto += `Tema: ${variable.tema}. `;
                    texto += `Descripción: ${variable.descripcion}. `;
                    if ( variable.tipo_priorizacion == 1 ) {
                        texto += `Tipo de priorización: Priorizar territorios con valores altos. `;
                    } else {
                        texto += `Tipo de priorización: Priorizar territorios con valores bajos. `;
                    }
                    texto += `Ponderación de la variable: ${variable.puntaje}. `;
                    texto += '. ---';
                    texto += "\n"
                }
            });
            return texto
        },
    },
    mounted(){
        this.startVariables()
        const selectedVariable = this.variables.find(variable =>
            String(variable.estado) === '1'
        )
        if ( selectedVariable ) {
            this.currentVariableId = String(selectedVariable.id)
            this.currentVariable = selectedVariable
            this.currentTema = selectedVariable.tema || ''
        } else {
            this.currentVariableId = ''
            this.currentVariable = null
            this.currentTema = ''
        }
        this.mapMode = 'priorizacion'
        this.syncMapRotation()
    }
}).mount('#priorizacionApp');


// Show text in a container, one character at a time
//-----------------------------------------------------------------------------
function typeText(text, interval) {
    const container = document.getElementById('typing-respuesta');
    let index = 0;
    container.textContent = ''
    
    function showText() {
        if (index < text.length) {
            container.textContent += text[index];
            index++;
        }
    }
    
    const intervalId = setInterval(showText, interval);
}
</script>
