<style>
    .medicion .opcion-respuesta {
        background-color: #e7e1f2;
        font-size: 0.9em;
        padding: 0.1em 0.5em;
        margin-right: 0.5em;
        min-width: 40px;
        display: inline-block;
        text-align: center;
        cursor: pointer;
        border-radius: 2px;
        margin-bottom: 0.5em;
    }

    .medicion .opcion-respuesta:hover {
        background-color: #fbb62d;
    }
</style>

<div id="medicionFormularioApp" class="medicion">
    <div class="row">
        <div class="col-md-4">
        <div class="list-group">
            <button type="button" class="list-group-item list-group-item-action"
                v-on:click="setSeccion(-1)" v-bind:class="{'active': currentSeccion == -1 }"
                >
                Todas las secciones ({{ secciones.length }})
            </button>
            <button type="button" class="list-group-item list-group-item-action"
                v-for="seccion in secciones" v-on:click="setSeccion(seccion.num_seccion)" v-bind:class="{'active': currentSeccion == seccion.num_seccion }"
                >
                <strong class="me-3">{{ seccion.num_seccion }}</strong>
                {{ seccion.nombre_seccion }}
            </button>
        </div>
        </div>
        <div class="col-md-8">
            <div class="card_no">
                <div class="card-body_no">
                    <div v-for="(pregunta, k) in preguntasSeccion" class="card mb-2">
                        <div class="card-header"><span class="badge bg-primary me-2">{{ pregunta.etiqueta_1 }}</span> {{ pregunta.nombre }}</div>
                        <div class="card-body">
                            <p class="lead text-center">{{ pregunta.enunciado_1 }}</p>
                            <p class="text-muted text-center fst-italic">
                                <small>{{ pregunta.instruccion }}</small>
                            </p>
                            <!-- CUANDO LA PREGUNTA TIENE MÁS DE UNA VARIABLE -->
                            <table class="table table-sm" v-if="pregunta.cantidad_variables > 1">
                                <tbody>
                                    <tr v-for="variable in variablesPregunta(pregunta.id)">
                                        <td width="20px"><span class="text-primary">{{ variable.etiqueta_orden }}</span></td>
                                        <td>{{ variable.enunciado_2 }}</td>
                                        <td>
                                            <span v-if="variable.tipo >= 50" class="opcion-respuesta" v-for="opcion in opcionesVariable(variable.id)">{{ opcion.texto_opcion }}</span>
                                            <input v-if="variable.tipo < 50" disabled class="form-control" v-bind:placeholder="variable.enunciado_2">
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <!-- CUANDO LA PREGUNTA SOLO UNA VARIABLE -->
                            <div v-if="pregunta.cantidad_variables == 1">
                                <p v-for="variable in variablesPregunta(pregunta.id)" class="text-center">
                                    <span v-if="variable.tipo >= 50" class="opcion-respuesta" v-for="opcion in opcionesVariable(variable.id)">{{ opcion.texto_opcion }}</span>
                                    <input v-if="variable.tipo < 50" disabled class="form-control" v-bind:placeholder="variable.enunciado_2">
                                </p>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
var medicionFormularioApp = createApp({
    data(){
        return{
            loading: false,
            fields: {},
            secciones: <?= json_encode($secciones->result()) ?>,
            preguntas: <?= json_encode($preguntas->result()) ?>,
            variables: <?= json_encode($variables->result()) ?>,
            opciones: <?= json_encode($opciones->result()) ?>,
            currentSeccion: 2,
        }
    },
    methods: {
        setSeccion: function(numSeccion){
            this.currentSeccion = numSeccion;
        },
        variablesPregunta: function(preguntaId){
            var variablesPregunta = this.variables.filter(variable => variable.pregunta_id == preguntaId)
            return variablesPregunta
        },
        opcionesVariable: function(variableId){
            var opcionesVariable = this.opciones.filter(opcion => opcion.variable_id == variableId)
            return opcionesVariable
        },
    },
    computed: {
        preguntasSeccion: function(){
            var preguntasSeccion = this.preguntas.filter(pregunta => pregunta.num_seccion == this.currentSeccion)
            if ( this.currentSeccion == -1 ) {
                preguntasSeccion = this.preguntas
            }
            return preguntasSeccion
        },
    },
    mounted(){
        //this.getList()
    }
}).mount('#medicionFormularioApp')
</script>