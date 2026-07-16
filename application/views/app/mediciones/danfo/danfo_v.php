<!-- Danfo.js -->
<script src="https://cdn.jsdelivr.net/npm/danfojs@1.1.2/lib/bundle.min.js"></script>

<!-- Highcharts -->
<script src="<?= URL_RESOURCES ?>assets/highcharts/highcharts.js"></script>

<?php $this->load->view('app/mediciones/danfo/style'); ?>

<div id="chartApp">    
    <div class="row">
        <div class="col-md-4">
           <!--  SELECTOR DE SECCIÓN -->
            <button id="sections-collapser" class="d-flex"
                type="button" data-bs-toggle="collapse" data-bs-target="#collapseSectionsList" aria-expanded="false" aria-controls="collapseWidthExample">
                <div class="me-2">
                    <span class="badge bg-primary rounded-pill w40p">{{ currentSeccion.num_seccion }}</span>
                </div>
                <p class="mb-0 text-start">
                    {{ currentSeccion.nombre_seccion }}
                </p>
            </button>
            <div id="collapseSectionsList" class="collapse">
                <div class="section-list mb-2">
                    <button type="button" class="section-item"
                        aria-current="true" v-for="seccion in secciones" :key="seccion.num_seccion"
                        :class="{'active': seccion.num_seccion == numSeccion}"
                        @click="setSeccion(seccion.num_seccion)"
                        >
                        <span class="badge bg-primary rounded-pill w40p">{{ seccion.num_seccion }}</span> {{ seccion.nombre_seccion }}
                    </button>
                </div>
            </div>

            <div class="questions-list" id="lista-preguntas">
                <button class="questions-item d-flex"
                    v-for="pregunta in preguntasFiltradas"
                    :key="pregunta.indice_pregunta"
                    :class="{'active': pregunta.indice_pregunta === preguntaIndice}"
                    @click="setPregunta(pregunta.indice_pregunta)"
                >
                    <div class="me-2">
                        <span class="badge bg-warning rounded-pill w40p">{{ pregunta.etiqueta_1 }}</span>
                    </div>
                    <p class="mb-0 text-start">
                        {{ pregunta.nombre }}
                    </p>
                </button>
            </div>
        </div>
        <div class="col-md-8">
            <!-- <div v-if="loading" class="loading">
                Calculando...
            </div> -->
            <p>
                <span class="badge bg-warning w40p">{{ pregunta.etiqueta_1 }}</span>
                {{ pregunta.enunciado_1 }}
                <br>
                <small class="text-muted">{{ pregunta.instruccion }} </small>
            </p>
            
            <ul class="nav nav-tabs mb-2">
                <li class="nav-item">
                    <a class="nav-link pointer" :class="{'active': section === 'chart'}" @click="section = 'chart'">
                        Gráfico
                    </a>
                </li>
                
                <li class="nav-item">
                    <a class="nav-link pointer" :class="{'active': section === 'info'}" @click="section = 'info'">Información</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link pointer" :class="{'active': section === 'debug'}" @click="section = 'debug'">Debug</a>
                </li>
            </ul>
            
            <div v-show="section === 'chart'">
                <div>
                    <div id="kpi" class="mt-2" v-show="variables[0] && variables[0].unidad_medida">
                        <div><small>Promedio</small></div>
                        <p class="value mb-0">{{ numberFormat(variables[0].promedio, 1) }}</p>
                        <p class="measure-unit mb-0">
                            {{ variables[0].unidad_medida }}
                        </p>
                    </div>
                    <div id="chart-container"></div>
                </div>
                <div class="progress my-2" title="Porcentaje de de muestra con respuesta">
                    <div class="progress-bar" role="progressbar" aria-label="Example with label" :style="{width: porcentajeMuestra + '%'}" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">
                        {{ numberFormat(porcentajeMuestra, 1) }}%
                    </div>
                </div>
            </div>
            
            <div v-show="section === 'info'">
                <div class="card mw750p">
                    <div class="card-body"> 

                        <p><b>Sección </b>
                            <span class="badge bg-primary">
                                {{ currentSeccion.num_seccion }}
                            </span>
                        <p>

                        <h3>
                            {{ currentSeccion.nombre_seccion }}
                        </h3>

                        <p> {{ currentSeccion.descripcion }} </p>

                        <hr>

                        <p>
                            <b>Pregunta </b>
                            <strong class="text-primary">
                                {{ pregunta.etiqueta_1 }}
                            </strong>
                        <p>

                        <p>
                            {{ pregunta.nombre }}
                        </p>
                        <p>
                            {{ pregunta.enunciado_1 }}
                        </p>
                        <p class="text-muted">
                            {{ pregunta.instruccion }}
                        </p>
                    </div>
                </div>
            </div>
            
            <div v-show="section === 'debug'">
                <p>
                    Sum Factor con respuesta: {{ numberFormat(sumatoriaFactor, 0) }} &middot;
                    Sum Factor total: {{ numberFormat(medicionInfo.sumatoria_factor, 0) }} &middot;
                </p>
                <div class="py-3 px-5 mb-2 border">
                    <h3 class="text-center">
                        {{ pregunta.nombre }}
                    </h3>
                    <h4 class="text-center">
                        {{ pregunta.enunciado_1 }}
                    </h4>
                    <div v-for="(variable, k) in tablasVariables" class="mb-2">
                        <div>{{ variable.enunciado_2 }}</div>
                        <div class="progress">
                            <div 
                                v-for="(respuesta, j) in variable.respuestas"
                                class="progress-bar" 
                                v-bind:class="`bg-response-` + j"
                                v-bind:title="respuesta.respuesta"
                                role="progressbar" 
                                v-bind:aria-label="`Segment ` + j" 
                                :style="{width: respuesta.porcentaje + '%'}" 
                                :aria-valuenow="respuesta.porcentaje" 
                                :aria-valuemin="0" 
                                :aria-valuemax="100">
                                {{ numberFormat(respuesta.porcentaje, 1) }}%
                            </div>
                        </div>
                    </div>
                    <div class="">
                        <div v-for="(posibleRespuesta, k) in posiblesRespuestas" class="d-flex">
                            <div class="p-2">
                                <div class="response-circle" v-bind:class="`bg-response-` + k"></div>
                            </div>
                            <div class="p-2">
                                <small>
                                    {{ posibleRespuesta }}
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
                <div v-for="variable in tablasVariables" class="mb-3 center_box_920 bg-white p-3">
                    <div class="row">
                        <div class="col-md-4">
                            <h4>{{ variable.enunciado_2 }}</h4>
                        </div>
                        <div class="col-md-8">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th class="text-center" title="Respuesta">Respuesta</th>
                                        <th class="text-center" title="suma_factor">Frecuencia</th>
                                        <th class="text-center" title="porcentaje">%</th>
                                        <th class="text-center" title="cantidad_respuestas">Cantidad filas</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(respuesta, key) in variable.respuestas" :key="key">
                                        <td>{{ respuesta.respuesta }}</td>
                                        <td class="text-end">{{ numberFormat(respuesta.suma_factor, 0) }}</td>
                                        <td class="text-end" width="30%">
                                            <!-- PROGRESS BAR -->
                                            <div class="progress">
                                                <div class="progress-bar" role="progressbar" aria-label="Example with label" :style="{width: respuesta.porcentaje + '%'}" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">
                                                    {{ numberFormat(respuesta.porcentaje, 1) }}%
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-end">{{ numberFormat(respuesta.cantidad_respuestas, 0) }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <h3>Variables ({{ variables.length }})</h3>
                <table class="table bg-white">
                    <thead>
                        <th>indice_variable</th>
                        <th>codigo_variable</th>
                        <th>enunciado_2</th>
                        <th>suma_factor</th>
                        <th>promedio</th>
                        <th>unidad_medida</th>
                    </thead>
                    <tbody>
                        <tr v-for="(variable, key) in variables" :key="key">
                            <td>{{ variable.indice_variable }}</td>
                            <td>{{ variable.codigo_variable }}</td>
                            <td>{{ variable.enunciado_2 }}</td>
                            <td class="text-end">{{ numberFormat(variable.suma_factor, 0) }}</td>
                            <td class="text-end">{{ numberFormat(variable.promedio, 1) }}</td>
                            <td>{{ variable.unidad_medida }}</td>
                        </tr>
                    </tbody>
                </table>

                <h3>Respuestas ({{ respuestas.length }})</h3>

                <table class="table bg-white">
                    <thead>
                        <th>indice_variable</th>
                        <th>codigo_variable</th>
                        <th>respuesta</th>
                        <th>suma_factor_sum</th>
                        <th>cantidad_respuestas</th>
                        <th>porcentaje</th>
                    </thead>
                    <tbody>
                        <tr v-for="(respuesta, key) in respuestas">
                            <td>{{ respuesta.indice_variable }}</td>
                            <td>{{ respuesta.codigo_variable }}</td>
                            <td>{{ respuesta.respuesta }}</td>
                            <td class="text-end">{{ numberFormat(respuesta.suma_factor, 0) }}</td>
                            <td class="text-end">{{ respuesta.cantidad_respuestas }}</td>
                            <td class="text-end">{{ numberFormat(respuesta.porcentaje, 1) }}%</td>
                            
                        </tr>
                    </tbody>
                </table>
                <!-- <pre>{{ variablesFiltradas }}</pre>
                <pre>{{ preguntas[preguntaIndice] }}</pre>
                <pre>{{ variables[variableIndice] }}</pre> -->
            </div>
        </div>

     </div>
</div>

<?php $this->load->view('app/mediciones/danfo/vue_v'); ?>