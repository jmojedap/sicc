<div id="requerimientosApp">
    <div class="row">
        <div class="col-md-3">
            <h3>Detalle requerimientos</h3>
        </div>
        <div class="col-md-9">
            <table class="table bg-white">
                <thead>
                    <th>#</th>
                    <th>Requerimiento</th>
                </thead>
                <tbody>
                    <tr v-for="(requerimiento, key) in requerimientos">
                        <td>{{ requerimiento.id }}</td>
                        <td>
                            <strong>{{ requerimiento.titulo }}</strong>
                            <br>
                            {{ requerimiento.descripcion }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
var requerimientosApp = createApp({
    data(){
        return{
            loading: false,
            fields: {},
            requerimientos: <?= json_encode($requerimientos) ?>
        }
    },
    methods: {
        
    },
    mounted(){
        //this.getList()
    }
}).mount('#requerimientosApp')
</script>