<?php
    $arrEtnia = $this->Item_model->arr_options('category_id = 116');
    $arrLocalidad = $this->Item_model->arr_options('category_id = 121');

    //Opciones modalidades de escuela    
    $arrModalidad = file_get_contents(PATH_CONTENT . "json/options/cuidado_modalidades.json");

?>

<div id="editUserApp">
    <div class="card center_box_750">
        <div class="card-body">
            <form id="userForm" accept-charset="utf-8" @submit.prevent="validateAndSubmit">
                <input type="hidden" name="id" value="<?= $row->id ?>">
                <fieldset v-bind:disabled="loading">
                    <div class="mb-3 row">
                        <label for="related_1" class="col-md-4 col-form-label text-right">Localidad vivienda *</label>
                        <div class="col-md-8">
                            <select name="related_1" v-model="fields.related_1" class="form-control" required>
                                <option v-for="optionLocalidad in arrLocalidad" v-bind:value="optionLocalidad.str_cod">{{ optionLocalidad.cod }} - {{ optionLocalidad.name }}</option>
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
                    

                    <div class="mb-3 row">
                        <div class="offset-md-4 col-md-8">
                            <button class="btn btn-primary w120p" type="submit">Guardar</button>
                        </div>
                    </div>
                </fieldset>
            </form>
        </div>
    </div>
</div>

<script>
// Variables
//-----------------------------------------------------------------------------
var fields = {
    related_1: '0<?= $row->related_1 ?>',
    text_1: '<?= $row->text_1 ?>',
    text_2: '<?= $row->text_2 ?>',
    text_3: '<?= $row->text_3 ?>',
    integer_1: '<?= $row->integer_1 ?>',
    integer_2: '<?= $row->integer_2 ?>',
    admin_notes: '<?= $row->admin_notes ?>',
};

// Vue App
//-----------------------------------------------------------------------------
var editUserApp = new Vue({
    el: '#editUserApp',
    data: {
        loading: false,
        fields: fields,
        rowId: '<?= $row->id ?>',
        validation: {
            document_number_unique: -1,
            username_unique: -1,
            email_unique: -1
        },
        arrRole: <?= json_encode($arrRole) ?>,
        arrGender: <?= json_encode($arrGender) ?>,
        arrDocumentType: <?= json_encode($arrDocumentType) ?>,
        options_city: <?= json_encode($options_city) ?>,
        arrLocalidad: <?= json_encode($arrLocalidad) ?>,
        arrDocumentType: <?= json_encode($arrDocumentType) ?>,
        arrEtnia: <?= json_encode($arrEtnia) ?>,
        arrModalidad: <?= $arrModalidad ?>,
    },
    methods: {
        validateForm: function() {
            var formValues = new FormData(document.getElementById('userForm'))
            axios.post(URL_APP + 'users/validate/' + this.rowId, formValues)
            .then(response => {
                this.validation = response.data.validation
            })
            .catch(function (error) { console.log(error) })
        },
        validateAndSubmit: function () {
            axios.post(URL_APP + 'users/validate/' + this.rowId, $('#userForm').serialize())
            .then(response => {
                if (response.data.status == 1) {
                    this.handleSubmit()
                } else {
                    toastr['error']('Hay casillas incompletas o incorrectas')
                    this.loading = false
                }
            })
            .catch(function (error) { console.log(error) })
        },
        handleSubmit: function() {
            this.loading = true
            axios.post(URL_APP + 'users/save/', $('#userForm').serialize())
            .then(response => {
                if (response.data.saved_id > 0) toastr['success']('Guardado')
                this.loading = false
            })
            .catch(function (error) { console.log(error) })
        },
        generateUsername: function() {
            const formValues = new FormData();
            formValues.append('email', this.fields.email)
            
            axios.post(URL_APP + 'users/username/', formValues)
            .then(response => {
                this.fields.username = response.data
            })
            .catch(function (error) { console.log(error) })
        }
    }
});
</script>