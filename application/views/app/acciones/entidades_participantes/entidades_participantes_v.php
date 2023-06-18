<div id="entidadesParticipantesApp">
    <div class="center_box_920">
        <form accept-charset="utf-8" method="POST" id="entidadesParticipantesForm" @submit.prevent="handleSubmit">
            <fieldset v-bind:disabled="loading">
                <input type="hidden" name="accion_id" value="<?= $row->id ?>">
                <input type="hidden" name="tipo_detalle" value="130">
                <input type="hidden" name="cod_detalle" v-model="fields.cod_detalle">

                <table class="table bg-white">
                    <thead>
                        <th>Tipo entidad</th>
                        <th>Nombre</th>
                        <th width="70px">Cantidad personas</th>
                        <th width="10px"></th>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <select name="relacionado_1" v-model="fields.relacionado_1" class="form-select form-control" required>
                                    <option v-for="optionTipoEntidad in arrTipoEntidad" v-bind:value="optionTipoEntidad.cod">{{ optionTipoEntidad.name }}</option>
                                </select>
                            </td>
                            <td>
                                <input
                                    name="nombre" type="text" class="form-control"
                                    required
                                    title="Nombre entidad organizacion" placeholder="Nombre entidad/organización"
                                    v-model="fields.nombre"
                                >
                            </td>
                            <td>
                                <input name="cantidad" type="number" class="form-control" min="1" required title="Cantidad" v-model="fields.cantidad">
                            </td>
                            <td>
                                <button class="btn btn-primary" type="submit">
                                    <i class="fa fa-plus"></i>
                                </button>
                            </td>
                        </tr>
                        <tr v-for="(detalle, key) in detalles">
                            <td>{{ tipoEntidadName(detalle.relacionado_1) }}</td>
                            <td>{{ detalle.nombre }}</td>
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

<?php $this->load->view('app/acciones/entidades_participantes/vue_v') ?>