<div id="addUserApp">
    <div class="card center_box_750">
        <div class="card-body">
            <form accept-charset="utf-8" method="POST" id="addUserForm" @submit.prevent="handleSubmit">
                <input type="hidden" name="role" value="22">
                <input type="hidden" name="city_id" value="909">
                <input type="hidden" name="password" value="<?= $password ?>">
                <fieldset v-bind:disabled="loading">
                    <div class="form-group row" id="form-group_document_number">
                        <label for="document_number" class="col-md-4 col-form-label text-right">No. Documento *</label>
                        <div class="col-md-4">
                            <input
                                name="document_number" class="form-control" required
                                title="Solo números, sin puntos, debe tener al menos 5 dígitos"
                                pattern=".{5,}[0-9]"
                                v-bind:class="{ 'is-invalid': validation.document_number_unique == 0, 'is-valid': validation.document_number_unique == 1 && fields.document_number > 0 }"
                                v-model="fields.document_number"
                                v-on:change="validateForm"
                                >
                            <span class="invalid-feedback">
                                El número de documento escrito ya fue registrado para otro usuario
                            </span>
                        </div>
                        <div class="col-md-4">
                            <select name="document_type" v-model="fields.document_type" class="form-control" required>
                                <option v-for="optionDocumentType in arrDocumentType" v-bind:value="optionDocumentType.str_cod">{{ optionDocumentType.name }}</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="first_name" class="col-md-4 col-form-label text-right">Nombre &middot;
                            Apellidos</label>
                        <div class="col-md-4">
                            <input name="first_name" class="form-control" placeholder="Nombres"
                                title="Nombres del usuario" v-model="fields.first_name">
                        </div>
                        <div class="col-md-4">
                            <input name="last_name" class="form-control" placeholder="Apellidos"
                                title="Apellidos del usuario" v-model="fields.last_name" v-on:change="setDisplayName(false)">
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="display_name" class="col-md-4 col-form-label text-right">Nombre completo *</label>
                        <div class="col-md-8">
                            <div class="input-group">
                                <input name="display_name" class="form-control" required v-model="fields.display_name">
                                <div class="input-group-append">
                                    <button class="btn btn-light" type="button"
                                        v-on:click="setDisplayName(true)"><i class="fas fa-magic"></i></button>
                                </div>
                            </div>
                            <small id="displayNameTip" class="form-text text-muted">Mostrar usuario como</small>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="related_1" class="col-md-4 col-form-label text-right">Localidad vivienda *</label>
                        <div class="col-md-8">
                            <select name="related_1" v-model="fields.related_1" class="form-control" required>
                                <option v-for="optionLocalidad in arrLocalidad" v-bind:value="optionLocalidad.str_cod">{{ optionLocalidad.cod }} - {{ optionLocalidad.name }}</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="address" class="col-md-4 col-form-label text-right">Dirección vivienda *</label>
                        <div class="col-md-8">
                            <input
                                name="address" type="text" class="form-control"
                                required
                                title="Dirección vivienda" placeholder="Dirección vivienda"
                                v-model="fields.address"
                            >
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="phone_number" class="col-md-4 col-form-label text-right">Celular</label>
                        <div class="col-md-8">
                            <input
                                name="phone_number" type="text" class="form-control"
                                v-model="fields.phone_number"
                            >
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="birth_date" class="col-md-4 col-form-label text-right">Fecha de nacimiento *</label>
                        <div class="col-md-8">
                            <input
                                name="birth_date" type="date" class="form-control"
                                required
                                title="Fecha de nacimiento" placeholder="Fecha de nacimiento"
                                v-model="fields.birth_date"
                            >
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="gender" class="col-md-4 col-form-label text-right">Sexo *</label>
                        <div class="col-md-8">
                            <select name="gender" v-model="fields.gender" class="form-control" required>
                                <option value="">[ Todos ]</option>
                                <option v-for="optionGender in arrGender" v-bind:value="optionGender.str_cod">{{ optionGender.name }}</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="text_3" class="col-md-4 col-form-label text-right">Identidad étnica *</label>
                        <div class="col-md-8">
                            <select name="text_3" v-model="fields.text_3" class="form-control" required>
                                <option value="">[ Todos ]</option>
                                <option v-for="optionEtnia in arrEtnia" v-bind:value="optionEtnia.name">{{ optionEtnia.name }}</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="text_1" class="col-md-4 col-form-label text-right">Modalidad escuela *</label>
                        <div class="col-md-8">
                            <select name="text_1" v-model="fields.text_1" class="form-control" required>
                                <option v-for="optionModalidad in arrModalidad" v-bind:value="optionModalidad.name">{{ optionModalidad.name }}</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="text_2" class="col-md-4 col-form-label text-right">Módulos inscritos</label>
                        <div class="col-md-8">
                            <input
                                name="text_2" type="text" class="form-control" title="Módulos inscritos" v-model="fields.text_2"
                            >
                            <small class="form-text text-muted">Números separados por coma</small>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="integer_1" class="col-md-4 col-form-label text-right">Convive con</label>
                        <div class="col-md-4">
                            <input
                                name="integer_1" type="number" class="form-control" min="0" max="100"
                                title="Cant. personas convive" placeholder="Cant. personas convive"
                                v-model="fields.integer_1"
                            >
                        </div>
                        <div class="col-md-4 col-form-label">
                            personas
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="integer_2" class="col-md-4 col-form-label text-right">A cargo de</label>
                        <div class="col-md-4">
                            <input
                                name="integer_2" type="text" class="form-control" title="Número de personas a cargo"
                                v-model="fields.integer_2"
                            >
                        </div>
                        <div class="col-md-4 col-form-label">personas</div>
                    </div>

                    <div class="mb-3 row">
                        <label for="admin_notes" class="col-md-4 col-form-label text-right">Observaciones</label>
                        <div class="col-md-8">
                            <textarea
                                name="admin_notes" rows="3" class="form-control" title="Observaciones"
                                v-model="fields.admin_notes"
                            ></textarea>
                        </div>
                    </div>

                    <hr>

                    <div class="mb-3 row" id="mb-3_email">
                        <label for="email" class="col-md-4 col-form-label text-right">Correo electrónico *</label>
                        <div class="col-md-8">
                            <input name="email" type="email" class="form-control"
                                title="Dirección de correo electrónico" required
                                v-bind:class="{ 'is-invalid': validation.email_unique == 0, 'is-valid': validation.email_unique == 1 }"
                                v-model="fields.email" v-on:change="validateForm">
                            <span class="invalid-feedback">
                                El correo electrónico ya fue registrado, por favor escriba otro
                            </span>
                        </div>
                    </div>
                
                    

                    <div class="mb-3 row">
                        <div class="offset-4 col-md-8">
                            <button class="btn btn-success w120p" type="submit">
                                <i class="fa fa-spin fa-spinner" v-show="loading"></i>
                                Crear
                            </button>
                        </div>
                    </div>
                </fieldset>
            </form>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="modal_created" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Usuario creado</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <i class="fa fa-check text-success"></i> Usuario creado correctamente
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" v-on:click="goToCreated">Abrir usuario</button>
                    <button type="button" class="btn btn-secondary" v-on:click="clearForm" data-dismiss="modal">
                        <i class="fa fa-plus"></i> Crear otro
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$this->load->view($this->views_folder . "add/{$role_type}/vue_v");