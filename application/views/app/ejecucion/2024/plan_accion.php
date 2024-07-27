<div id="planAccionApp">

    <h2 class="text-center">Introducción</h2>
    <p class="text-center">
        Este documento consiste en la definición de acciones orientadas al cumplimiento de las obligaciones específicas del contrato 306 de 2024.
    </p>
    <p class="text-muted text-center">Junio 28 de 2024</p>

    <div v-for="accion in acciones">
        <b class="text-muted">
            Acción {{ accion.num }}:
        </b>
        <b class="text-primary">
            {{ accion.accion }}: 
        </b>
        <br>
        <p>{{ accion.descripcion }}</p>
        <p>
            <span class="text-muted">Productos: </span>
            <b>{{ accion.productos }}</b>
            &middot;
            <span class="text-muted">Formato: </span>
            <b>{{ accion.formato }}</b>
            &middot;
            <span class="text-muted">Obligación relacionada: </span>
            <b>{{ accion.obligacion }}</b>
            &middot;
            <span class="text-muted">Fecha inicio: </span>
            <b>{{ accion.fecha_inicio }}</b>
            &middot;
            <span class="text-muted">Fecha fin: </span>
            <b>{{ accion.fecha_fin }}</b>
            &middot;
        </p>
        <hr>
    </div>

    <p class="text-muted text-center">FIN DEL DOCUMENTO</p>
    
</div>

<script>
var planAccionApp = createApp({
    data(){
        return{
            acciones: <?= json_encode($acciones) ?>,
            loading: false,
            fields: {},
        }
    },
}).mount('#planAccionApp')
</script>