<?php
    $fields = [];
    foreach ($row as $name => $value) {
        $field['name'] = $name;
        $field['value'] = $value;
        $field['required'] = false;
        $fields[$field['name']] = $field;
    }

    unset($fields['id']);
    unset($fields['password']);
?>

<div id="editUserApp">
    <div class="card center_box_750">
        <div class="card-body">
            <form id="userForm" accept-charset="utf-8" @submit.prevent="validateAndSubmit">
                <fieldset v-bind:disabled="loading">
                    <input type="hidden" name="id" value="<?= $row->id ?>">

                    <div class="mb-1 row">
                        <div class="offset-md-4 col-md-8">
                            <button class="btn btn-primary w120p" type="submit">Guardar</button>
                        </div>
                    </div>

                    <div class="mb-1 row" v-for="field in fields">
                        <label for="username" class="col-md-4 col-form-label text-end">{{ field.name }} <span class="text-danger" v-show="field.required">*</span></label>
                        <div class="col-md-8">
                            <input :required="field.required"
                            :name="field.name" v-model="field.value" class="form-control"></input>
                        </div>
                    </div>

                    <div class="mb-1 row">
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

var fields = <?= json_encode($fields); ?>;

// VueApp
//-----------------------------------------------------------------------------
var editUserApp = createApp({
    data(){
        return{
            loading: false,
            fields: fields,
            row: '<?= json_encode($row) ?>',
            rowId: '<?= $row->id ?>',
            validation: {
                document_number_unique: -1,
                username_unique: -1,
                email_unique: -1
            },
        }
    },
    methods: {
        validateForm: function() {
            var formValues = new FormData(document.getElementById('userForm'))
            axios.post(URL_API + 'users/validate/' + this.rowId, formValues)
            .then(response => {
                this.validation = response.data.validation
            })
            .catch(function (error) { console.log(error) })
        },
        validateAndSubmit: function () {
            axios.post(URL_API + 'users/validate/' + this.rowId, $('#userForm').serialize())
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
            axios.post(URL_API + 'users/save/', $('#userForm').serialize())
            .then(response => {
                if (response.data.saved_id > 0) toastr['success']('Guardado')
                this.loading = false
            })
            .catch(function (error) { console.log(error) })
        },
    },
    mounted(){
        //this.getList()
    }
}).mount('#editUserApp')
</script>