<div v-show="section == 'variables'" class="geofocus-priority-variables geofocus-base-content">
    <header class="geofocus-base-content-header">
        <div>
            <h1>Variables de la priorización</h1>
            <p v-if="capaBase"><i class="fas fa-layer-group me-1" aria-hidden="true"></i> {{ capaBase.nombre }}</p>
        </div>
    </header>

    <section class="geofocus-base-variables" aria-labelledby="priority-variables-title">
        <div class="geofocus-base-section-heading geofocus-priority-heading">
            <div>
                <h2 id="priority-variables-title">
                    Variables seleccionadas
                    <span class="geofocus-priority-count">({{ variablesActivas.length }})</span>
                </h2>
                <p class="geofocus-priority-help">Selecciona las variables, el sentido de priorización y su puntaje de 1 a 100.</p>
                <span v-show="!isEditable()" class="geofocus-base-count">Solo lectura</span>
            </div>
            <button type="button" class="btn btn-primary" v-on:click="validateSubmit" v-bind:disabled="loading || !isEditable()">
                <i class="fas fa-calculator me-1" aria-hidden="true"></i>
                Calcular
            </button>
        </div>

        <form accept-charset="utf-8" method="POST" id="priorizacionForm" @submit.prevent="validateSubmit">
            <fieldset v-bind:disabled="loading">
                <div class="table-responsive geofocus-priority-table" v-show="!loading">
                    <table class="table table-sm table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th scope="col" class="text-center">
                                    <input type="checkbox" class="form-check-input" v-model="allSelected" v-on:change="toggleSelectAll"
                                        aria-label="Seleccionar todas las variables">
                                </th>
                                <th scope="col" class="text-center">ID</th>
                                <th scope="col" class="text-center">Color</th>
                                <th scope="col">Nombre</th>
                                <th scope="col">Tema</th>
                                <th scope="col" class="text-center" title="Priorizar valores altos o bajos">Tipo</th>
                                <th scope="col" class="geofocus-priority-weight">Ponderación</th>
                                <th scope="col" class="text-center">Puntaje</th>
                                <th scope="col" class="text-end"><span class="visually-hidden">Acciones</span></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(variable, key) in variables" v-bind:key="variable.id" v-show="variable.estado == 1">
                                <td class="text-center" v-bind:class="variableClass(variable)">
                                    <input type="checkbox" class="form-check-input" v-model="variable.active"
                                        v-bind:aria-label="'Seleccionar ' + variable.nombre">
                                </td>
                                <td class="text-center"><strong>{{ variable.id }}</strong></td>
                                <td class="text-center">
                                    <span class="geofocus-priority-color" role="img"
                                        v-bind:style="{ backgroundColor: variable.color || '#0084bf' }"
                                        v-bind:title="variable.color || '#0084bf'"
                                        v-bind:aria-label="'Color ' + (variable.color || '#0084bf')"></span>
                                </td>
                                <td class="geofocus-priority-name" v-bind:class="variableClass(variable)">
                                    <button type="button" class="geofocus-priority-name-button variable-name"
                                        v-on:click="toggleActivateVariable(key)" v-bind:aria-pressed="!!variable.active">
                                        {{ variable.nombre }}
                                    </button>
                                    <code class="d-block">{{ variable.clave }}</code>
                                </td>
                                <td><span class="tema" v-bind:class="textToClass(variable.tema, 'tema')">{{ variable.tema }}</span></td>
                                <td class="text-center text-nowrap">
                                    <div v-show="variable.active" class="selector-tipo geofocus-priority-types">
                                        <button type="button" class="geofocus-priority-type"
                                            v-bind:class="{'text-info': variable.tipo_priorizacion == 1, 'text-off': variable.tipo_priorizacion == -1}"
                                            v-bind:aria-pressed="variable.tipo_priorizacion == 1"
                                            v-on:click="setTipoPriorizacion(key, 1)"
                                            title="Directa, priorizar territorios con valores más altos"
                                            aria-label="Priorización directa: valores más altos">
                                            <i class="fas fa-arrow-circle-up" aria-hidden="true"></i>
                                        </button>
                                        <button type="button" class="geofocus-priority-type"
                                            v-bind:class="{'text-warning': variable.tipo_priorizacion == -1, 'text-off': variable.tipo_priorizacion == 1}"
                                            v-bind:aria-pressed="variable.tipo_priorizacion == -1"
                                            v-on:click="setTipoPriorizacion(key, -1)"
                                            title="Inversa, priorizar territorios con valores más bajos"
                                            aria-label="Priorización inversa: valores más bajos">
                                            <i class="fas fa-arrow-circle-down" aria-hidden="true"></i>
                                        </button>
                                    </div>
                                </td>
                                <td class="geofocus-priority-weight">
                                    <div class="puntaje-slider" v-if="variable.estado == 1" v-show="variable.active">
                                        <input class="range slider w-100" type="range" min="1" max="100"
                                            v-model="variable.puntaje" v-bind:name="variable.key"
                                            v-bind:aria-label="'Puntaje de ' + variable.nombre">
                                    </div>
                                </td>
                                <td class="text-center">
                                    <span v-show="variable.active" class="geofocus-priority-score">{{ variable.puntaje }}</span>
                                </td>
                                <td class="text-end text-nowrap">
                                    <div class="dropdown">
                                        <button class="a4" type="button" data-bs-toggle="dropdown" aria-expanded="false"
                                            v-bind:aria-label="'Opciones de ' + variable.nombre">
                                            <i class="fas fa-ellipsis-v" aria-hidden="true"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li>
                                                <button class="dropdown-item" data-bs-toggle="modal" data-bs-target="#detallesModal" type="button"
                                                    v-on:click="setVariable(variable.id, 'variables')">
                                                    Información
                                                </button>
                                            </li>
                                            <li>
                                                <button class="dropdown-item" v-on:click="setVariable(variable.id, 'mapa')" type="button">
                                                    Ver en mapa
                                                </button>
                                            </li>
                                            <li>
                                                <a class="dropdown-item" v-bind:href="'<?= URL_APP ?>geofocus/export_variable/' + variable.id + '/' + variable.clave" target="_blank">
                                                    Descargar Excel
                                                </a>
                                            </li>
                                            <li v-show="userRole <= 2">
                                                <button class="dropdown-item" v-on:click="recalcularVariable(variable)" type="button" title="Normalizar valores de la variable">
                                                    Recalcular variable
                                                </button>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                            <tr v-if="!variables.some(variable => variable.estado == 1)">
                                <td colspan="9" class="text-center text-muted py-5">
                                    No hay variables disponibles para esta priorización.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </fieldset>
        </form>
    </section>
</div>
