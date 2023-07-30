<?php $this->load->view('assets/recaptcha') ?>

<div id="registroUsuarioApp">
    <div class="center_box_750 text-center" v-show="step == 'success'">
        <div class="alert alert-success" role="alert">
            <i class="fa fa-check-circle fa-2x text-success"></i>
            <br>
            Listo, tus datos fueron registrados exitosamente
        </div>
    </div>
    <div class="center_box_750" v-show="step == 'form'">
        <div class="mb-2 text-center">
            Registro de participantes Escuela de Cuidado
        </div>
        <hr>
        <form accept-charset="utf-8" method="POST" id="registroUsuarioForm" @submit.prevent="handleSubmit">
            <!-- Campo para validación Google ReCaptcha V3 -->
            <input type="hidden" id="g-recaptcha-response" name="g-recaptcha-response">

            <fieldset v-bind:disabled="loading">
                <div class="mb-3 row">
                    <label for="first_name" class="col-md-4 col-form-label text-end">Nombres</label>
                    <div class="col-md-8">
                        <input
                            name="first_name" type="text" class="form-control"
                            required
                            title="Nombres" placeholder="Nombres"
                            v-model="fields.first_name"
                        >
                    </div>
                </div>

                <div class="mb-3 row">
                    <label for="last_name" class="col-md-4 col-form-label text-end">Apellidos</label>
                    <div class="col-md-8">
                        <input
                            name="last_name" type="text" class="form-control"
                            required
                            title="Apellidos" placeholder="Apellidos"
                            v-model="fields.last_name"
                        >
                    </div>
                </div>

                <div class="mb-3 row">
                    <label for="document_number" class="col-md-4 col-form-label text-end">No. Documento</label>
                    <div class="col-md-4">
                        <input
                            name="document_number" class="form-control"
                            v-bind:class="{ 'is-invalid': validation.document_number_unique == 0, 'is-valid': validation.document_number_unique == 1 }"
                            placeholder="Número de documento"
                            title="Solo números, sin puntos, debe tener al menos 5 dígitos"
                            pattern=".{5,}[0-9]" v-model="fields.document_number"
                            v-on:change="validateForm"
                            >
                        <span class="invalid-feedback">
                            El número de documento escrito ya fue registrado para otro usuario
                        </span>
                    </div>
                    <div class="col-md-4">
                        <select name="document_type" v-model="fields.document_type" class="form-select" required>
                            <option v-for="optionDocumentType in arrDocumentTypes" v-bind:value="optionDocumentType.cod">{{ optionDocumentType.name }}</option>
                        </select>
                    </div>
                </div>

                <div class="mb-3 row">
                    <label for="email" class="col-md-4 col-form-label text-end">Correo electrónico</label>
                    <div class="col-md-8">
                        <input
                            name="email" type="text" class="form-control"
                            v-bind:class="{ 'is-invalid': validation.email_unique == 0, 'is-valid': validation.email_unique == 1 }"
                            required
                            title="Correo electrónico" placeholder="Correo electrónico"
                            v-model="fields.email" v-on:change="validateForm"
                        >
                        <span class="invalid-feedback">
                            El correo electrónico escrito ya fue registrado por otro usuario
                        </span>
                    </div>
                </div>
                
                <div class="mb-3 row">
                    <label for="phone_number" class="col-md-4 col-form-label text-end">Celular</label>
                    <div class="col-md-8">
                        <input
                            name="phone_number" type="number" class="form-control"
                            title="Celular" placeholder="Celular"
                            v-model="fields.phone_number"
                        >
                    </div>
                </div>

                <div class="mb-3 row">
                    <label for="gender" class="col-md-4 col-form-label text-end">Sexo</label>
                    <div class="col-md-8">
                        <select name="gender" v-model="fields.gender" class="form-select form-control">
                            <option value="99">(Vacío)</option>
                            <option v-for="optionSexo in arrSexos" v-bind:value="optionSexo.cod">{{ optionSexo.name }}</option>
                        </select>
                    </div>
                </div>

                <div class="mb-3 row">
                    <label for="text_2" class="col-md-4 col-form-label text-end">Identidad de género</label>
                    <div class="col-md-8">
                        <select name="text_2" v-model="fields.text_2" class="form-select form-control" required>
                            <option v-for="optionGender in arrGenders" v-bind:value="optionGender.name">{{ optionGender.name }}</option>
                        </select>
                    </div>
                </div>

                <div class="mb-3 row">
                    <label for="text_3" class="col-md-4 col-form-label text-end">Orientación sexual</label>
                    <div class="col-md-8">
                        <select name="text_3" v-model="fields.text_3" class="form-select form-control">
                            <option value="Prefiero no responder">(Vacío)</option>
                            <option v-for="optionSexualOrientation in arrSexualOrientation" v-bind:value="optionSexualOrientation.name">{{ optionSexualOrientation.name }}</option>
                        </select>
                    </div>
                </div>

                <div class="mb-3 row">
                    <label for="birth_date" class="col-md-4 col-form-label text-end">Fecha de nacimiento</label>
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
                    <label for="job_role" class="col-md-4 col-form-label text-end">Ocupación</label>
                    <div class="col-md-8">
                        <select name="job_role" v-model="fields.job_role" class="form-select form-control">
                            <option v-for="optionOcupacion in arrOcupaciones" v-bind:value="optionOcupacion.name">{{ optionOcupacion.name }}</option>
                        </select>
                    </div>
                </div>

                <div class="mb-3 row">
                    <label for="integer_1" class="col-md-4 col-form-label text-end">Estrato vivienda</label>
                    <div class="col-md-8">
                        <input
                            name="integer_1" type="number" class="form-control" min="0" max="6"
                            title="Estrato vivienda" placeholder="Estrato vivienda"
                            v-model="fields.integer_1"
                        >
                    </div>
                </div>
                
                <div class="mb-3 row">
                    <div class="col-md-8 offset-md-4">
                        <button class="btn btn-success w120p" type="submit">Guardar</button>
                    </div>
                </div>
            <fieldset>
        </form>
    </div>
</div>

<?php $this->load->view('app/acciones/registro_usuario/vue_v') ?>