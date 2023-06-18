<div id="obligaciones_app">
    <h1 class="text-center">Obligaciones</h1>    
    <p class="text-center">{{ obligaciones.length }} registros</p>
    <table class="table bg-white">
        <thead>
            <th>No.</th>
            <th>Título</th>
            <th>Obligación</th>
        </thead>
        <tbody>
            <tr v-for="(obligacion, key) in obligaciones">
                <td width="20px" class="text-center">{{ obligacion.no_obligacion }}</td>
                <td width="250px">
                    <strong class="text-main">{{ obligacion.titulo }}</strong>
                </td>
                <td>{{ obligacion.texto }}</td>
            </tr>
        </tbody>
    </table>
</div>

<script>
var obligaciones_app = new Vue({
    el: '#obligaciones_app',
    created: function(){
        //this.get_list()
    },
    data: {
        obligaciones: <?= json_encode($obligaciones->result()) ?>,
        loading: false,
    },
    methods: {
        
    }
})
</script>