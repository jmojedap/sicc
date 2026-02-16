<script src="<?= URL_RESOURCES ?>js/pml_searcher.js"></script>
<?php $this->load->view('app/observatorio/pai/style_v') ?>
<!-- <investigacion rel="stylesheet" href="<?= URL_RESOURCES ?>css/sicc/observatorio-investigaciones.css"> -->


<div id="investigacionesApp">
    <div class="text-center" v-show="loading">
        <div class="spinner-border text-secondary" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>

    <div class="container-fluid" v-show="!loading">
        <div class="center_box_920">
            <div class="search-container">
                <input class="search-input mb-2" type="text" v-model="q" placeholder="Buscar investigación" autofocus>
                <button class="search-button" v-show="this.q.length > 0" v-on:click="clearSearch()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
    
            <div class="d-flex justify-content-center mb-2">
                <div class="mb-2 me-2">
                    <select v-model="filters.estado" class="search-filter" v-bind:class="{'selected': filters.estado != '' }">
                        <option value="">Todos los estados</option>
                        <option v-for="optionEstado in estados" v-bind:value="optionEstado">{{ optionEstado }}</option>
                    </select>
                </div>
                <div class="mb-2 me-2">
                    <select v-model="filters.entidad" class="search-filter" v-bind:class="{'selected': filters.entidad != '' }">
                        <option value="">Todas las entidades</option>
                        <option v-for="optionEntidad in entidades" v-bind:value="optionEntidad">{{ optionEntidad }}</option>
                    </select>
                </div>
                <div class="mb-2 me-2">
                    <select v-model="filters.linea_investigacion" class="search-filter" v-bind:class="{'selected': filters.linea_investigacion != '' }">
                        <option value="">Todas</option>
                        <option v-for="lineaInvestigacion in lineasInvestigacion" v-bind:value="lineaInvestigacion">{{ lineaInvestigacion }}</option>
                    </select>
                </div>
                <div class="mb-2">
                    <select v-model="filters.categoria" class="search-filter" v-bind:class="{'selected': filters.categoria != '' }">
                        <option value="">Todas las categorías</option>
                        <option v-for="optionCategoria in categorias" v-bind:value="optionCategoria">{{ optionCategoria }}</option>
                    </select>
                </div>
            </div>
        </div>


        <p class="text-center"><strong class="color-text-1">{{ investigacionesFiltrados.length }}</strong> resultados</p>

        <!-- BOTONES DE SELECCIÓN DE INVESTIGACIONES -->
        <div class="d-flex my-3 justify-content-between">
            <div class="d-none">
                <button class="btn btn-circle btn-light me-2">
                    <i class="fas fa-chevron-left"></i>
                </button>
            </div>
            <div class="d-flex flex-wrap justify-content-center">
                <div class="sqr-selector" v-for="investigacion in investigacionesFiltrados" v-show="investigacion['Estado'] != `9 Cancelada`"
                    v-on:click="setCurrent(investigacion, currentSubseccion)" v-bind:class="{'active': investigacion['ID'] == currentInvestigacion['ID'] }"
                    v-bind:title="investigacion['Nombre clave']"
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
        <div v-show="seccion == 'listado'">
            <table class="table bg-white table-sm">
                <thead>
                    <th width="50px" class="text-center">ID</th>
                    <th>Investigación</th>
                    <th width="180px">Avance</th>
                    <th>Observaciones</th>
                    <th>Estado</th>
                    <th>Línea</th>
                    <th>Solicitante</th>
                    <th>Categoría</th>
                </thead>
                <tbody>
                    <tr v-for="(investigacion, key) in investigacionesFiltrados">
                        <td class="text-center">{{ investigacion['ID'] }}</td>
                        <td class="">
                            <a class="pointer" v-on:click="setCurrent(investigacion)">
                                {{ investigacion['Nombre clave'].substring(4) }}
                            </a>
                        </td>
                        <td>
                            <div class="progress">
                                <div class="progress-bar" role="progressbar" aria-label="Example with label"
                                    v-bind:class="avanceClass(investigacion['Avance'])"
                                    v-bind:style="`width: ` + investigacion['Avance'] + `;`" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">
                                    {{ investigacion['Avance'] }}
                                </div>
                            </div>
                        </td>
                        <td>
                            {{ investigacion['Observaciones'] }}
                        </td>
                        <td>
                            <span class="label-estado me-2"
                                v-bind:class="`estado-` + textToClass(investigacion['Estado'])">
                                {{ investigacion['Estado'].substring(2) }}
                            </span>
                        </td>
                        <td>
                            <span class="label-linea-investigacion me-2"
                                v-bind:class="`linea-` + textToClass(investigacion['Línea de investigación'])">
                                {{ investigacion['Línea de investigación'] }}
                            </span>
                        </td>
                        <td>
                            <span class="entidad" v-bind:class="textToClass(investigacion['Entidad'], 'entidad')">{{ investigacion['Entidad'] }}</span>
                        </td>
                        <td>
                            <span class="categoria" v-bind:class="textToClass(investigacion['Categoría'], 'categoria')">{{ investigacion['Categoría'] }}</span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

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

        <!-- DETALLES DE UNA INVESTIGACIÓN SELECCIONADA -->
        <div v-show="seccion == 'detalles'">
            <div class="mb-2">
                <button class="btn btn-light btn-sm" v-on:click="setSeccion('listado')">
                    <i class="fas fa-arrow-left"></i> Listado
                </button>
            </div>
            <div class="card">
                <div class="card-body">
                    <?php $this->load->view('app/observatorio/pai/detalles_v') ?>
                </div>
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

<?php $this->load->view('app/observatorio/pai/vue_v') ?>