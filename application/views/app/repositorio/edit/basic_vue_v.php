<script>
// Vars
//-----------------------------------------------------------------------------
var contenido = <?= json_encode($row) ?>;
contenido.formato_cod = '0<?= $row->formato_cod ?>';
contenido.metodologia_cod = '0<?= $row->metodologia_cod ?>';
contenido.metodologia_cod = '0<?= $row->metodologia_cod ?>';
contenido.tipo_archivo = '0<?= $row->tipo_archivo ?>';
contenido.tema_cod = '0<?= $row->tema_cod ?>';
contenido.subtema_1 = '0<?= $row->subtema_1 ?>';
contenido.subtema_2 = '0<?= $row->subtema_2 ?>';
contenido.estado_publicacion = '0<?= $row->estado_publicacion ?>';

// VueApp
//-----------------------------------------------------------------------------
var editContenidoApp = createApp({
    data(){
        return{
            loading: false,
            fields: contenido,
            arrFormato: <?= json_encode($arrFormato) ?>,
            arrTipoContenido: <?= json_encode($arrTipo) ?>,
            arrCategoriaContenido: <?= json_encode($arrCategoriaContenido) ?>,
            arrMetodologia: <?= json_encode($arrMetodologia) ?>,
            arrTema: <?= json_encode($arrTema) ?>,
            arrSubtema: <?= json_encode($arrSubtema) ?>,
            arrTipoArchivo: <?= json_encode($arrTipoArchivo) ?>,
            arrEstadoPublicacion: <?= json_encode($arrEstadoPublicacion) ?>,
            arrCampo: <?= json_encode($arrCampo) ?>,
            arrSubcampo: <?= json_encode($arrSubcampo) ?>,
            arrArea: <?= json_encode($arrArea) ?>,
        }
    },
    methods: {
        handleSubmit: function(){
            this.loading = true
            var formValues = new FormData(document.getElementById('contenidoForm'))
            axios.post(URL_API + 'repositorio/save/', formValues)
            .then(response => {
                if ( response.data.saved_id > 0 ) {
                    toastr['success']('Guardado')
                }
                this.loading = false
            })
            .catch( function(error) {console.log(error)} )
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
}).mount('#editContenidoApp')
</script>