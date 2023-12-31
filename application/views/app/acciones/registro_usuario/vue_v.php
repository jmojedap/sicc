
<?php
    $is_test = false;
    if ( $this->input->get('test') == 1 ) {
        $is_test = true;
    }
?>

<script>
    var fields = {
            first_name: '',
            last_name: '',
            document_number: '',
            document_type: '1',
            email: '',
            phone_number: '',
            gender: 2,
            birth_date: '1990-01-01',
            address: '',
            integer_1: '',
            text_1: '',
            text_2: 'Masculina',
            text_3: 'Prefiero no responder',
            job_role: ''
        };
</script>
    
<?php if ( $is_test ) : ?>
    <script>
        var fields = {
                first_name: 'Juán Prueba',
                last_name: 'Pérez Eliminable',
                document_number: '112233<?= rand(10,99) ?>',
                document_type: '1',
                email: 'jperez<?= rand(10,99) ?>@gmail.co',
                phone_number: '3009998877',
                gender: 2,
                birth_date: '1990-01-01',
                address: 'Fake Street 123',
                integer_1: 3,
                text_1: 'Engativá',
                text_2: 'Masculina',
                text_3: 'Gay',
                job_role: 'Empleada(o)'
            };
    </script>

<?php endif; ?>

<script>

// VueApp
//-----------------------------------------------------------------------------
var registroUsuarioApp = createApp({
    data(){
        return{
            step: 'form1',
            loading: false,
            fields: fields,
            arrDocumentTypes: <?= json_encode($arrDocumentTypes) ?>,
            arrSexos: <?= json_encode($arrSexos) ?>,
            arrLocalidades: <?= json_encode($arrLocalidades) ?>,
            arrGenders: <?= json_encode($arrGenders) ?>,
            arrSexualOrientation: <?= json_encode($arrSexualOrientation) ?>,
            arrOcupaciones: <?= json_encode($arrOcupaciones) ?>,
            validated: false,
            validation: {
                email_unique: -1,
                document_number_unique: -1
            },
            savedId: 0,
            activationKey: '',
            withoutEmail: false,
            randomEmail: 'no_tiene_<?= strtolower(random_string('alpha', 8)); ?>@notiene.com',
        }
    },
    methods: {
        setStep: function(newStep){
            this.step = newStep
        },
        validateForm: function() {
            var formValues = new FormData(document.getElementById('registroUsuarioForm'))
            axios.post(URL_API + 'accounts/validate_signup/', formValues)
            .then(response => {
                this.validated = response.data.status
                this.validation = response.data.validation;
            })
            .catch(function (error) { console.log(error) })
        },
        handleSubmit: function(){
            if ( this.validated )
            {
                this.loading = true
                var payload = new FormData(document.getElementById('registroUsuarioForm'))
                
                axios.post(URL_API + 'acciones/create_user/', payload)
                .then(response => {
                    this.loading = false
                    this.savedId = response.data.saved_id
                    this.activationKey = response.data.activation_key

                    if ( this.savedId > 0 ) {
                        this.step = 'form2'
                    }

                    if ( response.data.recaptcha == -1 ) {
                        toastr['error']('No se realizó la validación recaptcha')
                        setTimeout(() => {
                            window.location = URL_APP + 'acciones/registro_usuario'
                        }, 3000);
                    }
                })
                .catch(function (error) { console.log(error) })
            } else {
                toastr['error']('Revisa las casillas en rojo')
            }
        },
        updateCreatedUser: function(){
            this.loading = true
            var formValues = new FormData(document.getElementById('formUpdateUser'))
            axios.post(URL_API + 'acciones/update_user/', formValues)
            .then(response => {
                if ( parseInt(response.data.saved_id) > 0 ) {
                    toastr['success']('Usuario actualizado')
                    this.step = 'success'
                } else {
                    toastr['error']('Algo salió mal, inténtalo de nuevo')
                }
                this.loading = false
            })
            .catch( function(error) {console.log(error)} )
        },
        setRandomEmail: function(){
            console.log(this.withoutEmail)
            if (this.withoutEmail == true) {
                this.fields.email = this.randomEmail
            } else {
                this.fields.email = ''
            }
        },
    },
}).mount('#registroUsuarioApp')
</script>