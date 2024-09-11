<script>
// VueApp
//-----------------------------------------------------------------------------
var investigacionesApp = createApp({
    data() {
        return {
            seccion:'listado',
            currentSubseccion:'info',
            investigaciones: <?= json_encode($investigaciones) ?>,
            loading: false,
            fields: {},
            currentInvestigacion: {},
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
            filters: {
                grupo_1: '' 
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
            axios.get('<?= base_url() ?>api/tools/googlesheet_save_json/' + this.fileId + '/' + tabla.gid + '/barrios_vivos/' + tabla.nombre)
            .then(response => {
                if ( response.data.status == 1 ) {
                    toastr['success']('Datos actualizados, presione F5')
                    //this.investigaciones = response.data.array
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
        textToClass: function(text){
            return Pcrn.textToClass(text)
        },
    },
    computed: {
        investigacionesFiltrados: function() {
            var listaFiltrada = this.investigaciones
            listaFiltrada = listaFiltrada.filter(investigacion => investigacion['Estado'] != '7 Cancelada')
            if ( this.filters.grupo_1 != '' ) {
                listaFiltrada = listaFiltrada.filter(elemento => elemento['grupo_1'] == this.filters.grupo_1)
            }
            if (this.q.length > 0) {
                var fieldsToSearch = ['Nombre clave', 'Título', 'Palabras clave', 'Dirección/Dependencia',
                    'grupo_1'
                ]
                listaFiltrada = PmlSearcher.getFilteredResults(this.q, this.investigaciones, fieldsToSearch)
            }
            return listaFiltrada
        }
    },
    mounted(){
        //this.setCurrent(this.investigaciones[1], 'info')
    }
}).mount('#investigacionesApp');
</script>