
<script>
var fields = <?= json_encode($row) ?>;
fields.estrategia = '0<?= $row->estrategia ?>';
fields.linea_estrategica = '0<?= $row->linea_estrategica ?>';
fields.cumplimiento_objetivo = '0<?= $row->cumplimiento_objetivo ?>';
fields.localidad = '0<?= $row->localidad ?>';
fields.meta = '0<?= $row->meta ?>';

// VueApp
//-----------------------------------------------------------------------------
var addAccionApp = createApp({
    data(){
        return{
            fields: fields,
            loading: false,
            accionId: 0,
            arrPrograma: <?= json_encode($arrPrograma) ?>,
            arrEstrategia: <?= json_encode($arrEstrategia) ?>,
            arrDependencia: <?= json_encode($arrDependencia) ?>,
            arrEquipoTrabajo: <?= json_encode($arrEquipoTrabajo) ?>,
            arrLocalidad: <?= json_encode($arrLocalidad) ?>,
            arrModalidad: <?= json_encode($arrModalidad) ?>,
            arrSiNoNa: <?= json_encode($arrSiNoNa) ?>,
            validationStatus: 0,
            validation: {
                hora_fin_posterior: -1
            }
        }
    },
    methods: {
        handleSubmit: function(){
            this.setValidationStatus()
            if (this.validationStatus == 1) {
                this.submitForm()
            } else {
                toastr['error']('Revise las casillas en rojo')
            }
        },
        submitForm: function() {
            this.loading = true
            var formValues = new FormData(document.getElementById('postForm'))
            axios.post(URL_APP + 'acciones/save/', formValues)
            .then(response => {
                if ( response.data.saved_id > 0 )
                {
                    toastr['success']('Datos guardados')
                }
                this.loading = false
            })
            .catch(function (error) { console.log(error) })
        },
        clearEstrategia: function(){
            this.fields['estrategia'] = ''
        },
        validateHoraFin: function(){
            var isValid = -1
            if ( this.fields.hora_inicio.length > 0 && this.fields.hora_fin.length > 0 ) {
                isValid = 0
                if ( this.fields.hora_fin > this.fields.hora_inicio ) {
                    isValid = 1
                }
            }
            this.validation.hora_fin_posterior = isValid
        },
        setValidationStatus: function(){
            this.validateHoraFin()
            this.validationStatus = 1
            Object.keys(this.validation).forEach(key => {
                if ( this.validation[key] <= 0 ) {
                    this.validationStatus = 0
                }
            })
        },
    },
    computed: {
        arrEstrategiaFiltered(){
            programaValue = this.fields.programa || ''
            console.log(programaValue)
            if ( programaValue.length == 0 ) {
                return this.arrEstrategia
            } else {
                return this.arrEstrategia.filter(item =>
                    item.parent_id == parseInt(programaValue)
                )
            }
        },
    },
    mounted(){
        //this.getList()
    }
}).mount('#addAccionApp')
</script>