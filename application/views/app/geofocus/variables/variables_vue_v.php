<script>
let variablesMap = null
let variablesMapReady = null
let variablesMapRequestId = 0
let variablesMapCapaKey = null
let variablesMapPropertyKey = null
let variablesMapDataKey = null
let variablesMapPopup = null
let variablesMapInteractionsReady = false
const variablesMapDataCache = new Map()
const variablesMapSourceId = 'variables-base-source'
const variablesMapFillLayerId = 'variables-base-fill'
const variablesMapLineLayerId = 'variables-base-line'

var geofocusVariablesApp = createApp({
    // ===== Estado general de la aplicacion =====
    // Inicializa el estado de la herramienta y sus datos.
    data(){
        return{
            section: <?= json_encode($section, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>,
            currentVariableId: <?= json_encode($variable_id, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>,
            currentVariable: {},
            currentKeyCapa: <?= json_encode($key_capa, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>,
            loading: false,
            userRole: Number(APP_RID),
            temaFiltro: '',
            mapVariableId: '',
            mapTemaFiltro: '',
            mapRotationKey: '',
            mapLoading: false,
            mapError: '',
            mapScale: null,
            mapContentUrl: <?= json_encode(URL_CONTENT, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>,
            fields: {},
            capasBase: <?= json_encode(
                $capasBase,
                JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT
            ) ?>,
            arrEstadoVariable: <?= json_encode(
                $arrEstadoVariable,
                JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT
            ) ?>,
            variables: <?= json_encode(
                $variables->result(),
                JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT
            ) ?>,
            arrTemas: <?= json_encode($arrTemas, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>,
            rotaciones: <?= json_encode(
                $rotaciones,
                JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT
            ) ?>,
        }
    },
    methods: {
        // ===== Funciones generales de la aplicacion =====
        // Cambia la capa base activa y reinicia el filtro de temas.
        selectCapa: function(capa){
            this.currentKeyCapa = capa.key_capa
            this.temaFiltro = ''
            this.updateUrl('lista')
        },
        // Indica si una capa es la seleccionada actualmente.
        isCurrentCapa: function(capa){
            return String(capa.key_capa) === String(this.currentKeyCapa)
        },
        // Cuenta las variables asociadas a una capa.
        cantidadVariablesCapa: function(capa){
            return this.variables.filter(variable =>
                String(variable.key_capa) === String(capa.key_capa)
            ).length
        },
        // Determina si faltan valores para algunos poligonos.
        hasIncompleteValues: function(variable){
            const polygonCount = Number(this.currentCapa && this.currentCapa.cantidad_poligonos)
            const valueCount = Number(variable.cantidad_valores)
            return polygonCount > 0 && Number.isFinite(valueCount) && valueCount < polygonCount
        },
        // Selecciona una variable y prepara sus campos de edicion.
        setCurrent: function(variableId){
            this.currentVariableId = variableId
            this.currentVariable = this.variables.find(variable => variable.id == variableId) || {}
            this.fields = { ...this.currentVariable, color: this.currentVariable.color || '#0084bf' }

            if ( this.currentVariable.key_capa ) {
                this.currentKeyCapa = this.currentVariable.key_capa
            }
        },
        // Cambia la seccion visible y actualiza la URL.
        setSection: function(newSection){
            this.section = newSection
            if ( newSection == 'form' ) {
                this.updateUrl('form', this.currentVariableId)
            } else {
                this.updateUrl(newSection)
            }
        },
        // Construye y actualiza la URL de la seccion actual.
        updateUrl: function(newSection, variableId = null){
            let newUrl = URL_APP + 'geofocus/variables/' +
                encodeURIComponent(this.currentKeyCapa) + '/' +
                encodeURIComponent(newSection)
            if ( variableId !== null && variableId !== '' && variableId !== undefined ) {
                newUrl += '/' + encodeURIComponent(variableId)
            } else {
                newUrl += '/'
            }

            if ( window.location.href != newUrl ) {
                window.history.pushState(
                    {
                        key_capa: this.currentKeyCapa,
                        section: newSection,
                        variable_id: variableId
                    },
                    '',
                    newUrl
                )
            }
        },
        // Devuelve un valor o un guion cuando esta vacio.
        displayValue: function(value){
            return value === null || value === undefined || value === '' ? '—' : value
        },
        // Obtiene el nombre visible de un estado.
        estadoNombre: function(codigo){
            const estado = this.arrEstadoVariable.find(option => option.cod == codigo)
            return estado ? estado.name : this.displayValue(codigo)
        },
        // Verifica si un valor contiene un enlace HTTP o HTTPS.
        hasDataLink: function(value){
            return typeof value === 'string' && /^https?:[/][/]/i.test(value)
        },
        // Genera la clase CSS normalizada para un texto.
        textToClass: function(text, prefix = 'tema'){
            if ( ! text ) return prefix
            return prefix + '-' + Pcrn.textToClass(text)
        },
        // ===== Funciones del formulario =====
        // Prepara los valores iniciales para crear una variable.
        prepareNewVariable: function(){
            const defaultKeyCapa = this.currentKeyCapa ||
                (this.capasBase.length > 0 ? this.capasBase[0].key_capa : '')

            this.currentVariableId = 'add'
            this.currentVariable = {}
            this.fields = {
                estado: '2',
                puntaje: '0',
                color: '#0084bf',
                key_capa: defaultKeyCapa,
                tema: '',
                subtema: ''
            }
        },
        // Abre el formulario para crear una variable.
        openCreateForm: function(){
            if ( ! this.canEditVariables ) return
            this.prepareNewVariable()
            this.section = 'form'
            this.updateUrl('form', 'add')
        },
        // Abre el formulario para editar una variable.
        openForm: function(variableId){
            if ( ! this.canEditVariables ) return
            this.setCurrent(variableId)
            this.section = 'form'
            this.updateUrl('form', variableId)
        },
        // Valida y envia el formulario de la variable.
        handleSubmit: function(){
            const subtema = (this.fields.subtema || '').trim()
            if ( ! subtema ) {
                toastr['warning']('El subtema es obligatorio')
                return
            }

            this.fields.subtema = subtema
            this.loading = true
            const formValues = new FormData(document.getElementById('variables_form'))

            axios.post(URL_API + 'geofocus/variables_save/', formValues)
            .then(response => {
                const savedId = response.data.saved_id
                if ( savedId > 0 ) {
                    const savedVariable = { ...this.fields, id: String(savedId) }
                    const variableIndex = this.variables.findIndex(variable => variable.id == savedId)

                    if ( variableIndex >= 0 ) {
                        this.variables.splice(variableIndex, 1, savedVariable)
                    } else {
                        this.variables.unshift(savedVariable)
                    }

                    this.currentVariableId = savedVariable.id
                    this.currentVariable = savedVariable
                    this.currentKeyCapa = savedVariable.key_capa || this.currentKeyCapa
                    this.setSection('lista')
                    toastr['success']('Variable guardada')
                } else {
                    toastr['error']('No fue posible guardar la variable')
                }
            })
            // Maneja los errores al guardar la variable.
            .catch(function(error) {
                console.error(error)
                toastr['error']('Ocurrió un error al guardar la variable')
            })
            .finally(() => {
                this.loading = false
            })
        },
        // Solicita el recalculo estadistico de una variable.
        recalcularVariable: function(variable){
            axios.get(URL_API + 'geofocus/recalcular_variable/' + variable.id)
            .then(response => {
                toastr['info']('Variable recalculada')
                console.log(response.data)
            })
            // Registra los errores del recalculo de la variable.
            .catch(function(error) { console.log(error) })
        },
        // ===== Funciones del mapa =====
        // Abre el mapa con una variable seleccionada.
        openMap: function(variableId = null){
            const requestedVariable = this.variablesDeCapa.find(variable =>
                String(variable.id) === String(variableId)
            )
            const selectedVariable = requestedVariable ||
                this.variablesDeCapa.find(variable => String(variable.estado) === '1') ||
                this.variablesDeCapa[0]

            this.mapVariableId = selectedVariable ? String(selectedVariable.id) : ''
            this.mapTemaFiltro = selectedVariable ? (selectedVariable.tema || '') : ''
            this.syncMapRotationWithCapa()
            this.applyMapViewForCapa()
            this.section = 'mapa'
            this.updateUrl('mapa', this.mapVariableId || null)
            this.$nextTick(() => this.loadVariablesMap())
        },
        // Cambia la capa del mapa y carga su primera variable disponible.
        changeMapCapa: function(){
            this.mapTemaFiltro = ''
            this.syncMapRotationWithCapa()
            this.applyMapViewForCapa()
            const selectedVariable = this.variablesDeCapa.find(variable =>
                String(variable.estado) === '1'
            ) || this.variablesDeCapa[0]

            this.mapVariableId = selectedVariable ? String(selectedVariable.id) : ''
            this.updateUrl('mapa', this.mapVariableId || null)
            this.$nextTick(() => this.loadVariablesMap())
        },
        // Selecciona la primera variable del tema elegido.
        changeMapTema: function(){
            const selectedVariable = this.variablesMapaFiltradas[0]
            this.mapVariableId = selectedVariable ? String(selectedVariable.id) : ''
            this.changeMapVariable()
        },
        // Actualiza el mapa al cambiar de variable.
        changeMapVariable: function(){
            this.mapError = ''
            this.updateUrl('mapa', this.mapVariableId || null)
            if ( this.mapVariableId ) {
                this.updateVariablesMap()
            }
        },
        // Busca una rotacion del catalogo por su clave.
        findMapRotation: function(rotationKey){
            return this.rotaciones.find(rotation =>
                String(rotation.key) === String(rotationKey)
            ) || null
        },
        // Obtiene la rotacion predeterminada configurada para una capa base.
        defaultMapRotation: function(capa = this.currentCapa){
            const rotation = capa ? this.findMapRotation(capa.rotacion) : null
            return rotation ||
                this.rotaciones.find(option => Number(option.value) === 0) ||
                this.rotaciones[0] || null
        },
        // Devuelve en grados la rotacion seleccionada y garantiza un valor valido.
        selectedMapBearing: function(){
            const rotation = this.findMapRotation(this.mapRotationKey) ||
                this.defaultMapRotation()
            const bearing = rotation ? Number(rotation.value) : 0
            return Number.isFinite(bearing) ? bearing : 0
        },
        // Obtiene el centro de la capa base o usa el centro general de Bogota.
        mapCenterForCapa: function(capa = this.currentCapa){
            const center = capa && Array.isArray(capa.center)
                ? capa.center.map(value => Number(value))
                : []
            return center.length === 2 && center.every(Number.isFinite)
                ? center
                : [-74.10, 4.65]
        },
        // Obtiene el zoom de la capa base o usa un nivel general de referencia.
        mapZoomForCapa: function(capa = this.currentCapa){
            const zoom = capa ? Number(capa.zoom) : NaN
            return Number.isFinite(zoom) ? zoom : 10
        },
        // Ajusta centro, zoom y rotacion al cambiar de capa base.
        applyMapViewForCapa: function(capa = this.currentCapa){
            if ( variablesMap ) {
                variablesMap.easeTo({
                    center: this.mapCenterForCapa(capa),
                    zoom: this.mapZoomForCapa(capa),
                    bearing: this.selectedMapBearing(),
                    duration: 300
                })
            }
        },
        // Restablece el selector con la rotacion propia de la capa base activa.
        syncMapRotationWithCapa: function(){
            const rotation = this.defaultMapRotation()
            this.mapRotationKey = rotation ? String(rotation.key) : ''
        },
        // Aplica dinamicamente la rotacion elegida por el usuario.
        changeMapRotation: function(){
            this.setMapBearing(this.selectedMapBearing())
        },
        // Formatea un valor numerico para mostrarlo en el mapa.
        formatMapNumber: function(value){
            const numericValue = Number(value)
            if ( ! Number.isFinite(numericValue) ) return this.displayValue(value)
            return new Intl.NumberFormat('es-CO', {
                maximumFractionDigits: 2
            }).format(numericValue)
        },
        // Normaliza una clave para que la relacion sea independiente de su tipo.
        normalizeMapJoinKey: function(value){
            if ( value === null || value === undefined ) return ''
            return String(value).trim()
        },
        // Obtiene las claves disponibles en la propiedad configurada del GeoJSON.
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
        // Convierte y filtra los valores usando property_key, sin depender de cantidades iguales.
        mapValues: function(payload, mapData = null, propertyKey = ''){
            const geometryKeys = this.mapGeometryKeys(mapData, propertyKey)
            const hasGeometryFilter = geometryKeys.size > 0

            return (payload.valores || []).map(row => {
                const numericValue = row.value === null || row.value === ''
                    ? NaN
                    : Number(row.value)
                const joinValue = propertyKey && row[propertyKey] !== undefined
                    ? row[propertyKey]
                    : row.code
                return {
                    code: joinValue,
                    name: row.name,
                    value: Number.isFinite(numericValue) ? numericValue : null
                }
            }).filter(row => {
                if ( ! hasGeometryFilter ) return true
                return geometryKeys.has(this.normalizeMapJoinKey(row.code))
            })
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
        // Construye los limites y colores usados por MapLibre.
        mapColorScale: function(variable, payload, mapDataValues = []){
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

            const baseColor = /^#[0-9a-f]{6}$/i.test(variable.color || '')
                ? variable.color
                : '#0084bf'

            return {
                min: min,
                max: max,
                baseColor: baseColor,
                darkColor: this.darkenMapColor(baseColor)
            }
        },
        // Construye la expresion de color con los valores incluidos en el GeoJSON.
        mapFillExpression: function(scale){
            const middle = scale.min + ((scale.max - scale.min) * 0.65)
            return [
                'case',
                ['==', ['get', '__variable_has_value'], 1],
                [
                    'interpolate',
                    ['linear'],
                    ['to-number', ['get', '__variable_value'], 0],
                    scale.min, '#f4f6f9',
                    middle, scale.baseColor,
                    scale.max, scale.darkColor
                ],
                '#dfe4e8'
            ]
        },
        // Carga y cachea el GeoJSON de una capa base.
        getMapData: async function(capa){
            if ( variablesMapDataCache.has(capa.archivo_mapa) ) {
                return variablesMapDataCache.get(capa.archivo_mapa)
            }

            const response = await fetch(this.mapContentUrl + 'geofocus/capas_base/' + capa.archivo_mapa)
            if ( ! response.ok ) {
                throw new Error('No fue posible cargar la geometria de la capa base')
            }

            const mapData = await response.json()
            variablesMapDataCache.set(capa.archivo_mapa, mapData)
            return mapData
        },
        // Consulta en la API los valores de una variable.
        getVariableMapValues: async function(variableId){
            const response = await axios.get(
                URL_API + 'geofocus/get_variable_valores/variable_id/' + variableId
            )
            return response.data
        },
        // Inicializa MapLibre con un estilo local sin mapa base externo.
        initializeVariablesMap: async function(){
            if ( variablesMap ) {
                return variablesMapReady
            }

            variablesMap = new maplibregl.Map({
                container: 'variables-map-container',
                style: {
                    version: 8,
                    sources: {},
                    layers: [{
                        id: 'variables-background',
                        type: 'background',
                        paint: { 'background-color': '#eef1f4' }
                    }]
                },
                center: this.mapCenterForCapa(),
                zoom: this.mapZoomForCapa(),
                bearing: this.selectedMapBearing(),
                pitch: 0,
                dragRotate: true,
                attributionControl: true
            })
            variablesMap.addControl(
                new maplibregl.NavigationControl({ showCompass: true }),
                'top-right'
            )
            variablesMapPopup = new maplibregl.Popup({
                closeButton: false,
                closeOnClick: false,
                offset: 8
            })

            variablesMapReady = new Promise((resolve, reject) => {
                variablesMap.once('load', resolve)
                variablesMap.once('error', event => {
                    reject(event.error || new Error('No fue posible inicializar MapLibre'))
                })
            })

            try {
                await variablesMapReady
                return variablesMap
            } catch (error) {
                variablesMap.remove()
                variablesMap = null
                variablesMapReady = null
                throw error
            }
        },
        // Escapa texto antes de incluirlo en el popup del mapa.
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
        // Muestra el valor del poligono bajo el cursor.
        showMapFeaturePopup: function(event){
            if ( ! variablesMap || ! event.features || ! event.features.length ) return

            const feature = event.features[0]
            const properties = feature.properties || {}
            const hasValue = Number(properties.__variable_has_value) === 1
            const value = hasValue
                ? this.formatMapNumber(properties.__variable_value)
                : 'Sin dato'
            const unit = this.currentMapVariable && this.currentMapVariable.unidad_medida
                ? ' ' + this.escapeMapHtml(this.currentMapVariable.unidad_medida)
                : ''
            const name = properties.nombre || properties.name ||
                properties.__variable_name || 'Territorio'

            variablesMapPopup
                .setLngLat(event.lngLat)
                .setHTML(
                    '<strong>' + this.escapeMapHtml(name) + '</strong><br>' +
                    this.escapeMapHtml(value) + unit
                )
                .addTo(variablesMap)
        },
        // Vincula los eventos de interacción de la capa de polígonos.
        bindMapInteractions: function(){
            if ( variablesMapInteractionsReady ) return

            variablesMap.on('mouseenter', variablesMapFillLayerId, () => {
                variablesMap.getCanvas().style.cursor = 'pointer'
            })
            variablesMap.on('mouseleave', variablesMapFillLayerId, () => {
                variablesMap.getCanvas().style.cursor = ''
                if ( variablesMapPopup ) variablesMapPopup.remove()
            })
            variablesMap.on('mousemove', variablesMapFillLayerId, event => {
                this.showMapFeaturePopup(event)
            })
            variablesMapInteractionsReady = true
        },
        // Integra los valores de la variable en las propiedades de cada poligono.
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
                            __variable_has_value: hasValue ? 1 : 0,
                            __variable_value: hasValue ? row.value : 0,
                            __variable_name: row && row.name ? row.name : ''
                        }
                    }
                })
            }
        },
        // Crea o actualiza atomicamente la fuente y las capas de MapLibre.
        ensureMapLayers: function(mapData, propertyKey, dataKey){
            const source = variablesMap.getSource(variablesMapSourceId)
            const propertyChanged = variablesMapPropertyKey !== null &&
                variablesMapPropertyKey !== propertyKey
            const dataChanged = variablesMapDataKey !== null &&
                variablesMapDataKey !== dataKey

            if ( ! source || propertyChanged || dataChanged ) {
                if ( variablesMapPopup ) variablesMapPopup.remove()
                if ( variablesMap.getLayer(variablesMapFillLayerId) ) {
                    variablesMap.removeLayer(variablesMapFillLayerId)
                }
                if ( variablesMap.getLayer(variablesMapLineLayerId) ) {
                    variablesMap.removeLayer(variablesMapLineLayerId)
                }
                if ( variablesMap.getSource(variablesMapSourceId) ) {
                    variablesMap.removeSource(variablesMapSourceId)
                }

                variablesMap.addSource(variablesMapSourceId, {
                    type: 'geojson',
                    data: mapData
                })
                variablesMap.addLayer({
                    id: variablesMapFillLayerId,
                    type: 'fill',
                    source: variablesMapSourceId,
                    paint: {
                        'fill-color': '#dfe4e8',
                        'fill-opacity': 0.84
                    }
                })
                variablesMap.addLayer({
                    id: variablesMapLineLayerId,
                    type: 'line',
                    source: variablesMapSourceId,
                    paint: {
                        'line-color': '#59636f',
                        'line-width': 0.6,
                        'line-opacity': 0.9
                    }
                })
                variablesMapPropertyKey = propertyKey
                variablesMapDataKey = dataKey
            } else {
                source.setData(mapData)
            }

            this.bindMapInteractions()
        },
        // Dibuja o actualiza la capa y los valores de la variable.
        renderVariablesMap: async function(mapData, payload, variable, capa){
            const propertyKey = capa.property_key || 'poligono_id'
            if ( ! mapData || ! Array.isArray(mapData.features) ) {
                throw new Error('La capa base no contiene polígonos válidos')
            }
            if ( this.mapGeometryKeys(mapData, propertyKey).size === 0 ) {
                throw new Error('La capa base no contiene la propiedad de asociación configurada')
            }

            const mapDataValues = this.mapValues(payload, mapData, propertyKey)
            const scale = this.mapColorScale(variable, payload, mapDataValues)
            const mapDataReady = this.mapDataWithValues(mapData, mapDataValues, propertyKey)
            await this.initializeVariablesMap()
            this.ensureMapLayers(mapDataReady, propertyKey, capa.archivo_mapa)
            variablesMap.setPaintProperty(
                variablesMapFillLayerId,
                'fill-color',
                this.mapFillExpression(scale)
            )
            variablesMap.resize()
            this.mapScale = scale
            variablesMapCapaKey = String(capa.key_capa)
        },
        // Crea el mapa y carga su geometria y valores iniciales.
        loadVariablesMap: async function(){
            if ( ! this.currentCapa ) {
                this.mapError = 'No hay una capa base seleccionada'
                return
            }

            if ( ! this.currentMapVariable ) {
                this.mapError = 'Esta capa no tiene variables disponibles para visualizar'
                this.mapScale = null
                if ( variablesMap ) {
                    const source = variablesMap.getSource(variablesMapSourceId)
                    if ( source ) source.setData({ type: 'FeatureCollection', features: [] })
                }
                return
            }

            if (
                variablesMap &&
                variablesMapCapaKey === String(this.currentCapa.key_capa)
            ) {
                this.updateVariablesMap()
                return
            }

            const requestId = ++variablesMapRequestId
            this.mapLoading = true
            this.mapError = ''

            try {
                const [mapData, payload] = await Promise.all([
                    this.getMapData(this.currentCapa),
                    this.getVariableMapValues(this.currentMapVariable.id)
                ])
                if ( requestId !== variablesMapRequestId ) return

                await this.renderVariablesMap(
                    mapData,
                    payload,
                    this.currentMapVariable,
                    this.currentCapa
                )
            } catch (error) {
                console.error(error)
                this.mapError = error.message || 'No fue posible cargar el mapa'
            } finally {
                if ( requestId === variablesMapRequestId ) this.mapLoading = false
            }
        },
        // Actualiza los datos y la escala sin volver a crear el mapa.
        updateVariablesMap: async function(){
            if ( ! variablesMap || ! this.currentMapVariable ) {
                this.loadVariablesMap()
                return
            }

            const requestId = ++variablesMapRequestId
            const variable = this.currentMapVariable
            this.mapLoading = true
            this.mapError = ''

            try {
                const [payload, mapData] = await Promise.all([
                    this.getVariableMapValues(variable.id),
                    this.getMapData(this.currentCapa)
                ])
                if ( requestId !== variablesMapRequestId ) return

                await this.renderVariablesMap(
                    mapData,
                    payload,
                    variable,
                    this.currentCapa
                )
            } catch (error) {
                console.error(error)
                this.mapError = error.message || 'No fue posible actualizar la variable del mapa'
            } finally {
                if ( requestId === variablesMapRequestId ) this.mapLoading = false
            }
        },
        // Cambia la orientacion del mapa conservando sus datos.
        setMapBearing: function(bearing){
            if ( variablesMap ) {
                variablesMap.easeTo({ bearing: Number(bearing), duration: 300 })
            }
        },
    },
    computed: {
        // ===== Propiedades calculadas generales =====
        // Determina si el usuario puede editar variables.
        canEditVariables: function(){
            return [1, 2, 3, 8].includes(this.userRole)
        },
        // Obtiene la capa base actualmente seleccionada.
        currentCapa: function(){
            return this.capasBase.find(capa =>
                String(capa.key_capa) === String(this.currentKeyCapa)
            ) || null
        },
        // Devuelve las variables de la capa activa.
        variablesDeCapa: function(){
            if ( ! this.currentCapa ) return []

            return this.variables.filter(variable =>
                String(variable.key_capa) === String(this.currentCapa.key_capa)
            )
        },
        // Devuelve los temas parametrizados disponibles.
        temas: function(){
            return this.arrTemas
                .map(optionTema => optionTema.name)
                .filter(tema => typeof tema === 'string' && tema.trim().length > 0)
        },
        // Filtra las variables del listado por tema.
        variablesFiltradas: function(){
            if ( ! this.temaFiltro ) return this.variablesDeCapa
            return this.variablesDeCapa.filter(variable =>
                (variable.tema || '').trim() == this.temaFiltro
            )
        },
        // ===== Propiedades calculadas del mapa =====
        // Filtra los temas presentes en la capa del mapa.
        mapTemas: function(){
            const temasCapa = new Set(
                this.variablesDeCapa
                    .map(variable => (variable.tema || '').trim())
                    .filter(tema => tema.length > 0)
            )
            return this.temas.filter(tema => temasCapa.has(tema))
        },
        // Filtra las variables del mapa por tema.
        variablesMapaFiltradas: function(){
            if ( ! this.mapTemaFiltro ) return this.variablesDeCapa
            return this.variablesDeCapa.filter(variable =>
                (variable.tema || '').trim() === this.mapTemaFiltro
            )
        },
        // Obtiene la variable seleccionada en el mapa.
        currentMapVariable: function(){
            return this.variablesDeCapa.find(variable =>
                String(variable.id) === String(this.mapVariableId)
            ) || null
        }
    },
    // ===== Ciclo de vida general =====
    // Inicializa la vista, la variable y el mapa solicitados en la URL.
    mounted(){
        const requestedCapaExists = this.capasBase.some(capa =>
            String(capa.key_capa) === String(this.currentKeyCapa)
        )

        if ( ! requestedCapaExists ) {
            this.currentKeyCapa = this.capasBase.length > 0
                ? this.capasBase[0].key_capa
                : null
        }

        if ( ! this.canEditVariables && this.section == 'form' ) {
            this.section = 'lista'
            this.updateUrl('lista')
        }

        if ( this.currentVariableId == 'add' ) {
            this.prepareNewVariable()
        } else if ( this.currentVariableId !== null && this.currentVariableId !== '' ) {
            this.setCurrent(this.currentVariableId)
        }

        if ( this.section == 'mapa' ) {
            const requestedVariable = this.variablesDeCapa.find(variable =>
                String(variable.id) === String(this.currentVariableId)
            )
            const selectedVariable = requestedVariable ||
                this.variablesDeCapa.find(variable => String(variable.estado) === '1') ||
                this.variablesDeCapa[0]

            this.mapVariableId = selectedVariable ? String(selectedVariable.id) : ''
            this.mapTemaFiltro = selectedVariable ? (selectedVariable.tema || '') : ''
            this.syncMapRotationWithCapa()
            this.applyMapViewForCapa()
            this.$nextTick(() => this.loadVariablesMap())
        }
    }
}).mount('#geofocusVariablesApp')
</script>
