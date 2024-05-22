<script>
// VueApp
//-----------------------------------------------------------------------------
var investigacionesApp = createApp({
    data() {
        return {
            nombreElemento: 'investigación',
            nombreElementos: 'investigaciones',
            elementos: <?= json_encode($elementos) ?>,
            productos: <?= json_encode($productos) ?>,
            loading: false,
            fields: {},
            fileId: '<?= $fileId ?>',
            displayUrl: true,
            gid: '<?= $gid ?>',
            q: '',
            estados: [
                'Sin iniciar',
                'En ejecución',
                'Finalizada',
                'Enviada',
                'En espera',
                'Cancelada',
                'ND/NA'
            ],
            filters: {
                status: '' 
            },
            section: 'lista',
            currentElement: <?= json_encode($elementos[0]) ?>,
            currentId: -1,
        }
    },
    methods: {
        ago: function(date){
            if (!date) return ''
            return moment(date, 'YYYY-MM-DD HH:mm:ss').fromNow()            
        },
        updateList: function(){
            this.loading = true
            axios.get('<?= base_url() ?>api/tools/googlesheet_save_json/' + this.fileId + '/' + this.gid + '/observatorio/investigaciones')
            .then(response => {
                if ( response.data.status == 1 ) {
                    toastr['success']('Datos actualizados')
                    this.elementos = response.data.array
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
        setCurrent: function(investigacionId){
            this.section = 'ficha'
            this.currentId = investigacionId
            this.currentElement = this.elementos.find(elemento => elemento['ID'] == investigacionId)
        },
        textToClass: function(text){
            return Pcrn.textToClass(text)
        },
        getProductoClass: function(tipoProducto){
            var productoClass = 'fa-solid fa-arrow-right producto-general'
            if ( tipoProducto == 'Informe final' ) productoClass = 'fa-solid fa-file-lines producto-pdf'
            return productoClass
        },
    },
    computed: {
        elementosFiltrados: function() {
            var listaFiltrada = this.elementos
            if ( this.filters.status != '' ) {
                listaFiltrada = listaFiltrada.filter(elemento => elemento['Estado'] == this.filters.status)
            }
            if (this.q.length > 0) {
                var fieldsToSearch = ['Título','Descripción','Palabras clave', 'ENTIDAD',
                    'Dirección/Dependencia', 'Objetivo de la investigación'
                ]
                listaFiltrada = PmlSearcher.getFilteredResults(this.q, listaFiltrada, fieldsToSearch)
            }
            return listaFiltrada
        }
    }
}).mount('#investigacionesApp');
</script>