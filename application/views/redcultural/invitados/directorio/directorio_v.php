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
            <input class="search-input mb-2" type="text" v-model="q" placeholder="Buscar"
                autofocus>
            <button class="search-button" v-show="this.q.length > 0" v-on:click="clearSearch()">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <div class="mb-2">
            <!-- <select v-model="filters.status" class="search-filter" v-bind:class="{'selected': filters.status != '' }">
                <option value="">Todas</option>
                <option v-for="optionStatus in estados" v-bind:value="optionStatus">{{ optionStatus }}</option>
            </select> -->
        </div>

        <p class="text-center"><strong class="color-text-1">
                {{ elementosFiltrados.length }}</strong> resultados &middot;
        </p>

        <!-- FICHA DE LA PERSONA -->
        <div v-show="section == 'perfil'">
            <div class="mb-2">
                <button class="btn btn-light btn-sm" v-on:click="section = 'lista'">
                    <i class="fas fa-arrow-left"></i> Volver
                </button>
            </div>
            <div class="perfil mb-2 shadow">
                <div class="perfil-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h3 class="title">{{ currentElement['nombre_completo'] }}</h3>
                            <p class="">
                                {{ currentElement['perfil'] }}
                            </p>
                        </div>
                        <div class="col-md-6">
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- LISTA DE INVESTIGACIONES -->
        <div v-show="section == 'lista'">
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
                        <a href="#" v-on:click="setCurrent(elemento['ID'])"
                            v-bind:title="elemento['username']">
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