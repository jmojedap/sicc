
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
            text_3: '',
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
            step: 'form',
            loading: false,
            fields: fields,
            arrDocumentTypes: <?= json_encode($arrDocumentTypes) ?>,
            arrSexos: <?= json_encode($arrSexos) ?>,
            arrLocalidades: <?= json_encode($arrLocalidades) ?>,
            arrGenders: <?= json_encode($arrGenders) ?>,
            arrSexualOrientation: <?= json_encode($arrSexualOrientation) ?>,
            arrOcupaciones: <?= json_encode($arrOcupaciones) ?>,
            validation: {
                email_unique: -1,
                document_number_unique: -1
            },
            savedId: 0,
        }
    },
    methods: {
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

                    if ( this.savedId > 0 ) {
                        this.step = 'success'
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
    },
    mounted(){
        //this.getList()
    }
}).mount('#registroUsuarioApp')
</script>