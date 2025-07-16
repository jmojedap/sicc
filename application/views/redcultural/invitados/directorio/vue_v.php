<script>
// VueApp
//-----------------------------------------------------------------------------
var directorioApp = createApp({
    data() {
        return {
            section: 'listado',
            typeView: 'grid',
            nombreElemento: 'persona',
            nombreElementos: 'personas',
            elementos: <?= json_encode($elementos) ?>,
            paises: paises,
            loading: false,
            q: '',
            filters: {
                status: '' 
            },
            currentElement: <?= json_encode($elementos[0]) ?>,
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
        setCurrent: function(personaId){
            //this.section = 'perfil'
            this.currentId = personaId
            this.currentElement = this.elementos.find(elemento => elemento['id'] == personaId)
        },
        textToClass: function(text){
            return Pcrn.textToClass(text)
        },
        paisTo: function(countryCode, field = 'name') {
            return RciPaises.codeTo(countryCode, field);
        },
        paisFlag: function(countryCode) {
            return RciPaises.flagIconUrl(countryCode);
        }
    },
    computed: {
        elementosFiltrados: function() {
            var listaFiltrada = this.elementos
            /*if ( this.filters.status != '' ) {
                listaFiltrada = listaFiltrada.filter(elemento => elemento['Estado'] == this.filters.status)
            }*/
            if (this.q.length > 0) {
                var fieldsToSearch = ['nombre_completo','perfil','email', 'intereses'
                ]
                listaFiltrada = PmlSearcher.getFilteredResults(this.q, listaFiltrada, fieldsToSearch)
            }
            return listaFiltrada
        }
    }
}).mount('#directorioApp');
</script>