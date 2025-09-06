<script>
// VueApp
//-----------------------------------------------------------------------------
var preguntasApp = createApp({
    data() {
        return {
            section: 'listado',
            typeView: 'table',
            nombreElemento: 'persona',
            nombreElementos: 'personas',
            elementos: <?= json_encode($elementos) ?>,
            directorio: <?= json_encode($directorio) ?>,
            currentProfile: <?= json_encode($directorio[0]) ?>,
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
            this.currentId = personaId
            this.currentElement = this.elementos.find(elemento => elemento['id'] == personaId)
            console.log(this.currentElement['username'])
            this.currentProfile = this.directorio.find(profile => profile['username'] == this.currentElement['username'])
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
        },
        nextOrPreviusProfile: function(step = 1){
            const currentIndex = this.elementos.findIndex(elemento => elemento['id'] === this.currentId);
            const nextIndex = currentIndex + step;

            if (nextIndex >= 0 && nextIndex < this.elementos.length) {
                this.setCurrent(this.elementos[nextIndex]['id']);
            }
        },
        nextRandomProfile: function() {
            // Get a random profile from the directorio
            const randomIndex = Math.floor(Math.random() * this.elementos.length);
            var nextElement = this.elementos[randomIndex];
            this.setCurrent(nextElement['id']);
        },
        //Buscar un valor de un campo del directorio a partir de un username
        directorioValue: function(username, field, defaultValue = '') {
            const profile = this.directorio.find(profile => profile['username'] === username);
            if (profile && profile.hasOwnProperty(field)) {
                return profile[field];
            }
            return defaultValue;
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
    },
    mounted() {
        // Escuchar evento global de teclado
        window.addEventListener("keydown", (event) => {
            if (event.key === "ArrowRight") {
                this.nextRandomProfile();
            }
            if (event.key === "ArrowLeft") {
                this.nextRandomProfile();
            }
        });
    },
    beforeUnmount() {
        // Importante: limpiar el listener si desmontas el componente
        window.removeEventListener("keydown", this.keyHandler);
    }
}).mount('#preguntasApp');
</script>