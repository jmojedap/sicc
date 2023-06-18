<script>
var test = '<?= $this->input->get('test'); ?>' | 0;
var randomValue = Math.floor(Math.random() * 1000);
var fields = {
    nombre_accion: '',
    descripcion: '',
    fecha: '<?= date('Y-m-d') ?>',
    hora_inicio: '',
    hora_fin: '',
    modalidad: '',
    url_evidencia: '',
    url_accion_medios: '',
    grupo_valor_principal: '1005',
    cantidad_mujeres: 0,
    cantidad_hombres: 0,
    cantidad_sexo_nd: 0,
    programa: '',
    estrategia: '',
    dependencia: '',
    equipo_trabajo: '',
    proyecto: '',
    cod_localidad: '10',
    nombre_lugar:'',
    direccion: '',
    observaciones: '',
}

if ( test == '1' ) {
    fields = {
        nombre_accion: 'Acción Ejemplo Prueba ' + randomValue,
        descripcion: 'Acción de prueba, se puede eliminar tras la prueba. Decripción de la acción ' + randomValue,
        fecha: '<?= date('Y-m-d') ?>',
        hora_inicio: '14:00',
        hora_fin: '16:00',
        modalidad: 'Presencial',
        url_evidencia: 'https://docs.google.com/document/d/1PT1hF9qQtyPVvZkMUQCefbrQmARL-Ddr/edit',
        url_accion_medios: 'https://www.eltiempo.com',
        grupo_valor_principal: '1005',
        cantidad_mujeres: 11,
        cantidad_hombres: 12,
        cantidad_sexo_nd: 3,
        programa: '',
        estrategia: '',
        dependencia: 'Observatorio',
        equipo_trabajo: 'Sistema de Información y Narrativas',
        proyecto: 'FilBo 2023',
        cod_localidad: '10',
        nombre_lugar:'Institución Educativa Utopia',
        direccion: 'CL 13 10 23',
        observaciones: 'Observaciones sobre la acción ' + randomValue,
    }
}

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
                    this.accionId = response.data.saved_id
                    this.clearForm()

                    new bootstrap.Modal($('#modalCreated')).show();
                }
                this.loading = false
            })
            .catch(function (error) { console.log(error) })
        },
        clearForm: function() {
            for ( key in this.fields ) this.fields[key] = ''
            this.validationStatus = 0
        },
        goToCreated: function() {
            window.location = URL_APP + 'acciones/edit/' + this.accionId
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