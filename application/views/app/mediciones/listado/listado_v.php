<script src="<?= URL_RESOURCES ?>js/pml_searcher.js"></script>
<link rel="stylesheet" href="<?= URL_RESOURCES ?>css/sicc/observatorio-mediciones.css">

<div id="medicionesApp">
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
            <select v-model="filters.year" class="search-filter" v-bind:class="{'selected': filters.year > 0 }">
                <option value="">Todos los años</option>
                <option v-for="optionYear in years" v-bind:value="optionYear">{{ optionYear }}</option>
            </select>
        </div>

        <p class="text-center"><strong class="color-text-1">{{ medicionesFiltrados.length }}</strong> resultados</p>

        <div class="row mb-2 border-bottom pb-2" v-for="medicion in medicionesFiltrados">
            <div class="col-md-1">
                <span class="badge bg-primary">{{ medicion.codigo }}</span>
            </div>
            <div class="col-md-11">
                
                
                <h5 class="card-title text-main">
                    <a v-bind:href="medicion.link" target="_blank" v-bind:title="`[` + medicion.id + `] ` + medicion.nombre">
                        {{ medicion.nombre }}
                    </a>
                </h5>
                <span class="badge bg-year">{{ medicion.anio_informacion }}</span>
                <span class="badge-tipo" v-bind:class="`badge-tipo-` + medicion.tipo"></span>
                <br>
                {{ medicion.descripcion }}
                <br v-show="medicion.descripcion.length > 0">

                <span class="badge" v-bind:class="textToClass('estado-', medicion.estado)">{{ medicion.estado.slice(2) }}</span>
                <br>
                <small>
                    <span class="text-muted">Tipo: </span>
                    <span class="text-primary">{{ medicion.tipo }}</span>
                    &middot;
                    <span class="text-muted">Metodología: </span>
                    <span class="text-primary">{{ medicion.metodologia }}</span>
                    &middot;
                </small>
                
                <p v-show="displayUrl">
                    <a v-bind:href="medicion.url_datos_detallados" target="_blank" class="btn btn-sm btn-link" v-show="medicion.url_datos_detallados.length > 0">
                        <i class="fas fa-table"></i> Datos
                    </a>
                    <a v-bind:href="medicion.url_carpeta_archivos" target="_blank" class="btn btn-sm btn-link" v-show="medicion.url_carpeta_archivos.length > 0">
                        <i class="fa fa-folder-o"></i> Carpeta
                    </a>
                    <a v-bind:href="medicion.url_informe_resultados" target="_blank" class="btn btn-sm btn-link" v-show="medicion.url_informe_resultados.length > 0">
                        <i class="fa-solid fa-chart-simple"></i> Informe
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

<?php $this->load->view('app/mediciones/listado/vue_v') ?>