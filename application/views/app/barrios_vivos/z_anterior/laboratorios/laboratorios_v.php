<script src="<?= URL_RESOURCES ?>js/pml_searcher.js"></script>
<?php $this->load->view('app/barrios_vivos/laboratorios/style_v') ?>
<!-- <laboratorio rel="stylesheet" href="<?= URL_RESOURCES ?>css/sicc/observatorio-laboratorios.css"> -->


<div id="laboratoriosApp">
    <div class="text-center" v-show="loading">
        <div class="spinner-border text-secondary" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>

    <div class="center_box_920" v-show="!loading">
        <div class="search-container">
            <input class="search-input mb-2" type="text" v-model="q" placeholder="Buscar laboratorio" autofocus>
            <button class="search-button" v-show="this.q.length > 0" v-on:click="clearSearch()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <p class="text-center"><strong class="color-text-1">{{ laboratoriosFiltrados.length }}</strong> resultados</p>

        <div class="my-3 d-flex flex-wrap justify-content-center">
            <div class="sqr-selector" v-for="laboratorio in laboratoriosFiltrados" v-show="laboratorio['incluir'] != `No`"
                v-on:click="setCurrent(laboratorio, currentSubseccion)" v-bind:class="{'active': laboratorio.id == currentLaboratorio.id }"
            >
                {{ laboratorio.id }}
            </div>
        </div>

        <!-- LISTADO DE LABORATORIOS -->
        <div class="row mb-4" v-for="laboratorio in laboratoriosFiltrados" v-show="seccion == 'listado'">
            <div class="col-md-3 text-end">
                <img v-bind:src="`<?= URL_CONTENT ?>barrios_vivos/laboratorios/` + laboratorio.id + `.jpg`"
                    class="w-100 rounded shadow mb-2 pointer" v-bind:alt="laboratorio.nombre_laboratorio"
                    v-on:click="setCurrent(laboratorio, 'info')"
                    v-bind:onerror="`this.src='<?= URL_CONTENT ?>barrios_vivos/laboratorios/` + laboratorio.tipo_laboratorio.substr(0,6) + `.png'`">
            </div>
            <div class="col-md-9">
                <h5 class="card-title text-main">
                    <a v-bind:href="laboratorio.laboratorio" target="_blank"
                        v-bind:title="`[` + laboratorio.num + `] ` + laboratorio.nombre">
                        {{ laboratorio.nombre_laboratorio }}
                    </a>
                </h5>
                <span class="badge bg-year">{{ laboratorio.localidad }}</span>
                <span class="badge-tipo" v-bind:class="`badge-tipo-` + laboratorio.tipo"></span>
                <br>
                {{ laboratorio.barrio_ancla }}
                <br>
                <small class="text-muted">{{ laboratorio.tipo_laboratorio }}</small>
                &middot;
                <small class="text-muted">{{ laboratorio.categoria_laboratorio }}</small>
                &middot;
                <small class="text-muted">{{ laboratorio.origen_propuesta }}</small>
                &middot;
                <small class="text-muted">{{ laboratorio.direccion_lider_corto }}</small>
                &middot;
                <small class="text-muted">Inicio: {{ ago(laboratorio.fecha_inicio) }}</small>
                <br>

                <div class="progress">
                    <div class="progress-bar" role="progressbar" v-bind:style="`width: ` + laboratorio['avance'] + `;`" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">
                        {{ laboratorio['avance'] }}
                    </div>
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
                    <h3 class="card-title text-center">
                        {{ currentLaboratorio['nombre_laboratorio'] }}
                    </h3>
                    <div class="text-center">
                        {{ currentLaboratorio['barrio_ancla'] }}
                    </div>
                </div>
            </div>
            
            <!-- SUB MENÚ DEL LABORATORIO -->
            <ul class="nav nav-tabs justify-content-center mt-2">
                <li class="nav-item" v-for="subseccion in subsecciones">
                    <a class="nav-link pointer" aria-current="page" v-bind:class="{'active': subseccion.name == currentSubseccion }"
                        v-on:click="currentSubseccion = subseccion.name"
                    >
                        {{ subseccion.title }}
                    </a>
                </li>
            </ul>

            <div class="w-100" v-show="currentSubseccion == 'info'">
                <table class="table bg-white">
                    <tr>
                        <td class="td-title">Avance</td>
                        <td>
                            <div class="progress">
                                <div class="progress-bar" role="progressbar" v-bind:style="`width: ` + currentLaboratorio['avance'] + `;`" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">
                                    {{ currentLaboratorio['avance'] }}
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="td-title">Localidad</td>
                        <td>{{ currentLaboratorio['localidad'] }}</td>
                    </tr>
                    <tr>
                        <td class="td-title">Gerente</td>
                        <td>{{ currentLaboratorio['gerente'] }}</td>
                    </tr>
                    <tr>
                        <td class="td-title">Equipo líder duplas</td>
                        <td v-html="currentLaboratorio['equipo_lider_duplas']"></td>
                    </tr>
                    <tr>
                        <td class="td-title">Tipo</td>
                        <td>{{ currentLaboratorio['tipo_laboratorio'] }}</td>
                    </tr>
                    <tr>
                        <td class="td-title">Categoría</td>
                        <td>{{ currentLaboratorio['categoria_laboratorio'] }}</td>
                    </tr>
                    <tr>
                        <td class="td-title">Cronograma planteado</td>
                        <td>
                            <span class="text-muted">Inicio:</span> <span class="text-main">{{ currentLaboratorio['Semana inicio'] }}</span> <br>
                            <span class="text-muted">Ideación:</span> <span class="text-main">{{ currentLaboratorio['Semana ideación'] }}</span> <br>
                            <span class="text-muted">Producción:</span> <span class="text-main">{{ currentLaboratorio['Semana producción'] }}</span> <br>
                            <span class="text-muted">Finalización:</span> <span class="text-main">{{ currentLaboratorio['Semana finalización'] }}</span> <br>
                        </td>
                    </tr>
                    <tr>
                        <td class="td-title">Descripción</td>
                        <td>{{ currentLaboratorio['descripcion'] }}</td>
                    </tr>
                </table>
            </div>

            <div class="w-100" v-show="currentSubseccion == 'actividades'">
                <table class="table bg-white">
                    <tr v-for="actividad in actividades" v-show="showActividad(actividad)">
                        <td>
                            <strong class="fecha-dia color-text-5">
                                {{ dateFormat(actividad.fecha, `DD`) }}
                            </strong>
                            <br>    
                            {{ dateFormat(actividad.fecha, `MMM`) }}
                        </td>
                        <td>{{ actividad.fase_metodologia }}</td>
                        <td width="70%">
                            <p>
                                <span class="text-muted">Fase: </span>
                                <span class="text-main">{{ actividad.fase_laboratorio }}</span>
                                &middot;

                                <span class="text-muted">Sesión: </span>
                                <span class="text-main">{{ actividad.sesion }}</span>
                                &middot;

                                <span class="text-muted">Interacción: </span>
                                <span class="text-main">{{ actividad.interaccion }}</span>
                                &middot;

                                <span class="text-muted">Lugar: </span>
                                <span class="text-main">{{ actividad.lugar }}</span>
                                <span class="text-main">{{ actividad.direccion }}</span>
                                &middot;

                                <span class="text-muted">Asistentes: </span>
                                <span class="text-main">{{ actividad.total_mujeres }}</span>
                                &middot;
                            </p>
                            <p>
                                {{ actividad.descripcion }}
                            </p>
                        </td>
                    </tr>
                </table>
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

<?php $this->load->view('app/barrios_vivos/laboratorios/vue_v') ?>