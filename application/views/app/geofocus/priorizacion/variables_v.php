<div v-show="section == 'variables'">
    <div class="mb-2 d-flex justify-content-between">
        <div class="py-2">
            <span class="text-muted" v-show="!isEditable()">Solo lectura &middot;</span>
            Variables seleccionadas:
            <span class="text-primary">
                {{ variablesActivas.length }}
            </span>
        </div>
        <div>
            <button class="btn btn-main btn-lg" v-on:click="validateSubmit" v-bind:disabled="!isEditable()">
                Calcular
            </button>
        </div>
    </div>

    <form accept-charset="utf-8" method="POST" id="priorizacionForm" @submit.prevent="validateSubmit">
        <fieldset v-bind:disabled="loading">

            <table class="table bg-white table-sm" v-show="!loading">
                <thead>
                    <th width="30px">
                        <input type="checkbox" v-model="allSelected" v-on:change="toggleSelectAll">
                    </th>
                    <th>Variable</th>
                    <th>Tema</th>
                    <th width="70px" title="Tipo de prirización, valores altos o bajos">Tipo</th>
                    <th width="200px"></th>
                    <th width="10px">Puntaje</th>
                    <th width="10px"></th>
                </thead>
                <tbody>
                    <tr v-for="(variable, key) in variables" v-show="variable.estado == 'Cargada'">
                        <td v-bind:class="variableClass(variable)">
                            <input type="checkbox" v-model="variable.active">
                        </td>
                        
                        <td v-bind:class="variableClass(variable)" class="pointer" v-on:click="toggleActivateVariable(key)">
                            <span>
                                {{ variable.nombre }}
                            </span>
                        </td>
                        <td>
                            <div class="tema" v-bind:class="textToClass(variable.tema,'tema')">
                                {{ variable.tema }}
                            </div>
                        </td>
                        <td class="text-center">
                            <span v-show="variable.active" class="selector-tipo">
                                <i class="fas fa-arrow-circle-up pointer me-1" v-bind:class="{'text-info': variable.tipo_priorizacion == 1, 'text-off': variable.tipo_priorizacion == -1 }"
                                    v-on:click="setTipoPriorizacion(key, 1)" 
                                    title="Directa, priorizar territorios con valores más altos"></i>
                                <i class="fas fa-arrow-circle-down pointer" v-bind:class="{'text-warning': variable.tipo_priorizacion == -1, 'text-off': variable.tipo_priorizacion == 1 }"
                                    v-on:click="setTipoPriorizacion(key, -1)" 
                                    title="Inversa, priorizar territorios con valores más bajos"></i>
                            </span>
                        </td>
                        
                        <td>
                            <div class="puntaje-slider" v-if="variable.estado == 'Cargada'" v-show="variable.active">
                                <input class="range" type="range" min="1" max="100" v-model="variable.puntaje"
                                    class="slider w-100" v-bind:name="variable.key">
                            </div>
                        </td>
                        <td class="text-center">
                            <span v-show="variable.active">{{ variable.puntaje }}</span>
                        </td>
                        
                        
                        <td class="text-center">
                            <div class="dropdown">
                                <button class="a4" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fas fa-ellipsis-v"></i>
                                </button>
                                <ul class="dropdown-menu">
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
                                        <a class="dropdown-item" v-bind:href="`<?= URL_APP ?>geofocus/export_variable/`  + variable.id + '/' + variable.clave" target="_blank">
                                            Descargar Excel
                                        </a>
                                    </li>
                                    <li v-show="userRole <= 2">
                                        <button class="dropdown-item" v-on:click="normalizarVariable(variable)" type="button" title="Normalizar valores de la variable">
                                            Normalizar
                                        </button>
                                    </li>
                                    
                                </ul>
                            </div>
                        </td>

                    </tr>
                </tbody>
            </table>
            <fieldset>
    </form>
</div>