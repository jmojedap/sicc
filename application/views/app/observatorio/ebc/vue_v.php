<script>

// VueApp
//-----------------------------------------------------------------------------
var ebcApp = createApp({
    data() {
        return {
            loading: false,
            section_1: 'preguntas',
            fields: {},
            modulos: ebc_modulos,
            currentModulo: {
                modulo_id: 0,
                nombre:'Cargando...'
            },
            textos: ebc_textos,
            medicionId: 158,
            medicionData: {},
            medicionSeccionActiva: 5,
            medicionPreguntaActiva: 10147,
        }
    },
    methods: {
        linkModuloClass: function(modulo){
            var moduloClass = 'modulo-' + modulo.modulo_id
            if ( modulo.modulo_id == this.currentModulo.modulo_id ) {
                moduloClass += ' active'
            }
            return moduloClass
        },
        setModulo: function(modulo_id){
            this.currentModulo = this.modulos.find(modulo => modulo.modulo_id == modulo_id )
        },
        getMedicionData: function(){
            axios.get(URL_API + 'mediciones/get_contenido/' + this.medicionId)
            .then(response => {
                this.medicionData = response.data
            })
            .catch(function(error) { console.log(error) })
        },
        setSeccion: function(numSeccion){
            this.medicionSeccionActiva = numSeccion
        },
        setPregunta: function(preguntaId){
            this.medicionPreguntaActiva = preguntaId
        },
    },
    computed:{
        
    },
    mounted() {
        this.getMedicionData()
        this.setModulo(4)
    }
}).mount('#ebcApp')
</script>