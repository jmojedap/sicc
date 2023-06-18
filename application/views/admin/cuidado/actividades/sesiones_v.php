<div id="actividadAsistentesApp">
    <div class="center_box_750">
        <form accept-charset="utf-8" method="POST" id="sesionForm" @submit.prevent="saveSesion">
            <fieldset v-bind:disabled="loading">
                <table class="table bg-white">
                    <thead>
                        <th>Módulo</th>
                        <th>Núm. Sesión</th>
                        <th width="45px"></th>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <select name="related_1" v-model="fields.related_1" class="form-control" required>
                                    <option v-for="optionModulo in arrModulo" v-bind:value="optionModulo.str_cod">
                                        {{ optionModulo.name }}</option>
                                </select>
                            </td>
                            <td>
                                <input name="integer_1" type="number" class="form-control" min="0" max="7"
                                    v-model="fields.integer_1">
                            </td>
                            <td>
                                <button class="btn btn-success">
                                    Guardar
                                </button>
                            </td>
                        </tr>
                        <tr v-for="(sesion, key) in sesiones">
                            <td>{{ moduloName(sesion.related_1) }}</td>
                            <td>{{ sesion.integer_1 }}</td>
                            <td>
                                <button class="a4" type="button" v-on:click="setCurrent(key)" data-toggle="modal"
                                    data-target="#delete_modal">
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
var actividadAsistentesApp = new Vue({
    el: '#actividadAsistentesApp',
    created: function() {
        this.getList()
    },
    data: {
        actividad: {
            id: <?= $row->id ?>
        },
        filters: {
            q: '',
        },
        user: {
            id: <?= $row->id ?>,
        },
        fields: {
            related_1: '',
            integer_1: 1,
        },
        arrModulo: <?= json_encode($arrModulo) ?>,
        sesionKey: -1,
        sesiones: [],
        loading: false,
    },
    methods: {
        getList: function() {
            this.loading = true
            var formValues = new FormData()
            formValues.append('table_id', 151) //tabla ehc_actividades
            formValues.append('type_id', 15112) //sesiones
            formValues.append('row_id', this.actividad.id)
            axios.post(URL_API + 'details/get_list/', formValues)
                .then(response => {
                    this.sesiones = response.data.list
                    this.loading = false
                })
                .catch(function(error) {
                    console.log(error)
                })
        },
        saveSesion: function(sesionKey) {
            this.loading = true
            
            var formValues = new FormData(document.getElementById('sesionForm'))
            formValues.append('type_id', 15112)
            formValues.append('table_id', 151) //ehc_actividades
            formValues.append('row_id', this.actividad.id)
            
            axios.post(URL_API + 'details/save/related_1/integer_1', formValues)
                .then(response => {
                    if (response.data.saved_id > 0) {
                        toastr['success']('Guardado')
                        this.getList()
                        //this.clearForm()
                    }
                    this.loading = false
                })
                .catch(function(error) { console.log(error) })
        },
        moduloName: function(value = '', field = 'name'){
            var moduloName = ''
            var item = this.arrModulo.find(row => row.cod == value)
            if ( item != undefined ) moduloName = item[field]
            return moduloName
        },
        setCurrent: function(key){
            this.sesionKey = key
        },
        clearForm: function(){
            this.fields = {
                related_1: '',
                integer_1: 1,
            }
        },
        delete_element: function() {
            var detailId = this.sesiones[this.sesionKey].id
            var rowId = this.sesiones[this.sesionKey].row_id
            axios.get(URL_API + 'details/delete/' + detailId + '/' + rowId)
                .then(response => {
                    if (response.data.qty_deleted > 0) {
                        this.getList()
                        toastr['Info']('Elemento eliminado')
                    } else {
                        toastr['error']('No se eliminó el elemento')
                    }

                })
                .catch(function(error) {
                    console.log(error)
                })
        },
    }
})
</script>