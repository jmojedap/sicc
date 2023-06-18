<script>
// Variables
//-----------------------------------------------------------------------------
var fields = {
    titulo: '',
    anio_publicacion: '2022',
    tipo_archivo: '010',
    tema_cod: '012',
    formato_cod: '020',
    subtema_1: '012140',
};

// VueApp
//-----------------------------------------------------------------------------   
var addContenidoApp = new Vue({
    el: '#addContenidoApp',
    data: {
        loading: false,
        fields: fields,
        contenidoId: 0,
        arrFormato: <?= json_encode($arrFormato) ?>,
        arrTema: <?= json_encode($arrTema) ?>,
        arrSubtema: <?= json_encode($arrSubtema) ?>,
        arrTipoArchivo: <?= json_encode($arrTipoArchivo) ?>,
    },
    methods: {
        handleSubmit: function() {
            this.loading = true
            var formValues = new FormData(document.getElementById('contenidoForm'))
            axios.post(URL_API + 'repositorio/save/', formValues)
            .then(response => {
                if ( response.data.saved_id > 0 )
                {
                    this.contenidoId = response.data.saved_id
                    this.clearForm()
                    $('#modal_created').modal()
                }
                this.loading = false
            })
            .catch(function (error) { console.log(error) })
        },
        clearForm: function() {
            for ( key in fields ) this.fields[key] = ''
        },
        goToCreated: function() {
            window.location = URL_APP + 'repositorio/edit/' + this.contenidoId
        },
    }
});
</script>