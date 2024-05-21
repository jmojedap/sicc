<script>

</script>

<script src="<?= URL_CONTENT ?>observatorio/secciones/modulos.js"></script>

<style>
.modulos-container {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 0.5em;
}

/* Pantallas pequeñas */
@media (max-width: 767px) {
    .modulos-container {
        grid-template-columns: 1fr;
    }
}

.modulo {
    cursor: pointer;
}

.modulo-titulo{
    color: var(--color-text-link);
    font-size: 1.1em;
}
.modulo:hover {
    border: 1px solid var(--color-secondary);
}
</style>

<div id="inicioApp">
    <div class="center_box_920">
        <div class="modulos-container">
            <div class="card modulo" v-for="(modulo,k) in modulos" v-show="modulo.ocultar != 1" v-on:click="goToLink(modulo)">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <p class="modulo-titulo">
                            {{ modulo.titulo }}
                        </p>
                        <div v-bind:class="`color-text-` + (k+1)" class="me-2">
                            <i class="fas fs-4" v-bind:class="modulo.fa_icon"></i>
                        </div>
                    </div>
                    <p>{{ modulo.descripcion }}</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
var inicioApp = createApp({
    data() {
        return {
            loading: false,
            modulos: dogcc_modulos
        }
    },
    methods: {
        goToLink: function(modulo){
            window.location = URL_APP + modulo.link
        },
    },
    mounted() {
        //this.getList()
    }
}).mount('#inicioApp')
</script>