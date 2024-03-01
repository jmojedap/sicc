<script>

// VueApp
//-----------------------------------------------------------------------------
var ebcApp = createApp({
    data() {
        return {
            loading: false,
            section_1: 'informacion',
            fields: {},
            moduloId: 0,
            modulos: ebc_modulos,
            currentModulo: {
                modulo_id: 0,
                nombre:'Cargando...',
                medicion_id: 158
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
        btnModuloClass: function(modulo){
            var moduloClass = 'btn-modulo-' + modulo.modulo_id
            if ( modulo.modulo_id == this.currentModulo.modulo_id ) {
                moduloClass += ' active'
            }
            return moduloClass
        },
        setModulo: function(modulo_id){
            this.currentModulo = this.modulos.find(modulo => modulo.modulo_id == modulo_id )
            this.getMedicionData()
        },
        getMedicionData: function(){
            axios.get(URL_API + 'mediciones/get_contenido/' + this.currentModulo.medicion_id)
            .then(response => {
                this.medicionData = response.data
                this.setPrimeraPregunta()
            })
            .catch(function(error) { console.log(error) })
        },
        setSeccion: function(numSeccion){
            this.medicionSeccionActiva = numSeccion
        },
        setPregunta: function(preguntaId){
            this.medicionPreguntaActiva = preguntaId
        },
        setPrimeraPregunta: function(){
            this.medicionPreguntaActiva = 0
            if ( this.medicionData.preguntas.length > 0 ) {
                this.medicionPreguntaActiva = this.medicionData.preguntas[0].id
            }
        },
    },
    computed:{
        
    },
    mounted() {
        this.getMedicionData()
        this.setModulo(2)
    }
}).mount('#ebcApp')
</script>