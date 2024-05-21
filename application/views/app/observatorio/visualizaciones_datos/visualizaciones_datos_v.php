<script src="<?= URL_RESOURCES ?>js/pml_searcher.js"></script>

<div id="visualizacionesDatosApp">
    <div class="text-center" v-show="loading">
        <div class="spinner-border text-secondary" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>

    <div>

    </div>
    <div class="center_box_750" v-show="!loading">
        <div class="search-container">
            <input class="search-input mb-2" type="text" v-model="q" v-on:change="filtrarTableros"
                placeholder="Buscar contenido" autofocus>
            <button class="search-button" v-show="this.q.length > 0" v-on:click="clearSearch()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <p class="text-center">{{ tablerosFiltrados.length }} resultados</p>


        <div class="row mb-3" v-for="tablero in tablerosFiltrados">
            <div class="col-md-4">
                <a v-bind:href="tablero.url" target="_blank">
                    <img v-bind:src="`<?= URL_CONTENT ?>observatorio/dataviz/` + tablero.id + `.jpg`"
                        class="w-100 rounded shadow mb-2" v-bind:alt="tablero.nombre"
                        onerror="this.src='<?= URL_IMG ?>app/nd_power_bi.png'">
                </a>
            </div>
            <div class="col-md-8">
                <h5 class="card-title text-main">
                    {{ tablero.nombre }}
                </h5>
                <span class="badge bg-year">{{ tablero.year_hasta }}</span>
                <br>
                {{ tablero.descripcion }}</br>
                
            </div>
        </div>

        <div class="mt-2">
            <button class="btn btn-light btn-sm" v-on:click="updateList" type="button" title="Actualizar listado">
                <i class="fa-solid fa-rotate-right"></i> Actualizar listado
            </button>
        </div>
    </div>
</div>

<?php $this->load->view('app/observatorio/visualizaciones_datos/vue_v') ?>