<div id="actividadAsistentesApp">
    <div class="row">
        <div class="col-md-4">
            <div class="card mb-2">
                <div class="card-body">
                    <form accept-charset="utf-8" method="POST" id="searchForm" @submit.prevent="searchUsers">
                        <input type="hidden" name="role" value="22">
                        <fieldset v-bind:disabled="loading">
                            <div class="mb-3 row">
                                <label for="q" class="col-md-4 col-form-label text-right">Buscar</label>
                                <div class="col-md-8">
                                    <input
                                        name="q" type="text" class="form-control"
                                        required
                                        title="Buscar" placeholder="Buscar estudiantes"
                                        v-model="filters.q"
                                    >
                                </div>
                            </div>
                        <fieldset>
                    </form>
                </div>
            </div>
            <table class="table bg-white">
                <thead>
                    <th>Estudiantes</th>
                    <th width="10px"></th>
                </thead>
                <tbody>
                    <tr v-for="(user, key) in users">
                        <td>{{ user.display_name }}</td>
                        <td>
                            <button class="btn btn-primary btn-sm" v-on:click="addStudent(key)">
                                <i class="fa fa-plus"></i>
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="col-md-8">
            <table class="table bg-white">
                <thead>
                    <th>Asistentes</th>
                    <th width="45px"></th>
                </thead>
                <tbody>
                    <tr v-for="(student, key) in students">
                        <td>{{ student.text_1 }}</td>
                        <td>
                            <button class="a4" type="button" v-on:click="setCurrent(key)" data-toggle="modal" data-target="#delete_modal">
                                <i class="fa fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="center_box_920">
        
    </div>
    <?php $this->load->view('common/modal_single_delete_v') ?>
</div>

<script>
// VueApp
//-----------------------------------------------------------------------------
var actividadAsistentesApp = new Vue({
    el: '#actividadAsistentesApp',
    created: function(){
        this.getList()
        this.searchUsers()
    },
    data: {
        actividad: {
            id: <?= $row->id ?>
        },
        filters: {
            q:'',
        },
        user: {
            id: <?= $row->id ?>,
        },
        fields: {
            id: 0,
            text_1: '',
        },
        users: [],
        students: [],
        userKey: -1,
        loading: false,
    },
    methods: {
        getList: function(){
            this.loading = true
            var formValues = new FormData()
            formValues.append('table_id',151)      //tabla ehc_actividades
            formValues.append('type_id',15110)   //asistentes
            formValues.append('row_id',this.actividad.id)
            axios.post(URL_API + 'details/get_list/', formValues)
            .then(response => {
                this.students = response.data.list
                this.loading = false
            })
            .catch( function(error) {console.log(error)} )
        },
        searchUsers: function(){
            this.loading = true
            var formValues = new FormData(document.getElementById('searchForm'))
            axios.post(URL_API + 'users/get/', formValues)
            .then(response => {
                this.users = response.data.list
                this.loading = false
            })
            .catch( function(error) {console.log(error)} )
        },
        addStudent: function(userKey){
            this.loading = true
            var formValues = new FormData()
            formValues.append('type_id', 15110) //asistentes
            formValues.append('table_id', 151)  //ehc_actividades
            formValues.append('row_id', this.actividad.id)
            formValues.append('related_1', this.users[userKey].id)
            formValues.append('text_1', this.users[userKey].display_name)
            formValues.append('text_2', this.users[userKey].document_number)
            axios.post(URL_API + 'details/save/related_1', formValues)
            .then(response => {
                if ( response.data.saved_id > 0 ) {
                    toastr['success']('Guardado')
                    this.getList()
                    this.clearForm()
                }
                this.loading = false
            })
            .catch( function(error) {console.log(error)} )
        },
        setCurrent: function(key){
            this.userKey = key
        },
        delete_element: function(){
            var detailId = this.students[this.userKey].id
            var rowId = this.students[this.userKey].row_id
            axios.get(URL_API + 'details/delete/' + detailId + '/' + rowId)
            .then(response => {
                if ( response.data.qty_deleted > 0 ) {
                    this.getList()
                    toastr['Info']('Elemento eliminado')
                } else {
                    toastr['error']('No se eliminó el elemento')
                }

            })
            .catch(function(error) { console.log(error) })
        },
    }
})
</script>