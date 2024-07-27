
<div id="informeEjecutivoApp">
    <h2 class="text-center">Informe Ejecutivo Final</h2>
    <p class="text-center">
        Este documento presenta un balance de los logros de la ejecución del contrato 170 de 2023.
    </p>
    
    <table class="table table-condensed table-bordered">
        <tbody>
            <tr>
                <td width="30%">Fecha</td>
                <td>30/Abr/2024</td>
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
        Obligaciones ({{ obligaciones.length }})
    </h2>

    <h2>Obligaciones específicas</h2>

    <div v-for="obligacion in obligaciones" class="border-bottom py-2">
        <h4 class="text-primary">
            {{ obligacion.titulo }}
        </h4>
        <p>
        <b>Obligación: </b>{{ obligacion.obligacion }}
        </p>
        <p>
            <b>Logros: </b> <span v-html="obligacion.logros"></span>
        </p>
        <p v-show="obligacion.pendientes.length > 0">
            <b>Pendientes: </b>{{ obligacion.pendientes }}
        </p>
        <p>
            <span class="text-muted">Productos finales: </span><strong class="">{{ obligacion.productos_finales }}</strong>
        </p>
        

    </div>

    <p class="text-muted text-center">FIN DEL DOCUMENTO</p>
</div>

<script>
var informeEjecutivoApp = createApp({
    data(){
        return{
            obligaciones: <?= json_encode($obligaciones) ?>,
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
}).mount('#informeEjecutivoApp')
</script>