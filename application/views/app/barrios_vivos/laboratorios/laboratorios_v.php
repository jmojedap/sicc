<script src="<?= URL_RESOURCES ?>js/pml_searcher.js"></script>
<?php $this->load->view('app/barrios_vivos/laboratorios/style_v') ?>
<!-- <laboratorio rel="stylesheet" href="<?= URL_RESOURCES ?>css/sicc/observatorio-laboratorios.css"> -->
 

<div id="laboratoriosApp">
    <div class="text-center" v-show="loading">
        <div class="spinner-border text-secondary" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>

    <div class="center_box_750" v-show="!loading">
        <div class="search-container">
            <input class="search-input mb-2" type="text" v-model="q"
                placeholder="Buscar laboratorio" autofocus>
            <button class="search-button" v-show="this.q.length > 0" v-on:click="clearSearch()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <p class="text-center"><strong class="color-text-1">{{ laboratoriosFiltrados.length }}</strong> resultados</p>

        <div class="row mb-4" v-for="laboratorio in laboratoriosFiltrados">
            <div class="col-md-3 text-end">
                <img v-bind:src="`<?= URL_CONTENT ?>barrios_vivos/laboratorios/` + laboratorio.id + `.jpg`"
                    class="w-100 rounded shadow mb-2" v-bind:alt="laboratorio.nombre_laboratorio"
                    v-bind:onerror="`this.src='<?= URL_CONTENT ?>barrios_vivos/laboratorios/` + laboratorio.tipo_laboratorio.substr(0,6) + `.png'`">
            </div>
            <div class="col-md-9">
                
                
                <h5 class="card-title text-main">
                    <a v-bind:href="laboratorio.laboratorio" target="_blank" v-bind:title="`[` + laboratorio.num + `] ` + laboratorio.nombre">
                        {{ laboratorio.nombre_laboratorio }}
                    </a>
                </h5>
                <span class="badge bg-year">{{ laboratorio.localidad }}</span>
                <span class="badge-tipo" v-bind:class="`badge-tipo-` + laboratorio.tipo"></span>
                <br>
                {{ laboratorio.barrio_ancla }}
                <br>
                <small class="text-muted">{{ laboratorio.tipo_laboratorio }}</small>
                &middot;
                <small class="text-muted">{{ laboratorio.categoria_laboratorio }}</small>
                &middot;
                <small class="text-muted">{{ laboratorio.origen_propuesta }}</small>
                &middot;
                <small class="text-muted">{{ laboratorio.direccion_lider_corto }}</small>
                &middot;
                <small class="text-muted">Actualizado {{ ago(laboratorio.fecha_actualizacion) }}</small>
                <br>
                
                <p v-show="displayUrl">
                    <a v-bind:href="laboratorio.laboratorio" class="clase" target="_blank">
                        {{ laboratorio.laboratorio }}
                    </a>
                </p>
            </div>
        </div>

        <?php if ( $this->session->userdata('role') == 1 ) : ?>
            <div class="mt-2">
                <button class="btn btn-light btn-sm me-1" v-on:click="updateList(tabla)" type="button" title="Actualizar" v-for="tabla in tablas">
                    <i class="fa-solid fa-rotate-right"></i> {{ tabla.nombre }}
                </button>
            </div>
        <?php endif; ?>

    </div>
</div>

<?php $this->load->view('app/barrios_vivos/laboratorios/vue_v') ?>