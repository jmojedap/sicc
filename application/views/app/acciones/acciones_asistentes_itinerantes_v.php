
<div id="accionesAsistentesItinerantesApp">
    <p>
        Registros de asistencia: <strong class="text-primary">{{ asistentes.length }}</strong>
    </p>
    <table class="table bg-white my-2">
        <thead>
            <th>accion_id</th>
            <th>num_documento</th>
            <th>nombre</th>
            <th>identidad_genero</th>
            <th>grupo_poblacion</th>
            <th>edad</th>
            <th>telefono</th>
        </thead>
        <tbody>
            <tr v-for="(asistente, key) in asistentes">
                <td>{{ asistente.accion_id }}</td>
                <td>{{ asistente.cod_detalle }}</td>
                <td>{{ asistente.nombre }}</td>
                <td>{{ identidadGeneroName(asistente.relacionado_2) }}</td>
                <td>{{ grupoPoblacionName(asistente.relacionado_1) }}</td>
                <td>{{ asistente.cantidad }}</td>
                <td>{{ asistente.descripcion }}</td>
            </tr>
        </tbody>
    </table>
</div>

<script>
var accionesAsistentesItinerantesApp = createApp({
    data(){
        return{
            loading: false,
            asistentes: [],
            arrGrupoPoblacion: <?= json_encode($arrGrupoPoblacion) ?>,
            arrIdentidadGenero: <?= json_encode($arrIdentidadGenero) ?>,
            arrTipoDocumento: <?= json_encode($arrTipoDocumento) ?>,
        }
    },
    methods: {
        getDetails: function(){
            this.loading = true
            var formValues = new FormData()
            formValues.append('type', 140)  //Tipo detalle de la acción
            axios.post(URL_API + 'acciones/get_details/', formValues)
            .then(response => {
                this.asistentes = response.data.details
                this.loading = false
            })
            .catch( function(error) {console.log(error)} )
        },
        grupoPoblacionName: function(value = '', field = 'name'){
            var grupoPoblacionName = ''
            var item = this.arrGrupoPoblacion.find(row => row.cod == value)
            if ( item != undefined ) grupoPoblacionName = item[field]
            return grupoPoblacionName
        },
        identidadGeneroName: function(value = '', field = 'name'){
            var identidadGeneroName = ''
            var item = this.arrIdentidadGenero.find(row => row.cod == value)
            if ( item != undefined ) identidadGeneroName = item[field]
            return identidadGeneroName
        },
        // Formato y valores
        //-----------------------------------------------------------------------------
        ago: function(date){
            if (!date) return ''
            return moment(date, 'YYYY-MM-DD HH:mm:ss').fromNow()
        },
        dateFormat: function(date){
            if (!date) return ''
            return moment(date).format('D MMM YYYY')
        },
    },
    mounted(){
        this.getDetails()
    }
}).mount('#accionesAsistentesItinerantesApp')
</script>