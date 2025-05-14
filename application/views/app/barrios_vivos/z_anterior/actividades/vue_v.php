<script>
// VueApp
//-----------------------------------------------------------------------------
var actividadesApp = createApp({
    data() {
        return {
            seccion:'listado',
            laboratorios: <?= json_encode($laboratorios) ?>,
            actividades: <?= json_encode($actividades) ?>,
            loading: false,
            fields: {},
            displayUrl: false,
            fileId: '<?= $fileId ?>',
            gid: '<?= $gid ?>',
            tablas: <?= json_encode($tablas) ?>,
            q: ''
        }
    },
    methods: {
        ago: function(date){
            if (!date) return ''
            return moment(date, 'YYYY-MM-DD HH:mm:ss').fromNow()            
        },
        dateFormat: function(date){
            if (!date) return ''
            return moment(date).format('D MMM')
        },
        setSeccion: function(nuevaSeccion){
            this.seccion = nuevaSeccion
        },
        updateList: function(tabla){
            this.loading = true
            axios.get('<?= base_url() ?>api/tools/googlesheet_save_json/' + this.fileId + '/' + tabla.gid + '/barrios_vivos/' + tabla.nombre)
            .then(response => {
                if ( response.data.status == 1 ) {
                    toastr['success']('Datos actualizados, presione F5')
                }
                this.loading = false
            })
            .catch(function(error) { console.log(error) })
        },
        clearSearch: function(){
            this.q = ''
        },
        laboratorioDetalle: function(laboratorioId, campo){
            var laboratorioDetalle = ''
            var laboratorio = this.laboratorios.find(laboratorio => laboratorio.laboratorio = laboratorioId)
            if ( laboratorio != null ) {
                laboratorioDetalle = laboratorio[campo]
            }
            return laboratorioDetalle
        },
        textToClass: function(text, prefix = null){
            if ( prefix == null) {
                return Pcrn.textToClass(text)
            }
            return prefix + '-' + Pcrn.textToClass(text)
        },
        ordenarActividades: function(){
            //Ordenar actividades de forma ascendente
            this.actividades.sort((a, b) => new Date(a.fecha) - new Date(b.fecha));
        },
    },
    computed: {
        actividadesFiltrados: function() {
            var listaFiltrada = this.actividades
            listaFiltrada = listaFiltrada.filter(actividad => actividad['fecha'].length > 0)
            listaFiltrada = listaFiltrada.filter(actividad => actividad['laboratorio_id'] != 20)
            if (this.q.length > 0) {
                var fieldsToSearch = ['laboratorio', 'sesion' ,'interaccion','fase_laboratorio',
                    'fase_metodologia', 'estado', 'lugar', 'descripcion', 'descripcion_evidencias']
                listaFiltrada = PmlSearcher.getFilteredResults(this.q, this.actividades, fieldsToSearch)
            }
            return listaFiltrada
        }
    },
    mounted(){
        this.ordenarActividades()
    }
}).mount('#actividadesApp');
</script>