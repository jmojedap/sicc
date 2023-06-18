<div id="editActividadApp" class="center_box_750">
    <div class="card">
        <div class="card-body">
            <form id="actividadForm" accept-charset="utf-8" @submit.prevent="handleSubmit">
                <input type="hidden" name="id" value="<?= $row->id ?>">
                <fieldset v-bind:disabled="loading">
                    <div class="mb-2 text-right">
                        <button class="btn btn-primary w120p" type="submit">
                            <span><i class="fa fa-spin fa-spinner" v-show="loading"></i></span>
                            Guardar
                        </button>
                    </div>
                    <div class="mb-1 row">
                        <label for="inicio" class="col-md-4 col-form-label text-right">Fecha Hora Inicio</label>
                        <div class="col-md-8">
                            <input
                                name="inicio" type="datetime-local" class="form-control" required
                                v-model="fields.inicio"
                            >
                        </div>
                    </div>
                    <div class="mb-1 row">
                        <label for="fin" class="col-md-4 col-form-label text-right">Fecha Hora Fin</label>
                        <div class="col-md-8">
                            <input
                                name="fin" type="datetime-local" class="form-control"
                                v-model="fields.fin"
                            >
                        </div>
                    </div>
                    <div class="mb-1 row">
                        <label for="tipo_actividad" class="col-md-4 col-form-label text-right">Tipo</label>
                        <div class="col-md-8">
                            <select name="tipo_actividad" v-model="fields.tipo_actividad" class="form-control" required>
                                <option v-for="optionTipo in arrTipo" v-bind:value="optionTipo.name">{{ optionTipo.name }}</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-1 row">
                        <label for="nombre_actividad" class="col-md-4 col-form-label text-right">Nombre</label>
                        <div class="col-md-8">
                            <input
                                class="form-control" name="nombre_actividad"
                                v-model="fields.nombre_actividad">
                        </div>
                    </div>

                    <div class="mb-1 row">
                        <label for="descripcion" class="col-md-4 col-form-label text-right">Descripción</label>
                        <div class="col-md-8">
                            <textarea
                                name="descripcion" class="form-control" rows="3"
                                v-model="fields.descripcion"
                            ></textarea>
                        </div>
                    </div>

                    <div class="mb-1 row">
                        <label for="localidad_cod" class="col-md-4 col-form-label text-right">Localidad</label>
                        <div class="col-md-8">
                            <select name="localidad_cod" v-model="fields.localidad_cod" class="form-control">
                                <option value="">[ N/A ]</option>
                                <option v-for="optionLocalidad in arrLocalidad" v-bind:value="optionLocalidad.str_cod">{{ optionLocalidad.name }}</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-1 row">
                        <label for="en_manzana" class="col-md-4 col-form-label text-right">En manzana</label>
                        <div class="col-md-8">
                            <select name="en_manzana" v-model="fields.en_manzana" class="form-control" required>
                                <option v-for="optionSiNoNa in arrSiNoNa" v-bind:value="optionSiNoNa.name">{{ optionSiNoNa.name }}</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-1 row">
                        <label for="modalidad" class="col-md-4 col-form-label text-right">Modalidad</label>
                        <div class="col-md-8">
                            <select name="modalidad" v-model="fields.modalidad" class="form-control" required>
                                <option v-for="optionModalidad in arrModalidad" v-bind:value="optionModalidad.name">{{ optionModalidad.name }}</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-1 row">
                        <label for="nombre_lugar" class="col-md-4 col-form-label text-right">Nombre lugar</label>
                        <div class="col-md-8">
                            <input
                                name="nombre_lugar" type="text" class="form-control"
                                v-model="fields.nombre_lugar" title="Ej. Colegio ABC"
                            >
                        </div>
                    </div>

                    <div class="mb-1 row">
                        <label for="direccion" class="col-md-4 col-form-label text-right">Dirección</label>
                        <div class="col-md-8">
                            <input
                                name="direccion" type="text" class="form-control"
                                v-model="fields.direccion"
                            >
                            <small class="text-muted text-form">Ej. Calle 12 34 56</small>
                        </div>
                    </div>

                    <div class="mb-1 row">
                        <label for="facilitadores" class="col-md-4 col-form-label text-right">Facilitadores/Responsables</label>
                        <div class="col-md-8">
                            <textarea
                                name="facilitadores" class="form-control" rows="3"
                                v-model="fields.facilitadores"
                            ></textarea>
                        </div>
                    </div>

                    <div class="mb-1 row">
                        <label for="medicion_realizada" class="col-md-4 col-form-label text-right">Se realizó medición</label>
                        <div class="col-md-8">
                            <select name="medicion_realizada" v-model="fields.medicion_realizada" class="form-select form-control" required>
                                <option v-for="optionSiNoNa in arrSiNoNa" v-bind:value="optionSiNoNa.str_cod">{{ optionSiNoNa.name }}</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-1 row">
                        <label for="cantidad_mujeres" class="col-md-4 col-form-label text-right">Cantidad mujeres</label>
                        <div class="col-md-8">
                            <input
                                name="cantidad_mujeres" type="number" class="form-control" min="0"
                                required
                                v-model="fields.cantidad_mujeres"
                            >
                        </div>
                    </div>

                    <div class="mb-1 row">
                        <label for="cantidad_hombres" class="col-md-4 col-form-label text-right">Cantidad hombres</label>
                        <div class="col-md-8">
                            <input
                                name="cantidad_hombres" type="number" class="form-control" min="0"
                                required
                                v-model="fields.cantidad_hombres"
                            >
                        </div>
                    </div>

                    <div class="mb-1 row">
                        <label for="url_asistencia" class="col-md-4 col-form-label text-right">Link asistencia</label>
                        <div class="col-md-8">
                            <input
                                name="url_asistencia" type="url" class="form-control"
                                v-model="fields.url_asistencia"
                            >
                        </div>
                    </div>

                    <div class="mb-1 row">
                        <label for="contacto_espacio" class="col-md-4 col-form-label text-right">Contacto en el espacio</label>
                        <div class="col-md-8">
                            <textarea
                                name="contacto_espacio" class="form-control" rows="3"
                                v-model="fields.contacto_espacio"
                            ></textarea>
                        </div>
                    </div>

                    <div class="mb-1 row">
                        <label for="observaciones" class="col-md-4 col-form-label text-right">Observaciones</label>
                        <div class="col-md-8">
                            <textarea
                                name="observaciones" class="form-control" rows="2"
                                v-model="fields.observaciones"
                            ></textarea>
                        </div>
                    </div>

                    <div class="mb-1 row">
                        <label for="radicado_orfeo" class="col-md-4 col-form-label text-right">Radicado Orfeo</label>
                        <div class="col-md-8">
                            <input
                                name="radicado_orfeo" type="text" class="form-control"
                                v-model="fields.radicado_orfeo"
                            >
                        </div>
                    </div>
                </fieldset>
            </form>
        </div>
    </div>
</div>

<?php
$this->load->view($this->views_folder . 'edit/vue_v');