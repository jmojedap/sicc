<div id="editApp">
    <div class="card center_box_750">
        <div class="card-body">
            <form id="editForm" accept-charset="utf-8" @submit.prevent="handleSubmit">
                <div class="mb-3 row">
                    <label for="display_name" class="col-md-4 col-form-label text-end">Nombre y Apellidos <span class="text-danger">*</span></label>
                    <div class="col-md-8">
                        <input
                            name="display_name" class="form-control"
                            placeholder="Tu nombre"
                            required autofocus
                            v-model="fields.display_name"
                            >
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="email" class="col-md-4 col-form-label text-end">Correo electrónico <span class="text-danger">*</span></label>
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
                    <label for="birth_date" class="col-md-4 col-form-label text-end">Fecha de nacimiento</label>
                    <div class="col-md-8">
                        <input name="birth_date" class="form-control" type="date" v-model="fields.birth_date">
                    </div>
                </div>

                <div class="mb-3 row">
                    <label for="gender" class="col-md-4 col-form-label text-end">Sexo</label>
                    <div class="col-md-8">
                        <select name="gender" v-model="fields.gender" class="form-select" required>
                            <option v-for="(option_gender, key_gender) in options_gender" v-bind:value="key_gender">{{ option_gender }}</option>
                        </select>
                    </div>
                </div>

                

                <div class="mb-3 row">
                    <div class="offset-md-4 col-md-8">
                        <button class="btn btn-primary w120p" type="submit">
                            Guardar
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
var fields = {
    display_name: '<?= $row->display_name ?>',
    email: '<?= $row->email ?>',
    birth_date: '<?= $row->birth_date ?>',
    gender: '0<?= $row->gender ?>',
    phone_number: '<?= $row->phone_number ?>',
};

// VueApp
//-----------------------------------------------------------------------------
var editApp = createApp({
    data(){
        return{
            loading: false,
            fields: fields,
            row_id: '<?= $row->id ?>',
            validated: -1,
            validation: {
                email_unique: -1,
            },
            options_gender: <?= json_encode($options_gender) ?>,
        }
    },
    methods: {
        validateForm: function() {
            var formValues = new FormData(document.getElementById('editForm'))
            axios.post(URL_API + 'accounts/validate_form/', formValues)
            .then(response => {
                this.validated = response.data.status
                this.validation = response.data.validation;
            })
            .catch(function (error) { console.log(error) })
        },
        handleSubmit: function(){
            this.loading = true
            var formValues = new FormData(document.getElementById('editForm'))
            axios.post(URL_API + 'accounts/update/', formValues)
            .then(response => {   
                this.loading = false 
                if (response.data.saved_id > 0) toastr['success']('Guardado');
            })
            .catch(function (error) { console.log(error) })
        },
    }
}).mount('#editApp')
</script>