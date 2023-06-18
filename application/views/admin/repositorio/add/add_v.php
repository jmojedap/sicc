<div id="addContenidoApp">
    <div class="card center_box_750">
        <div class="card-body">
            <form id="contenidoForm" accept-charset="utf-8" @submit.prevent="handleSubmit">
                <fieldset v-bind:disabled="loading">
                    <div class="mb-3 row">
                        <label for="tema_cod" class="col-md-4 col-form-label text-right">Tema</label>
                        <div class="col-md-8">
                            <select name="tema_cod" v-model="fields.tema_cod" class="form-control">
                                <option v-for="optionTema in arrTema" v-bind:value="optionTema.str_cod">
                                    {{ optionTema.name }}</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="subtema_1" class="col-md-4 col-form-label text-right">Subtema 1</label>
                        <div class="col-md-8">
                            <select name="subtema_1" v-model="fields.subtema_1" class="form-select form-control">
                                <option v-for="optionSubtema in arrSubtema" v-bind:value="optionSubtema.str_cod">
                                    {{ optionSubtema.name }}</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="anio_publicacion" class="col-md-4 col-form-label text-right">Año publicación</label>
                        <div class="col-md-8">
                            <input name="anio_publicacion" type="number" class="form-control" min="1900"
                                max="<?= date('Y') ?>" v-model="fields.anio_publicacion">
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="formato_cod" class="col-md-4 col-form-label text-right">Formato</label>
                        <div class="col-md-8">
                            <select name="formato_cod" v-model="fields.formato_cod" class="form-control">
                                <option value="">[ Ninguno ]</option>
                                <option v-for="optionFormato in arrFormato" v-bind:value="optionFormato.str_cod">
                                    {{ optionFormato.name }}</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="mb-3 row">
                        <label for="titulo" class="col-md-4 col-form-label text-right">Título</label>
                        <div class="col-md-8">
                            <input class="form-control" name="titulo" required
                                v-model="fields.titulo">
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

    <?php $this->load->view('common/modal_created_v') ?>
</div>

<?php
$this->load->view($this->views_folder . 'add/vue_v');