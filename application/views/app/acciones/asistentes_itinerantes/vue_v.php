<script>
var asistentesItinerantesApp = createApp({
    data(){
        return{
            row: <?= json_encode($row) ?>,
            fields: {
                relacionado_1: '1005',
                relacionado_2: '',
                nombre: '',
                cantidad: '',
                categoria_1: '1',
                cod_detalle: '',
                descripcion: ''
            },
            filters:{
                'prnt':<?= $row->id ?>,
            },
            loading: false,
            arrGrupoPoblacion: <?= json_encode($arrGrupoPoblacion) ?>,
            arrIdentidadGenero: <?= json_encode($arrIdentidadGenero) ?>,
            arrTipoDocumento: <?= json_encode($arrTipoDocumento) ?>,
            arrLocalidad: <?= json_encode($arrLocalidad) ?>,
            detalles: [],
        }
    },
    methods: {
        handleSubmit: function(){
            this.loading = true
            var formValues = new FormData(document.getElementById('asistentesItinerantesForm'))
            axios.post(URL_API + 'acciones/save_detail/', formValues)
            .then(response => {
                if ( response.data.saved_id > 0 ) {
                    toastr['success']('Detalle guardado')
                    this.getDetails()
                    this.fields.cod_detalle = ''
                    this.clearForm()
                    //this.setCodDetalle()
                }
                this.loading = false
            })
            .catch( function(error) {console.log(error)} )
        },
        getDetails: function(){
            this.loading = true
            var formValues = new FormData()
            formValues.append('prnt', this.row.id)  //ID Acción
            formValues.append('type', 140)  //Tipo detalle de la acción
            axios.post(URL_API + 'acciones/get_details/', formValues)
            .then(response => {
                this.detalles = response.data.details
                this.loading = false
            })
            .catch( function(error) {console.log(error)} )
        },
        deleteDetail: function(detailId){
            axios.get(URL_API + 'acciones/delete_detail/' + this.row.id + '/' + detailId)
            .then(response => {
                if ( response.data.qty_deleted > 0 ) {
                    toastr['info']('Detalle eliminado')
                    this.getDetails()
                }
            })
            .catch(function(error) { console.log(error) })
        },
        clearForm: function(){
            this.fields = {
                relacionado_1: '1005',
                relacionado_2: '',
                cantidad: '',
                cod_detalle: ''
            }
        },
        grupoPoblacionName: function(value = '', field = 'name'){
            var grupoPoblacionName = ''
            var item = this.arrGrupoPoblacion.find(row => row.cod == value)
            if ( item != undefined ) grupoPoblacionName = item[field]
            return grupoPoblacionName
        },
        identidadGeneroName: function(value = '', field = 'name'){
            var identidadGeneroName = ''
            var item = this.arrIdentidadGenero.find(row => row.cod == value)
            if ( item != undefined ) identidadGeneroName = item[field]
            return identidadGeneroName
        },
        tipoDocumentoName: function(value = '', field = 'name'){
            var tipoDocumentoName = ''
            var item = this.arrTipoDocumento.find(row => row.cod == value)
            if ( item != undefined ) tipoDocumentoName = item[field]
            return tipoDocumentoName
        },
        setCodDetalle: function(){
            //Establecer valor diferente para que pueda ser guardado en otro registro
            this.fields.cod_detalle = Math.floor(Math.random() * (99999 - 10000 + 1)) + 10000;

        },
    },
    mounted(){
        //this.setCodDetalle()
        this.getDetails()
    }
}).mount('#asistentesItinerantesApp')
</script>