<div id="editContenidoApp">
    <div class="center_box_920">
        <form accept-charset="utf-8" method="POST" id="contenidoForm" @submit.prevent="handleSubmit">
            <input type="hidden" name="id" value="<?= $row->id ?>">
            <fieldset v-bind:disabled="loading">
                <div class="mb-2">
                    <button class="btn btn-success w120p" type="submit">Guardar</button>
                </div>
                <div class="card mb-2">
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="titulo">Título</label>
                            <textarea name="titulo" type="text" class="form-control" required rows="2"
                                v-model="fields.titulo"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="descripcion">Descripción</label>
                            <textarea name="descripcion" type="text" class="form-control" required rows="6"
                                v-model="fields.descripcion"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="palabras_clave">Palabras clave</label>
                            <textarea name="palabras_clave" type="text" class="form-control" required rows="2"
                                v-model="fields.palabras_clave"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="slug">Slug</label>
                            <input name="slug" type="text" class="form-control" v-model="fields.slug">
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <p class="lead text-center text-primary">Ubicación y referencia</p>
                        <div class="mb-3 row">
                            <label for="url_contenido" class="col-md-4 col-form-label text-end">
                                URL al contenido
                                <?php if ( strlen($row->url_contenido) > 0 ) : ?>
                                &middot;
                                <a href="<?= $row->url_contenido ?>" target="_blank">Abrir ></a>
                                <?php endif; ?>
                            </label>
                            <div class="col-md-8">
                                <input name="url_contenido" type="url" class="form-control" title="URL al contenido"
                                    placeholder="URL al contenido" v-model="fields.url_contenido">
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="url_image" class="col-md-4 col-form-label text-end text-right">URL portada</label>
                            <div class="col-md-8">
                                <input
                                    name="url_image" type="url" class="form-control"
                                    title="URL portada" placeholder="URL portada"
                                    v-model="fields.url_image"
                                >
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="tipo_archivo" class="col-md-4 col-form-label text-end">Tipo archivo
                                contenido</label>
                            <div class="col-md-8">
                                <select name="tipo_archivo" v-model="fields.tipo_archivo" class="form-select">
                                    <option value="">[ No definido ]</option>
                                    <option v-for="optionTipoArchivo in arrTipoArchivo"
                                        v-bind:value="optionTipoArchivo.cod">{{ optionTipoArchivo.name }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="url_carpeta_anexos" class="col-md-4 col-form-label text-end text-right">Link carpeta anexos</label>
                            <div class="col-md-8">
                                <input
                                    name="url_carpeta_anexos" type="url" class="form-control"
                                    title="Link carpeta anexos" placeholder="Link carpeta anexos"
                                    v-model="fields.url_carpeta_anexos"
                                >
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="url_contenido_externo" class="col-md-4 col-form-label text-end text-right">Link externo al contenido</label>
                            <div class="col-md-8">
                                <input
                                    name="url_contenido_externo" type="url" class="form-control"
                                    title="Link externo al contenido" placeholder="Link externo al contenido"
                                    v-model="fields.url_contenido_externo"
                                >
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label for="radicado_orfeo" class="col-md-4 col-form-label text-end text-right">Radicado ORFEO</label>
                            <div class="col-md-8">
                                <input name="radicado_orfeo" type="text" class="form-control" title="Radicado ORFEO" v-model="fields.radicado_orfeo">
                            </div>
                        </div>

                        

                        <div class="mb-3 row">
                            <label for="estado_publicacion" class="col-md-4 col-form-label text-end">Estado
                                publicación</label>
                            <div class="col-md-8">
                                <select name="estado_publicacion" v-model="fields.estado_publicacion"
                                    class="form-select" required>
                                    <option v-for="optionEstadoPublicacion in arrEstadoPublicacion"
                                        v-bind:value="optionEstadoPublicacion.cod">
                                        {{ optionEstadoPublicacion.name }}
                                    </option>
                                </select>
                            </div>
                        </div>

                        <p class="lead text-center text-primary">Creadores</p>

                        <div class="mb-3 row">
                            <label for="entidad_sigla" class="col-md-4 col-form-label text-end">Entidad</label>
                            <div class="col-md-8">
                                <select name="entidad_sigla" v-model="fields.entidad_sigla" class="form-select form-control" required v-on:change="setEntidad">
                                    <option v-for="optionEntidad in arrEntidad" v-bind:value="optionEntidad.abbreviation">{{ optionEntidad.name }}</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label for="dependencia" class="col-md-4 col-form-label text-end text-right">Dependencia/Área</label>
                            <div class="col-md-8">
                                <input
                                    name="dependencia" type="text" class="form-control"
                                    required
                                    title="Dependencia/Área" placeholder="Dependencia/Área"
                                    v-model="fields.dependencia"
                                >
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label for="investigadores" class="col-md-4 col-form-label text-end text-right">Investigadores/Autores</label>
                            <div class="col-md-8">
                                <textarea
                                    name="investigadores" class="form-control" rows="2"
                                    title="Investigadores/Autores" placeholder="Investigadores/Autores"
                                    v-model="fields.investigadores"
                                ></textarea>
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label for="entidades" class="col-md-4 col-form-label text-end text-right">Otras entidades</label>
                            <div class="col-md-8">
                                <input
                                    name="entidades" type="text" class="form-control"
                                    title="Otras entidades" placeholder="Otras entidades que participaron en elaboración del contenido"
                                    v-model="fields.entidades"
                                >
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label for="codigo" class="col-md-4 col-form-label text-end text-right">Código publicación</label>
                            <div class="col-md-8">
                                <input
                                    name="codigo" type="text" class="form-control"
                                    title="Código publicación" placeholder="Código publicación"
                                    v-model="fields.codigo"
                                >
                                <span class="form-text">Código con el que el creador identifica al contenido</span>
                            </div>
                        </div>

                        <p class="lead text-center text-primary">Contenido</p>

                        <div class="mb-3 row">
                            <label for="tema_cod" class="col-md-4 col-form-label text-end">Tema</label>
                            <div class="col-md-8">
                                <select name="tema_cod" v-model="fields.tema_cod" class="form-select">
                                    <option v-for="optionTema in arrTema" v-bind:value="optionTema.cod">
                                        {{ optionTema.name }}</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label for="subtema_1" class="col-md-4 col-form-label text-end">Subtema 1</label>
                            <div class="col-md-8">
                                <select name="subtema_1" v-model="fields.subtema_1" class="form-select">
                                    <option v-for="optionSubtema in arrSubtema" v-bind:value="optionSubtema.cod">
                                        {{ optionSubtema.name }}</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label for="subtema_2" class="col-md-4 col-form-label text-end">Subtema 2</label>
                            <div class="col-md-8">
                                <select name="subtema_2" v-model="fields.subtema_2" class="form-select">
                                    <option value="">[ NA ]</option>
                                    <option v-for="optionSubtema in arrSubtema" v-bind:value="optionSubtema.cod">
                                        {{ optionSubtema.name }}</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label for="localidades" class="col-md-4 col-form-label text-end text-right">Localidades</label>
                            <div class="col-md-8">
                                <input
                                    name="localidades" type="text" class="form-control"
                                    title="Localidades" placeholder="Localidades asociadas separadas por coma"
                                    v-model="fields.localidades"
                                >
                            </div>
                        </div>

                        <p class="lead text-center text-primary">Taxonomía sector</p>

                        <div class="mb-3 row">
                            <label for="sector_campo" class="col-md-4 col-form-label text-end">Campo</label>
                            <div class="col-md-8">
                                <select name="sector_campo" v-model="fields.sector_campo"
                                    class="form-select form-control" v-on:change="unsetSubcampo">
                                    <option value="">[ ND/NA ]</option>
                                    <option v-for="optionCampo in arrCampo" v-bind:value="optionCampo.cod">
                                        {{ optionCampo.name }}</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label for="sector_subcampo" class="col-md-4 col-form-label text-end">Subcampo</label>
                            <div class="col-md-8">
                                <select name="sector_subcampo" v-model="fields.sector_subcampo"
                                    class="form-select form-control" v-on:change="unsetArea">
                                    <option value="">[ ND/NA ]</option>
                                    <option v-for="optionSubcampo in filteredSubcampos"
                                        v-bind:value="optionSubcampo.cod">{{ optionSubcampo.name }}</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label for="sector_area" class="col-md-4 col-form-label text-end">Área</label>
                            <div class="col-md-8">
                                <select name="sector_area" v-model="fields.sector_area"
                                    class="form-select form-control">
                                    <option value="">[ ND/NA ]</option>
                                    <option v-for="optionArea in filteredAreas" v-bind:value="optionArea.cod">
                                        {{ optionArea.name }}</option>
                                </select>
                            </div>
                        </div>

                        <p class="lead text-center text-primary">Descripción</p>
                        <div class="mb-3 row">
                            <label for="anio_publicacion" class="col-md-4 col-form-label text-end">Año
                                publicación</label>
                            <div class="col-md-8">
                                <input name="anio_publicacion" type="number" class="form-control" min="1900"
                                    max="<?= date('Y') ?>" v-model="fields.anio_publicacion">
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="formato_cod" class="col-md-4 col-form-label text-end">Formato</label>
                            <div class="col-md-8">
                                <select name="formato_cod" v-model="fields.formato_cod" class="form-control form-select">
                                    <option value="">[ Ninguno ]</option>
                                    <option v-for="optionFormato in arrFormato" v-bind:value="optionFormato.cod">
                                        {{ optionFormato.name }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="tipo_contenido" class="col-md-4 col-form-label text-end">Tipo
                                contenido</label>
                            <div class="col-md-8">
                                <select name="tipo_contenido" v-model="fields.tipo_contenido" class="form-control form-select">
                                    <option value="">[ Ninguno ]</option>
                                    <option v-for="optionTipoContenido in arrTipoContenido"
                                        v-bind:value="optionTipoContenido.cod">
                                        {{ optionTipoContenido.name }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="categoria_contenido" class="col-md-4 col-form-label text-end">Categoría</label>
                            <div class="col-md-8">
                                <select name="categoria_contenido" v-model="fields.categoria_contenido"
                                    class="form-control form-select">
                                    <option value="">[ Ninguno ]</option>
                                    <option v-for="optionCategoriaContenido in arrCategoriaContenido"
                                        v-bind:value="optionCategoriaContenido.cod">
                                        {{ optionCategoriaContenido.name }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="metodologia_cod" class="col-md-4 col-form-label text-end">Metodología</label>
                            <div class="col-md-8">
                                <select name="metodologia_cod" v-model="fields.metodologia_cod" class="form-control form-select">
                                    <option value="">[ Ninguno ]</option>
                                    <option v-for="optionMetodologia in arrMetodologia"
                                        v-bind:value="optionMetodologia.cod">{{ optionMetodologia.name }}
                                    </option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label for="etapa_cod" class="col-md-4 col-form-label text-end text-right">Etapa de elaboración</label>
                            <div class="col-md-8">
                                <select name="etapa_cod" v-model="fields.etapa_cod" class="form-select form-control" required>
                                    <option v-for="optionEtapa in arrEtapa" v-bind:value="optionEtapa.cod">{{ optionEtapa.name }}</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label for="medio_divulgacion_cod" class="col-md-4 col-form-label text-end text-right">Medio de divulgación</label>
                            <div class="col-md-8">
                                <select name="medio_divulgacion_cod" v-model="fields.medio_divulgacion_cod" class="form-select form-control">
                                    <option v-for="optionMedioDivulgacion in arrMedioDivulgacion" v-bind:value="optionMedioDivulgacion.cod">{{ optionMedioDivulgacion.name }}</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label for="periodicidad" class="col-md-4 col-form-label text-end text-right">Periodicidad elaboración</label>
                            <div class="col-md-8">
                                <select name="periodicidad" v-model="fields.periodicidad" class="form-select form-control">
                                    <option v-for="optionPeriodicidad in arrPeriodicidad" v-bind:value="optionPeriodicidad.cod">{{ optionPeriodicidad.name }}</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label for="cantidad_paginas" class="col-md-4 col-form-label text-end text-right">Cantidad de páginas</label>
                            <div class="col-md-8">
                                <input
                                    name="cantidad_paginas" type="number" class="form-control" min="0"
                                    title="Cantidad de páginas"
                                    v-model="fields.cantidad_paginas"
                                >
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label for="fecha_inicio" class="col-md-4 col-form-label text-end text-right">Fecha inicio</label>
                            <div class="col-md-8">
                                <input
                                    name="fecha_inicio" type="date" class="form-control"
                                    title="Fecha inicio"
                                    v-model="fields.fecha_inicio"
                                >
                                <span class="form-text">Inicio de recolección de datos</span>
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label for="fecha_fin" class="col-md-4 col-form-label text-end text-right">Fecha fin</label>
                            <div class="col-md-8">
                                <input
                                    name="fecha_fin" type="date" class="form-control"
                                    title="Fecha fin"
                                    v-model="fields.fecha_fin"
                                >
                                <span class="form-text">Fin de recolección de datos</span>
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label for="isbn_issn" class="col-md-4 col-form-label text-end text-right">Código ISBN/ISBN</label>
                            <div class="col-md-8">
                                <input
                                    name="isbn_issn" type="text" class="form-control"
                                    title="Código ISBN/ISBN" placeholder="Código ISBN/ISBN"
                                    v-model="fields.isbn_issn"
                                >
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label for="fuente_informacion" class="col-md-4 col-form-label text-end text-right">Fuente de información</label>
                            <div class="col-md-8">
                                <input
                                    name="fuente_informacion" type="text" class="form-control"
                                    title="Fuente de información" placeholder="Fuente de información"
                                    v-model="fields.fuente_informacion"
                                >
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label for="fuente_financiacion" class="col-md-4 col-form-label text-end text-right">Fuente de financiación</label>
                            <div class="col-md-8">
                                <input
                                    name="fuente_financiacion" type="text" class="form-control"
                                    title="Fuente de financiación" placeholder="Fuente de financiación"
                                    v-model="fields.fuente_financiacion"
                                >
                            </div>
                        </div>


                    </div>
                </div>
                <div class="mt-2">
                    <button class="btn btn-success w120p" type="submit">Guardar</button>
                </div>
            <fieldset>
        </form>
    </div>
</div>

<?php $this->load->view('app/repositorio/edit/basic_vue_v') ?>