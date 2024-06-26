<script>
// VueApp
//-----------------------------------------------------------------------------
var linksApp = createApp({
    data() {
        return {
            links: <?= json_encode($links) ?>,
            loading: false,
            fields: {},
            displayUrl: false,
            fileId: '<?= $fileId ?>',
            gid: '<?= $gid ?>',
            q: ''
        }
    },
    methods: {
        ago: function(date){
            if (!date) return ''
            return moment(date, 'YYYY-MM-DD HH:mm:ss').fromNow()            
        },
        updateList: function(){
            this.loading = true
            axios.get('<?= base_url() ?>api/tools/googlesheet_save_json/' + this.fileId + '/' + this.gid + '/observatorio/links')
            .then(response => {
                if ( response.data.status == 1 ) {
                    toastr['success']('Datos actualizados')
                    this.links = response.data.array
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
        linksFiltrados: function() {
            if (this.q.length > 0) {
                var fieldsToSearch = ['nombre','descripcion','palabras_clave']
                return PmlSearcher.getFilteredResults(this.q, this.links, fieldsToSearch)
            }
            return this.links
        }
    }
}).mount('#linksApp');
</script>