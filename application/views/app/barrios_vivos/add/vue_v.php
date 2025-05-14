<script>
var test = '<?= $this->input->get('test'); ?>' | 0;
var randomValue = Math.floor(Math.random() * 1000);
var fields = {
    nombre_laboratorio: '',
    descripcion: '',
    relato_barrial: '',
    tipo_laboratorio: '',
    categoria_laboratorio: '',
    notas: '',
}

if ( test == '1' ) {
    fields = {
        nombre_laboratorio: 'Laboratorio Prueba ' + randomValue,
        nombre_corto: 'Lab' + randomValue,
        descripcion: 'Laboratorio de prueba, se puede eliminar tras la prueba. Decripción del laboratorio' + randomValue,
        relato_barrial: 'Relato barrial del kaboratorio de prueba, se puede eliminar tras la prueba. Relato barrial del laboratorio' + randomValue,
        tipo_laboratorio: 'Transformaciones culturales, deportivas y recreativas',
        categoria_laboratorio: 'Transformaciones culturales - CC',
        direccion_lider: 'Observatorio',
        notas: 'Observaciones sobre el laboratorio' + randomValue,
    }
}

// VueApp
//-----------------------------------------------------------------------------
var addLaboratorioApp = createApp({
    data(){
        return{
            fields: fields,
            loading: false,
            laboratorioId: 0,
            arrTipo: <?= json_encode($arrTipo) ?>,
            arrCategoria: <?= json_encode($arrCategoria) ?>,
            arrDependencia: <?= json_encode($arrDependencia) ?>,
            validationStatus: 0,
            validation: {
                hora_fin_posterior: -1
            },
            section: 'form',
            appUid: APP_UID,
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
            var formValues = new FormData(document.getElementById('laboratorioForm'))
            axios.post(URL_API + 'barrios_vivos/save/', formValues)
            .then(response => {
                if ( response.data.saved_id > 0 )
                {
                    this.laboratorioId = response.data.saved_id
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
            window.location = URL_APP + 'barrios_vivos/edit/' + this.laboratorioId
        },
        clearCategoria: function(){
            this.fields['categoria_laboratorio'] = ''
        },
        setValidationStatus: function(){
            this.validationStatus = 1
            /*Object.keys(this.validation).forEach(key => {
                if ( this.validation[key] <= 0 ) {
                    this.validationStatus = 0
                }
            })*/
        },
    },
    computed: {
        
    },
    mounted(){
        //this.getList()
    }
}).mount('#addLaboratorioApp')
</script>