<div id="poblacionBeneficiariaApp">
    <div class="center_box_920">
        <form accept-charset="utf-8" method="POST" id="poblacionBeneficiariaForm" @submit.prevent="handleSubmit">
            <fieldset v-bind:disabled="loading">
                <input type="hidden" name="accion_id" value="<?= $row->id ?>">
                <input type="hidden" name="tipo_detalle" value="120">
                <input type="hidden" name="cod_detalle" v-model="fields.cod_detalle">

                <table class="table bg-white">
                    <thead>
                        <th>Grupo población</th>
                        <th>Sexo</th>
                        <th width="70px">Cantidad</th>
                        <th width="10px"></th>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <select name="relacionado_1" v-model="fields.relacionado_1" class="form-select form-control" required>
                                    <option v-for="optionGrupoPoblacion in arrGrupoPoblacion" v-bind:value="optionGrupoPoblacion.cod">{{ optionGrupoPoblacion.name }}</option>
                                </select>
                            </td>
                            <td>
                                <select name="relacionado_2" v-model="fields.relacionado_2" class="form-select form-control" required v-on:change="setCodDetalle">
                                    <option v-for="optionSexo in arrSexo" v-bind:value="optionSexo.cod">{{ optionSexo.name }}</option>
                                </select>
                            </td>
                            <td>
                                <input name="cantidad" type="number" class="form-control" min="0" required title="Cantidad" v-model="fields.cantidad">
                            </td>
                            <td>
                                <button class="btn btn-primary" type="submit">
                                    <i class="fa fa-plus"></i>
                                </button>
                            </td>
                        </tr>
                        <tr v-for="(detalle, key) in detalles">
                            <td>{{ grupoPoblacionName(detalle.relacionado_1) }}</td>
                            <td>{{ sexoName(detalle.relacionado_2) }}</td>
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

<?php $this->load->view('app/acciones/poblacion_beneficiaria/vue_v') ?>