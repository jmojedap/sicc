<script>
var entidadesParticipantesApp = createApp({
    data(){
        return{
            row: <?= json_encode($row) ?>,
            fields: {
                relacionado_1: '10',
                nombre: '',
                descripcion: '',
                cantidad: 1,
                cod_detalle: ''
            },
            filters:{
                'prnt':<?= $row->id ?>,
            },
            loading: false,
            arrTipoEntidad: <?= json_encode($arrTipoEntidad) ?>,
            detalles: [],
        }
    },
    methods: {
        handleSubmit: function(){
            this.loading = true
            var formValues = new FormData(document.getElementById('entidadesParticipantesForm'))
            formValues.append('descripcion', this.tipoEntidadName(this.fields.relacionado_1))
            axios.post(URL_API + 'acciones/save_detail/', formValues)
            .then(response => {
                if ( response.data.saved_id > 0 ) {
                    toastr['success']('Detalle guardado')
                    this.getDetails()
                    this.fields.cod_detalle = ''
                    this.clearForm()
                    this.setCodDetalle()
                }
                this.loading = false
            })
            .catch( function(error) {console.log(error)} )
        },
        getDetails: function(){
            this.loading = true
            var formValues = new FormData()
            formValues.append('prnt', this.row.id)  //ID Acción
            formValues.append('type', 130)  //Tipo detalle de la acción
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
                relacionado_1: '10',
                nombre: '',
                descripcion: '',
                cantidad: 1,
                cod_detalle: ''
            }
        },
        tipoEntidadName: function(value = '', field = 'name'){
            var tipoEntidadName = ''
            var item = this.arrTipoEntidad.find(row => row.cod == value)
            if ( item != undefined ) tipoEntidadName = item[field]
            return tipoEntidadName
        },
        setCodDetalle: function(){
            //Establecer valor diferente para que pueda ser guardado en otro registro
            this.fields.cod_detalle = Math.floor(Math.random() * (99999 - 10000 + 1)) + 10000;

        },
        // Formato y valores
        //-----------------------------------------------------------------------------
        ago: function(date){
            if (!date) return ''
            return moment(date, 'YYYY-MM-DD HH:mm:ss').fromNow()
        },
        dateFormat: function(date){
            if (!date) return ''
            return moment(date).format('D MMM YYYY')
        },
    },
    mounted(){
        this.setCodDetalle()
        this.getDetails()
    }
}).mount('#entidadesParticipantesApp')
</script>