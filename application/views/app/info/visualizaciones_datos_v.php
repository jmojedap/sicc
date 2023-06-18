<div id="visualizacionesDatosApp">
    <div class="text-center" v-show="loading">
        <div class="spinner-border text-secondary" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>
    <div class="row" v-show="!loading">
        <div class="col-md-4">
            <h3>
                Tableros Power Bi
            </h3>
            <p>
                Observatorio de Cultura 2022-2023
            </p>
        </div>
        <div class="col-md-8">
            <div class="card mb-2" v-for="tablero in tableros">
                <div class="row g-0">
                    <div class="col-md-3">
                        <a v-bind:href="tablero.url_powerbi" target="_blank">
                            <img
                                v-bind:src="`<?= URL_CONTENT ?>dataviz/thumbnails/` + tablero.id + `.jpg`"
                                class="w-100"
                                v-bind:alt="tablero.nombre"
                                onerror="this.src='<?= URL_IMG ?>app/nd_power_bi.png'"
                            >
                        </a>
                    </div>
                    <div class="col-md-9">
                        <div class="card-body">
                            <h5 class="card-title">
                                {{ tablero.nombre }}
                            </h5>
                            <p>{{ tablero.descripcion }}</p>
                            <p v-show="displayUrl">
                                <a v-bind:href="tablero.url_powerbi" class="clase" target="_blank">
                                    {{ tablero.url_powerbi }}
                                </a>
                            </p>
        
                        </div>
                    </div>
                </div>  
            </div>
        </div>
    </div>
</div>

<script>
var visualizacionesDatosApp = createApp({
    data(){
        return{
            tableros: [],
            loading: false,
            fields: {},
            displayUrl: false,
            fileId: '<?= $fileId ?>',
            gid: '<?= $gid ?>',
        }
    },
    methods: {
        getList: function(gid){
            this.loading = true
            axios.get(URL_API + 'app/googlesheet_array/' + this.fileId + '/' + this.gid)
            .then(response => {
                this.tableros = response.data
                this.loading = false
            })
            .catch(function(error) { console.log(error) })
        },
    },
    mounted(){
        this.getList()
    }
}).mount('#visualizacionesDatosApp')
</script>