<div id="addMedicionApp">
    <div class="card center_box_750" v-show="section == 'form'">
        <div class="card-body">
            <form id="addForm" accept-charset="utf-8" @submit.prevent="handleSubmit">
                <fieldset v-bind:disabled="loading">
                    <div class="mb-3 row">
                        <label for="tipo" class="col-md-4 col-form-label text-end">Tipo</label>
                        <div class="col-md-8">
                            <select name="tipo" v-model="fields.tipo" class="form-select" required>
                                <option v-for="optionTipo in arrTipo" v-bind:value="optionTipo.str_cod">{{ optionTipo.name }}</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="nombre_medicion" class="col-md-4 col-form-label text-end">Nombre</label>
                        <div class="col-md-8">
                            <input
                                class="form-control" name="nombre_medicion" required
                                v-model="fields.nombre_medicion">
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <div class="offset-md-4 col-md-8 col-sm-12">
                            <button class="btn btn-success w120p" type="submit">
                                <span><i class="fa fa-spin fa-spinner" v-show="loading"></i></span>
                                Crear
                            </button>
                        </div>
                    </div>
                </fieldset>
            </form>
        </div>

    </div>
    <div class="card center_box_750" v-show="section == 'created'">
        <div class="card-body">
            <i class="fa fa-check"></i>
            Medición creada correctamente
        </div>
        <div class="card-footer">
            <button type="button" class="btn btn-primary w120p me-2" v-on:click="goToCreated">
                Abrir
            </button>
            <button type="button" class="btn btn-secondary w120p" v-on:click="clearForm" data-dismiss="modal">
                <i class="fa fa-plus"></i>
                Crear otra
            </button>
        </div>
    </div>
</div>

<?php
$this->load->view($this->views_folder . 'add/vue_v');