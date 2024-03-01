<script>
var visualizacionesDatosApp = createApp({
    data() {
        return {
            tableros: dogcc_dataviz,
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
                    this.tableros = response.data
                    this.loading = false
                })
                .catch(function(error) {
                    console.log(error)
                })
        },
        filtrarTableros: function() {
            if (this.q.length > 0) {
                this.section = 'componentes'
            } else {
                this.section = 'modulos'
            }
        },
    },
    computed: {
        tablerosFiltrados: function() {
            if (this.q.length > 0) {
                var tablerosFiltrados = this.tableros.filter((item) =>
                    item.nombre.toLowerCase().includes(this.q.toLowerCase()) ||
                    item.descripcion.toLowerCase().includes(this.q.toLowerCase()) ||
                    item.palabras_clave.toLowerCase().includes(this.q.toLowerCase())
                );
                return tablerosFiltrados
            }
            return this.tableros
        },
    },
    mounted() {
        this.getList()
    }
}).mount('#visualizacionesDatosApp')
</script>