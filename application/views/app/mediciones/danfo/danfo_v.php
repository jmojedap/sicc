<!-- Danfo.js -->
<script src="https://cdn.jsdelivr.net/npm/danfojs@1.1.2/lib/bundle.min.js"></script>

<!-- Highcharts -->
<script src="https://code.highcharts.com/highcharts.js"></script>

<title>Danfo + Highcharts + Vue 3</title>

<style>
    body { font-family: system-ui, -apple-system, Segoe UI, Roboto, Arial, sans-serif; margin: 24px; }
    #container { height: 600px; width: 100%; border: 1px solid #f0f0f0; border-radius: 8px; margin-top: 1rem; }
    .loading { color: #666; font-style: italic; margin-bottom: 1rem; }
</style>

<div id="chartApp">
    
    <div v-if="loading" class="loading">
        Procesando datos con Danfo.js...
    </div>

    <!-- El contenedor del gráfico -->
     <div class="row">
        <div class="col-md-4">

            <div class="list-group mb-2">
                <button type="button" class="list-group-item list-group-item-action"
                    aria-current="true" v-for="seccion in secciones" :key="seccion.indice_seccion"
                    :class="{'active': seccion.num_seccion === seccionSeleccionada}"
                    @click="seccionSeleccionada = seccion.num_seccion"
                    >
                    <span class="badge bg-primary rounded-pill">{{ seccion.num_seccion }}</span> {{ seccion.nombre_seccion }}
                </button>
            </div>
            
            <select size="20" v-model="preguntaIndice" @change="changePregunta" class="form-select">
                <option v-for="(pregunta, index) in preguntasFiltradas" v-bind:value="pregunta.indice_pregunta">{{ pregunta.etiqueta_1 }} - {{ pregunta.enunciado_1 }}</option>
            </select>
            <!-- Selector de variable oculto por solicitud de mostrar todas las variables en un gráfico -->
            <select v-model="variableIndice" @change="changeVariable" class="form-select" style="display:none;">
                <option v-for="variable in variablesFiltradas" v-bind:value="variable.indice_variable">
                    {{ variable.indice_variable }} |
                    {{ variable.codigo_variable }} - {{ variable.enunciado_2 }}
                </option>
            </select>
        </div>
        <div class="col-md-8">
            <p>{{ pregunta.enunciado_1 }}</p>
            <p>{{ variable.enunciado_2 }}</p>
            <ul class="nav nav-tabs"    >
                <li class="nav-item">
                    <a class="nav-link pointer" :class="{'active': section === 'chart'}" @click="section = 'chart'">Gráfico</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link pointer" :class="{'active': section === 'table'}" @click="section = 'table'">Tabla</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link pointer" :class="{'active': section === 'debug'}" @click="section = 'debug'">Debug</a>
                </li>
            </ul>
            <div id="container" v-show="section === 'chart'"></div>
            <table v-show="section === 'table'" class="table table-striped" id="table_danfo">
                <thead>
                    <tr>
                        <th>Categoría</th>
                        <th class="text-center">Respuesta</th>
                        <th class="text-center">Cant.</th>
                        <th class="text-center">% Rel.</th>
                        <th class="text-center">Distribución</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(row, i) in tablaDatos" :key="i">
                        <td><small>{{ row.variable }}</small></td>
                        <td class="text-start">{{ row.respuesta }}</td>
                        <td class="text-end">{{ numberFormat(row.valor) }}</td>
                        <td class="text-end"><b>{{ numberFormat(row.porcentaje, 1) }}%</b></td>
                        <td class="align-middle">
                            <div class="progress" style="height: 6px;">
                                <div class="progress-bar" role="progressbar" :style="{width: row.porcentaje + '%'}"></div>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
            <div v-show="section === 'debug'">
                <pre>{{ variablesFiltradas }}</pre>
                <pre>{{ preguntas[preguntaIndice] }}</pre>
                <pre>{{ variables[variableIndice] }}</pre>
            </div>
        </div>
     </div>
</div>

<?php $this->load->view('app/mediciones/danfo/vue_v'); ?>