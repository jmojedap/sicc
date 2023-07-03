<div id="editContenidoApp">
    <form accept-charset="utf-8" method="POST" id="contenidoForm" @submit.prevent="handleSubmit">
        <input type="hidden" name="id" value="<?= $row->id ?>">
        <fieldset v-bind:disabled="loading">
            <div class="mb-2">
                <button class="btn btn-success w120p" type="submit">Guardar</button>
            </div>
            <div class="row">
                <div class="col-md-7">
                    <div class="card">
                        <div class="card-body">
                            <div class="mb-3">
                                <label for="titulo">Título</label>
                                <textarea
                                    name="titulo" type="text" class="form-control" required rows="3"
                                    v-model="fields.titulo"
                                ></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="tipo_archivo">Tipo archivo contenido</label>
                                <select name="tipo_archivo" v-model="fields.tipo_archivo" class="form-select">
                                    <option value="">[ No definido ]</option>
                                    <option v-for="optionTipoArchivo in arrTipoArchivo" v-bind:value="optionTipoArchivo.str_cod">{{ optionTipoArchivo.name }}</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="url_contenido" class="">
                                    URL archivo contenido
                                    <?php if ( strlen($row->url_contenido) > 0 ) : ?>
                                        &middot;
                                        <a href="<?= $row->url_contenido ?>" target="_blank">Abrir ></a>
                                    <?php endif; ?>
                                </label>
                                <input
                                    name="url_contenido" type="url" class="form-control"
                                    title="URL al contenido" placeholder="URL al contenido"
                                    v-model="fields.url_contenido"
                                >
                            </div>
                            <div class="mb-3">
                                <label for="descripcion">Descripción</label>
                                <textarea
                                    name="descripcion" type="text" class="form-control" required rows="6"
                                    v-model="fields.descripcion"
                                ></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="palabras_clave">Palabras clave</label>
                                <textarea
                                    name="palabras_clave" type="text" class="form-control" required rows="2"
                                    v-model="fields.palabras_clave"
                                ></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="slug">Slug</label>
                                <input
                                    name="slug" type="text" class="form-control" 
                                    v-model="fields.slug"
                                >
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-5">
                    <div class="card">
                        <div class="card-body">
                            <div class="mb-3 row">
                                <label for="estado_publicacion" class="col-md-4 col-form-label text-end">Estado publicación</label>
                                <div class="col-md-8">
                                    <select name="estado_publicacion" v-model="fields.estado_publicacion" class="form-select" required>
                                        <option v-for="optionEstadoPublicacion in arrEstadoPublicacion"
                                            v-bind:value="optionEstadoPublicacion.str_cod">
                                            {{ optionEstadoPublicacion.name }}
                                        </option>
                                    </select>
                                </div>
                            </div>

                            <p class="lead text-center">Contenido</p>

                            <div class="mb-3 row">
                                <label for="tema_cod" class="col-md-4 col-form-label text-end">Tema</label>
                                <div class="col-md-8">
                                    <select name="tema_cod" v-model="fields.tema_cod" class="form-select">
                                        <option v-for="optionTema in arrTema" v-bind:value="optionTema.str_cod">{{ optionTema.name }}</option>
                                    </select>
                                </div>
                            </div>

                            <div class="mb-3 row">
                                <label for="subtema_1" class="col-md-4 col-form-label text-end">Subtema 1</label>
                                <div class="col-md-8">
                                    <select name="subtema_1" v-model="fields.subtema_1" class="form-select">
                                        <option v-for="optionSubtema in arrSubtema" v-bind:value="optionSubtema.str_cod">{{ optionSubtema.name }}</option>
                                    </select>
                                </div>
                            </div>

                            <div class="mb-3 row">
                                <label for="subtema_2" class="col-md-4 col-form-label text-end">Subtema 2</label>
                                <div class="col-md-8">
                                    <select name="subtema_2" v-model="fields.subtema_2" class="form-select">
                                        <option value="">[ Todos ]</option>
                                        <option v-for="optionSubtema in arrSubtema" v-bind:value="optionSubtema.str_cod">{{ optionSubtema.name }}</option>
                                    </select>
                                </div>
                            </div>

                            <p class="lead text-center">Descripción</p>
                            <div class="mb-3 row">
                                <label for="anio_publicacion" class="col-md-4 col-form-label text-end">Año publicación</label>
                                <div class="col-md-8">
                                    <input
                                        name="anio_publicacion" type="number" class="form-control" min="1900" max="<?= date('Y') ?>"
                                        v-model="fields.anio_publicacion"
                                    >
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label for="formato_cod" class="col-md-4 col-form-label text-end">Formato</label>
                                <div class="col-md-8">
                                    <select name="formato_cod" v-model="fields.formato_cod" class="form-control">
                                        <option value="">[ Ninguno ]</option>
                                        <option v-for="optionFormato in arrFormato" v-bind:value="optionFormato.str_cod">{{ optionFormato.name }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label for="tipo_contenido" class="col-md-4 col-form-label text-end">Tipo contenido</label>
                                <div class="col-md-8">
                                    <select name="tipo_contenido" v-model="fields.tipo_contenido" class="form-control">
                                        <option value="">[ Ninguno ]</option>
                                        <option v-for="optionTipoContenido in arrTipoContenido" v-bind:value="optionTipoContenido.str_cod">{{ optionTipoContenido.name }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label for="categoria_contenido" class="col-md-4 col-form-label text-end">Categoría</label>
                                <div class="col-md-8">
                                    <select name="categoria_contenido" v-model="fields.categoria_contenido" class="form-control">
                                        <option value="">[ Ninguno ]</option>
                                        <option v-for="optionCategoriaContenido in arrCategoriaContenido" v-bind:value="optionCategoriaContenido.str_cod">{{ optionCategoriaContenido.name }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label for="metodologia_cod" class="col-md-4 col-form-label text-end">Metodología</label>
                                <div class="col-md-8">
                                    <select name="metodologia_cod" v-model="fields.metodologia_cod" class="form-control">
                                        <option value="">[ Ninguno ]</option>
                                        <option v-for="optionMetodologia in arrMetodologia" v-bind:value="optionMetodologia.str_cod">{{ optionMetodologia.name }}</option>
                                    </select>
                                </div>
                            </div>

                            
                        </div>
                    </div>
                </div>
            </div>
        <fieldset>
    </form>
</div>

<?php $this->load->view('app/repositorio/edit/basic_vue_v') ?>