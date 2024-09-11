<script src="<?= URL_RESOURCES ?>js/pml_searcher.js"></script>
<?php $this->load->view('app/observatorio/pai_2024/style_v') ?>
<!-- <investigacion rel="stylesheet" href="<?= URL_RESOURCES ?>css/sicc/observatorio-investigaciones.css"> -->


<div id="investigacionesApp">
    <div class="text-center" v-show="loading">
        <div class="spinner-border text-secondary" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>

    <div class="center_box_750" v-show="!loading">
        <div class="search-container">
            <input class="search-input mb-2" type="text" v-model="q" placeholder="Buscar investigación" autofocus>
            <button class="search-button" v-show="this.q.length > 0" v-on:click="clearSearch()">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <div class="mb-2">
            <select v-model="filters.grupo_1" class="search-filter" v-bind:class="{'selected': filters.grupo_1 != '' }">
                <option value="">Todas</option>
                <option v-for="optionGrupo1 in grupos" v-bind:value="optionGrupo1">{{ optionGrupo1 }}</option>
            </select>
        </div>

        <p class="text-center"><strong class="color-text-1">{{ investigacionesFiltrados.length }}</strong> resultados</p>

        <div class="d-flex my-3 justify-content-between">
            <div class="d-none">
                <button class="btn btn-circle btn-light me-2">
                    <i class="fas fa-chevron-left"></i>
                </button>
            </div>
            <div class="d-flex flex-wrap justify-content-center">
                <div class="sqr-selector" v-for="investigacion in investigacionesFiltrados" v-show="investigacion['Estado'] != `7 Cancelada`"
                    v-on:click="setCurrent(investigacion, currentSubseccion)" v-bind:class="{'active': investigacion['ID'] == currentInvestigacion['ID'] }"
                >
                    {{ investigacion['ID'] }}
                </div>
            </div>
            <div class="d-none">
                <button class="btn btn-circle btn-light ms-2">
                    <i class="fas fa-chevron-right"></i>
                </button>
            </div>
        </div>


        <!-- LISTADO DE investigaciones -->
        <table class="table" v-show="seccion == 'listado'">
            <tr v-for="investigacion in investigacionesFiltrados">
                <td>
                    <strong class="color-text-5">
                        {{ investigacion['ID'] }}
                    </strong>
                </td>
                <td>
                    <span class="grupo-investigacion color-text-2">
                        {{ investigacion['grupo_1'] }}
                    </span>
                    <br>
                    <span class="label-linea-investigacion me-2" v-bind:class="`linea-` + textToClass(investigacion['Línea de investigación'])">
                        {{ investigacion['Línea de investigación'] }}
                    </span>
                    
                    <br>
                    <strong class="color-text-1 pointer" v-on:click="setCurrent(investigacion)">
                        {{ investigacion['Título'] }}
                    </strong>
                    <br>
                    {{ investigacion['Descripción'] }}
                </td>
                <td width="25%;">
                    <span class="text-main">{{ investigacion['Entidad'] }}</span>
                    <br>
                    <small>{{ investigacion['Tema'] }}</small>
                    <br>
                    <small>Inicio en campo: </small>
                    <br>
                    <small>{{ dateFormat(investigacion['Inicio Recolección'], 'DD MMM') }}</small>
                </td>
            </tr>
        </table>

        <div class="row mb-4 ficha-investigacion d-none" v-for="investigacion in investigacionesFiltrados" v-show="seccion == 'listado'">
            <div class="col-md-3 text-end">
                {{ investigacion['Línea de investigación'] }}
            </div>
            <div class="col-md-9">
                <h5 class="card-title text-main">
                    <a v-bind:href="investigacion.investigacion" target="_blank"
                        v-bind:title="`[` + investigacion.num + `] ` + investigacion.nombre">
                        {{ investigacion['Título'] }}
                    </a>
                </h5>
                <div class="d-none">
                    <p>{{ investigacion['Descripción'] }}</p>
                </div>
                
            </div>
        </div>

        <!-- DETALLES DE UN LABORATORIO SELECCIONADO -->
        <div v-show="seccion == 'detalles'">
            <div class="mb-2">
                <button class="btn btn-light btn-sm" v-on:click="setSeccion('listado')">
                    <i class="fas fa-arrow-left"></i> Listado
                </button>
            </div>
            <div class="card">
                <div class="card-body">
                    <div class="text-center">
                        <span class="text-muted">{{ currentInvestigacion['Tema'] }}</span>
                    </div>
                    <h3 class="card-title text-center mb-5 color-text-1">
                        {{ currentInvestigacion['Título'] }}
                    </h3>
                    <div class="row">
                        <div class="col-md-4">
                            <span class="text-muted">{{ currentInvestigacion['Tema'] }}</span>
                            <br>
                            <span class="text-muted">{{ currentInvestigacion['Línea de investigación'] }}</span>
                            <br>
                            <strong class="color-text-1">{{ currentInvestigacion['Entidad'] }}</strong>
                        </div>
                        <div class="col-md-8">
                            <p>
                                {{ currentInvestigacion['Descripción'] }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- SUB MENÚ DEL LABORATORIO -->
            <ul class="nav nav-tabs justify-content-center mt-2 d-none">
                <li class="nav-item" v-for="subseccion in subsecciones">
                    <a class="nav-link pointer" aria-current="page" v-bind:class="{'active': subseccion.name == currentSubseccion }"
                        v-on:click="currentSubseccion = subseccion.name"
                    >
                        {{ subseccion.title }}
                    </a>
                </li>
            </ul>

            <div class="w-100" v-show="currentSubseccion == 'info'">
                
            </div>

            
        </div>

        <?php if ( $this->session->userdata('role') == 1 ) : ?>
        <div class="mt-2">
            <button class="btn btn-light btn-sm me-1" v-on:click="updateList(tabla)" type="button" title="Actualizar"
                v-for="tabla in tablas">
                <i class="fa-solid fa-rotate-right"></i> {{ tabla.nombre }}
            </button>
        </div>
        <?php endif; ?>

    </div>
</div>

<?php $this->load->view('app/observatorio/pai_2024/vue_v') ?>