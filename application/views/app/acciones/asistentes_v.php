<div id="asistentesApp">
    <p class="text-center">{{ dateFormat(row.fecha) }}&middot; {{ ago(row.fecha) }}</p>
    <form accept-charset="utf-8" method="POST" id="getUsersForm" @submit.prevent="getUsers">
        <fieldset v-bind:disabled="loading">
            <div class="center_box_320">
                <div class="input-group">
                    <input name="fe1" type="text" class="form-control" required title="Número de documento"
                        placeholder="Número de documento" v-model="filters.fe1">
                    <button class="btn btn-light" type="submit"><i class="fa fa-search"></i></button>
                </div>
            </div>
            <fieldset>
    </form>

    <div class="center_box_750 my-2">
        <div class="list-group" v-if="searchStatus > 0">
            <div>
                <p class="text-center"> <i class="fa fa-check-circle text-success"></i> {{ users.length }} resultados</p>
                <button type="button" class="list-group-item list-group-item-action" v-for="(user,key) in users" v-on:click="addUser(key)">
                    <i class="fa fa-plus me-2 text-primary"></i>{{ user.display_name }} ({{ user.document_number }})
                </button>
            </div>
        </div>
        <div v-show="searchStatus == 0" class="alert alert-info text-center">
            {{ users.length }} resultados para el documento <strong>{{ filters.fe1 }}</strong><br>
            <a href="<?= URL_APP . "acciones/registro_usuario" ?>">
                Registrar participante
            </a>
        </div>
    </div>

    <table class="table bg-white my-2">
        <thead>
            <th width="20">({{ asistentes.length }})</th>
            <th>Doc</th>
            <th>Nombre</th>
            <th width="20"></th>
        </thead>
        <tbody>
            <tr v-for="(asistente, key) in asistentes">
                <td>{{ key + 1 }}</td>
                <td>{{ asistente.cod_detalle }}</td>
                <td>{{ asistente.nombre }}</td>
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
    data() {
        return {
            row: <?= json_encode($row) ?>,
            fields: {
                cod_detalle: ''
            },
            filters: {
                q: '',
                'prnt': <?= $row->id ?>,
            },
            loading: false,
            asistentes: [],
            searchStatus: -1,
            users: [],
            currentUser:{},
        }
    },
    methods: {
        addUser: function(userKey) {
            this.currentUser = this.users[userKey]
            this.loading = true
            //var formValues = new FormData(document.getElementById('asistentesForm'))
            formValues = new FormData()
            formValues.append('accion_id', this.row.id)
            formValues.append('tipo_detalle', 110)
            formValues.append('cod_detalle',this.currentUser.document_number)
            formValues.append('nombre',this.currentUser.display_name)
            axios.post(URL_API + 'acciones/save_detail/', formValues)
                .then(response => {
                    if (response.data.saved_id > 0) {
                        toastr['success']('Documento asignado')
                        this.getDetails()
                        this.fields.cod_detalle = ''
                        this.users = []
                        this.searchStatus = -1
                        this.filters.fe1 = ''
                    }
                    this.loading = false
                })
                .catch(function(error) {
                    console.log(error)
                })
        },
        getDetails: function() {
            this.loading = true
            var formValues = new FormData()
            formValues.append('prnt', this.row.id) //ID Acción
            formValues.append('type', 110) //Tipo detalle de la acción
            axios.post(URL_API + 'acciones/get_details/', formValues)
                .then(response => {
                    this.asistentes = response.data.details
                    this.loading = false
                })
                .catch(function(error) {
                    console.log(error)
                })
        },
        deleteDetail: function(detailId) {
            axios.get(URL_API + 'acciones/delete_detail/' + this.row.id + '/' + detailId)
                .then(response => {
                    if (response.data.qty_deleted > 0) {
                        toastr['info']('Registro de asistencia eliminado')
                        this.getDetails()
                    }
                })
                .catch(function(error) { console.log(error) })
        },
        getUsers: function(){
            this.loading = true
            var formValues = new FormData(document.getElementById('getUsersForm'))
            axios.post(URL_API + 'users/get/', formValues)
            .then(response => {
                this.searchStatus = response.data.search_num_rows
                this.users = response.data.list
                this.loading = false
            })
            .catch( function(error) {console.log(error)} )
        },
        // Formato y valores
        //-----------------------------------------------------------------------------
        ago: function(date) {
            if (!date) return ''
            return moment(date, 'YYYY-MM-DD HH:mm:ss').fromNow()
        },
        dateFormat: function(date) {
            if (!date) return ''
            return moment(date).format('D MMM YYYY')
        },
    },
    mounted() {
        this.getDetails()
    }
}).mount('#asistentesApp')
</script>