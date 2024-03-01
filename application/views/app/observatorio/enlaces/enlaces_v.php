<script src="<?= URL_CONTENT ?>observatorio/otros/links.js"></script>

<?php $this->load->view('app/observatorio/enlaces/style_v') ?>

<h4 class="page-title">
    Enlaces a contenidos
</h4>
<div id="enlacesApp">
    <div class="text-center" v-show="loading">
        <div class="spinner-border text-secondary" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>
    <div class="center_box_750" v-show="!loading">
        <div class="card_no">
            <div class="card-body">
                <div class="mb-3">
                    <input class="form-control input-search form-control-lg mb-2" type="text" v-model="q" v-on:change="filtrarTableros"
                    placeholder="Buscar enlace" autofocus>
                    <p class="text-center">{{ enlacesFiltrados.length }} resultados</p>
                </div>


                <div class="row mb-3" v-for="enlace in enlacesFiltrados">
                    <div class="col-md-3 text-end">
                        <a v-bind:href="enlace.link" target="_blank">
                            <img v-bind:src="`<?= URL_CONTENT ?>observatorio/enlaces/` + enlace.num + `.jpg`"
                                class="w-100 rounded shadow mb-2" v-bind:alt="enlace.nombre"
                                v-bind:onerror="`this.src='<?= URL_CONTENT ?>observatorio/enlaces/` + enlace.tipo + `.png'`">
                        </a>
                    </div>
                    <div class="col-md-9">
                        <h5 class="card-title text-main">
                            <a v-bind:href="enlace.link" target="_blank">
                                {{ enlace.nombre }}
                            </a>
                        </h5>
                        <span class="badge-tipo" v-bind:class="`badge-tipo-` + enlace.tipo">
                            
                        </span>
                        <p>{{ enlace.descripcion }}</p>
                        
                        <p v-show="displayUrl">
                            <a v-bind:href="enlace.link" class="clase" target="_blank">
                                {{ enlace.link }}
                            </a>
                        </p>
                    </div>
                </div>


            </div>
        </div>
    </div>
</div>

<?php $this->load->view('app/observatorio/enlaces/vue_v') ?>