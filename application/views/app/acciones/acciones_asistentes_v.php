
<div id="accionesAsistentesApp">
    <p>
        Registros de asistencia: <strong class="text-primary">{{ asistentes.length }}</strong>
    </p>
    <table class="table bg-white my-2">
        <thead>
            <th>accion_id</th>
            <th>num_documento</th>
        </thead>
        <tbody>
            <tr v-for="(asistente, key) in asistentes">
                <td>{{ asistente.accion_id }}</td>
                <td>{{ asistente.cod_detalle }}</td>
            </tr>
        </tbody>
    </table>
</div>

<script>
var accionesAsistentesApp = createApp({
    data(){
        return{
            loading: false,
            asistentes: [],
        }
    },
    methods: {
        getDetails: function(){
            this.loading = true
            var formValues = new FormData()
            formValues.append('type', 110)  //Tipo detalle de la acción
            axios.post(URL_API + 'acciones/get_details/', formValues)
            .then(response => {
                this.asistentes = response.data.details
                this.loading = false
            })
            .catch( function(error) {console.log(error)} )
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
}).mount('#accionesAsistentesApp')
</script>