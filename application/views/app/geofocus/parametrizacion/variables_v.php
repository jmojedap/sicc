<div v-show="section == 'variables'">
    <div class="mb-2 d-flex justify-content-between">
        <div class="py-2">
            Variables seleccionadas:
            <span class="text-primary">
                {{ variablesActivas.length }}
            </span>
        </div>
        <button class="btn btn-main btn-lg" v-on:click="validateSubmit">
            Calcular
        </button>

        
    </div>


    <form accept-charset="utf-8" method="POST" id="parametrizacionForm" @submit.prevent="validateSubmit">
        <fieldset v-bind:disabled="loading">

            <div class="d-flex justify-content-center mb-2">
                <div class="d-flex" v-show="loading">
                    <div class="loader" v-show="loading"></div>
                </div>
            </div>

            <table class="table bg-white table-sm" v-show="!loading">
                <thead>
                    <th width="30px">
                        <input type="checkbox" v-model="allSelected" v-on:change="toggleSelectAll">
                    </th>
                    <th>Variable</th>
                    <th>Tema</th>
                    <th width="200px"></th>
                    <th width="10px">Puntaje</th>
                    <th width="70px" title="Tipo de prirización, valores altos o bajos">Tipo</th>
                    <th width="10px"></th>
                    <th width="10px"></th>
                    <th width="10px" v-show="userRole <= 2"></th>
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
                        
                        <td>
                            <div class="puntaje-slider" v-if="variable.estado == 'Cargada'" v-show="variable.active">
                                <input class="range" type="range" min="0" max="100" v-model="variable.puntaje"
                                    class="slider w-100" v-bind:name="variable.key">
                            </div>
                        </td>
                        <td class="text-center">
                            <span v-show="variable.active">{{ variable.puntaje }}</span>
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
                            <button class="a4" data-bs-toggle="modal" data-bs-target="#detallesModal" type="button"
                                v-on:click="setCurrent(variable)">
                                <i class="fas fa-info-circle"></i>
                            </button>
                        </td>
                        <td>
                            <button class="a4" v-on:click="actualizarCapa(variable)" type="button">
                                <i class="fas fa-globe"></i>
                            </button>
                        </td>
                        <td v-show="userRole <= 2">
                            <button class="a4" v-on:click="normalizarVariable(variable)" type="button" title="Normalizar valores de la viariable">
                                <i class="fa-solid fa-calculator"></i>
                            </button>
                        </td>

                    </tr>
                </tbody>
            </table>
            <fieldset>
    </form>
</div>