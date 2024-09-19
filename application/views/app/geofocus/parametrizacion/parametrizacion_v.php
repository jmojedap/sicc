<?php $this->load->view('app/geofocus/parametrizacion/style_v') ?>

<div id="parametrizacionApp">
    <div class="center_box_920">
        <div class="mb-2 d-flex justify-content-between">
            <div class="me-1">
                <input type="checkbox" v-model="display.descripcion"> Descripción
            </div>
            <button class="btn btn-light btn-lg" v-on:click="validateSubmit">
                Calcular
            </button>
        </div>

        <div class="py-2">
            Variables seleccionadas:
            <span class="text-primary">
                {{ variablesActivas.length }}
            </span>
        </div>

        <div class="my-3 d-flex justify-content-center">
            <div class="loader" v-show="loading"></div>
        </div>


        <form accept-charset="utf-8" method="POST" id="parametrizacionForm" @submit.prevent="validateSubmit">
            <fieldset v-bind:disabled="loading">
                <table class="table bg-white table-sm">
                    <thead>
                        <th width="30px"></th>
                        <th>Variables</th>
                        <th width="30%"></th>
                        <th width="10px">Puntaje</th>
                        <th width="10px"></th>
                    </thead>
                    <tbody>
                        <tr v-for="(variable, key) in variables" v-show="variable.estado == 'Cargada'">
                            <td>
                                <input type="checkbox" name="" id="" v-model="variable.active">
                            </td>
                            <td>
                                <span class="pointer">
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
                                <div class="puntaje-slider" v-if="variable.estado == 'Cargada'">
                                    <input class="range" type="range" min="0" max="100" v-model="variable.puntaje"
                                        class="slider w-100" v-bind:name="variable.key">
                                </div>
                            </td>
                            <td class="text-center">
                                {{ variable.puntaje }}
                            </td>
                            <td>
                                <button class="a4" data-bs-toggle="modal" data-bs-target="#detallesModal" type="button"
                                    v-on:click="setCurrent(variable)">
                                    <i class="fas fa-file-alt"></i>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <fieldset>
        </form>
    </div>


    <!-- Modal detalles de la variable actual -->
    <div class="modal fade" id="detallesModal" tabindex="-1" aria-labelledby="detallesModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="detallesModalLabel">{{ currentVariable.nombre }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>
                        {{ currentVariable.descripcion }}
                    </p>
                    <table class="table table-borderless">
                        <tr>
                            <td class="td-title">Tema</td>
                            <td>{{ currentVariable.tema }}</td>
                        </tr>
                        <tr>
                            <td class="td-title">Año datos</td>
                            <td>{{ currentVariable.anio_valores }}</td>
                        </tr>
                        <tr>
                            <td class="td-title">Cálculo</td>
                            <td>{{ currentVariable.descripcion_calculo }}</td>
                        </tr>
                        <tr>
                            <td class="td-title">Entidad</td>
                            <td>{{ currentVariable.entidad }}</td>
                        </tr>
                        <tr>
                            <td class="td-title">Rangos</td>
                            <td>
                                <span class="text-muted">Mín</span>
                                {{ currentVariable.min }}
                                &middot;
                                <span class="text-muted">Media</span>
                                {{ currentVariable.media }}
                                &middot;
                                <span class="text-muted">Máx</span>
                                {{ currentVariable.max }}
                                &middot;
                            </td>
                        </tr>
                        <tr>
                            <td class="td-title">Datos origen</td>
                            <td>{{ currentVariable.datos_origen }}</td>
                        </tr>
                        <tr>
                            <td class="td-title">key</td>
                            <td>{{ currentVariable.key }}</td>
                        </tr>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light w120p" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <div class="center_box_750">
        <table class="table bg-white">
            <thead>
                <th>Orden</th>
                <th>Barrio</th>
                <th>Índice calculado</th>
            </thead>
            <tbody>
                <tr v-for="(territorio, key) in territorios">
                    <td>{{ territorio.orden }}</td>
                    <td>{{ territorio.nombre }}</td>
                    <td>{{ territorio.valor }}</td>
                </tr>
            </tbody>
        </table>
    </div>

</div>

<?php $this->load->view('app/geofocus/parametrizacion/vue_v') ?>