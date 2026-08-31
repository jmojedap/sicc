<script>
var geofocusVariablesApp = createApp({
    data(){
        return{
            section: <?= json_encode($section, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>,
            currentVariableId: <?= json_encode($variable_id, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>,
            currentVariable: {},
            loading: false,
            temaFiltro: '',
            fields: {},
            capasBase: <?= json_encode(
                $capas_base,
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
        setCurrent: function(variableId){
            this.currentVariableId = variableId
            this.currentVariable = this.variables.find(variable => variable.id == variableId) || {}
            this.fields = { ...this.currentVariable }
        },
        prepareNewVariable: function(){
            this.currentVariableId = 'add'
            this.currentVariable = {}
            this.fields = {
                estado: '2',
                puntaje: '0'
            }
        },
        openCreateForm: function(){
            this.prepareNewVariable()
            this.section = 'form'
            this.updateUrl('form', 'add')
        },
        openForm: function(variableId){
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
            let newUrl = URL_APP + 'geofocus/variables/' + newSection
            if ( variableId !== null && variableId !== '' && variableId !== undefined ) {
                newUrl += '/' + encodeURIComponent(variableId)
            } else {
                newUrl += '/'
            }

            if ( window.location.href != newUrl ) {
                window.history.pushState(
                    { section: newSection, variable_id: variableId },
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
            return typeof value === 'string' && /^https?:\/\//i.test(value)
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
        temas: function(){
            return [...new Set(
                this.variables
                    .map(variable => (variable.tema || '').trim())
                    .filter(tema => tema.length > 0)
            )].sort((a, b) => a.localeCompare(b, 'es'))
        },
        variablesFiltradas: function(){
            if ( ! this.temaFiltro ) return this.variables
            return this.variables.filter(variable => (variable.tema || '').trim() == this.temaFiltro)
        }
    },
    mounted(){
        if ( this.currentVariableId == 'add' ) {
            this.prepareNewVariable()
        } else if ( this.currentVariableId !== null && this.currentVariableId !== '' ) {
            this.setCurrent(this.currentVariableId)
        }
    }
}).mount('#geofocusVariablesApp')
</script>
