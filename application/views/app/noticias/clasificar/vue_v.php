<script>
var clasificarApp = createApp({
    data(){
        return{
            username: '<?= $this->session->userdata('username') ?>',
            noticia: <?= json_encode($row) ?>,
            loading: false,
            fields: {
                cat_1: <?= $row->cat_1 ?>,
                clasificacion: <?= $row->clasificacion ?>,
                compartible: <?= $row->compartible ?>,
            },
            optionsClasificacion: <?= json_encode($options_clasificacion) ?>,
            optionsCat: <?= json_encode($options_cat_1) ?>,
            optionsCompartible: [
                {value: 1, name: 'No la compartiría'},
                {value: 2, name: 'Sí la compartiría'},
            ],
            savedId: 0,
            checkGoal: <?= $checkGoal ?>,
            qtyUserChecked: <?= $qtyUserChecked ?>,
            section: 'clasificar',
        }
    },
    methods: {
        setClasificacion: function(value){
            this.fields.clasificacion = value
        },
        setCat1: function(value){
            this.fields.cat_1 = value
        },
        setCat2: function(value){
            this.fields.cat_2 = value
        },
        setCompartible: function(value){
            this.fields.compartible = value
        },
        sendForm: function(){
            this.loading = true
            var fields = new FormData(document.getElementById('noticiaForm'))

            axios.post(URL_APP + 'noticias/actualizar/' + this.noticia.id, fields)
            .then(response => {
                if ( response.data.saved_id > 0 ) {
                    this.savedId = response.data.saved_id
                    toastr['success']('Cargando siguiente...')
                    this.qtyUserChecked++
                    this.goToNext(500)
                }
                this.loading = false
            })
            .catch( function(error) {console.log(error)} )
        },
        btnClasificacionClass: function(value){
            currentOption = this.optionsClasificacion.find(item => item.value == value)
            if ( this.fields.clasificacion == value) {
                return currentOption.class;
            } else {
                return currentOption.emptyClass;
            }
        },
        goToNext: function(ms = 0){
            setTimeout(() => {
                window.location = URL_APP + 'noticias/siguiente/' + this.checkGoal
            }, ms);
        },
        setSection: function(value){
            this.section = value
        },
        // Formatos y nombres
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
    computed:{
        submitDisabled: function(){
            if ( this.fields.cat_1 == 0 ) return true
            if ( this.fields.clasificacion == 0 ) return true
            if ( this.fields.compartible == 0 ) return true
            return false
        },
        checkedPercent: function() {
            return Pcrn.intPercent(this.qtyUserChecked, this.checkGoal)
        }
    },
    mounted(){
        //this.getList()
    },
}).mount('#clasificarApp')
</script>