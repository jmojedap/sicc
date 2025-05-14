<div id="editLaboratorioApp">
    <div class="card center_box_920">
            <div class="card-body">
            <h5 class="card-title text-center mb-3">BV <span class="badge bg-primary">{{ fields.id }}</span> {{ fields.nombre_corto }}</h5>
            <form id="laboratorioForm" accept-charset="utf-8" @submit.prevent="handleSubmit">
                <input type="hidden" name="id" value="<?= $row->id ?>">
                <fieldset v-bind:disabled="loading">
                    <div class="mb-1 row">
                        <div class="offset-md-4 col-md-8 col-sm-12">
                            <button class="btn btn-primary w120p" type="submit">
                                <span><i class="fa fa-spin fa-spinner" v-show="loading"></i></span>
                                Guardar
                            </button>
                        </div>
                    </div>
                    <div class="mb-1 row">
                        <label for="tipo_laboratorio" class="col-md-4 col-form-label text-end">Tipo</label>
                        <div class="col-md-8">
                            <select name="tipo_laboratorio" v-model="fields.tipo_laboratorio" class="form-select"
                                required>
                                <option v-for="optionTipo in arrTipo" v-bind:value="optionTipo.name">
                                    {{ optionTipo.name }}</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-1 row">
                        <label for="categoria_laboratorio" class="col-md-4 col-form-label text-end">Categoría</label>
                        <div class="col-md-8">
                            <select name="categoria_laboratorio" v-model="fields.categoria_laboratorio" class="form-select" required>
                                <option v-for="optionCategoria in arrCategoria"
                                    v-bind:value="optionCategoria.name">
                                    {{ optionCategoria.name }}
                                </option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-1 row">
                        <label for="direccion_lider_sigla" class="col-md-4 col-form-label text-end">Dirección lider
                            <span class="text-danger">*</span>
                        </label>
                        <div class="col-md-8">
                            <select name="direccion_lider_sigla" v-model="fields.direccion_lider_sigla" class="form-select form-control"
                                required>
                                <option v-for="optionDependencia in arrDependencia"
                                    v-bind:value="optionDependencia.abbreviation">{{ optionDependencia.abbreviation }} &middot; {{ optionDependencia.name }}</option>
                            </select>
                        </div>
                    </div>

                    <hr>

                    <div class="mb-3 row">
                        <label for="nombre_corto" class="col-md-4 col-form-label text-end">Nombre corto</label>
                        <div class="col-md-8">
                            <input
                                name="nombre_corto" type="text" class="form-control"
                                required v-bind:disabled="APP_RID == 1" maxlength="24"  
                                title="Nombre corto" placeholder="Nombre corto"
                                v-model="fields.nombre_corto"
                            >
                        </div>
                    </div>

                    <div class="mb-1 row">
                        <label for="nombre_laboratorio" class="col-md-4 col-form-label text-end">
                                Nombre
                                <span class="text-danger">*</span></label>
                        <div class="col-md-8">
                            <input id="field-nombre_laboratorio" class="form-control" name="nombre_laboratorio"
                                v-model="fields.nombre_laboratorio" required autofocus>
                        </div>
                    </div>

                    <div class="mb-1 row">
                        <label for="relato_barrial" class="col-md-4 col-form-label text-end">
                            Relato barrial
                            <span class="text-danger">*</span></label>
                        <div class="col-md-8">
                            <textarea class="form-control" name="relato_barrial" rows="5" v-model="fields.relato_barrial" required maxlength="300"
                                placeholder="Relato barrial, historia del barrio, objetivos del laboratorio"
                                title="Relato barrial, historia del barrio, objetivos del laboratorio">
                            </textarea>
                            <small class="text-form">
                                {{ 300 - fields.relato_barrial.length }} &middot;
                                Expliqué en 300 caracteres el relato barrial, la historia del barrio, los objetivos del laboratorio.
                            </small>
                        </div>
                    </div>

                    <div class="mb-1 row">
                        <label for="descripcion" class="col-md-4 col-form-label text-end">
                            Descripción
                            <span class="text-danger">*</span></label>
                        <div class="col-md-8">
                            <textarea class="form-control" name="descripcion" rows="7" v-model="fields.descripcion" maxlength="512"
                                required>
                            </textarea>
                            <small class="text-form">
                                Descripción más detallada del laboratorio que se está diseñando o implementando.
                            </small>
                        </div>
                    </div>

                    <div class="mb-1 row">
                        <label for="palabras_clave" class="col-md-4 col-form-label text-end">
                            Palabras clave
                            <span class="text-danger">*</span></label>
                        </label>
                        <div class="col-md-8">
                            <textarea
                                name="palabras_clave" class="form-control" rows="3"
                                title="Palabras clave" placeholder="Palabras clave"
                                v-model="fields.palabras_clave" required
                            ></textarea>
                        </div>
                    </div>

                    <div class="mb-1 row">
                        <label for="gerente" class="col-md-4 col-form-label text-end text-right">Gerente</label>
                        <div class="col-md-8">
                            <input
                                name="gerente" type="text" class="form-control"
                                required
                                title="Gerente" placeholder="Gerente o líder del laboratorio"
                                v-model="fields.gerente"
                            >
                        </div>
                    </div>

                    <div class="mb-1 row">
                        <label for="duplas" class="col-md-4 col-form-label text-end text-right">Duplas</label>
                        <div class="col-md-8">
                            <textarea
                                name="duplas" class="form-control" rows="rows"
                                title="Duplas" placeholder="Duplas"
                                v-model="fields.duplas"
                            ></textarea>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="expediente_orfeo" class="col-md-4 col-form-label text-end text-right">Expediente Orfeo</label>
                        <div class="col-md-8">
                            <input
                                name="expediente_orfeo" type="text" class="form-control"
                                title="Expediente Orfeo" placeholder="Expediente Orfeo del Laboratorio"
                                v-model="fields.expediente_orfeo"
                            >
                        </div>
                    </div>

                    <hr>

                    <div class="mb-1 row">
                        <label for="localidad_cod" class="col-md-4 col-form-label text-end">Localidad</label>
                        <div class="col-md-8">
                            <select name="localidad_cod" v-model="fields.localidad_cod" class="form-select form-control" required>
                                <option value="">[ No disponbile ]</option>
                                <option v-for="optionLocalidad in arrLocalidad" v-bind:value="optionLocalidad.cod">{{ optionLocalidad.name }}</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-1 row">
                        <label for="barrio_id" class="col-md-4 col-form-label text-end">Barrio ancla</label>
                        <div class="col-md-8">
                            <select name="barrio_id" v-model="fields.barrio_id" class="form-select form-control" required>
                                <option v-for="optionBarrio in arrBarriosFiltered" v-bind:value="optionBarrio.id">
                                    {{ optionBarrio.nombre }} &middot; {{ optionBarrio.upz }}
                                </option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="barrios_adicionales_sector" class="col-md-4 col-form-label text-end text-right">Barrios adicionales sector</label>
                        <div class="col-md-8">
                            <textarea
                                name="barrios_adicionales_sector" class="form-control" rows="2"
                                title="Barrios adicionales sector, cercanos o integrados al laboratorio" placeholder="Barrios adicionales sector"
                                v-model="fields.barrios_adicionales_sector"
                            ></textarea>
                        </div>
                    </div>

                    <div class="mb-1 row">
                        <label for="direcciones_apoyo" class="col-md-4 col-form-label text-end">Dependencias apoyo</label>
                        <div class="col-md-8">
                            <textarea
                                name="direcciones_apoyo" class="form-control" rows="rows"
                                title="Dependencias apoyo" placeholder="Dependencias apoyo"
                                v-model="fields.direcciones_apoyo"
                            ></textarea>
                        </div>
                    </div>

                    <!-- <div class="mb-3 row">
                        <label for="barrios_adicionales_sector" class="col-md-4 col-form-label text-end">Barrios adicionales sector</label>
                        <div class="col-md-8">
                            <textarea
                                name="barrios_adicionales_sector" class="form-control" rows="rows" required
                                title="Barrios adicionales sector" placeholder="Barrios adicionales sector"
                                v-model="fields.barrios_adicionales_sector"
                            ></textarea>
                        </div>
                    </div> -->

                    <div class="mb-1 row">
                        <label for="fecha_inicio" class="col-md-4 col-form-label text-end">Fecha inicio</label>
                        <div class="col-md-4">
                            <input type="date" name="fecha_inicio" class="form-control"
                                v-model="fields.fecha_inicio">
                        </div>
                        <div class="col-md-4">
                            <input type="date" name="fecha_finalizacion" class="form-control"
                                v-model="fields.fecha_finalizacion">
                        </div>
                    </div>



                    

                    <div class="mb-1 row">
                        <label for="notas" class="col-md-4 col-form-label text-end">Notas</label>
                        <div class="col-md-8">
                            <textarea name="notas" class="form-control" rows="4" title="Observaciones"
                                placeholder="Observaciones" v-model="fields.notas"></textarea>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="tags" class="col-md-4 col-form-label text-end text-right">Tags o etiquetas</label>
                        <div class="col-md-8">
                            <textarea
                                name="tags" class="form-control" rows="2"
                                title="Tags o etiquetas" placeholder="Tags o etiquetas"
                                v-model="fields.tags"
                            ></textarea>
                        </div>
                    </div>

                    <div class="mb-1 row">
                        <div class="offset-md-4 col-md-8 col-sm-12">
                            <button class="btn btn-primary w120p" type="submit">
                                <span><i class="fa fa-spin fa-spinner" v-show="loading"></i></span>
                                Guardar
                            </button>
                        </div>
                    </div>
                </fieldset>


            </form>
        </div>
    </div>
</div>

<?php
$this->load->view($this->views_folder . 'edit/basic_vue_v');