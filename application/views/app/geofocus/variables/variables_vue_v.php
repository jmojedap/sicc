<script>
var geofocusVariablesApp = createApp({
    data(){
        return{
            section: <?= json_encode($section, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>,
            currentVariableId: <?= json_encode($variable_id, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>,
            currentVariable: {},
            currentKeyCapa: <?= json_encode($key_capa, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>,
            loading: false,
            userRole: Number(APP_RID),
            temaFiltro: '',
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
        }
    },
    methods: {
        selectCapa: function(capa){
            this.currentKeyCapa = capa.key_capa
            this.temaFiltro = ''
            this.updateUrl('lista')
        },
        isCurrentCapa: function(capa){
            return String(capa.key_capa) === String(this.currentKeyCapa)
        },
        cantidadVariablesCapa: function(capa){
            return this.variables.filter(variable =>
                String(variable.key_capa) === String(capa.key_capa)
            ).length
        },
        hasIncompleteValues: function(variable){
            const polygonCount = Number(this.currentCapa && this.currentCapa.cantidad_poligonos)
            const valueCount = Number(variable.cantidad_valores)
            return polygonCount > 0 && Number.isFinite(valueCount) && valueCount < polygonCount
        },
        setCurrent: function(variableId){
            this.currentVariableId = variableId
            this.currentVariable = this.variables.find(variable => variable.id == variableId) || {}
            this.fields = { ...this.currentVariable, color: this.currentVariable.color || '#0084bf' }

            if ( this.currentVariable.key_capa ) {
                this.currentKeyCapa = this.currentVariable.key_capa
            }
        },
        prepareNewVariable: function(){
            const defaultKeyCapa = this.currentKeyCapa ||
                (this.capasBase.length > 0 ? this.capasBase[0].key_capa : '')

            this.currentVariableId = 'add'
            this.currentVariable = {}
            this.fields = {
                estado: '2',
                puntaje: '0',
                color: '#0084bf',
                key_capa: defaultKeyCapa
            }
        },
        openCreateForm: function(){
            if ( ! this.canEditVariables ) return
            this.prepareNewVariable()
            this.section = 'form'
            this.updateUrl('form', 'add')
        },
        openForm: function(variableId){
            if ( ! this.canEditVariables ) return
            this.setCurrent(variableId)
            this.section = 'form'
            this.updateUrl('form', variableId)
        },
        setSection: function(newSection){
            this.section = newSection
            if ( newSection == 'form' ) {
                this.updateUrl('form', this.currentVariableId)
            } else {
                this.updateUrl(newSection)
            }
        },
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
        displayValue: function(value){
            return value === null || value === undefined || value === '' ? '—' : value
        },
        estadoNombre: function(codigo){
            const estado = this.arrEstadoVariable.find(option => option.cod == codigo)
            return estado ? estado.name : this.displayValue(codigo)
        },
        hasDataLink: function(value){
            return typeof value === 'string' && /^https?:[/][/]/i.test(value)
        },
        textToClass: function(text, prefix = 'tema'){
            if ( ! text ) return prefix
            return prefix + '-' + Pcrn.textToClass(text)
        },
        handleSubmit: function(){
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
            .catch(function(error) {
                console.error(error)
                toastr['error']('Ocurrió un error al guardar la variable')
            })
            .finally(() => {
                this.loading = false
            })
        }
    },
    computed: {
        canEditVariables: function(){
            return [1, 2, 3, 8].includes(this.userRole)
        },
        currentCapa: function(){
            return this.capasBase.find(capa =>
                String(capa.key_capa) === String(this.currentKeyCapa)
            ) || null
        },
        variablesDeCapa: function(){
            if ( ! this.currentCapa ) return []

            return this.variables.filter(variable =>
                String(variable.key_capa) === String(this.currentCapa.key_capa)
            )
        },
        temas: function(){
            return [...new Set(
                this.variablesDeCapa
                    .map(variable => (variable.tema || '').trim())
                    .filter(tema => tema.length > 0)
            )].sort((a, b) => a.localeCompare(b, 'es'))
        },
        variablesFiltradas: function(){
            if ( ! this.temaFiltro ) return this.variablesDeCapa
            return this.variablesDeCapa.filter(variable =>
                (variable.tema || '').trim() == this.temaFiltro
            )
        }
    },
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
    }
}).mount('#geofocusVariablesApp')
</script>
