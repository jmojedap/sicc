<div id="addLaboratorioApp" class="center_box_920">
    <div class="card">
        <div class="card-body" v-show="section == 'form'">
            <form id="laboratorioForm" accept-charset="utf-8" @submit.prevent="handleSubmit">
                <fieldset v-bind:disabled="loading">
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
                        <label for="direccion_lider" class="col-md-4 col-form-label text-end">Dirección lider</label>
                        <div class="col-md-8">
                            <select name="direccion_lider" v-model="fields.direccion_lider" class="form-select form-control"
                                required>
                                <option v-for="optionDependencia in arrDependencia"
                                    v-bind:value="optionDependencia.name">{{ optionDependencia.name }}</option>
                            </select>
                        </div>
                    </div>

                    

                    <hr>

                    
                    <div class="mb-1 row">
                        <label for="nombre_laboratorio" class="col-md-4 col-form-label text-end">Nombre del laboratorio</label>
                        <div class="col-md-8">
                            <input id="field-nombre_laboratorio" class="form-control" name="nombre_laboratorio"
                                v-model="fields.nombre_laboratorio" required autofocus>
                        </div>
                    </div>

                    <div class="mb-1 row">
                        <label for="nombre_corto" class="col-md-4 col-form-label text-end">Nombre corto</label>
                        <div class="col-md-8">
                            <input id="field-nombre_corto" class="form-control" name="nombre_corto"
                                v-model="fields.nombre_corto" required maxlength="32" placeholder="Hasta 32 caracteres">
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
                        <label for="notas" class="col-md-4 col-form-label text-end">Notas</label>
                        <div class="col-md-8">
                            <textarea name="notas" class="form-control" rows="4" title="Información adicional interna sobre el laboratorio"
                                placeholder="Información adicional interna sobre el laboratorio" v-model="fields.notas"></textarea>
                        </div>
                    </div>

                    <div class="mb-1 row">
                        <div class="offset-md-4 col-md-8 col-sm-12">
                            <button class="btn btn-primary w120p" type="submit">
                                <span><i class="fa fa-spin fa-spinner" v-show="loading"></i></span>
                                Crear
                            </button>
                        </div>
                    </div>
                </fieldset>
            </form>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="modalCreated" tabindex="-1" aria-labelledby="modalCreatedLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalCreatedLabel">Laboratorio creado</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <i class="fa fa-check"></i>
                    El laboratorio fue creado correctamente, puede completar datos adicionales
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" v-on:click="goToCreated">Completar datos</button>
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal" v-on:click="clearForm">
                        Crear otro
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$this->load->view($this->views_folder . 'add/vue_v');