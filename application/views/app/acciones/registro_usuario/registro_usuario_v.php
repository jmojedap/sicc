<?php $this->load->view('assets/recaptcha') ?>

<div id="registroUsuarioApp">
    <div class="mb-2 text-center">
        Registro de participantes Escuela de Cuidado
    </div>
    <hr>
    <div class="center_box_750" v-show="step == 'form1'">
        <h3 class="text-center text-muted">Identificación</h3>
        <form accept-charset="utf-8" method="POST" id="registroUsuarioForm" @submit.prevent="handleSubmit">
            <!-- Campo para validación Google ReCaptcha V3 -->
            <input type="hidden" id="g-recaptcha-response" name="g-recaptcha-response">

            <fieldset v-bind:disabled="loading">
                

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

                <div class="mb-3 row" v-show="!withoutEmail">
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
                    <div class="col-md-8 offset-4">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="" v-model="withoutEmail" id="withoutEmail" v-on:change="setRandomEmail">
                            <label class="form-check-label" for="withoutEmail">
                                No tengo correo electrónico
                            </label>
                        </div>
                    </div>
                </div>
                
                <div class="mb-3 row">
                    <div class="col-md-8 offset-md-4">
                        <button class="btn btn-primary w120p" type="submit" v-bind:disabled="!validated">Siguiente <i class="fa fa-arrow-right"></i></button>
                    </div>
                </div>
            <fieldset>
        </form>
    </div>
    <?php $this->load->view('app/acciones/registro_usuario/form2_v') ?>

    <div class="center_box_750 text-center" v-show="step == 'success'">
        <div class="alert alert-success" role="alert">
            <i class="fa fa-check-circle fa-2x text-success"></i>
            <br>
            Listo, tus datos fueron registrados exitosamente
        </div>
    </div>
</div>

<?php $this->load->view('app/acciones/registro_usuario/vue_v') ?>