<script>
// Variables
//-----------------------------------------------------------------------------
let arrInvestigaciones = <?= json_encode($investigaciones) ?>;
let investigacionStart = arrInvestigaciones[0];

// VueApp
//-----------------------------------------------------------------------------
var investigacionesApp = createApp({
    data() {
        return {
            page: 'explore',
            seccion:'listado',
            currentSubseccion:'info',
            investigaciones: arrInvestigaciones,
            productos: <?= json_encode($productos) ?>,
            hallazgos: <?= json_encode($hallazgos) ?>,
            notas: <?= json_encode($notas) ?>,
            loading: false,
            year: <?= $year ?>,
            currentInvestigacion: investigacionStart,
            displayUrl: false,
            fileId: '<?= $fileId ?>',
            gid: '<?= $gid ?>',
            tablas: <?= json_encode($tablas) ?>,
            subsecciones: [
                {name:'info', title:'Info'},
                {name:'actividades', title:'Actividades'},
            ],
            q: '',
            grupos: [
                'Proyectos estratégicos',
                'Encuesta Bienal y Festivales al Parque',
                'Solicitudes Sector',
            ],
            estados: [
                '1 Sin iniciar',
                '2 En ejecución',
                '3 En Pausa',
                '4 Entregada',
                '5 Finalizada',
                '6 No aplica',
                '9 Cancelada'
            ],
            entidades: [
                'Sector',
                'SCRD',
                'Canal Capital',
                'IDARTES',
                'IDRD',
                'FUGA',
                'IDPC',
            ],
            lineasInvestigacion: [
                'Cultura Ciudadana',
                'Sector Cultura',
            ],
            categorias: [
                'Meta PDD',
                'Convenio',
            ],
            filters: {
                grupo_1: '',
                linea_investigacion: '',
                estado: '',
                entidad: '',
                categoria: '',
            },
        }
    },
    methods: {
        ago: function(date){
            if (!date) return ''
            return moment(date, 'YYYY-MM-DD HH:mm:ss').fromNow()            
        },
        dateFormat: function(date, format='D MMM YYYY'){
            if (!date) return ''
            return moment(date).format(format)
        },
        setSeccion: function(nuevaSeccion){
            this.seccion = nuevaSeccion
        },
        updateList: function(tabla){
            this.loading = true
            axios.get('<?= base_url('api/tools/googlesheet_save_json/') ?>' + this.fileId + '/' + tabla.gid + '/pai_' + this.year + '/' + tabla.nombre)
            .then(response => {
                if ( response.data.status == 1 ) {
                    toastr['success']('Datos actualizados, presione F5')
                }
                this.loading = false
            })
            .catch(function(error) { console.log(error) })
        },
        clearSearch: function(){
            this.q = ''
        },
        setCurrent: function(investigacion, nuevaSubseccion = 'info'){
            this.seccion = 'detalles'
            this.currentSubseccion = nuevaSubseccion
            this.currentInvestigacion = investigacion;
        },
        textToClass: function(text, prefix = null){
            if ( prefix == null) {
                return Pcrn.textToClass(text)
            }
            return prefix + '-' + Pcrn.textToClass(text)
        },
        getProductoClass: function(tipoProducto){
            var productoClass = 'fa-solid fa-file producto-general'
            if ( tipoProducto == 'Informe final' ) productoClass = 'fa-solid fa-file-lines producto-pdf'
            if ( tipoProducto == 'Presentación' ) productoClass = 'fa-solid fa-display producto-presentacion'
            if ( tipoProducto == 'Visualización/Infografía' ) productoClass = 'fa-solid fa-chart-simple producto-dataviz'
            if ( tipoProducto == 'Visualización' ) productoClass = 'fa-solid fa-chart-simple producto-dataviz'
            if ( tipoProducto == 'Geovisor' ) productoClass = 'fa-solid fa-chart-simple producto-dataviz'
            if ( tipoProducto == 'Base de datos' ) productoClass = 'fa-solid fa-table producto-db'
            if ( tipoProducto == 'Informe cuantitativo' ) productoClass = 'fa-solid fa-file-lines producto-cuantitativo'
            if ( tipoProducto == 'Audiovisual' ) productoClass = 'fa-solid fa-circle-play producto-audiovisual'
            return productoClass
        },
        startApp: function(){
            var investigacion = this.investigaciones.find(investigacion => investigacion['ID'] == 95)
            this.setCurrent(investigacion, 'info')
        },
        tituloProducto: function(producto){
            var titulo = producto['Tipo de producto']
            if ( producto['Título'] != '' ) {
                titulo = producto['Título']
            }
            return titulo
        },
        avanceClass: function(avance){
            //Quitar el signo de porcentaje
            var avanceInt = parseInt(avance.replace('%', ''))
            if ( avanceInt < 25 ) return 'bg-danger'
            if ( avanceInt < 50 ) return 'bg-warning'
            if ( avanceInt < 75 ) return 'bg-info'
            if ( avanceInt < 90 ) return 'bg-primary'
            return 'bg-success'
        },
    },
    computed: {
        investigacionesFiltrados: function() {
            var listaFiltrada = this.investigaciones
            listaFiltrada = listaFiltrada.filter(investigacion => investigacion['Estado'] != '9 Cancelada')
            if ( this.filters.estado != '' ) {
                listaFiltrada = listaFiltrada.filter(elemento => elemento['Estado'] == this.filters.estado)
            }
            if ( this.filters.linea_investigacion != '' ) {
                listaFiltrada = listaFiltrada.filter(elemento => elemento['Línea de investigación'] == this.filters.linea_investigacion)
            }
            if ( this.filters.entidad != '' ) {
                listaFiltrada = listaFiltrada.filter(elemento => elemento['Entidad'] == this.filters.entidad)
            }
            if ( this.filters.categoria != '' ) {
                listaFiltrada = listaFiltrada.filter(elemento => elemento['Categoría'] == this.filters.categoria)
            }
            if (this.q.length > 0) {
                var fieldsToSearch = ['Nombre clave', 'Título', 'Palabras clave', 'Dirección/Dependencia',
                    'grupo_1'
                ]
                listaFiltrada = PmlSearcher.getFilteredResults(this.q, this.investigaciones, fieldsToSearch)
            }
            return listaFiltrada
        },
        productosFiltrados: function() {
            var listaFiltrada = this.productos
            listaFiltrada = listaFiltrada.filter(producto => producto['ID Investigación'] == this.currentInvestigacion['ID'])
            listaFiltrada = listaFiltrada.filter(producto => producto['Incluir en ficha'] == 'Sí')
            /*if ( this.filters.grupo_1 != '' ) {
                listaFiltrada = listaFiltrada.filter(elemento => elemento['grupo_1'] == this.filters.grupo_1)
            }
            if (this.q.length > 0) {
                var fieldsToSearch = ['Nombre clave', 'Título', 'Palabras clave', 'Dirección/Dependencia',
                    'grupo_1'
                ]
                listaFiltrada = PmlSearcher.getFilteredResults(this.q, this.investigaciones, fieldsToSearch)
            }*/
            return listaFiltrada
        }
    },
    mounted(){
        //this.startApp()
        //this.setCurrent(this.investigaciones[1], 'info')
    }
}).mount('#investigacionesApp');
</script>