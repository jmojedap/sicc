
<script>
var fields = <?= json_encode($row) ?>;

// VueApp
//-----------------------------------------------------------------------------
var editLaboratorioApp = createApp({
    data(){
        return{
            fields: fields,
            loading: false,
            accionId: 0,
            arrTipo: <?= json_encode($arrTipo) ?>,
            arrCategoria: <?= json_encode($arrCategoria) ?>,
            arrDependencia: <?= json_encode($arrDependencia) ?>,
            arrLocalidad: <?= json_encode($arrLocalidad) ?>,
            arrBarrios: <?= json_encode($arrBarrios) ?>,
            arrEstadoRegistro: <?= json_encode($arrEstadoRegistro) ?>,
            validationStatus: 0,
            validation: {
                hora_fin_posterior: -1
            },
            nombreCortoDisabled: true   
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
                    toastr['success']('Datos guardados')
                }
                this.loading = false
            })
            .catch(function (error) { console.log(error) })
        },
        setValidationStatus: function(){
            this.validationStatus = 1
            /*Object.keys(this.validation).forEach(key => {
                if ( this.validation[key] <= 0 ) {
                    this.validationStatus = 0
                }
            })*/
        },
        textoToClass: function(texto){
            return Pcrn.textoToClass(texto)
        },
    },
    computed: {
        arrBarriosFiltered: function() {
            console.log(this.fields.localidad_cod)
            return this.arrBarrios.filter((item) => {
                return item.cod_localidad == this.fields.localidad_cod
            })
        }
    },
    mounted(){
        //this.getList()
    }
}).mount('#editLaboratorioApp')
</script>