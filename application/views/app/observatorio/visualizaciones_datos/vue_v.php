<script src="<?= URL_RESOURCES ?>js/pml_searcher.js"></script>

<script>
var visualizacionesDatosApp = createApp({
    data() {
        return {
            tableros: <?= json_encode($tableros) ?>,
            loading: false,
            fields: {},
            displayUrl: false,
            fileId: '<?= $fileId ?>',
            gid: '<?= $gid ?>',
            q:'',
        }
    },
    methods: {
        ago: function(date){
            if (!date) return ''
            return moment(date, 'YYYY-MM-DD HH:mm:ss').fromNow()            
        },
        updateList: function(){
            this.loading = true
            axios.get('<?= base_url() ?>api/tools/googlesheet_save_json/' + this.fileId + '/' + this.gid + '/observatorio/dataviz')
            .then(response => {
                if ( response.data.status == 1 ) {
                    toastr['success']('Datos actualizados')
                    this.tableros = response.data.array
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
        tablerosFiltrados: function() {
            if (this.q.length > 0) {
                var fieldsToSearch = ['nombre','descripcion','palabras_clave']
                return PmlSearcher.getFilteredResults(this.q, this.tableros, fieldsToSearch)
            }
            return this.tableros
        },
    },
}).mount('#visualizacionesDatosApp')
</script>