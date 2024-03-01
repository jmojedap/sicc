
<div id="avancePlanApp">
    <h2 class="text-center">Informe de avance del plan de acción</h2>
    <p class="text-center">
        Este documento presenta los avances de la ejecución del plan de acción orientado al cumplimiento de las obligaciones del contrato 170 de 2023.
    </p>
    
    <table class="table table-condensed table-bordered">
        <tbody>
            <tr>
                <td width="30%">Fecha</td>
                <td>2/Ene/2024</td>
            </tr>
            <tr>
                <td>Entidad</td>
                <td>Secretaría Distrital de Cultura, Recreación y Deporte</td>
            </tr>
            <tr>
                <td>Contrato No.</td>
                <td>170 de 2023</td>
            </tr>
            <tr>
                <td>Nombre completo del contratista</td>
                <td>Javier Mauricio Ojeda Pepinosa</td>
            </tr>
        </tbody>
    </table>

    <h2 class="text-center">
        Acciones del plan ({{ acciones.length }})
    </h2>

    <table class="table bg-white d-none">
        <thead>
            <th class="text-center" width="50px">Núm.</th>
            <th class="text-center">Acción</th>
            <th class="text-center" width="50px">% Avance</th>
        </thead>
        <tbody>
            <tr v-for="(accion, key) in acciones">
                <td class="text-center">{{ accion.num }}</td>
                <td>
                    <strong class="text-primary">{{ accion.accion }}</strong>
                    <!-- <br>
                    {{ accion.descripcion }} -->
                </td>
                <td class="text-center">
                    {{ accion.avance }}
                    
                </td>
            </tr>
        </tbody>
    </table>

    <h2>Actividades específicas</h2>

    <div v-for="accion in acciones" class="border-bottom py-2">
        <h4 class="text-primary">
            {{ accion.num }}) {{ accion.accion }}
        </h4>
        <p>
            <b>Descripción: </b>{{ accion.descripcion }}
        </p>
        <p>
            <b>Resultado: </b>{{ accion.resultado_cualitativo }}
        </p>
        <p>
            <span class="text-muted">Radicados Orfeo Asociados: </span><strong class="">{{ accion.radicados_orfeo }}</strong>
        </p>
        <div class="progress">
            <div class="progress-bar" v-bind:class="classAvance(accion.avance)" role="progressbar" v-bind:style="`width: ` + accion.avance + `%;`" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">{{ accion.avance }}%</div>
        </div>
        <div class="py-2">
            <h5>Actividades relacionadas</h5>
            <div v-for="actividad in actividades" v-show="actividad.num_accion == accion.num" class="ps-4">
                {{ actividad.titulo }}
                <br>
                Link >> <a v-bind:href="actividad.url_evidencia" target="_blank">{{ actividad.url_evidencia.substring(0,50) }}...</a>
            </div>
        </div>

    </div>

    <p class="text-muted text-center">FIN DEL DOCUMENTO</p>
</div>

<script>
var avancePlanApp = createApp({
    data(){
        return{
            acciones: <?php echo json_encode($acciones) ?>,
            actividades: <?php echo json_encode($actividades) ?>,            
        }
    },
    methods: {
        classAvance: function(avance){
            var classAvance = 'bg-warning'
            avance = avance
            if ( avance < 5 ) { avance = 5; }
            if ( avance > 25  ) classAvance = 'bg-warning'
            if ( avance > 45  ) classAvance = 'bg-primary'
            if ( avance > 80  ) classAvance = 'bg-success'

            return classAvance
        },
    },
    mounted(){
        //this.getList()
    }
}).mount('#avancePlanApp')
</script>