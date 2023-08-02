<div id="addAccionApp">
    <div class="card center_box_920">
        <div class="card-body">
            <form id="postForm" accept-charset="utf-8" @submit.prevent="handleSubmit">
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
                        <label for="programa" class="col-md-4 col-form-label text-end">Programa</label>
                        <div class="col-md-8">
                            <select name="programa" v-model="fields.programa" class="form-select" v-on:change="clearEstrategia">
                                <option value="">[ND/NA]</option>
                                <option v-for="optionPrograma in arrPrograma" v-bind:value="optionPrograma.cod">{{ optionPrograma.name }}</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-1 row">
                        <label for="estrategia" class="col-md-4 col-form-label text-end">Estrategia</label>
                        <div class="col-md-8">
                            <select name="estrategia" v-model="fields.estrategia" class="form-select">
                                <option value="">[ND/NA]</option>
                                <option v-for="optionEstrategia in arrEstrategiaFiltered" v-bind:value="optionEstrategia.str_cod">
                                    {{ optionEstrategia.name }}
                                </option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-1 row">
                        <label for="tipo_accion" class="col-md-4 col-form-label text-end">Tipo acción</label>
                        <div class="col-md-8">
                            <select name="tipo_accion" v-model="fields.tipo_accion" class="form-select">
                                <option value="">[ND/NA]</option>
                                <option v-for="tipoAccion in arrTipoAccionFiltered"
                                    v-bind:value="tipoAccion.cod">
                                    {{ tipoAccion.name }}
                                </option>
                            </select>
                        </div>
                    </div>

                    <hr>

                    <div class="mb-1 row">
                        <label for="dependencia" class="col-md-4 col-form-label text-end">Dependencia</label>
                        <div class="col-md-8">
                            <select name="dependencia" v-model="fields.dependencia" class="form-select form-control" required>
                                <option value="">[ Todos ]</option>
                                <option v-for="optionDependencia in arrDependencia" v-bind:value="optionDependencia.name">{{ optionDependencia.name }}</option>
                            </select>
                        </div>
                    </div>

                    <hr>

                    <div class="mb-1 row">
                        <label for="nombre_accion" class="col-md-4 col-form-label text-end">Nombre de la acción</label>
                        <div class="col-md-8">
                            <input id="field-nombre_accion" class="form-control" name="nombre_accion"
                                v-model="fields.nombre_accion" required autofocus>
                        </div>
                    </div>

                    <div class="mb-1 row">
                        <label for="descripcion" class="col-md-4 col-form-label text-end">Descripción</label>
                        <div class="col-md-8">
                            <textarea class="form-control" name="descripcion" rows="3"
                                v-model="fields.descripcion" required></textarea>
                        </div>
                    </div>

                    <div class="mb-1 row">
                        <label for="fecha" class="col-md-4 col-form-label text-end">Fecha</label>
                        <div class="col-md-8">
                            <input
                                name="fecha" type="date" class="form-control"
                                title="Fecha" placeholder="Fecha"
                                v-model="fields.fecha"
                            >
                        </div>
                    </div>

                    <div class="mb-1 row">
                        <label for="hora_inicio" class="col-md-4 col-form-label text-end">Hora inicio y fin</label>
                        <div class="col-md-4">
                            <input
                                name="hora_inicio" type="time" class="form-control" required
                                title="hora_inicio" v-model="fields.hora_inicio" v-on:change="validateHoraFin"
                            >
                        </div>
                        <div class="col-md-4">
                            <input
                                name="hora_fin" type="time" class="form-control" required
                                title="hora_fin" v-model="fields.hora_fin" v-on:change="validateHoraFin"
                                v-bind:class="{'is-invalid': validation.hora_fin_posterior == 0 }"
                            >
                            <div class="invalid-feedback">
                                La hora de finalización debe ser posterior a la de inicio
                            </div>
                        </div>
                    </div>

                    <div class="mb-1 row">
                        <label for="url_evidencia" class="col-md-4 col-form-label text-end">Link evidencia</label>
                        <div class="col-md-8">
                            <input
                                name="url_evidencia" type="url" class="form-control"
                                title="Link evidencia" placeholder="Link evidencia"
                                v-model="fields.url_evidencia"
                            >
                        </div>
                    </div>
                    <div class="mb-1 row">
                        <label for="url_carpeta_archivos" class="col-md-4 col-form-label text-end">Link carpeta archivos</label>
                        <div class="col-md-8">
                            <input
                                name="url_carpeta_archivos" type="url" class="form-control"
                                title="Link carpeta archivos" placeholder="Link carpeta archivos"
                                v-model="fields.url_carpeta_archivos"
                            >
                        </div>
                    </div>

                    <div class="mb-1 row">
                        <label for="url_accion_medios" class="col-md-4 col-form-label text-end">Link acción en noticias/medios</label>
                        <div class="col-md-8">
                            <input
                                name="url_accion_medios" type="url" class="form-control"
                                title="Link carpeta archivos" placeholder="Link carpeta archivos"
                                v-model="fields.url_accion_medios"
                            >
                        </div>
                    </div>

                    <div class="mb-1 row">
                        <label for="radicado_orfeo" class="col-md-4 col-form-label text-end">Radicado Orfeo</label>
                        <div class="col-md-8">
                            <input
                                name="radicado_orfeo" type="text" class="form-control"
                                title="Radicado Orfeo" placeholder="Radicado Orfeo"
                                v-model="fields.radicado_orfeo"
                            >
                        </div>
                    </div>

                    <div class="mb-1 row">
                        <label for="en_manzana" class="col-md-4 col-form-label text-end">En Manzana</label>
                        <div class="col-md-8">
                            <select name="en_manzana" v-model="fields.en_manzana" class="form-select form-control" required>
                                <option v-for="optionSiNoNa in arrSiNoNa" v-bind:value="optionSiNoNa.cod">{{ optionSiNoNa.name }}</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-1 row">
                        <label for="proyecto" class="col-md-4 col-form-label text-end">Proyecto/Producto</label>
                        <div class="col-md-8">
                            <input
                                name="proyecto" type="text" class="form-control"
                                title="Proyecto" placeholder="Proyecto"
                                v-model="fields.proyecto"
                            >
                        </div>
                    </div>

                    <hr>
                    <div class="mb-3 row">
                        <label for="cantidad_personas" class="col-md-4 col-form-label text-end">Cantidad</label>
                        <div class="col-md-2">
                            <div class="form-text">Mujeres</div>
                            <input
                                name="cantidad_mujeres" type="number" class="form-control" min="0"
                                required
                                v-model="fields.cantidad_mujeres"
                            >
                        </div>
                        <div class="col-md-2">
                            <div class="form-text">Hombres</div>
                            <input
                                name="cantidad_hombres" type="number" class="form-control" min="0"
                                required
                                v-model="fields.cantidad_hombres"
                            >
                        </div>
                        <div class="col-md-2">
                            <div class="form-text">Sexo NR/ND</div>
                            <input
                                name="cantidad_sexo_nd" type="number" class="form-control" min="0"
                                required
                                v-model="fields.cantidad_sexo_nd"
                            >
                        </div>
                    </div> 

                    <div class="mb-1 row">
                        <label for="hubo_medicion" class="col-md-4 col-form-label text-end">Se realizó medición</label>
                        <div class="col-md-8">
                            <select name="hubo_medicion" v-model="fields.hubo_medicion" class="form-select form-control" required>
                                <option v-for="optionSiNoNa in arrSiNoNa" v-bind:value="optionSiNoNa.cod">{{ optionSiNoNa.name }}</option>
                            </select>
                        </div>
                    </div>
                    
                    

                    <div class="mb-1 row">
                        <label for="participantes_equipo" class="col-md-4 col-form-label text-end">Participantes equipo</label>
                        <div class="col-md-8">
                            <textarea
                                name="participantes_equipo" class="form-control" rows="3"
                                title="Participantes equipo" placeholder="Participantes equipo"
                                v-model="fields.participantes_equipo"
                            ></textarea>
                            <div class="form-text">Nombres facilitadores, separados por coma. Ej: diana.lopez, juan.perez</div>
                        </div>
                    </div>

                    <div class="mb-1 row">
                        <label for="observaciones" class="col-md-4 col-form-label text-end">Notas/Observaciones</label>
                        <div class="col-md-8">
                            <textarea
                                name="observaciones" class="form-control" rows="4"
                                title="Observaciones" placeholder="Observaciones"
                                v-model="fields.observaciones"
                            ></textarea>
                        </div>
                    </div>

                    <div class="mb-1 row">
                        <label for="contacto_lugar" class="col-md-4 col-form-label text-end">Persona contacto en lugar</label>
                        <div class="col-md-8">
                            <textarea
                                name="contacto_lugar" class="form-control" rows="2"
                                title="Persona contacto en lugar" placeholder="Persona contacto en lugar"
                                v-model="fields.contacto_lugar"
                            ></textarea>
                        </div>
                    </div>

                    <hr>

                    <div class="mb-1 row">
                        <label for="modalidad" class="col-md-4 col-form-label text-end">Modalidad</label>
                        <div class="col-md-8">
                            <select name="modalidad" v-model="fields.modalidad" class="form-select form-control">
                                <option value="">[ ND/NA ]</option>
                                <option v-for="optionModalidad in arrModalidad" v-bind:value="optionModalidad.name">{{ optionModalidad.name }}</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-1 row">
                        <label for="cod_localidad" class="col-md-4 col-form-label text-end">Localidad</label>
                        <div class="col-md-8">
                            <select name="cod_localidad" v-model="fields.cod_localidad" class="form-select form-control" required>
                                <option value="99">[ ND/NA ]</option>
                                <option v-for="optionLocalidad in arrLocalidad" v-bind:value="optionLocalidad.cod">{{ optionLocalidad.name }}</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-1 row">
                        <label for="nombre_lugar" class="col-md-4 col-form-label text-end">Nombre lugar</label>
                        <div class="col-md-8">
                            <input
                                name="nombre_lugar" type="text" class="form-control"
                                required
                                title="Nombre lugar" placeholder="Nombre lugar"
                                v-model="fields.nombre_lugar"
                            >
                            <div class="form-text">Ej. Institución Educativa Albert Einstein</div>
                        </div>
                    </div>

                    <div class="mb-1 row">
                        <label for="direccion" class="col-md-4 col-form-label text-end">Dirección lugar</label>
                        <div class="col-md-8">
                            <input
                                name="direccion" type="text" class="form-control"
                                title="Dirección lugar" placeholder="Dirección lugar"
                                v-model="fields.direccion"
                            >
                            <div id="direccionHelp" class="form-text">Seguir protocolo nomenclatura DANE.
                                <a href="https://docs.google.com/document/d/1PT1hF9qQtyPVvZkMUQCefbrQmARL-Ddr/edit" target="_blank">Ver más</a>
                            </div>
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