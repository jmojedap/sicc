<script src="<?= URL_RESOURCES ?>js/pml_searcher.js"></script>
<link rel="stylesheet" href="<?= URL_RESOURCES ?>css/sicc/observatorio-links.css">

<div id="linksApp">
    <div class="text-center" v-show="loading">
        <div class="spinner-border text-secondary" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>

    <div class="center_box_750" v-show="!loading">
        <div class="search-container">
            <input class="search-input mb-2" type="text" v-model="q" v-on:change="filtrarTableros"
                placeholder="Buscar contenido" autofocus>
            <button class="search-button" v-show="this.q.length > 0" v-on:click="clearSearch()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <p class="text-center"><strong class="color-text-1">{{ linksFiltrados.length }}</strong> resultados</p>

        <div class="row mb-4" v-for="link in linksFiltrados">
            <div class="col-md-3 text-end">
                <a v-bind:href="link.link" target="_blank">
                    <img v-bind:src="`<?= URL_CONTENT ?>observatorio/links/` + link.num + `.jpg`"
                        class="w-100 rounded shadow mb-2" v-bind:alt="link.nombre"
                        v-bind:onerror="`this.src='<?= URL_CONTENT ?>observatorio/links/` + link.tipo + `.png'`">
                </a>
            </div>
            <div class="col-md-9">
                
                
                <h5 class="card-title text-main">
                    <a v-bind:href="link.link" target="_blank" v-bind:title="`[` + link.num + `] ` + link.nombre">
                        {{ link.nombre }}
                    </a>
                </h5>
                <span class="badge bg-year">{{ link.year_informacion }}</span>
                <span class="badge-tipo" v-bind:class="`badge-tipo-` + link.tipo"></span>
                <br>
                {{ link.descripcion }}
                <br>
                <small class="text-muted">{{ link.proyecto }}</small>
                &middot;
                <small class="text-muted">Actualizado {{ ago(link.fecha_actualizacion) }}</small>
                <br>
                
                <p v-show="displayUrl">
                    <a v-bind:href="link.link" class="clase" target="_blank">
                        {{ link.link }}
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

<?php $this->load->view('app/observatorio/links/vue_v') ?>