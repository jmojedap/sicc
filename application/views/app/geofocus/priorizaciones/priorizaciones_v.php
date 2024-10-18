<script src="<?= URL_RESOURCES ?>js/pml_searcher.js"></script>
<!-- <link rel="stylesheet" href="<?= URL_RESOURCES ?>css/sicc/observatorio-priorizaciones.css"> -->

<?php $this->load->view('app/geofocus/priorizaciones/style_v') ?>

<div id="priorizacionesApp">
    <div class="text-center" v-show="loading">
        <div class="spinner-border text-secondary" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>

    <div class="center_box_920" v-show="!loading">
        <div class="search-container">
            <input class="search-input mb-2" type="text" v-model="q" v-bind:placeholder="`Buscar ` + nombreElemento"
                autofocus>
            <button class="search-button" v-show="this.q.length > 0" v-on:click="clearSearch()">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <p class="text-center"><strong class="color-text-1">
                {{ elementosFiltrados.length }}</strong> resultados
        </p>

        <!-- LISTA DE PRIORIZACIONES -->
        <div v-show="section == 'lista'">
            <div class="row mb-2 border-bottom pb-2" v-for="(elemento, i) in elementosFiltrados">
                <div class="col-md-1">
                    <span class="badge bg-primary">{{ elemento['id'] }}</span>
                </div>
                <div class="col-md-10">
                    <h5 class="card-title text-main">
                        <a v-bind:href="`<?= URL_APP ?>geofocus/parametrizacion/` + elemento['id']"
                            v-bind:title="`[` + elemento['id'] + `] ` + elemento['nombre']">
                            {{ elemento['nombre'] }}
                        </a>
                    </h5>
                    {{ elemento['descripcion'] }}
                    
                </div>
                <div class="col-md-1 text-end">
                    <button class="a4" v-on:click="goToEditForm(elemento['id'])">
                        <i class="fas fa-pencil-alt"></i>
                    </button>
                </div>
            </div>
        </div>

        <?php $this->load->view('app/geofocus/priorizaciones/form_v') ?>

    </div>
</div>

<?php $this->load->view('app/geofocus/priorizaciones/vue_v') ?>