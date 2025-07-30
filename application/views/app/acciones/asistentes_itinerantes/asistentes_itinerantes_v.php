<div id="asistentesItinerantesApp">
    <div class="">
        <form accept-charset="utf-8" method="POST" id="asistentesItinerantesForm" @submit.prevent="handleSubmit">
            <fieldset v-bind:disabled="loading">
                <input type="hidden" name="accion_id" value="<?= $row->id ?>">
                <input type="hidden" name="tipo_detalle" value="140">
                <!-- <input type="hidden" name="cod_detalle" v-model="fields.cod_detalle"> -->

                <table class="table bg-white">
                    <thead>
                        <th>Identidad género</th>
                        <th>Grupo social</th>
                        <th>Localidad</th>
                        <th>Nombres y apellidos</th>
                        <th width="200px">Tipo Doc.</th>
                        <th>Núm. Documento</th>
                        <th width="150px">Teléfono</th>
                        <th width="90px">Edad</th>
                        <th width="70px"></th>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <select name="relacionado_2" v-model="fields.relacionado_2" class="form-select form-control" required>
                                    <option v-for="optionIdentidadGenero in arrIdentidadGenero" v-bind:value="optionIdentidadGenero.cod">{{ optionIdentidadGenero.name }}</option>
                                </select>
                            </td>
                            <td>
                                <select name="relacionado_1" v-model="fields.relacionado_1" class="form-select form-control" required>
                                    <option v-for="optionGrupoPoblacion in arrGrupoPoblacion" v-bind:value="optionGrupoPoblacion.cod">{{ optionGrupoPoblacion.name }}</option>
                                </select>
                            </td>
                            <td>
                                <select name="texto_1" v-model="fields.texto_1" class="form-select form-control" required>
                                    <option value="ND/NA">ND/NA</option>
                                    <option v-for="optionLocalidad in arrLocalidad" v-bind:value="optionLocalidad.name">{{ optionLocalidad.name }}</option>
                                </select>
                            </td>
                            <td>
                                <input type="text" name="nombre" class="form-control" required v-model="fields.nombre">
                            </td>
                            <td>
                                <select name="categoria_1" v-model="fields.categoria_1" class="form-select form-control" required>
                                    <option v-for="optionTipoDocumento in arrTipoDocumento" v-bind:value="optionTipoDocumento.cod">{{ optionTipoDocumento.name }}</option>
                                </select>
                            </td>
                            <td>
                                <input type="text" pattern="[0-9]*" name="cod_detalle" class="form-control" required v-model="fields.cod_detalle" title="Solo números, sin puntos ni símbolos">
                            </td>
                            <td>
                                <input type="text" pattern="[0-9]{10,}" name="descripcion" class="form-control" required v-model="fields.descripcion" title="Solo números, sin puntos ni símbolos y al menos 10 dígitos">
                            </td>
                            <td>
                                <input name="cantidad" type="number" class="form-control" min="8" max="100" required title="Cantidad" v-model="fields.cantidad">
                            </td>
                            <td>
                                <button class="btn btn-primary" type="submit">
                                    <i class="fa fa-plus"></i>
                                </button>
                            </td>
                        </tr>
                        <tr v-for="(detalle, key) in detalles">
                            <td>{{ identidadGeneroName(detalle.relacionado_2) }}</td>
                            <td>{{ grupoPoblacionName(detalle.relacionado_1) }}</td>
                            <td>{{ detalle.texto_1 }}</td>
                            <td>{{ detalle.nombre }}</td>
                            <td>{{ tipoDocumentoName(detalle.categoria_1) }}</td>
                            <td>{{ detalle.cod_detalle }}</td>
                            <td>{{ detalle.descripcion }}</td>
                            <td class="text-center">{{ parseInt(detalle.cantidad) }}</td>
                            <td>
                                <button class="a4" v-on:click="deleteDetail(detalle.id)" type="button">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>


            <fieldset>
        </form>
        <div class="row">
            <div class="col-md-4">
            </div>
            <div class="col-md-8">
                
            </div>
        </div>
    </div>
</div>

<?php $this->load->view('app/acciones/asistentes_itinerantes/vue_v') ?>