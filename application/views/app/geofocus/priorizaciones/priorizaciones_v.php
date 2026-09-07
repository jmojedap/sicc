<script src="<?= URL_RESOURCES ?>js/pml_searcher.js"></script>
<!-- <link rel="stylesheet" href="<?= URL_RESOURCES ?>css/sicc/observatorio-priorizaciones.css"> -->

<?php $this->load->view('app/geofocus/geofocus_style_v') ?>

<div id="priorizacionesApp" class="geofocus-prioritizations-page">

    <div class="geofocus-prioritizations-loading" v-show="loading">
        <div class="spinner-border text-secondary" role="status">
            <span class="visually-hidden">Cargando...</span>
        </div>
        <span>Cargando priorizaciones…</span>
    </div>

    <div class="container geofocus-prioritizations-shell" v-show="!loading">

        <!-- LISTA DE PRIORIZACIONES -->
        <section v-show="section == 'lista'" class="geofocus-prioritizations-list geofocus-base-content">
            <header class="geofocus-base-content-header geofocus-prioritizations-header">
                <div>
                    <h1>Priorizaciones</h1>
                    <p>Configura y consulta ejercicios de priorización territorial.</p>
                </div>
                <button class="btn btn-primary" type="button" v-on:click="clearForm()">
                    <i class="fas fa-plus me-1" aria-hidden="true"></i>
                    Nueva priorización
                </button>
            </header>

            <div class="geofocus-prioritizations-body">
                <div class="geofocus-prioritizations-toolbar">
                    <div class="geofocus-prioritizations-search">
                        <i class="fas fa-search geofocus-prioritizations-search-icon" aria-hidden="true"></i>
                        <input id="prioritizations-search" class="geofocus-prioritizations-search-input" type="search"
                            v-model="q" placeholder="Buscar priorizaciones" aria-label="Buscar priorizaciones"
                            autocomplete="off" autofocus>
                        <button class="geofocus-prioritizations-search-clear" type="button" v-show="q.length > 0"
                            v-on:click="clearSearch()" aria-label="Limpiar búsqueda">
                            <i class="fas fa-times" aria-hidden="true"></i>
                        </button>
                    </div>
                    <span class="geofocus-prioritizations-result-count">
                        <strong>{{ elementosFiltrados.length }}</strong> resultados
                    </span>
                </div>

            <div class="geofocus-prioritizations-results">
            <article class="geofocus-prioritization-card" v-for="(elemento, i) in elementosFiltrados" v-bind:key="elemento.id">
                <div class="geofocus-prioritization-id">
                    <span class="badge bg-primary">{{ elemento['id'] }}</span>
                </div>
                <div class="geofocus-prioritization-copy">
                    <h2>
                        <a v-bind:href="`<?= URL_APP ?>geofocus/priorizacion/` + elemento['id']"
                            v-bind:title="`[` + elemento['id'] + `] ` + elemento['nombre']">
                            {{ elemento['nombre'] }}
                        </a>
                    </h2>
                    <p>{{ elemento['descripcion'] }}</p>
                    <div class="geofocus-prioritization-meta">
                        <small>
                            <i class="fas fa-user-circle me-1" aria-hidden="true"></i>
                            Creada por <strong class="geofocus-prioritization-creator">{{ elemento.creator_username }}</strong>
                        </small>
                        <small class="geofocus-prioritization-layer" v-bind:title="elemento.key_capa">
                            <i class="fas fa-layer-group me-1" aria-hidden="true"></i>
                            {{ capaBaseNombre(elemento.key_capa) }}
                        </small>
                    </div>
                </div>
                <div class="geofocus-prioritization-actions" v-show="isEditable(elemento)">
                        <button class="a4" type="button" v-on:click="goToEditForm(elemento['id'])"
                            v-bind:aria-label="'Editar ' + elemento.nombre" title="Editar priorización">
                            <i class="fas fa-pencil-alt" aria-hidden="true"></i>
                        </button>
                        <button class="a4" type="button" v-on:click="setCurrent(elemento['id'])" data-bs-toggle="modal"
                            data-bs-target="#deleteModal" v-bind:aria-label="'Eliminar ' + elemento.nombre" title="Eliminar priorización">
                            <i class="fas fa-trash" aria-hidden="true"></i>
                        </button>
                </div>
            </article>

            <div class="geofocus-prioritizations-empty" v-if="elementosFiltrados.length === 0">
                <i class="fas fa-search" aria-hidden="true"></i>
                <strong>Sin resultados</strong>
                <span>No encontramos priorizaciones que coincidan con la búsqueda.</span>
            </div>
            </div>
            </div>
        </section>

        <?php $this->load->view('app/geofocus/priorizaciones/form_v') ?>
        <?php $this->load->view('common/bs5/modal_single_delete_v') ?>

    </div>
</div>

<?php $this->load->view('app/geofocus/priorizaciones/vue_v') ?>
