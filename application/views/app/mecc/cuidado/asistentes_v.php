<div id="asistentesApp">
    <form accept-charset="utf-8" method="POST" id="asistentesForm" @submit.prevent="handleSubmit">
        <fieldset v-bind:disabled="loading">
            <input type="hidden" name="accion_id" value="<?= $row->id ?>">
            <input type="hidden" name="tipo_detalle" value="110">
            <div class="mb-3 row">
                <label for="cod_detalle" class="col-md-4 col-form-label text-end">Número de documento</label>
                <div class="col-md-8">
                    <input
                        name="cod_detalle" type="text" class="form-control"
                        required
                        title="Número de documento" placeholder="Número de documento"
                        v-model="fields.cod_detalle"
                    >
                </div>
            </div>

            <div class="mb-3 row">
                <div class="col-md-8 offset-md-4">
                    <button class="btn btn-primary w120p" type="submit">Agregar</button>
                </div>
            </div>
        <fieldset>
    </form>
    <table class="table bg-white">
        <thead>
            <th width="20">({{ asistentes.length }})</th>
            <th>Documentos</th>
            <th width="20"></th>
        </thead>
        <tbody>
            <tr v-for="(asistente, key) in asistentes">
                <td>{{ key + 1 }}</td>
                <td>{{ asistente.cod_detalle }}</td>
                <td>
                    <button class="a4" v-on:click="deleteDetail(asistente.id)" type="button">
                        <i class="fa fa-trash"></i>
                    </button>
                </td>
            </tr>
        </tbody>
    </table>
</div>

<script>
var asistentesApp = createApp({
    data(){
        return{
            row: <?= json_encode($row) ?>,
            fields: {
                cod_detalle: '16073346'
            },
            filters:{
                'prnt':<?= $row->id ?>,
            },
            loading: false,
            asistentes: [],
        }
    },
    methods: {
        handleSubmit: function(){
            this.loading = true
            var formValues = new FormData(document.getElementById('asistentesForm'))
            axios.post(URL_API + 'acciones/save_detail/', formValues)
            .then(response => {
                if ( response.data.saved_id > 0 ) {
                    toastr['success']('Documento asignado')
                    this.getDetails()
                    this.fields.cod_detalle = ''
                }
                this.loading = false
            })
            .catch( function(error) {console.log(error)} )
        },
        getDetails: function(){
            this.loading = true
            var formValues = new FormData()
            formValues.append('prnt', this.row.id)  //ID Acción
            formValues.append('type', 110)  //Tipo detalle de la acción
            axios.post(URL_API + 'acciones/get_details/', formValues)
            .then(response => {
                this.asistentes = response.data.details
                this.loading = false
            })
            .catch( function(error) {console.log(error)} )
        },
        deleteDetail: function(detailId){
            axios.get(URL_API + 'acciones/delete_detail/' + this.row.id + '/' + detailId)
            .then(response => {
                if ( response.data.qty_deleted > 0 ) {
                    toastr['info']('Registro de asistencia eliminado')
                    this.getDetails()
                }
            })
            .catch(function(error) { console.log(error) })
        },
    },
    mounted(){
        this.getDetails()
    }
}).mount('#asistentesApp')
</script>