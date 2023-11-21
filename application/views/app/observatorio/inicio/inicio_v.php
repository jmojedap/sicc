<script>

</script>

<script src="<?= URL_CONTENT ?>observatorio/secciones/modulos.js"></script>

<style>
.modulos-container {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1em;
}

/* Pantallas pequeñas */
@media (max-width: 767px) {
    .modulos-container { 
        grid-template-columns: 1fr;
    }
}

.modulo {
    text-align: center;
    background-color: #FFF;
    min-height: 200px;
}
</style>

<div id="inicioApp">
    <div class="center_box_750">
        <div class="modulos-container">
            <div class="card" v-for="(modulo,k) in modulos" v-show="modulo.ocultar != 1">
                <div class="card-body">
                    <div class="text-center">
                        <p v-bind:class="`color-text-` + (k+1)">
                            <i class="fas fa-2x" v-bind:class="modulo.fa_icon"></i>
                        </p>
                        <a v-bind:href="`<?= URL_APP ?>` + modulo.link">
                            <p class="lead color-text-2">
                                {{ modulo.titulo }}
                            </p>
                            <p>
                        </a>
                        {{ modulo.descripcion }}
                        </p>
                    </div>
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

    },
    mounted() {
        //this.getList()
    }
}).mount('#inicioApp')
</script>