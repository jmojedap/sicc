
<script>
var rowClasificacion = {
    cat_1: 0,
    cat_2: 0,
    cat_3: 0,
    cat_4: 0,
    texto_1: '',
    updated_at: ''
};
<?php if ( ! is_null($rowClasificacion) ) : ?>
var rowClasificacion = <?= json_encode($rowClasificacion) ?>;
<?php endif; ?>

// VueApp
//-----------------------------------------------------------------------------
var clasificarApp = createApp({
    data(){
        return{
            username: '<?= $this->session->userdata('username') ?>',
            noticia: <?= json_encode($row) ?>,
            loading: false,
            fields: rowClasificacion,
            arrCat1: [
                {'cod':'2','name':'No'},
                {'cod':'1','name':'Sí'},
            ],
            arrCat2: [
                {"cod":"110","name":"Cantante, bailarín, actor o humorista"},
                {"cod":"120","name":"Víctima de una agresión o delito"},
                {"cod":"130","name":"Presunto perpetrador de agresión o delito"},
                {"cod":"140","name":"Deportista"},
                {"cod":"150","name":"Político"},
                {"cod":"160","name":"Policía"},
                {"cod":"170","name":"Estudiante, docente, Investigador/a Académica"},
                {"cod":"180","name":"Líder religioso"},
                {"cod":"190","name":"Líder social"},
                {"cod":"200","name":"Persona que ejerce actividades sexuales pagas; trabajo sexual"},
                {"cod":"210","name":"Persona privada de la libertad"},
                {"cod":"220","name":"Persona habitante de calle"},
                {"cod":"230","name":"Trabajador/a del cuidado, servicios generales; trabajo doméstico"},
                {"cod":"990","name":"Otra recurrente"}
            ],
            arrCat3: [
                {"cod":"310","name":"No reporta"},
                {"cod":"320","name":"Mujer cisgénero"},
                {"cod":"330","name":"Hombre cisgénero"},
                {"cod":"340","name":"Mujer trans"},
                {"cod":"350","name":"Hombre trans"},
                {"cod":"360","name":"Persona no binaria"},
                {"cod":"370","name":"Persona intersexual"}
            ],
            arrCat4: [
                {"cod":"510","name":"No reporta"},
                {"cod":"520","name":"Hombre gay"},
                {"cod":"530","name":"Mujer lesbiana"},
                {"cod":"540","name":"Hombre heterosexual"},
                {"cod":"550","name":"Mujer heterosexual"},
                {"cod":"560","name":"Hombre bisexual"},
                {"cod":"570","name":"Mujer bisexual"},
                {"cod":"580","name":"Persona pansexual"},
                {"cod":"590","name":"Persona asexual"}
            ],
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
        setCat1: function(value){
            this.fields.cat_1 = value
            if ( value == '02' ) {
                this.fields.cat_2 = 0
                this.fields.cat_3 = 0
                this.fields.cat_4 = 0
            }
        },
        setCat2: function(value){
            this.fields.cat_2 = value
        },
        setCat3: function(value){
            this.fields.cat_3 = value
        },
        setCat4: function(value){
            this.fields.cat_4 = value
        },
        setCompartible: function(value){
            this.fields.compartible = value
        },
        sendForm: function(){
            this.loading = true
            var fields = new FormData(document.getElementById('noticiaForm'))

            axios.post(URL_APP + 'noticias_afro/guardar_clasificacion/' + this.noticia.id, fields)
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
                window.location = URL_APP + 'noticias_afro/siguiente/' + this.checkGoal
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
            if ( this.fields.cat_1 == '01' ) {
                if ( this.fields.cat_2 == 0 ) return true
                if ( this.fields.cat_3 == 0 ) return true
                if ( this.fields.cat_4 == 0 ) return true
            }
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