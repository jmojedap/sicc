<?php
    $this->load->helper('string');
    $username = 'u' . date('Ymdhis') . random_string('numeric', 2);
    $password = 'Pw' . random_string('alnum', 12);

    $arrEtnia = $this->Item_model->arr_options('category_id = 116');
    $arrLocalidad = $this->Item_model->arr_options('category_id = 121');

    //Opciones modalidades de escuela    
    $arrModalidad = file_get_contents(PATH_CONTENT . "json/options/cuidado_modalidades.json");
?>

<script>
    /*var random = '16073' + Math.floor(Math.random() * 100000);
    var fields = {
        role: '021',
        first_name: 'Henry',
        last_name: 'Jones',
        display_name: 'Henry Jones',
        document_number: random,
        document_type: '01',
        email: '<?= $username ?>@nodisponible.com',
        username: '<?= $username ?>',
        password: '<?= $password ?>',
        birth_date: '1982-12-31',
        gender: '02',
        address: 'CL 13 10 23',
        phone_number: '3001112233',
        related_1: '010',
        text_1: 'Escuela presencial',
        text_2: '1,3',
        text_3: 'Indígena',
        integer_1: 3,
        integer_2: 2,
        admin_notes: 'Anotaciones sobre el estudiante',
    };*/
    
    var fields = {
        role: '',
        last_name: '',
        firts_name: '',
        display_name: '',
        document_type: '01',
        email: '<?= $username ?>@nodisponible.com',
        username: '',
        gender: '02',
        address: '',
        phone_number: '',
        password: '<?= $password ?>',
        related_1: '',
        text_1: 'Escuela presencial',
        text_2: '',
        text_3: '',
        integer_1: 0,
        integer_2: 0,
    };

// VueApp
//-----------------------------------------------------------------------------
var addUserApp = new Vue({
    el: '#addUserApp',
    data: {
        loading: false,
        fields: fields,
        validation: {
            email_unique: -1,
        },
        savedId: 0,
        options_role: <?= json_encode($options_role) ?>,
        arrGender: <?= json_encode($arrGender) ?>,
        arrLocalidad: <?= json_encode($arrLocalidad) ?>,
        arrDocumentType: <?= json_encode($arrDocumentType) ?>,
        arrEtnia: <?= json_encode($arrEtnia) ?>,
        arrModalidad: <?= $arrModalidad ?>,
    },
    methods: {
        handleSubmit: function () {
            var payLoad = new FormData(document.getElementById('addUserForm'))
            axios.post(URL_APP + 'users/validate/', payLoad)
            .then(response => {
                if ( response.data.status == 1 ) {
                    this.submitForm()
                } else {
                    toastr['error']('Revise las casillas en rojo')
                }
            }).catch(function (error) { console.log(error) })
        },
        submitForm: function() {
            this.loading = true
            var payLoad = new FormData(document.getElementById('addUserForm'))
            axios.post(URL_APP + 'users/save/', payLoad)
            .then(response => {
                if ( response.data.saved_id > 0 )
                {
                    this.savedId = response.data.saved_id
                    this.clearForm()
                    $('#modal_created').modal()
                    this.loading = false
                }
            }).catch(function (error) {console.log(error)})
        },
        setDisplayName: function(reset){
            //Calcular displayName
            var displayName = ''
            if ( this.fields.first_name.length > 0 || this.fields.last_name.length > 0 ) {
                displayName = this.fields.first_name + ' ' + this.fields.last_name
            }
            //Identificar si se actualiza o no
            if ( this.fields.display_name.length < 3 ) {
                this.fields.display_name = displayName
            }
            if ( reset == true ) {
                this.fields.display_name = displayName
            }
        },
        generateUsername: function() {
            var payLoad = new FormData()
            payLoad.append('email', this.fields.email)
            
            axios.post(URL_APP + 'users/username/', payLoad)
            .then(response => {
                this.fields.username = response.data
            })
            .catch(function(error) { console.log(error)} )
        },
        validateForm: function() {
            var payLoad = new FormData(document.getElementById('addUserForm'))
            axios.post(URL_APP + 'users/validate/', payLoad)
            .then(response => {
                this.validation = response.data.validation
            })
            .catch(function (error) { console.log(error)})
        },
        clearForm: function() {
            for ( key in fields ) this.fields[key] = ''
            this.validation.document_number_unique = -1
            this.validation.email_unique = -1
            this.validation.username_unique = -1
        },
        goToCreated: function() {
            window.location = URL_APP + 'users/profile/' + this.savedId
        }
    }
});
</script>