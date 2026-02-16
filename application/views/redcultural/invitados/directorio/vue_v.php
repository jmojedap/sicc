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
            loadingFollowing: false,
            followed: <?= json_encode($followed->result()) ?>,
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
        altFollow: function() {
            this.loadingFollowing = true
            axios.get(URL_API + 'users/alt_follow/' + this.currentElement.id)
            .then(response => {
                console.log(response.data)
                this.followingStatus = response.data.status;
                if (response.data.status == 1) {
                    //Se agrega al array this.followed
                    var newFollowed = {
                        user_id: this.currentElement.id,
                        username: this.currentElement.username
                    }
                    this.followed.push({user_id: this.currentElement.id});
                    toastr['success']('Se agregó a tu listado de intereses culturales')
                } else if (response.data.status == 2) {
                    //Se elimina de this.followed
                    this.followed = this.followed.filter(f => f.user_id != this.currentElement.id);
                    toastr['info']('Se retiró de tu listado de intereses culturales')
                }
                this.loadingFollowing = false
            })
            .catch(function(error) {console.log(error)})
        },
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
        },
        //Establecer si this.currentElement.id esta en this.followed.user_id
        inFollowed: function(){
            return this.followed.some(f => f.user_id == this.currentElement.id);
        },
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
        // Sumar un valor aleatorio a elementos['puntaje'] entre 1 y 15
        this.elementos.forEach(elemento => {
            //elemento['puntaje'] = intval(elemento['puntaje']) + Math.floor(Math.random() * 15) + 1;
            elemento['puntaje'] = elemento['puntaje'] * 1 + (Math.floor(Math.random() * 50) + 1);
        });
        // Redorderar elementos según puntaje
        this.elementos.sort((a, b) => b.puntaje - a.puntaje);
        // Establecer el primer elemento como el actual
        this.setCurrent(this.elementos[0]['id']);
        // Ubicación SanTru
        var elementoSanTru = this.elementos.find(elemento => elemento['username'] == 'santiago.trujillo');
        this.elementos = this.elementos.filter(elemento => elemento['username'] != 'santiago.trujillo');
        this.elementos.splice(2, 0, elementoSanTru);
        var elementoDieMal = this.elementos.find(elemento => elemento['username'] == 'diego.maldonado');
        this.elementos = this.elementos.filter(elemento => elemento['username'] != 'diego.maldonado');
        this.elementos.splice(1, 0, elementoDieMal);
    },
    beforeUnmount() {
        // Importante: limpiar el listener si desmontas el componente
        window.removeEventListener("keydown", this.keyHandler);
    }
}).mount('#directorioApp');
</script>