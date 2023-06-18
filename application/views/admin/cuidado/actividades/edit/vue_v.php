<script>
// Variables
//-----------------------------------------------------------------------------
var fields = <?= json_encode($row) ?>;
fields.localidad_cod = '0<?= $row->localidad_cod ?>';
fields.medicion_realizada = '0<?= $row->medicion_realizada ?>';

// VueApp
//-----------------------------------------------------------------------------   
var editActividadApp = new Vue({
    el: '#editActividadApp',
    data: {
        loading: false,
        fields: fields,
        actividadId: '<?= $row->id ?>',
        arrTipo: <?= json_encode($arrTipo) ?>,
        arrLocalidad: <?= json_encode($arrLocalidad) ?>,
        arrSiNoNa: <?= json_encode($arrSiNoNa) ?>,
        arrModalidad: <?= json_encode($arrModalidad) ?>,
    },
    methods: {
        handleSubmit: function() {
            this.loading = true
            var formValues = new FormData(document.getElementById('actividadForm'))
            axios.post(URL_API + 'cuidado/save/', formValues)
            .then(response => {
                if ( response.data.saved_id > 0 )
                {
                    toastr['success']('Cambios guardados')
                }
                this.loading = false
            })
            .catch(function (error) { console.log(error) })
        },
    }
});
</script>