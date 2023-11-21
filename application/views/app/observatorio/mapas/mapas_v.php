<script src="<?= URL_CONTENT ?>observatorio/secciones/mapas.js"></script>
<script src="<?= URL_CONTENT ?>observatorio/secciones/mapas_subsecciones.js"></script>

<style>
.geo-content {
    width: 100%;
    height: calc(100vh - 180px);
    border: 1px solid #FAFAFA;
}
</style>

<div id="mapasApp">

    <ul class="nav nav-tabs mb-2 justify-content-center">
        <li class="nav-item" v-for="seccion in secciones">
            <a class="nav-link" href="#" v-bind:class="{'active': currentSeccion == seccion }" v-on:click="currentSeccion = seccion">
              {{ seccion }}
            </a>
        </li>
    </ul>
    <ul class="nav nav-pills mb-2 justify-content-center">
        <li class="nav-item" v-for="subseccion in subsecciones" v-show="subseccion.seccion == currentSeccion">
            <a class="nav-link" href="#" v-bind:class="{'active': currentSubseccion == subseccion.nombre }" v-on:click="setSubseccion(subseccion.nombre)">
              {{ subseccion.nombre }}
            </a>
        </li>
    </ul>

    <ul class="nav nav-pills mb-2 justify-content-center">
        <li class="nav-item" v-for="(mapa,key) in mapas" v-show="mapa.subseccion == currentSubseccion && mapa.seccion == currentSeccion">
            <a class="nav-link" href="#" v-bind:class="{'active': currentMapa.nombre == mapa.nombre }" v-on:click="setMapa(key)">
              {{ mapa.nombre }}
            </a>
        </li>
    </ul>

    <!-- <p>
      {{ currentMapa }}
    </p> -->

    <iframe class="geo-content" v-bind:src="currentMapa.url"
        frameborder="0"></iframe>
</div>

<script>
var mapasApp = createApp({
    data() {
        return {
            loading: false,
            currentSeccion: 'Agentes Culturales',
            currentSubseccion: 'Artistas',
            currentMapa: {},
            currentKeyMapa: 0,
            secciones: [
              'Agentes Culturales',
              'Espacios Culturales',
              'Gestión Cultural',
              'Investigación',
            ],
            subsecciones: dogcc_mapas_subsecciones,
            mapas: dogcc_mapas,
        }
    },
    methods: {
        getList: function(gid) {
            this.loading = true
            axios.get(URL_API + 'app/googlesheet_array/' + this.fileId + '/' + this.gid)
            .then(response => {
                this.mapas = response.data
                this.loading = false
            })
            .catch(function(error) { console.log(error)})
        },
        setSubseccion: function(subseccion){
          this.currentSubseccion = subseccion
          this.currentMapa = this.mapas.find(item => item.subseccion == this.currentSubseccion )
        },
        setMapa: function(key){
          this.currentKeyMapa = key
          this.currentMapa = this.mapas[key]
        },
    },
    mounted() {
        //this.getList()
        this.setMapa(0)
    }
}).mount('#mapasApp')
</script>