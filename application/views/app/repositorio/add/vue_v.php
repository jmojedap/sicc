<script>
// Variables
//-----------------------------------------------------------------------------
var test = '<?= $this->input->get('test'); ?>' | 0;
var randomValue = Math.floor(Math.random() * 1000);
var fields = {
    titulo: '',
    descripcion: '',
    entidad_sigla: '',
    entidad: '',
    anio_publicacion: '2022',
    tipo_archivo: '',
    tema_cod: '',
    formato_cod: '',
    subtema_1: '',
    sector_campo: '',
    sector_subcampo: '',
    sector_area: '',
};

if ( test == '1' ) {
    var fields = {
        titulo: 'Contenido Prueba Eliminar ' + randomValue,
        descripcion: 'Descripción de prueba eliminar del contenido ' + randomValue,
        entidad_sigla: 'SCRD',
        entidad: 'Secretaría de Cultura, Recreación y Deporte',
        anio_publicacion: '2022',
        tipo_archivo: '010',
        url_contenido: 'https://lookerstudio.google.com/embed/u/0/reporting/6d0a6608-79d1-409c-8575-e4ae0da471a1/page/UL1SD',
        tema_cod: '012',
        formato_cod: '020',
        subtema_1: '012140',
        sector_campo: '10',
        sector_subcampo: '100',
        sector_area: '10010',
    };
}

// VueApp
//-----------------------------------------------------------------------------
var addContenidoApp = createApp({
    data(){
        return{
            loading: false,
            fields: fields,
            contenidoId: 0,
            arrFormato: <?= json_encode($arrFormato) ?>,
            arrTema: <?= json_encode($arrTema) ?>,
            arrSubtema: <?= json_encode($arrSubtema) ?>,
            arrTipoArchivo: <?= json_encode($arrTipoArchivo) ?>,
            arrEntidad: <?= json_encode($arrEntidad) ?>,
            arrCampo: <?= json_encode($arrCampo) ?>,
            arrSubcampo: <?= json_encode($arrSubcampo) ?>,
            arrArea: <?= json_encode($arrArea) ?>,
        }
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
                    new bootstrap.Modal($('#modalCreated')).show();
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
        setEntidad: function(){
            this.fields.entidad = this.entidadName(this.fields.entidad_sigla)
        },
        entidadName: function(value = '', field = 'name'){
            var entidadName = ''
            var item = this.arrEntidad.find(row => row.abbreviation == value)
            if ( item != undefined ) entidadName = item[field]
            return entidadName
        },
        unsetSubcampo: function(){
            this.fields.sector_subcampo = ''
            this.fields.sector_area = ''
        },
        unsetArea: function(){
            this.fields.sector_area = ''
        },
    },
    computed: {
        filteredSubcampos: function (){
            //Solo subcampos que pertentecen al campo padre
            if ( this.fields.sector_campo.length > 0 ) {
                return this.arrSubcampo.filter(item => item.parent_id == this.fields.sector_campo)
            }
            return this.arrSubcampo
        },
        filteredAreas: function (){
            //Solo áreas que pertentecen al subcampo padre
            if ( this.fields.sector_subcampo.length > 0 ) {
                return this.arrArea.filter(item => item.parent_id == this.fields.sector_subcampo)
            }
            return this.arrArea
        }
    },
    mounted(){
        //this.getList()
    }
}).mount('#addContenidoApp')
</script>