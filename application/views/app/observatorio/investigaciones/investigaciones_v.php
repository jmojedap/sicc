<script src="<?= URL_RESOURCES ?>js/pml_searcher.js"></script>
<!-- <link rel="stylesheet" href="<?= URL_RESOURCES ?>css/sicc/observatorio-investigaciones.css"> -->

<div id="investigacionesApp">
    <div class="text-center" v-show="loading">
        <div class="spinner-border text-secondary" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>

    <div class="center_box_750" v-show="!loading">
        <div class="search-container">
            <input class="search-input mb-2" type="text" v-model="q"
                v-bind:placeholder="`Buscar ` + nombreElemento" autofocus>
            <button class="search-button" v-show="this.q.length > 0" v-on:click="clearSearch()">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <div class="mb-2">
            <select v-model="filters.status" class="search-filter" v-bind:class="{'selected': filters.status != '' }">
                <option value="">Todas</option>
                <option v-for="optionStatus in estados" v-bind:value="optionStatus">{{ optionStatus }}</option>
            </select>
        </div>

        <p class="text-center"><strong class="color-text-1">{{ elementosFiltrados.length }}</strong> resultados</p>

        <div class="row mb-2 border-bottom pb-2" v-for="elemento in elementosFiltrados">
            <div class="col-md-1">
                <span class="badge bg-primary">{{ elemento['ID'] }}</span>
            </div>
            <div class="col-md-3 text-end">
                <a href="#">
                    <img v-bind:src="`<?= URL_CONTENT ?>observatorio/investigaciones/` + elemento['ID'] + `.jpg`"
                        class="w-100 rounded shadow mb-2" v-bind:alt="elemento['Título']"
                        v-bind:onerror="`this.src='<?= URL_CONTENT ?>observatorio/investigaciones/nd.jpg'`">
                </a>
            </div>
            <div class="col-md-8">
                <h5 class="card-title text-main">
                    <a v-bind:href="elemento.link" target="_blank" v-bind:title="`[` + elemento['ID'] + `] ` + elemento['Título']">
                        {{ elemento['Título'] }}
                    </a>
                </h5>
                {{ elemento['Descripción'] }}

                <br>
                <small>
                    <span class="text-muted">Solicitante: </span>
                    <span class="text-primary">{{ elemento['ENTIDAD'] }}</span>
                    &middot;
                    <span class="text-muted">Método recolección: </span>
                    <span class="text-primary">{{ elemento['Método de recolección'] }}</span>
                    &middot;
                    <span class="text-muted">Expediente ORFEO: </span>
                    <span class="text-primary">{{ elemento['EXPEDIENTE'] }}</span>
                    &middot;
                </small>
                
                <p v-show="displayUrl">
                    <a v-bind:href="elemento['Carpeta productos']" target="_blank" class="btn btn-sm btn-link" v-show="elemento['Carpeta productos'].length > 0">
                        <i class="fa fa-folder-o"></i> Carpeta
                    </a>
                    <a v-bind:href="elemento['Link Repositorio']" target="_blank" class="btn btn-sm btn-link" v-show="elemento['Link Repositorio'].length > 0">
                        <i class="fa fa-file-o"></i> Informe
                    </a>
                </p>
            </div>
        </div>

        <div class="mt-2">
            <button class="btn btn-light btn-sm" v-on:click="updateList" type="button" title="Actualizar listado">
                <i class="fa-solid fa-rotate-right"></i> Actualizar listado
            </button>
        </div>

    </div>
</div>

<?php $this->load->view('app/observatorio/investigaciones/vue_v') ?>