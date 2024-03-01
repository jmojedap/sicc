<script src="<?= URL_CONTENT ?>observatorio/otros/equipos_funciones.js"></script>
<script src="<?= URL_CONTENT ?>observatorio/otros/equipos_funciones_casos.js"></script>

<?php $this->load->view('app/observatorio/equipos/style_v') ?>

<h4 class="page-title">
    Funciones y Actividades
</h4>
<div id="funcionesApp">


    <div class="text-center" v-show="loading">
        <div class="spinner-border text-secondary" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4" v-for="tema in temas">
            <h3 class="tema" v-bind:class="`tema-` + tema.valor">
                {{ tema.titulo }}
            </h3>
            <div v-for="(funcion,i) in funciones" v-show="funcion.tema == tema.valor"
                data-bs-toggle="modal" data-bs-target="#detailsModal" class="funcion" v-on:click="setCurrentFuncion(i)"
                >
                {{ funcion.nombre }}
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="detailsModal" tabindex="-1" aria-labelledby="detailsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="detailsModalLabel">{{ currentFuncion.nombre }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>
                        {{ currentFuncion.descripcion }}
                    </p>
                    <h3 class="subseccion">Funciones</h3>
                    <p>
                        {{ currentFuncion.funciones }}
                    </p>

                    <h3 class="subseccion">Casos específicos</h3>
                    <div class="row" v-for="(caso,k) in casos" v-show="caso.funcion_id == currentFuncion.id">
                        <div class="col-md-4">
                            <a v-bind:href="caso.link" target="_blank">
                                <img v-bind:src="`<?= URL_CONTENT ?>observatorio/funciones_casos/` + caso.id + `.jpg`"
                                    class="w-100 rounded shadow mb-2" v-bind:alt="caso.nombre"
                                    v-bind:onerror="`this.src='<?= URL_CONTENT ?>observatorio/funciones_casos/caso-general.png'`">
                            </a>
                        </div>
                        <div class="col-md-8">
                            <strong>{{ caso.nombre }}</strong>
                            <p>{{ caso.descripcion }}</p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<?php $this->load->view('app/observatorio/equipos/vue_v') ?>