<script>


var actividadesApp = createApp({
    data(){
        return{
            displayFormat: 'cards',
            laboratorio: <?= json_encode($row) ?>,
            fields: {
                relacionado_1: '',
                cod_detalle: Math.floor(Math.random() * (99999 - 10000 + 1)) + 10000,
                fecha_1: moment().format('YYYY-MM-DD'),
            },
            detalleId: 0,
            filters:{
                'prnt':<?= $row->id ?>,
            },
            loading: false,
            detalles: [],
            arrFase: <?= json_encode($arrFase) ?>,
            appUid: APP_UID,
            appRid: APP_RID,
        }
    },
    methods: {
        handleSubmit: function(){
            this.loading = true
            var formValues = new FormData(document.getElementById('actividadesForm'))
            formValues.append('cod_detalle', this.fields.cod_detalle)  
            axios.post(URL_API + 'barrios_vivos/save_detail/', formValues)
            .then(response => {
                if ( response.data.saved_id > 0 ) {
                    toastr['success']('Actividad guardada')
                    this.getDetails()
                    modalForm.hide()
                    this.clearForm()
                }
                this.loading = false
            })
            .catch( function(error) {console.log(error)} )
        },
        getDetails: function(){
            this.loading = true
            var formValues = new FormData()
            formValues.append('prnt', this.laboratorio.id)  //ID Laboratorio
            formValues.append('type', 17101)  //Tipo detalle actividad del laboratorio
            axios.post(URL_API + 'barrios_vivos/get_details/', formValues)
            .then(response => {
                this.detalles = response.data.details
                this.loading = false
            })
            .catch( function(error) {console.log(error)} )
        },
        deleteElement: function(){
            axios.get(URL_API + 'barrios_vivos/delete_detail/' + this.laboratorio.id + '/' + this.detalleId)
            .then(response => {
                if ( response.data.qty_deleted > 0 ) {
                    toastr['info']('Actividad eliminada')
                    this.getDetails()
                }
            })
            .catch(function(error) { console.log(error) })
        },
        clearForm: function(){
            this.detalleId = 0
            this.fields = {
                relacionado_1: '',
                fecha_1: moment().format('YYYY-MM-DD'),
            }
            this.setCodDetalle()
        },
        setCodDetalle: function(){
            //Establecer valor diferente para que pueda ser guardado en otro registro
            this.fields.cod_detalle = Math.floor(Math.random() * (99999 - 10000 + 1)) + 10000;

        },
        setDetalle: function(detalleId){
            this.detalleId = detalleId
            this.fields = this.detalles.find((item) => {
                return item.id == detalleId
            })
        },
        // Formato y valores
        //-----------------------------------------------------------------------------
        ago: function(date){
            if (!date) return ''
            return moment(date, 'YYYY-MM-DD HH:mm:ss').fromNow()
        },
        dateFormat: function(date, format = 'D MMM YYYY'){
            if (!date) return ''
            return moment(date).format(format)
        },
        timeFormat: function(time) {
            if (!time) return '';
            return moment(time, 'HH:mm').format('h:mm A');
        },
        textToClass: function(texto){
            return Pcrn.textToClass(texto)
        },
    },
    mounted(){
        this.getDetails()
    }
}).mount('#actividadesApp');

const modalForm = new bootstrap.Modal(document.getElementById('formModal'))
</script>