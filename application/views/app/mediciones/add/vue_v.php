<script>
// Variables
//-----------------------------------------------------------------------------
var fields = {
    nombre_medicion: '',
    tipo: ''
};

// VueApp
//-----------------------------------------------------------------------------
var addMedicionApp = createApp({
    data(){
        return{
            loading: false,
            fields: fields,
            savedId: 0,
            arrTipo: <?= json_encode($arrTipo) ?>,
            section: 'form',
        }
    },
    methods: {
        handleSubmit: function() {
            this.loading = true
            var formValues = new FormData(document.getElementById('addForm'))
            axios.post(URL_API + 'mediciones/save/', formValues)
            .then(response => {
                if ( response.data.saved_id > 0 )
                {
                    this.savedId = response.data.saved_id
                    this.clearForm()
                    this.section = 'created'
                }
                this.loading = false
            })
            .catch(function (error) { console.log(error) })
        },
        clearForm: function() {
            for ( key in fields ) this.fields[key] = ''
            this.section = 'form'
        },
        goToCreated: function() {
            window.location = URL_APP + 'mediciones/edit/' + this.savedId
        },
    },
    mounted(){
        //this.getList()
    }
}).mount('#addMedicionApp')
</script>