<script src="<?= URL_RESOURCES ?>js/pml_searcher.js"></script>
<!-- <link rel="stylesheet" href="<?= URL_RESOURCES ?>css/sicc/observatorio-investigaciones.css"> -->

<?php $this->load->view('app/observatorio/investigaciones/style_v') ?>

<div id="investigacionesApp">
    <div class="text-center" v-show="loading">
        <div class="spinner-border text-secondary" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>

    <div class="center_box_920" v-show="!loading">
        <div class="search-container">
            <input class="search-input mb-2" type="text" v-model="q" v-bind:placeholder="`Buscar ` + nombreElemento"
                autofocus>
            <button class="search-button" v-show="this.q.length > 0" v-on:click="clearSearch()">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <div class="mb-2">
            <select v-model="filters.status" class="search-filter" v-bind:class="{'selected': filters.status != '' }">
                <option value="">Todas</option>
                <option v-for="optionStatus in estados" v-bind:value="optionStatus">{{ optionStatus }}</option>
            </select>
        </div>

        <p class="text-center"><strong class="color-text-1">
                {{ elementosFiltrados.length }}</strong> resultados &middot;
            <strong class="color-text-1">{{ productos.length }}</strong>
            productos asociados
        </p>

        <!-- FICHA PRODUCTOS -->
        <div v-show="section == 'ficha'">
            <div class="mb-2">
                <button class="btn btn-light btn-sm" v-on:click="section = 'lista'">
                    <i class="fas fa-arrow-left"></i> Volver
                </button>
            </div>
            <div class="ficha mb-2 shadow">
                <div class="ficha-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h3 class="title">{{ currentElement['Título'] }}</h3>
                            <p class="">
                                {{ currentElement['Descripción'] }}
                            </p>
                        </div>
                        <div class="col-md-6">
                            <div v-for="producto in productos" class="producto" v-show="displayProducto(producto)">
                                <a class="d-flex" v-bind:href="producto['Link para ficha']" target="_blank">
                                    <div width="65px" class="text-center me-3">
                                        <div class="icon-container">
                                            <span>
                                                <i v-bind:class="getProductoClass(producto['Tipo producto'])"></i>
                                            </span>
                                        </div>
                                    </div>
                                    <div>
                                        {{ producto['Título'] }}
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="ficha-footer d-flex justify-content-between">
                    <div class="p-2" style="">
                        <img class="logo-orfeo me-1" src="<?= URL_IMG ?>observatorio/investigaciones/orfeo.png" alt="Logo Orfeo">
                        <strong>Orfeo: </strong>
                        <span title="Expediente documental en Orfeo">{{ currentElement['EXPEDIENTE'] }}</span>
                    </div>
                    <div class="p-2 only-lg"><img class="logo-dogcc"
                            src="<?= URL_IMG ?>observatorio/investigaciones/logo-dogcc-yellow.png" alt="Logo Observatorio de Cultura">
                    </div>
                </div>
            </div>
        </div>

        <!-- LISTA DE INVESTIGACIONES -->
        <div v-show="section == 'lista'">
            <div class="row mb-2 border-bottom pb-2" v-for="(elemento, i) in elementosFiltrados">
                <div class="col-md-1">
                    <span class="badge bg-primary">{{ elemento['ID'] }}</span>
                </div>
                <div class="col-md-3 text-end">
                    <a v-on:click="setCurrent(elemento['ID'])" class="pointer">
                        <img v-bind:src="`<?= URL_CONTENT ?>observatorio/investigaciones/` + elemento['ID'] + `.jpg`"
                            class="w-100 rounded shadow mb-2" v-bind:alt="elemento['Título']"
                            v-bind:onerror="`this.src='<?= URL_CONTENT ?>observatorio/investigaciones/nd.jpg'`">
                    </a>
                </div>
                <div class="col-md-8">
                    <h5 class="card-title text-main">
                        <a href="#" v-on:click="setCurrent(elemento['ID'])"
                            v-bind:title="`[` + elemento['ID'] + `] ` + elemento['Título']">
                            {{ elemento['Título'] }}
                        </a>
                    </h5>
                    {{ elemento['Descripción'] }}
    
                    <br>
                    <small>
                        <span class="text-muted">Solicitante: </span>
                        <span class="text-primary">{{ elemento['ENTIDAD'] }}</span>
                        &middot;
                        <span class="text-muted">Método recolección: </span>
                        <span class="text-primary">{{ elemento['Método de recolección'] }}</span>
                        &middot;
                        <span class="text-muted">Expediente ORFEO: </span>
                        <span class="text-primary">{{ elemento['EXPEDIENTE'] }}</span>
                        &middot;
                    </small>
    
                    <p v-show="displayUrl">
                        <a v-bind:href="elemento['Carpeta productos']" target="_blank" class="btn btn-sm btn-link"
                            v-show="elemento['Carpeta productos'].length > 0">
                            <i class="fa fa-folder-o"></i> Carpeta
                        </a>
                        <a v-bind:href="elemento['Link Repositorio']" target="_blank" class="btn btn-sm btn-link"
                            v-show="elemento['Link Repositorio'].length > 0">
                            <i class="fa fa-file-o"></i> Informe
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
</div>

<?php $this->load->view('app/observatorio/investigaciones/vue_v') ?>