<div v-show="section == 'form'" class="variable-form-container">
    <div class="card center_box_920">
        <div class="card-body">
            <h5 class="card-title text-center mb-3">
                {{ fields.id ? 'Editar variable' : 'Nueva variable' }}
                <span v-if="fields.id" class="badge bg-primary">{{ fields.id }}</span>
            </h5>

            <form accept-charset="utf-8" method="POST" id="variables_form" @submit.prevent="handleSubmit">
                <input v-if="fields.id" type="hidden" name="id" v-bind:value="fields.id">
                <input type="hidden" name="puntaje" v-bind:value="fields.puntaje || '0'">
                <input type="hidden" name="key_capa" v-bind:value="fields.key_capa">

                <fieldset v-bind:disabled="loading">
                    <div class="mb-3 row">
                        <div class="offset-md-4 col-md-8 col-sm-12">
                            <button class="btn btn-primary w120p me-2" type="submit">
                                <span><i class="fa fa-spin fa-spinner" v-show="loading"></i></span>
                                Guardar
                            </button>
                            <button class="btn btn-light w120p" type="button" v-on:click="setSection('lista')">
                                Cancelar
                            </button>
                        </div>
                    </div>

                    <div class="mb-1 row">
                        <label for="variable-key-capa" class="col-md-4 col-form-label text-end">
                            Capa proyectada <span class="text-danger">*</span>
                        </label>
                        <div class="col-md-8">
                            <select id="variable-key-capa" name="key_capa"
                                v-model="fields.key_capa" class="form-select" required disabled>
                                <option value="">[ Seleccione una capa base ]</option>
                                <option v-for="capaBase in capasBase"
                                    v-bind:key="capaBase.key_capa" v-bind:value="capaBase.key_capa">
                                    {{ capaBase.nombre }} · {{ capaBase.key_capa }}
                                </option>
                            </select>
                        </div>
                    </div>


                    <div class="mb-1 row">
                        <label for="variable-color" class="col-md-4 col-form-label text-end">
                            Color de la variable <span class="text-danger">*</span>
                        </label>
                        <div class="col-md-8">
                            <div class="d-flex align-items-center gap-2">
                                <input id="variable-color" name="color" type="color"
                                    class="form-control form-control-color"
                                    v-model="fields.color" required
                                    title="Color de la variable">
                                <code>{{ fields.color || '#0084bf' }}</code>
                            </div>
                            <small class="text-form">Selecciona el color hexadecimal personalizado para la variable.</small>
                        </div>
                    </div>                    <div class="mb-1 row">
                        <label for="estado" class="col-md-4 col-form-label text-end">
                            Estado <span class="text-danger">*</span>
                        </label>
                        <div class="col-md-8">
                            <select id="estado" name="estado" v-model="fields.estado" class="form-select" required>
                                <option v-for="optionEstado in arrEstadoVariable"
                                    v-bind:key="optionEstado.cod" v-bind:value="optionEstado.cod">
                                    {{ optionEstado.name }}
                                </option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-1 row">
                        <label for="variable-clave" class="col-md-4 col-form-label text-end">
                            Clave <span class="text-danger">*</span>
                        </label>
                        <div class="col-md-8">
                            <input id="variable-clave" name="clave" type="text" class="form-control"
                                v-model.trim="fields.clave" required maxlength="100"
                                placeholder="Ej. icc_movilidad_2023">
                            <small class="text-form">Identificador único de la variable, sin espacios.</small>
                        </div>
                    </div>

                    <div class="mb-1 row">
                        <label for="variable-nombre" class="col-md-4 col-form-label text-end">
                            Nombre <span class="text-danger">*</span>
                        </label>
                        <div class="col-md-8">
                            <input id="variable-nombre" name="nombre" type="text" class="form-control"
                                v-model.trim="fields.nombre" required maxlength="255"
                                placeholder="Nombre de la variable">
                        </div>
                    </div>

                    <div class="mb-1 row">
                        <label for="variable-tema" class="col-md-4 col-form-label text-end">
                            Tema <span class="text-danger">*</span>
                        </label>
                        <div class="col-md-8">
                            <select id="variable-tema" name="tema" class="form-select"
                                v-model="fields.tema" required>
                                <option value="">[ Seleccione un tema ]</option>
                                <option v-for="optionTema in arrTemas"
                                    v-bind:key="optionTema.cod" v-bind:value="optionTema.name">
                                    {{ optionTema.name }}
                                </option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-1 row">
                        <label for="variable-subtema" class="col-md-4 col-form-label text-end">
                            Subtema <span class="text-danger">*</span>
                        </label>
                        <div class="col-md-8">
                            <input id="variable-subtema" name="subtema" type="text" class="form-control"
                                v-model.trim="fields.subtema" required maxlength="20"
                                placeholder="Subtema (máximo 20 caracteres)">
                        </div>
                    </div>

                    <div class="mb-1 row">
                        <label for="variable-descripcion" class="col-md-4 col-form-label text-end">
                            Descripción <span class="text-danger">*</span>
                        </label>
                        <div class="col-md-8">
                            <textarea id="variable-descripcion" name="descripcion" class="form-control" rows="5"
                                v-model.trim="fields.descripcion" required
                                placeholder="Describe qué mide la variable y cuál es su alcance"></textarea>
                        </div>
                    </div>

                    <div class="mb-1 row">
                        <label for="variable-anio" class="col-md-4 col-form-label text-end">
                            Año de los valores <span class="text-danger">*</span>
                        </label>
                        <div class="col-md-8">
                            <input id="variable-anio" name="anio_valores" type="number" class="form-control"
                                v-model="fields.anio_valores" required min="0" max="2100" step="1"
                                placeholder="Año de referencia">
                        </div>
                    </div>

                    <div class="mb-1 row">
                        <label for="variable-unidad" class="col-md-4 col-form-label text-end">
                            Unidad de medida <span class="text-danger">*</span>
                        </label>
                        <div class="col-md-8">
                            <input id="variable-unidad" name="unidad_medida" type="text" class="form-control"
                                v-model.trim="fields.unidad_medida" required maxlength="100"
                                placeholder="Ej. Cantidad, Índice o Porcentaje">
                        </div>
                    </div>

                    <hr>

                    <div class="mb-1 row">
                        <label for="variable-entidad" class="col-md-4 col-form-label text-end">
                            Entidad responsable
                        </label>
                        <div class="col-md-8">
                            <input id="variable-entidad" name="entidad" type="text" class="form-control"
                                v-model.trim="fields.entidad" maxlength="255"
                                placeholder="Entidad que produce o administra los datos">
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="variable-palabras-clave" class="col-md-4 col-form-label text-end">
                            Palabras clave
                        </label>
                        <div class="col-md-8">
                            <textarea id="variable-palabras-clave" name="palabras_clave" class="form-control" rows="2"
                                v-model.trim="fields.palabras_clave"
                                placeholder="Sepáralas con comas"></textarea>
                        </div>
                    </div>

                    <div class="mb-1 row">
                        <label for="variable-calculo" class="col-md-4 col-form-label text-end">
                            Descripción del cálculo
                        </label>
                        <div class="col-md-8">
                            <textarea id="variable-calculo" name="descripcion_calculo" class="form-control" rows="5"
                                v-model.trim="fields.descripcion_calculo"
                                placeholder="Explica el procedimiento utilizado para calcular la variable"></textarea>
                        </div>
                    </div>

                    <div class="mb-1 row">
                        <label for="variable-datos-origen" class="col-md-4 col-form-label text-end">
                            Datos de origen
                        </label>
                        <div class="col-md-8">
                            <input id="variable-datos-origen" name="datos_origen" type="text" class="form-control"
                                v-model.trim="fields.datos_origen" placeholder="Fuente o conjunto de datos">
                        </div>
                    </div>

                    <div class="mb-1 row">
                        <label for="variable-capa-origen" class="col-md-4 col-form-label text-end">
                            Capa de origen
                        </label>
                        <div class="col-md-8">
                            <input id="variable-capa-origen" name="capa_origen" type="text" class="form-control"
                                v-model.trim="fields.capa_origen"
                                placeholder="Nombre de la capa geográfica de origen">
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="variable-enlace" class="col-md-4 col-form-label text-end">
                            Enlace al archivo de datos
                        </label>
                        <div class="col-md-8">
                            <input id="variable-enlace" name="link_archivo_datos" type="url" class="form-control"
                                v-model.trim="fields.link_archivo_datos" placeholder="https://...">
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="variable-notas" class="col-md-4 col-form-label text-end">Notas</label>
                        <div class="col-md-8">
                            <textarea id="variable-notas" name="notas" class="form-control" rows="4"
                                v-model.trim="fields.notas"
                                placeholder="Observaciones, limitaciones o aclaraciones sobre la variable"></textarea>
                        </div>
                    </div>

                    <div class="mb-1 row">
                        <div class="offset-md-4 col-md-8 col-sm-12">
                            <button class="btn btn-primary w120p me-2" type="submit">
                                <span><i class="fa fa-spin fa-spinner" v-show="loading"></i></span>
                                Guardar
                            </button>
                            <button class="btn btn-light w120p" type="button" v-on:click="setSection('lista')">
                                Cancelar
                            </button>
                        </div>
                    </div>
                </fieldset>
            </form>
        </div>
    </div>
</div>
