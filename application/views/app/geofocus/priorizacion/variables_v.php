<div v-show="section == 'variables'" class="geofocus-priority-variables geofocus-base-content">
    <!-- 2026-10-12: prompt (30%) y tabla (70%), con herramientas y conteo sobre la tabla. -->
    <div class="geofocus-priority-variables-layout" v-bind:class="{'has-assistant': isEditable() && Number(userId) > 0}">
        <?php $this->load->view('app/geofocus/priorizacion/ai_variables_config_v') ?>

        <section class="geofocus-base-variables" aria-label="Selección de variables">
            <div class="geofocus-priority-table-toolbar">
                <div class="geofocus-priority-table-filters" role="group" aria-label="Filtros de variables">
                    <button type="button" class="btn btn-sm"
                        v-bind:class="variablesFilters.onlySelected ? 'btn-primary' : 'btn-outline-secondary'"
                        v-bind:aria-pressed="variablesFilters.onlySelected"
                        v-bind:title="variablesFilters.onlySelected ? 'Mostrar todas las variables' : 'Mostrar solo las variables seleccionadas'"
                        v-on:click="variablesFilters.onlySelected = !variablesFilters.onlySelected">
                        <i class="fas fa-filter me-1" aria-hidden="true"></i> Solo seleccionadas
                    </button>
                    <label for="priority-variable-theme" class="visually-hidden">Filtrar variables por tema</label>
                    <select id="priority-variable-theme" class="form-select form-select-sm" v-model="variablesFilters.tema">
                        <option value="">Todos los temas</option>
                        <option v-for="tema in variablesTemas" v-bind:key="tema" v-bind:value="tema">{{ tema }}</option>
                    </select>
                </div>
                <button type="button" class="btn btn-primary btn-lg geofocus-priority-calculate" v-on:click="validateSubmit"
                    v-bind:disabled="loading || descripcion.loading || aiVariablesConfig.loading || !isEditable()">
                    <i class="fas fa-calculator me-1" aria-hidden="true"></i> Calcular
                </button>
            </div>
            <p class="geofocus-priority-count" role="status" aria-live="polite">
                {{ variablesActivas.length }} variables seleccionadas
                <span v-if="!isEditable()" class="ms-2">· Solo lectura</span>
            </p>
            <form accept-charset="utf-8" method="POST" id="priorizacionForm" @submit.prevent="validateSubmit">
                <fieldset v-bind:disabled="loading || descripcion.loading || aiVariablesConfig.loading">
                    <div class="table-responsive geofocus-priority-table" v-show="!loading">
                        <table class="table table-sm table-hover align-middle mb-0">
                            <colgroup>
                                <col class="geofocus-priority-col-select">
                                <col class="geofocus-priority-col-name">
                                <col class="geofocus-priority-col-theme">
                                <col class="geofocus-priority-col-type">
                                <col class="geofocus-priority-col-weight">
                                <col class="geofocus-priority-col-score">
                                <col class="geofocus-priority-col-actions">
                            </colgroup>
                            <thead>
                                <tr>
                                    <th scope="col" class="text-center">
                                        <input type="checkbox" class="form-check-input" v-model="allSelected" v-on:change="toggleSelectAll"
                                            aria-label="Seleccionar todas las variables de la priorización"
                                            title="Seleccionar todas las variables, incluso las ocultas por los filtros">
                                    </th>
                                    <th scope="col">Nombre</th>
                                    <th scope="col">Tema</th>
                                    <th scope="col" class="text-center" title="Priorizar valores altos o bajos">Sentido</th>
                                    <th scope="col" class="geofocus-priority-weight">Ponderación</th>
                                    <th scope="col" class="text-center">Puntaje</th>
                                    <th scope="col" class="text-end"><span class="visually-hidden">Acciones</span></th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- 2026-10-12: aplicar el color de selección y sentido a todas las columnas de la fila. -->
                                <tr v-for="(variable, key) in variables" v-bind:key="variable.id" v-show="matchesVariableFilters(variable)"
                                    v-bind:class="variableClass(variable)"
                                    v-bind:style="{'--variable-color': variable.color || '#0084bf'}" v-bind:data-variable-id="variable.id">
                                    <td class="text-center">
                                        <input type="checkbox" class="form-check-input" v-model="variable.active"
                                            v-on:change="markPrioritizationPending"
                                            v-bind:aria-label="'Seleccionar ' + variable.nombre">
                                    </td>
                                    <td class="geofocus-priority-name geofocus-priority-name-cell"
                                        role="checkbox" v-bind:tabindex="loading || descripcion.loading || aiVariablesConfig.loading ? -1 : 0"
                                        v-bind:aria-disabled="loading || descripcion.loading || aiVariablesConfig.loading"
                                        v-bind:aria-checked="!!variable.active"
                                        v-on:click="toggleActivateVariable(key)"
                                        v-on:keydown.enter="toggleActivateVariable(key)"
                                        v-on:keydown.space.prevent="toggleActivateVariable(key)">
                                        <span class="variable-name">{{ variable.nombre }}</span>
                                        <code class="d-block">{{ variable.clave }}</code>
                                    </td>
                                    <td><span class="tema" v-bind:class="textToClass(variable.tema, 'tema')">{{ variable.tema }}</span></td>
                                    <td class="text-center text-nowrap">
                                        <div v-show="variable.active" class="selector-tipo geofocus-priority-types">
                                            <!-- 2026-10-12: control azul/amarillo con una flecha persistente que gira al alternar el sentido. -->
                                            <button type="button" class="geofocus-priority-type"
                                                v-bind:class="variable.tipo_priorizacion == 1 ? 'is-direct' : 'is-inverse'"
                                                v-on:click="setTipoPriorizacion(key, variable.tipo_priorizacion == 1 ? -1 : 1)"
                                                v-bind:title="variable.tipo_priorizacion == 1
                                                    ? 'Directa: prioriza valores altos. Clic para cambiar a inversa.'
                                                    : 'Inversa: prioriza valores bajos. Clic para cambiar a directa.'"
                                                v-bind:aria-pressed="variable.tipo_priorizacion != 1"
                                                v-bind:aria-label="'Priorizar valores bajos en ' + variable.nombre">
                                                <i class="fas fa-arrow-up geofocus-priority-direction-arrow" aria-hidden="true"></i>
                                            </button>
                                        </div>
                                    </td>
                                    <td class="geofocus-priority-weight">
                                        <div class="puntaje-slider" v-if="variable.estado == 1" v-show="variable.active">
                                            <input class="range slider w-100" type="range" min="1" max="100"
                                                v-model="variable.puntaje" v-bind:name="variable.key"
                                                v-on:input="markPrioritizationPending"
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
                                <tr v-if="!variablesFiltradas.length">
                                    <td colspan="7" class="text-center text-muted py-5">
                                        No hay variables que coincidan con los filtros actuales.
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </fieldset>
            </form>
        </section>
    </div>
</div>
