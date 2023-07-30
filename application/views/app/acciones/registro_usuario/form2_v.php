<div class="center_box_750" v-show="step == 'form2'">
    <h3 class="text-center text-muted">Datos personales</h3>
    <form accept-charset="utf-8" method="POST" id="formUpdateUser" @submit.prevent="updateCreatedUser">
        <fieldset v-bind:disabled="loading">
            <input type="hidden" name="id" v-model="savedId">
            <input type="hidden" name="activation_key" v-model="activationKey">

            <div class="mb-3 row">
                <label for="first_name" class="col-md-4 col-form-label text-end">Nombres</label>
                <div class="col-md-8">
                    <input name="first_name" type="text" class="form-control" required title="Nombres"
                        placeholder="Nombres" v-model="fields.first_name">
                </div>
            </div>

            <div class="mb-3 row">
                <label for="last_name" class="col-md-4 col-form-label text-end">Apellidos</label>
                <div class="col-md-8">
                    <input name="last_name" type="text" class="form-control" required title="Apellidos"
                        placeholder="Apellidos" v-model="fields.last_name">
                </div>
            </div>

            <div class="mb-3 row">
                <label for="phone_number" class="col-md-4 col-form-label text-end">Celular</label>
                <div class="col-md-8">
                    <input name="phone_number" type="number" class="form-control" title="Celular" placeholder="Celular"
                        v-model="fields.phone_number">
                </div>
            </div>

            <div class="mb-3 row">
                <label for="gender" class="col-md-4 col-form-label text-end">Sexo</label>
                <div class="col-md-8">
                    <select name="gender" v-model="fields.gender" class="form-select form-control">
                        <option value="99">(Vacío)</option>
                        <option v-for="optionSexo in arrSexos" v-bind:value="optionSexo.cod">{{ optionSexo.name }}
                        </option>
                    </select>
                </div>
            </div>

            <div class="mb-3 row">
                <label for="text_2" class="col-md-4 col-form-label text-end">Identidad de género</label>
                <div class="col-md-8">
                    <select name="text_2" v-model="fields.text_2" class="form-select form-control" required>
                        <option v-for="optionGender in arrGenders" v-bind:value="optionGender.name">
                            {{ optionGender.name }}</option>
                    </select>
                </div>
            </div>

            <div class="mb-3 row">
                <label for="text_3" class="col-md-4 col-form-label text-end">Orientación sexual</label>
                <div class="col-md-8">
                    <select name="text_3" v-model="fields.text_3" class="form-select form-control">
                        <option value="Prefiero no responder">(Vacío)</option>
                        <option v-for="optionSexualOrientation in arrSexualOrientation"
                            v-bind:value="optionSexualOrientation.name">{{ optionSexualOrientation.name }}</option>
                    </select>
                </div>
            </div>

            <div class="mb-3 row">
                <label for="birth_date" class="col-md-4 col-form-label text-end">Fecha de nacimiento</label>
                <div class="col-md-8">
                    <input name="birth_date" type="date" class="form-control" required title="Fecha de nacimiento"
                        placeholder="Fecha de nacimiento" v-model="fields.birth_date">
                </div>
            </div>

            <div class="mb-3 row">
                <label for="job_role" class="col-md-4 col-form-label text-end">Ocupación</label>
                <div class="col-md-8">
                    <select name="job_role" v-model="fields.job_role" class="form-select form-control">
                        <option v-for="optionOcupacion in arrOcupaciones" v-bind:value="optionOcupacion.name">
                            {{ optionOcupacion.name }}</option>
                    </select>
                </div>
            </div>

            <div class="mb-3 row">
                <label for="integer_1" class="col-md-4 col-form-label text-end">Estrato vivienda</label>
                <div class="col-md-8">
                    <input name="integer_1" type="number" class="form-control" min="0" max="6" title="Estrato vivienda"
                        placeholder="Estrato vivienda" v-model="fields.integer_1">
                </div>
            </div>

            <div class="mb-3 row">
                <div class="col-md-8 offset-md-4">
                    <button class="btn btn-primary w-100" type="submit">ENVIAR</button>
                </div>
            </div>
            <fieldset>
    </form>
</div>