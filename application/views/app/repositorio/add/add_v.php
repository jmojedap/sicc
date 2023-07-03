<div id="addContenidoApp">
    <div class="card center_box_920">
        <div class="card-body">
            <form id="contenidoForm" accept-charset="utf-8" @submit.prevent="handleSubmit">
                <input type="hidden" name="entidad" v-model="fields.entidad">
                <fieldset v-bind:disabled="loading">
                    <div class="mb-3 row">
                        <label for="titulo" class="col-md-4 col-form-label text-end">Título</label>
                        <div class="col-md-8">
                            <input class="form-control" name="titulo" required
                                v-model="fields.titulo">
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="descripcion" class="col-md-4 col-form-label text-end">Descripción</label>
                        <div class="col-md-8">
                            <textarea
                                name="descripcion" class="form-control" rows="3" required
                                title="Descripción" placeholder="Descripción"
                                v-model="fields.descripcion"
                            ></textarea>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="entidad_sigla" class="col-md-4 col-form-label text-end">Entidad</label>
                        <div class="col-md-8">
                            <select name="entidad_sigla" v-model="fields.entidad_sigla" class="form-select form-control" required v-on:change="setEntidad">
                                <option v-for="optionEntidad in arrEntidad" v-bind:value="optionEntidad.abbreviation">{{ optionEntidad.name }}</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="tipo_archivo" class="col-md-4 col-form-label text-end">Tipo archivo</label>
                        <div class="col-md-8">
                            <select name="tipo_archivo" v-model="fields.tipo_archivo" class="form-select form-control" required>
                                <option v-for="optionTipoArchivo in arrTipoArchivo" v-bind:value="optionTipoArchivo.cod">{{ optionTipoArchivo.name }}</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="url_contenido" class="col-md-4 col-form-label text-end">URL archivo contenido</label>
                        <div class="col-md-8">
                            <input
                                name="url_contenido" type="url" class="form-control"
                                required
                                title="URL Archivo / Contenido" placeholder="URL Archivo / Contenido"
                                v-model="fields.url_contenido"
                            >
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="tema_cod" class="col-md-4 col-form-label text-end">Tema</label>
                        <div class="col-md-8">
                            <select name="tema_cod" v-model="fields.tema_cod" class="form-control">
                                <option v-for="optionTema in arrTema" v-bind:value="optionTema.str_cod">
                                    {{ optionTema.name }}</option>
                            </select>
                        </div>
                    </div>


                    <div class="mb-3 row">
                        <label for="subtema_1" class="col-md-4 col-form-label text-end">Subtema</label>
                        <div class="col-md-8">
                            <select name="subtema_1" v-model="fields.subtema_1" class="form-select form-control">
                                <option v-for="optionSubtema in arrSubtema" v-bind:value="optionSubtema.str_cod">
                                    {{ optionSubtema.name }}</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="anio_publicacion" class="col-md-4 col-form-label text-end">Año publicación</label>
                        <div class="col-md-8">
                            <input name="anio_publicacion" type="number" class="form-control" min="1900"
                                max="<?= date('Y') ?>" v-model="fields.anio_publicacion">
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="formato_cod" class="col-md-4 col-form-label text-end">Formato</label>
                        <div class="col-md-8">
                            <select name="formato_cod" v-model="fields.formato_cod" class="form-select">
                                <option v-for="optionFormato in arrFormato" v-bind:value="optionFormato.str_cod">
                                    {{ optionFormato.name }}</option>
                            </select>
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

    <?php $this->load->view('common/bs5/modal_created_v') ?>
</div>

<?php
$this->load->view($this->views_folder . 'add/vue_v');