<script src="<?= URL_RESOURCES ?>js/pml_searcher.js"></script>
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
            <button class="btn btn-sm" v-bind:class="{'btn-light': typeView == 'grid' }" v-on:click="typeView = 'grid'"
                title="Ver como cuadrícula">
                <i class="fas fa-grip-horizontal"></i>
            </button>
            <button class="btn btn-sm me-1" v-bind:class="{'btn-light': typeView == 'list' }"
                v-on:click="typeView = 'list'" title="Ver como lista">
                <i class="fas fa-table-list"></i>
            </button>
        </p>

        <?php $this->load->view('redcultural/invitados/directorio/modal_v') ?>

    </div>
    <!-- LISTA DE INVITADOS -->
    <div v-show="section == 'listado'">
        <div class="container-fluid" v-show="typeView == 'grid'">
            <div class="row justify-content-center g-3">
                <div v-for="(elemento, i) in elementosFiltrados" :key="i" class="col-md-3 col-sm-6 col-lg-2">
                    <div class="card h-100 border-0 pointer" v-on:click="setCurrent(elemento['id'])"
                        data-bs-toggle="modal" data-bs-target="#profileModal">
                        <img :src="`<?= URL_CONTENT ?>redcultural/images/profiles/` + elemento['username'] + `.jpg`"
                            class="card-img-top object-fit-cover" :alt="elemento.display_name"
                            v-bind:onerror="`this.src='<?= URL_IMG ?>redcultural/user.png'`">
                        <div class="card-body text-center">
                            <h5 class="card-title">
                                {{ elemento.nombre_completo }}
                            </h5>
                            <span class="resaltar-1">
                                <img v-bind:src="paisFlag(elemento['pais_origen'])" :alt="elemento['pais_origen']"
                                    :title="elemento['pais_origen']" width="" height="auto">
                                {{ elemento.ciudad }} &middot; {{ paisTo(elemento['pais_origen']) }}
                            </span><br>
                            <span class="text-muted small mb-0">
                                {{ elemento.rol_actividad }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div v-show="typeView == 'list'" class="center_box_920 p-2">
            <ul class="nav nav-pills mb-3 justify-content-center">
                <li class="nav-item" v-for="visibleInfoOption in visibleInfoOptions">
                    <a class="nav-link" aria-current="page" href="#"
                        v-bind:class="{'active': visibleInfo == visibleInfoOption.value }"
                        v-show="visibleInfoOption.enabled"
                        v-on:click="setVisibleInfo(visibleInfoOption.value)"
                        >
                        {{ visibleInfoOption.text }}
                    </a>
                </li>
            </ul>
            <div class="row mb-2 pb-2" v-for="(elemento, i) in elementosFiltrados">
                <div class="col-md-4 text-end">
                    <a v-bind:href="`<?= RCI_URL_APP ?>invitados/abrir_perfil/` + elemento['id'] + `/` + elemento['username']"
                        class="pointer">
                        <img v-bind:src="`<?= URL_CONTENT ?>redcultural/images/profiles/` + elemento['username'] + `.jpg`"
                            class="w-100 rounded shadow mb-2" v-bind:alt="`Imagen de ` + elemento['nombre_completo']"
                            v-bind:onerror="`this.src='<?= URL_IMG ?>redcultural/user.png'`">
                    </a>
                </div>
                <div class="col-md-8">
                    <h5 class="card-title text-main">
                        <a v-bind:href="`<?= RCI_URL_APP ?>invitados/abrir_perfil/` + elemento['id'] + `/` + elemento['username']"
                            v-on:click="setCurrent(elemento['id'])" v-bind:title="elemento['username']">
                            <img v-bind:src="paisFlag(elemento['pais_origen'])" :alt="elemento['pais_origen']"
                                :title="elemento['pais_origen']" width="" height="auto">
                            {{ elemento['nombre_completo'] }}
                        </a>
                    </h5>
                    <p>{{ elemento['rol_actividad'] }}</p>
                    <p v-show="visibleInfo == 'perfil'">
                        {{ elemento['perfil'].slice(0, 380) }} <span v-if="elemento['perfil'].length > 380">...</span>
                    </p>
                    <p v-show="visibleInfo == 'intereses'">
                        {{ elemento['intereses'].slice(0, 380) }} <span v-if="elemento['intereses'].length > 380">...</span>
                    </p>
                    <?php if ( in_array($this->session->userdata('role'), [1]) ) : ?>
                    <br>
                    <a class="btn btn-light btn-sm"
                        v-bind:href="`<?= base_url("admin/users/edit/") ?>` + elemento['id']" target="_blank">
                        Editar
                    </a>
                    <?php endif; ?>

                    <br>
                </div>
            </div>
        </div>
    </div>

</div>

<?php $this->load->view('redcultural/invitados/directorio/vue_v') ?>