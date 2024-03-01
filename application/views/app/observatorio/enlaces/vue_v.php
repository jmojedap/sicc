<script>
var linksApp = createApp({
    data() {
        return {
            enlaces: dogcc_links,
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
                    this.enlaces = response.data
                    this.loading = false
                })
                .catch(function(error) { console.log(error) })
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
        enlacesFiltrados: function() {
            if (this.q.length > 0) {
                var enlacesFiltrados = this.enlaces.filter((item) =>
                    item.nombre.toLowerCase().includes(this.q.toLowerCase()) ||
                    item.descripcion.toLowerCase().includes(this.q.toLowerCase()) ||
                    item.palabras_clave.toLowerCase().includes(this.q.toLowerCase())
                );
                return enlacesFiltrados
            }
            return this.enlaces
        },
    },
    mounted() {
        this.getList()
    }
}).mount('#enlacesApp')
</script>