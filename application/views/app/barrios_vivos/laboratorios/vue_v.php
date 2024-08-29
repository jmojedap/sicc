<script>
// VueApp
//-----------------------------------------------------------------------------
var laboratoriosApp = createApp({
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
        setSeccion: function(nuevaSeccion){
            this.seccion = nuevaSeccion
        },
        updateList: function(tabla){
            this.loading = true
            axios.get('<?= base_url() ?>api/tools/googlesheet_save_json/' + this.fileId + '/' + tabla.gid + '/barrios_vivos/' + tabla.nombre)
            .then(response => {
                if ( response.data.status == 1 ) {
                    toastr['success']('Datos actualizados, presione F5')
                    //this.laboratorios = response.data.array
                }
                this.loading = false
            })
            .catch(function(error) { console.log(error) })
        },
        clearSearch: function(){
            this.q = ''
        },
    },
    computed: {
        laboratoriosFiltrados: function() {
            var listaFiltrada = this.laboratorios
            listaFiltrada = listaFiltrada.filter(laboratorio => laboratorio['Incluir'] == 'Sí')
            if (this.q.length > 0) {
                var fieldsToSearch = ['nombre_laboratorio', 'nombre_corto' ,'descripcion','palabras_clave',
                    'barrio_ancla', 'equipo_lider_duplas', 'categoria_laboratorio', 'gerente']
                listaFiltrada = PmlSearcher.getFilteredResults(this.q, this.laboratorios, fieldsToSearch)
            }
            return listaFiltrada
        }
    }
}).mount('#laboratoriosApp');
</script>