<div id="addUserApp">
    <div class="card center_box_750">
        <div class="card-body">
            <form accept-charset="utf-8" method="POST" id="addUserForm" @submit.prevent="handleSubmit">
                <fieldset v-bind:disabled="loading">
                    <div class="mb-3 row">
                        <label for="role" class="col-md-4 col-form-label text-end">Rol</label>
                        <div class="col-md-8">
                            <select name="role" v-model="fields.role" class="form-select" required>
                                <option v-for="(option_role, key_role) in options_role" v-bind:value="key_role">
                                    {{ option_role }}</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="first_name" class="col-md-4 col-form-label text-end">Nombre &middot;
                            Apellidos</label>
                        <div class="col-md-4">
                            <input name="first_name" class="form-control" placeholder="Nombres"
                                title="Nombres del usuario" v-model="fields.first_name">
                        </div>
                        <div class="col-md-4">
                            <input name="last_name" class="form-control" placeholder="Apellidos"
                                title="Apellidos del usuario" v-model="fields.last_name"
                                v-on:change="setDisplayName(false)">
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="display_name" class="col-md-4 col-form-label text-end">Nombre completo</label>
                        <div class="col-md-8">
                            <div class="input-group">
                                <input name="display_name" class="form-control" required v-model="fields.display_name">
                                <div class="input-group-append">
                                    <button class="btn btn-light" type="button" v-on:click="setDisplayName(true)"><i
                                            class="fas fa-magic"></i></button>
                                </div>
                            </div>
                            <small id="displayNameTip" class="form-text text-muted">Mostrar usuario como</small>
                        </div>
                    </div>

                    <div class="mb-3 row" id="mb-3_email">
                        <label for="email" class="col-md-4 col-form-label text-end">Correo electrónico</label>
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
                        <label for="password" class="col-md-4 col-form-label text-end">Contraseña</label>
                        <div class="col-md-8">
                            <input name="password" class="form-control"
                                title="Debe tener al menos un número y una letra minúscula, y al menos 8 caractéres"
                                required pattern="(?=.*\d)(?=.*[a-z]).{8,}" v-model="fields.password">
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="gender" class="col-md-4 col-form-label text-end">Sexo</label>
                        <div class="col-md-8">
                            <select name="gender" v-model="fields.gender" class="form-select">
                                <option v-for="optionGender in arrGender" v-bind:value="optionGender.str_cod">
                                    {{ optionGender.name }}</option>
                            </select>
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

    <?php $this->load->view('admin/users/add/modal_created_v') ?>

</div>

<?php
$this->load->view($this->views_folder . 'add/general/vue_v');