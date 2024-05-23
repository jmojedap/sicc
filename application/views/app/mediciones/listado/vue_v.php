<script>
// VueApp
//-----------------------------------------------------------------------------
var medicionesApp = createApp({
    data() {
        return {
            nombreElemento: 'medición',
            nombreElementos: 'mediciones',
            mediciones: <?= json_encode($mediciones) ?>,
            loading: false,
            fields: {},
            fileId: '<?= $fileId ?>',
            displayUrl: true,
            gid: '<?= $gid ?>',
            q: '',
            years: [2024,2023,2022,2021,2020,2019,2018,2017],
            filters: {
                year: '' 
            }
        }
    },
    methods: {
        ago: function(date){
            if (!date) return ''
            return moment(date, 'YYYY-MM-DD HH:mm:ss').fromNow()            
        },
        updateList: function(){
            this.loading = true
            axios.get('<?= base_url() ?>api/tools/googlesheet_save_json/' + this.fileId + '/' + this.gid + '/observatorio/mediciones')
            .then(response => {
                if ( response.data.status == 1 ) {
                    toastr['success']('Datos actualizados')
                    this.mediciones = response.data.array
                }
                this.loading = false
            })
            .catch(function(error) { console.log(error) })
        },
        clearSearch: function(){
            this.q = ''
        },
        textToClass: function(prefix='', inputText){
            return prefix + Pcrn.textToClass(inputText)
        },
    },
    computed: {
        medicionesFiltrados: function() {
            var listaFiltrada = this.mediciones
            if ( this.filters.year != '' ) {
                listaFiltrada = listaFiltrada.filter(medicion => medicion.anio_informacion == this.filters.year)
            }
            if (this.q.length > 0) {
                var fieldsToSearch = ['nombre','descripcion','palabras_clave',
                'subtitulo','estado', 'anio_informacion', 'codigo']
                listaFiltrada = PmlSearcher.getFilteredResults(this.q, listaFiltrada, fieldsToSearch)
            }
            return listaFiltrada
        }
    }
}).mount('#medicionesApp');
</script>