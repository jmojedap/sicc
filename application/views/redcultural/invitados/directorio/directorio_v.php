<script src="<?= URL_RESOURCES ?>js/pml_searcher.js"></script>
<script src="<?= URL_RESOURCES ?>config/redcultural/data/paises.js"></script>
<!-- <link rel="stylesheet" href="<?= URL_RESOURCES ?>css/sicc/observatorio-investigaciones.css"> -->

<?php $this->load->view('redcultural/invitados/directorio/style_v') ?>

<div id="directorioApp">
    <div class="text-center" v-show="loading">
        <div class="spinner-border text-secondary" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>

    <div class="center_box_920" v-show="!loading">
        <div class="search-container">
            <input class="search-input mb-2" type="text" v-model="q" placeholder="Buscar" autofocus>
            <button class="search-button" v-show="this.q.length > 0" v-on:click="clearSearch()">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <p class="text-center"><strong class="color-text-1">
                {{ elementosFiltrados.length }}</strong> resultados &middot;
            <button class="btn btn-light btn-sm me-1" v-on:click="typeView = 'list'">
                <i class="fas fa-table-list"></i>
            </button>
            <button class="btn btn-light btn-sm" v-on:click="typeView = 'grid'">
                <i class="fas fa-grip-horizontal"></i>
            </button>
        </p>

        <!-- Modal -->
        <div class="modal fade" id="profileModal" tabindex="-1" aria-labelledby="profileModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="perfil mb-2">
                        <div class="perfil-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <img :src="`<?= URL_CONTENT ?>redcultural/images/profiles/` + currentElement['username'] + `.jpg`"
                                        class="card-img-top object-fit-cover" :alt="currentElement.display_name"
                                        v-bind:onerror="`this.src='<?= URL_CONTENT ?>redcultural/images/default/user.png'`">
                                </div>
                                <div class="col-md-6">
                                    <h3 class="title">{{ currentElement['nombre_completo'] }}</h3>
                                    <p class="small">
                                        <img v-bind:src="`https://flagcdn.com/w20/${currentElement['pais_origen'].toLowerCase()}.png`"
                                            :alt="currentElement['pais_origen']" width="" height="auto">
                                        {{ paisName(currentElement['pais_origen']) }}
                                    </p>
                                    <p class="">
                                        {{ currentElement['perfil'] }}
                                    </p>
                                    <div>
                                        <a v-bind:href="`<?= RCI_URL_APP ?>invitados/perfil/` + currentElement['id'] + `/` + currentElement['username']"
                                            class="btn btn-main w120p text-white">
                                            Ver más
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="perfil-footer">
                            <img class="w30p" src="<?= URL_RESOURCES ?>brands/redcultural/logo-navbar.png"
                                alt="Acción Cultural Iberoamericana">
                        </div>
                    </div>
                </div>
            </div>
        </div>



    </div>
    <!-- LISTA DE INVITADOS -->
    <div v-show="section == 'listado'">
        <div class="container-fluid" v-show="typeView == 'grid'">
            <div class="row justify-content-center g-3">
                <div v-for="(elemento, i) in elementosFiltrados" :key="i" class="col-md-3 col-sm-6 col-lg-2">
                    <div class="card h-100 shadow-sm border-0 pointer" v-on:click="setCurrent(elemento['id'])"
                        data-bs-toggle="modal" data-bs-target="#profileModal">
                        <img :src="`<?= URL_CONTENT ?>redcultural/images/profiles/` + elemento['username'] + `.jpg`"
                            class="card-img-top object-fit-cover" :alt="elemento.display_name"
                            v-bind:onerror="`this.src='<?= URL_CONTENT ?>redcultural/images/default/user.png'`">
                        <div class="card-body text-center">
                            <h5 class="card-title">{{ elemento.nombre_completo }}</h5>
                            <p class="text-muted small mb-0">{{ elemento.lema }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div v-show="typeView == 'list'" class="center_box_920">
            <div class="row mb-2 pb-2" v-for="(elemento, i) in elementosFiltrados">
                <div class="col-md-4 text-end">
                    <a v-on:click="setCurrent(elemento['id'])" class="pointer">
                        <img v-bind:src="`<?= URL_CONTENT ?>redcultural/images/profiles/` + elemento['username'] + `.jpg`"
                            class="w-100 rounded shadow mb-2" v-bind:alt="`Imagen de ` + elemento['nombre_completo']"
                            v-bind:onerror="`this.src='<?= URL_CONTENT ?>redcultural/images/default/user.png'`">
                    </a>
                </div>
                <div class="col-md-8">
                    <h5 class="card-title text-main">
                        <a v-bind:href="`<?= RCI_URL_APP ?>invitados/perfil/` + elemento['id'] + `/` + elemento['username']"
                            v-on:click="setCurrent(elemento['id'])" v-bind:title="elemento['username']">
                            {{ elemento['nombre_completo'] }}
                        </a>
                    </h5>
                    <p>{{ elemento['lema'] }}</p>
                    {{ elemento['perfil'] }}

                    <br>
                </div>
            </div>
        </div>
    </div>

</div>

<?php $this->load->view('redcultural/invitados/directorio/vue_v') ?>