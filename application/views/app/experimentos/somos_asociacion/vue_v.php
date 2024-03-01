<script>
var somosAsociacionApp = createApp({
    data(){
        return{
            loading: false,
            fields: {},
            faseActiva: 1,
            fases: [
                {id:1, nombre:'Fase 1'},
                {id:2, nombre:'Fase 2'},
                {id:3, nombre:'Fase 3'},
                {id:4, nombre:'Fase 4'},
                {id:5, nombre:'Fase 5'},
                {id:6, nombre:'Fase 6'},
                {id:7, nombre:'Fase 7'},
                {id:8, nombre:'Fase 8'},
            ],
            opcionKey: 0,
            opciones: sa_opciones,
            opcionActiva: {
                numero: -1,
                opcion: "",
                tipo: "",
                respuesta_correcta: "",
                url_imagen: "",
                file_id: ""
            },
            respuestas: [],
            momentoInicio: 0,
            momentoFin: 0,
            status: 'preparacion',
        }
    },
    methods: {
        setOpcion: function(keyOpcion){
            this.opcionActiva = this.opciones[keyOpcion]
        },
        responder: function(respuesta_usuario){
            console.log(respuesta_usuario, this.opcionActiva.respuesta_correcta)
            if ( respuesta_usuario == this.opcionActiva.respuesta_correcta ) {
                this.momentoFin = Date.now()
                var respuesta = {
                    fase: this.faseActiva,
                    opcion: this.opcionActiva.opcion,
                    respuesta_correcta: this.opcionActiva.respuesta_correcta,
                    respuesta_usuario: respuesta_usuario,
                    milisegundos: this.momentoFin - this.momentoInicio,
                }
                this.respuestas.push(respuesta)
    
                if ( this.opcionKey < this.opciones.length ) {
                    this.opcionKey++
                    this.setOpcion(this.opcionKey)
                }
                this.momentoInicio = this.momentoFin
            } else {
                toastr['warning']('Intenta de nuevo')
            }

        },
        iniciar: function(){
            this.status = 'respondiendo'
            this.momentoInicio = Date.now()
        },
    },
    mounted(){
        this.setOpcion(0)
    }
}).mount('#somosAsociacionApp')
</script>