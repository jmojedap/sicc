<script>
// Variables
//-----------------------------------------------------------------------------
/*var fields = {
    nombre_actividad: 'Eliminar',
    descripcion: 'Descripción de la actividad',
    tipo_actividad: 'Escuela Presencial',
    localidad_cod: '01',
    inicio: '<?= date('Y-m-d') ?> 08:00:00',
    fin: '<?= date('Y-m-d') ?> 10:00:00',
    en_manzana: 'Sí',
    modalidad: 'Presencial',
    nombre_lugar: 'Colegio ABC',
    direccion: 'Calle 13 10 23',
    facilitadores: 'Juan Pérez y Luis Suárez',
    medicion_realizada: 1,
    cantidad_mujeres: 5,
    cantidad_hombres: 5,
    url_asistencia: 'https://www.mauricioojeda.com',
    contacto_espacio: 'Doña Matilde es el contacto',
    observaciones: 'Una observación sobre la actividad',
    radicado_orfeo: '2000300852963',
};*/

var fields = {
    nombre_actividad: '', descripcion: '', tipo_actividad: '',
    localidad_cod: '',
    inicio: '<?= date('Y-m-d') ?> 08:00:00', fin: '<?= date('Y-m-d') ?> 10:00:00',
    en_manzana: '', modalidad: '', nombre_lugar: '', direccion: '',
    facilitadores: '', medicion_realizada: 0, cantidad_mujeres: 0, cantidad_hombres: 0,
    url_asistencia: '', contacto_espacio: '', observaciones: '', radicado_orfeo: '',
};



// VueApp
//-----------------------------------------------------------------------------   
var addPostApp = new Vue({
    el: '#addPostApp',
    data: {
        loading: false,
        fields: fields,
        actividadId: 0,
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
                    this.actividadId = response.data.saved_id
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
            window.location = URL_APP + 'cuidado/edit/' + this.actividadId
        },
    }
});
</script>