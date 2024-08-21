<div id="priorizacionGeograficaApp">
    <div class="center_box_920">
        <div class="mb-2 d-flex justify-content-between">
            <div class="me-1">
                <input type="checkbox" v-model="display.descripcion"> Descripción
            </div>
            <button class="btn btn-light btn-lg">
                Calcular
            </button>
        </div>
        <table class="table bg-white">
            <thead>
                <th>Variables</th>
                <th width="30%"></th>
                <th width="10px">Puntaje</th>
            </thead>
            <tbody>
                <tr v-for="(variable, key) in variables" v-show="variable.estado == 'Disponible'">
                    <td>
                        <strong>
                            {{ variable.nombre }}
                        </strong>
                        <p v-show="display.descripcion">
                            <small class="text-muted">{{ variable.tema }}</small>
                            &middot;
                            <small class="text-muted">{{ variable.entidad }}</small>
                        </p>
                        <p v-show="display.descripcion">
                            {{ variable.descripcion }}
                        </p>
                    </td>
                    <td>
                        <div class="puntaje-slider">
                            <input type="range" min="0" max="100" v-model="variable.puntaje" class="slider w-100">
                        </div>
                    </td>
                    <td class="text-center">
                        {{ variable.puntaje }}
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<script>
var priorizacionGeograficaApp = createApp({
    data(){
        return{
            loading: false,
            display: {
                descripcion: false
            },
            variables: <?= json_encode($variables) ?>,
        }
    },
    methods: {
        
    },
    mounted(){
        //this.getList()
    }
}).mount('#priorizacionGeograficaApp')
</script>