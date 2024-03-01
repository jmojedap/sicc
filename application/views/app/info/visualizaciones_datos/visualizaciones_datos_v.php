<script src="<?= URL_CONTENT ?>observatorio/otros/dataviz.js"></script>

<div id="visualizacionesDatosApp">
    <div class="text-center" v-show="loading">
        <div class="spinner-border text-secondary" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>

    <div>
        
    </div>
    <div class="center_box_750" v-show="!loading">
        <div class="text-center">
            <h3>
                Visualización de Datos
            </h3>
            Dirección Observatorio y Gestión del Conocimiento Cultural 2022-2023
        </div>
        <div class="card_no">
            <div class="card-body">
                <div class="mb-3">
                    <input class="form-control input-search form-control-lg mb-2" type="text" v-model="q" v-on:change="filtrarTableros"
                    placeholder="Buscar informe" autofocus>
                    <p class="text-center">{{ tablerosFiltrados.length }} resultados</p>
                </div>


                <div class="row mb-3" v-for="tablero in tablerosFiltrados">
                    <div class="col-md-4">
                        <a v-bind:href="tablero.url" target="_blank">
                            <img v-bind:src="`<?= URL_CONTENT ?>dataviz/thumbnails/` + tablero.id + `.jpg`"
                                class="w-100 rounded shadow mb-2" v-bind:alt="tablero.nombre"
                                onerror="this.src='<?= URL_IMG ?>app/nd_power_bi.png'">
                        </a>
                    </div>
                    <div class="col-md-8">
                        <h5 class="card-title text-main">
                            {{ tablero.nombre }}
                        </h5>
                        <p>{{ tablero.descripcion }}</p>
                        <p v-show="displayUrl">
                            <a v-bind:href="tablero.url" class="clase" target="_blank">
                                {{ tablero.url }}
                            </a>
                        </p>
                    </div>
                </div>


            </div>
        </div>
    </div>
</div>

<?php $this->load->view('app/info/visualizaciones_datos/vue_v') ?>