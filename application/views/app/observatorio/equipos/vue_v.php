<script>
// Variables
//-----------------------------------------------------------------------------
var temas = dogcc_opciones_valor.filter(item => item.variable == 'tema')


// VueApp
//-----------------------------------------------------------------------------
var funcionesApp = createApp({
    data() {
        return {
            funciones: dogcc_funciones,
            casos: dogcc_casos,
            currentFuncion: {},
            temas: temas,
            currentTema: 'gestion-investigaciones',
            loading: false,
            fields: {},
            displayUrl: false,
            fileId: '<?= $fileId ?>',
            gid: '<?= $gid ?>',
            q:'',
        }
    },
    methods: {
        getList: function(gid) {
            this.loading = true
            axios.get(URL_API + 'app/googlesheet_array/' + this.fileId + '/' + this.gid)
                .then(response => {
                    this.funciones = response.data
                    this.loading = false
                })
                .catch(function(error) { console.log(error) })
        },
        setCurrentFuncion: function(index){
            this.currentFuncion = this.funciones[index]
        },
    },
    computed: {
        funcionesFiltrados: function() {
            if (this.q.length > 0) {
                var funcionesFiltrados = this.funciones.filter((item) =>
                    item.nombre.toLowerCase().includes(this.q.toLowerCase()) ||
                    item.descripcion.toLowerCase().includes(this.q.toLowerCase()) ||
                    item.palabras_clave.toLowerCase().includes(this.q.toLowerCase())
                );
                return funcionesFiltrados
            }
            return this.funciones
        },
    },
    mounted() {
        //this.getList()
    }
}).mount('#funcionesApp')
</script>