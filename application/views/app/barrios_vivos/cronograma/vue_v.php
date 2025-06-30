<script>
// VueApp
//-----------------------------------------------------------------------------
var cronogramaApp = createApp({
    data() {
        return {
            seccion:'listado',
            actividades: [],
            loading: false,
            q: ''
        }
    },
    methods: {
        getList: function(){
            this.loading = true
            var formValues = new FormData()
            formValues.append('sf', 'actividades')
            formValues.append('y', '2025')
            axios.post(URL_API + 'barrios_vivos/get_details/', formValues)
            .then(response => {
                this.actividades = response.data.details
                this.loading = false
            })
            .catch( function(error) {console.log(error)} )
        },
        ago: function(date){
            if (!date) return ''
            return moment(date, 'YYYY-MM-DD HH:mm:ss').fromNow()            
        },
        dateFormat: function(date){
            if (!date) return ''
            return moment(date).format('D MMM')
        },
        setSeccion: function(nuevaSeccion){
            this.seccion = nuevaSeccion
        },
        clearSearch: function(){
            this.q = ''
        },
        textToClass: function(text, prefix = null){
            if ( prefix == null) {
                return Pcrn.textToClass(text)
            }
            return prefix + '-' + Pcrn.textToClass(text)
        },
    },
    computed: {
        actividadesFiltrados: function() {
            var listaFiltrada = this.actividades
            /*listaFiltrada = listaFiltrada.filter(actividad => actividad['fecha'].length > 0)
            listaFiltrada = listaFiltrada.filter(actividad => actividad['laboratorio_id'] != 20)*/
            if (this.q.length > 0) {
                var fieldsToSearch = ['lab_nombre_corto', 'descripcion']
                listaFiltrada = PmlSearcher.getFilteredResults(this.q, this.actividades, fieldsToSearch)
            }
            return listaFiltrada
        }
    },
    mounted(){
        this.getList()
        //this.ordenarActividades()
    }
}).mount('#cronogramaApp');
</script>