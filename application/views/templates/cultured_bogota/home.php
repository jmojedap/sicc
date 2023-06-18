<style>
.text-white {
    color: white;
}

.input-search {
    width: 100%;
    border-radius: 0.5em;
    border: 1px solid #0256c7;
    background-color: #f6f9fd;
    height: 3em;
}

.grid-3 {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  align-items: stretch;
}

.icon-modulo {
    border: 1px solid #f6f9fd;
    padding: 0.2em;
    border-radius: 20px;
}

.icon-modulo:hover {
    border: 1px solid #FFEBF4;
}

.card:hover{
    border-color: #94c1fe;
}

.grid-columns-30rem{
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(20rem, 1fr));
    gap: 0.5em;
}

</style>
<div id="homeCultuRedBogotaApp">
    <section class= container">
        <div class="py-lg-5">
            <div class="center_box_750 text-center">
                <img src="<?= URL_RESOURCES ?>brands/cultured_bogota/logo-start.png" alt="Logo cultured_bogota"
                    class="w120p mb-4">
                <h1 class="title">CultuRed_Bogotá<span v-bind:class="{'text-white': hideCursor }">_</span></h1>
                <p class="lead text-muted">
                    Contenidos, datos y servicios del sector de cultura, recreación y deporte en un solo lugar.
                </p>
                <p class="d-none">
                    <a href="#" class="btn btn-primary my-2 me-2 w120p">Explorar</a>
                    <a href="#" class="btn btn-secondary my-2 w120p">Ingresar</a>
                </p>
                <div class="mb-5">
                    <input class="form-control input-search form-control-lg" type="text" v-model="q" v-on:change="filterComponentes"
                        placeholder="Busca información y servicios">
                </div>
            </div>
            
            <!-- MÓDULOS -->
            <div class="container mb-2" v-show="section == `modulos`">
                <h3 class="title text-center mb-5">¿Qué puedes encontrar en CultuRed_Bogotá?</h3>
                <div class="grid-3">
                    <div class="card" v-for="(modulo,key) in modulos">
                        <div class="card-body">
                            <p class="text-center">
                                <a v-on:click="setModulo(key)" v-bind:class="`color-text-` + modulo.classColor" class="pointer">
                                    <i v-bind:class="modulo.icon" class="fa-3x icon-modulo"></i>
                                </a>
                            </p>
                            <a class="pointer" v-on:click="setModulo(key)">
                                <h5 class="card-title text-center">{{ modulo.nombre }}</h5>
                            </a>
                            <p class="text-left">
                               {{ modulo.descripcion }} 
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="container" v-show="section == `moduloDetalle`">
                <div class="d-flex mb-2">
                    <div class="w320p me-3 rounded">
                        <img v-bind:src="`<?= URL_IMG ?>sicc/home/` + currentModulo.id + `.jpg`" alt="Imágen módulo" class="w320p">
                    </div>
                    <div class="">
                        <div class="mb-2">
                            <button class="btn btn-light" v-on:click="unsetModulo">
                                <i class="fa fa-arrow-left"></i> Volver
                            </button>
                        </div>
                        <h2><strong>{{ currentModulo.nombre }}</strong>
                            
                        </h2>
                        <p>
                            {{ currentModulo.contenido }}
                        </p>
                    </div>
                </div>
                <div class="grid-columns-30rem">
                    <div class="card mb-2" v-for="componente in componentes" v-show="componente.modulo_id == currentModulo.id">
                        <div class="card-body">
                            <div class="text-center" v-bind:class="`color-text-` + componente.imageIndex">
                                <i v-bind:class="icons[componente.imageIndex]" class="fa-3x icon-modulo"></i>
                            </div>
                            <h5 class="card-title">{{ componente.servicio }}</h5>
                            <p>{{ componente.descripcion }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- COMPONENTE FILTRADOS -->
            <div class="center_box_750" v-show="section == `componentes`">
                <h3 class="title text-center mb-5">{{ componentesFiltrados.length }} resultados sobre "{{ q }}"</h3>
                <div class="card mb-2" v-for="componente in componentesFiltrados">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <p>
                                <small>
                                    <span v-bind:class="`color-text-` + componente.classColor"><i v-bind:class="componente.icon"></i></span>
                                    {{ componente.moduloNombre }}
                                </small>
                            </p>
                            <div>
                                <button class="btn btn-secondary btn-sm w50p">
                                    Abrir
                                </button>
                            </div>
                        </div>
                        <h5 class="card-title">{{ componente.servicio }}</h5>
                        <p>{{ componente.descripcion }}</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
var homeCultuRedBogotaApp = createApp({
    data() {
        return {
            //section: 'modulos',
            loading: false,
            fields: {},
            hideCursor: false,
            q: '',
            modulos: <?= $modulos ?>,
            componentes: <?= $componentes ?>,
            currentModulo: {id:-1},
            icons: [
                'fa-solid fa-pencil',
                'fa-solid fa-music',
                'fa-solid fa-map',
                'fa-solid fa-palette',
                'fa-solid fa-kit-medical',
                'fa-solid fa-volleyball',
                'fa-solid fa-book',
                'fa-solid fa-camera',
                'fa-solid fa-house',
                'fa-solid fa-user-group',
            ]
        }
    },
    methods: {
        animateCursor: function() {
            setInterval(() => {
                this.hideCursor = !this.hideCursor
            }, 400);
        },
        setModulo: function(index){
            this.currentModulo = this.modulos[index]
            this.section = 'moduloDetalle'
        },
        unsetModulo: function(){
            this.currentModulo = {id:-1}
            this.section = 'modulos'
        },
        filterComponentes: function(){
            if ( this.q.length > 0 ) {
                this.section = 'componentes'
            } else {
                this.section = 'modulos'
            }
        },
    },
    computed: {
        section: function(){
            if ( this.q.length > 0 ) return 'componentes'
            if ( this.currentModulo.id > 0 ) return 'moduloDetalle'
            return 'modulos'
        },
        componentesFiltrados: function(){
            if ( this.q.length > 0 ) {
                var componentesFiltrados = this.componentes.filter((item) =>
                    item.keywords.toLowerCase().includes(this.q.toLowerCase())
                );
                return componentesFiltrados
            }
            return this.componentes
        },
    },
    mounted() {
        //this.getList()
        this.animateCursor()
    }
}).mount('#homeCultuRedBogotaApp')
</script>