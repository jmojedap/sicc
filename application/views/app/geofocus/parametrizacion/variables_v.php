<div v-show="section == 'variables'">
    <div class="mb-2 d-flex justify-content-between">
        <div class="me-1">
            <input type="checkbox" v-model="display.descripcion"> Descripción
        </div>
        <button class="btn btn-warning btn-lg" v-on:click="validateSubmit">
            Calcular
        </button>
    </div>

    <div class="py-2">
        Variables seleccionadas:
        <span class="text-primary">
            {{ variablesActivas.length }}
        </span>
    </div>

    <form accept-charset="utf-8" method="POST" id="parametrizacionForm" @submit.prevent="validateSubmit">
        <fieldset v-bind:disabled="loading">
            <div class="d-flex justify-content-center mb-2">
                <div class="text-center" v-show="loading">
                    <p class="text-muted">Calculando...</p>
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
                    <th width="10px">Tipo</th>
                    <th width="10px">Puntaje</th>
                    <th width="10px"></th>
                    <th width="10px">
    
                    </th>
                </thead>
                <tbody>
                    <tr v-for="(variable, key) in variables" v-show="variable.estado == 'Cargada'">
                        <td v-bind:class="{'table-info': variable.active }">
                            <input type="checkbox" v-model="variable.active">
                        </td>
                        <td v-bind:class="{'table-info': variable.active }">
                            <span class="pointer" v-on:click="toggleActivateVariable(key)">
                                {{ variable.nombre }}
                            </span>
                            <p v-show="display.descripcion">
                                <small class="text-muted">{{ variable.tema }}</small>
                                &middot;
                                <small class="text-muted">{{ variable.entidad }}</small>
                            </p>
                            <p v-show="display.descripcion">
                                {{ variable.descripcion }}
                            </p>
                        </td>
                        <td>
                            {{ variable.tema }}
                        </td>
                        <td>
                            <div class="puntaje-slider" v-if="variable.estado == 'Cargada'" v-show="variable.active">
                                <input class="range" type="range" min="0" max="100" v-model="variable.puntaje"
                                    class="slider w-100" v-bind:name="variable.key">
                            </div>
                        </td>
                        <td class="text-center">
                            <span v-show="variable.active">
                                <i class="fas fa-arrow-circle-up text-info pointer" v-show="variable.tipo_priorizacion == 1"
                                    v-on:click="setTipoPriorizacion(key, -1)" 
                                    title="Priorización directa, valores más altos"></i>
                                <i class="fas fa-arrow-circle-down text-warning pointer" v-show="variable.tipo_priorizacion == -1"
                                    v-on:click="setTipoPriorizacion(key, 1)" 
                                    title="Priorización inversa, valores más bajos"></i>
                            </span>
                        </td>
                        <td class="text-center">
                            <span v-show="variable.active">{{ variable.puntaje }}</span>
                        </td>
                        <td>
                            <button class="a4" data-bs-toggle="modal" data-bs-target="#detallesModal" type="button"
                                v-on:click="setCurrent(variable)">
                                <i class="fas fa-info-circle"></i>
                            </button>
                        </td>
                        <td>
                            <button class="btn btn-light btn-sm" v-on:click="normalizarVariable(variable)" type="button">
                                Normalizar
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
            <fieldset>
    </form>
</div>