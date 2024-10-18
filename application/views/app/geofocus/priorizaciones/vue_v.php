<script>
const elementos = <?= json_encode($elementos->result()) ?>;

// VueApp
//-----------------------------------------------------------------------------
var priorizacionesApp = createApp({
    data() {
        return {
            section: 'lista',
            nombreElemento: 'priorizacion',
            nombreElementos: 'priorizaciones',
            elementos: elementos,
            loading: false,
            fields: {},
            q: '',
            filters: {
                status: '' 
            },
            currentElement: elementos[0],
            currentId: -1,
        }
    },
    methods: {
        ago: function(date){
            if (!date) return ''
            return moment(date, 'YYYY-MM-DD HH:mm:ss').fromNow()            
        },
        clearSearch: function(){
            this.q = ''
        },
        textToClass: function(prefix='', inputText){
            return prefix + Pcrn.textToClass(inputText)
        },
        setSection: function(newSection){
            this.section = newSection
        },
        setCurrent: function(priorizacionId){
            this.currentId = priorizacionId
            this.currentElement = this.elementos.find(elemento => elemento['id'] == priorizacionId)
            history.pushState(null, null, URL_APP +'geofocus/priorizaciones/' + priorizacionId)
        },
        textToClass: function(text){
            return Pcrn.textToClass(text)
        },
        saveElement: function(){
            this.loading = true
            var formValues = new FormData(document.getElementById('priorizacionForm'))
            axios.post(URL_API + 'geofocus/save_priorizacion/', formValues)
            .then(response => {
                if ( response.data.saved_id > 0 ) {
                    toastr['success']('Guardado')
                }
                this.loading = false
            })
            .catch( function(error) {console.log(error)} )
        },
        goToEditForm: function(priorizacionId){
            console.log(priorizacionId)
            this.setCurrent(priorizacionId)
            this.section = 'form'
        },
    },
    computed: {
        elementosFiltrados: function() {
            var listaFiltrada = this.elementos
            if (this.q.length > 0) {
                var fieldsToSearch = ['nombre','descripcion']
                listaFiltrada = PmlSearcher.getFilteredResults(this.q, listaFiltrada, fieldsToSearch)
            }
            return listaFiltrada
        }
    }
}).mount('#priorizacionesApp');
</script>