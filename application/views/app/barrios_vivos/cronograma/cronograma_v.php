<script src="<?= URL_RESOURCES ?>js/pml_searcher.js"></script>
<?php $this->load->view('app/barrios_vivos/cronograma/style_v') ?>
 

<div id="cronogramaApp">
    <div class="text-center" v-show="loading">
        <div class="spinner-border text-secondary" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>

    <div class="center_box_750" v-show="!loading">
        <div class="search-container">
            <input class="search-input mb-2" type="text" v-model="q"
                placeholder="Buscar actividad" autofocus>
            <button class="search-button" v-show="this.q.length > 0" v-on:click="clearSearch()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <p class="text-center"><strong class="color-text-1">{{ actividadesFiltrados.length }}</strong> resultados</p>
    </div>

    <div class="container">
        <div class="card mb-2" v-for="(actividad, key) in actividadesFiltrados" v-show="actividad.fecha.length > 0">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-2">
                        <h3>
                            {{ dateFormat(actividad.fecha) }}
                        </h3>
                        <small class="text-primary">{{ ago(actividad.fecha) }}</small>
                    </div>
                    <div class="col-md-2">
                        {{ actividad.fase_barrios_vivos }}
                    </div>
                    <div class="col-md-8">
                        <strong class="text-main">{{ actividad.laboratorio_nombre }}</strong>
                        <br>
                        
                        <p>{{ actividad.descripcion }}</p>

                        <div class="d-flex">
                            <i class="fas fa-users me-3 color-text-5"></i>
                            <div class="progress w-100">
                                <div class="progress-bar bg-main" role="progressbar" v-bind:style="`width: `+ actividad.total_participantes +`%;`" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100" title="Participantes">
                                    {{ actividad.total_participantes }}
                                </div>
                            </div>
                        </div>
    
                        
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $this->load->view('app/barrios_vivos/cronograma/vue_v') ?>