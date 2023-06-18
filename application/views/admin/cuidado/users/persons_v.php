<div id="userPersonsApp">
    <div class="center_box_920">
        <form accept-charset="utf-8" method="POST" id="userPersonsForm" @submit.prevent="handleSubmit">
            <input type="hidden" name="id" v-bind:value="fields.id">
            <input type="hidden" name="user_id" value="<?= $row->id ?>">
            <input type="hidden" name="type_id" value="100021">
            <fieldset v-bind:disabled="loading">
                <table class="table bg-white">
                    <thead>
                        <th class="d-none">ID</th>
                        <th>Nombre persona</th>
                        <th>Parentesco</th>
                        <th>A cargo</th>
                        <th width="90px">
                            <button class="btn btn-sm w100p btn-light" type="button" v-on:click="clearForm">
                                <i class="fa fa-plus"></i>
                            </button>
                        </th>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="d-none">{{ fields.id }}</td>
                            <td>
                                <input
                                    name="text_1" type="text" class="form-control" required
                                    v-model="fields.text_1"
                                >
                            </td>
                            <td>
                                <input
                                    name="text_2" type="text" class="form-control" required
                                    v-model="fields.text_2"
                                >
                            </td>
                            <td>
                                <select name="integer_1" v-model="fields.integer_1" class="form-control" required>
                                    <option v-for="optionSiNo in arrSiNo" v-bind:value="optionSiNo.str_cod">{{ optionSiNo.name }}</option>
                                </select>
                            </td>
                            <td>
                                <button class="btn w100p" type="submit" v-bind:class="{'btn-success': fields.id == 0, 'btn-primary': fields.id > 0 }">
                                    <span v-show="fields.id == 0">Guardar</span>
                                    <span v-show="fields.id > 0">Actualizar</span>
                                </button>
                            </td>
                        </tr>
                        <tr v-for="(person, key) in persons" v-bind:class="{'table-primary': person.id == fields.id }">
                            <td class="d-none">{{ person.id }}</td>
                            <td>{{ person.text_1 }}</td>
                            <td>{{ person.text_2 }}</td>
                            <td class="text-center">
                                <i class="fa fa-check-circle text-primary" v-show="person.integer_1 == 1"></i>
                            </td>
                            <td>
                                <button class="a4" type="button" v-on:click="setCurrent(key)">
                                    <i class="fa fa-pencil-alt"></i>
                                </button>
                                <button class="a4" type="button" v-on:click="setCurrent(key)" data-toggle="modal" data-target="#delete_modal">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            <fieldset>
        </form>
    </div>
    <?php $this->load->view('common/modal_single_delete_v') ?>
</div>

<script>
// VueApp
//-----------------------------------------------------------------------------
var userPersonsApp = new Vue({
    el: '#userPersonsApp',
    created: function(){
        this.getList()
    },
    data: {
        user: {
            id: <?= $row->id ?>,
        },
        fields: {
            id: 0,
            text_1: '',
        },
        persons: [],
        arrSiNo: <?= json_encode($arrSiNo) ?>,
        loading: false,
    },
    methods: {
        getList: function(){
            this.loading = true
            var formValues = new FormData()
            formValues.append('condition', 'user_id = ' + this.user.id + ' AND type_id = 100021')
            axios.post(URL_API + 'users/get_meta/', formValues)
            .then(response => {
                this.persons = response.data.list
                this.loading = false
            })
            .catch( function(error) {console.log(error)} )
        },
        handleSubmit: function(){
            this.loading = true
            var formValues = new FormData(document.getElementById('userPersonsForm'))
            axios.post(URL_API + 'users/save_meta/', formValues)
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
            this.fields = this.persons[key]
            this.fields.integer_1 = '0' + this.persons[key].integer_1
        },
        delete_element: function(){
            axios.get(URL_API + 'users/delete_meta/' + this.user.id + '/' + this.fields.id)
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
        clearForm: function(){
            this.fields = {
                id: 0,
                text_1: '',
            }
        },
        siNoName: function(value = '', field = 'name'){
            var siNoName = ''
            var item = this.arrSiNo.find(row => row.cod == value)
            if ( item != undefined ) siNoName = item[field]
            return siNoName
        },
    }
})
</script>