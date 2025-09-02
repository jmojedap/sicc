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
            visibleInfo: 'perfil',
            visibleInfoOptions:[
                { value: 'perfil', text: 'Perfil', enabled: true },
                { value: 'redes', text: 'Redes Sociales', enabled: false },
                { value: 'experiencia', text: 'Experiencia', enabled: false },
                { value: 'intereses', text: 'Intereses', enabled: true }
            ],
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
        },
        setVisibleInfo: function(infoType) {
            this.visibleInfo = infoType;
        }
    },
    computed: {
        elementosFiltrados: function() {
            var listaFiltrada = this.elementos
            if (this.q.length > 0) {
                var fieldsToSearch = ['nombre_completo','perfil','email', 'intereses',
                    'pais_origen', 'institucion_red', 'rol_actividad'
                ]
                listaFiltrada = PmlSearcher.getFilteredResults(this.q, listaFiltrada, fieldsToSearch)
            }
            return listaFiltrada
        }
    }
}).mount('#directorioApp');
</script>