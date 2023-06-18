<div id="edit_variables">
    <div class="center_box_750">
        <form accept-charset="utf-8" method="POST" id="pregunta_form" @submit.prevent="send_form">
            <div class="card">
                <div class="card-body">
                    <fieldset v-bind:disabled="loading">
                        <input type="hidden" name="id" value="<?= $row->id ?>">
                        <div class="mb-3 row">
                            <label for="codigo" class="col-md-4 col-form-label text-right">Código pregunta *</label>
                            <div class="col-md-8">
                                <input
                                    name="codigo" type="text" class="form-control"
                                    required
                                    title="Código pregunta" placeholder="Código pregunta"
                                    v-model="form_values.codigo"
                                >
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label for="codigo_grupo" class="col-md-4 col-form-label text-right">Código grupo</label>
                            <div class="col-md-8">
                                <input
                                    name="codigo_grupo" type="text" class="form-control"
                                    title="Código grupo"
                                    v-model="form_values.codigo_grupo"
                                >
                            </div>
                        </div>
                        
                        <div class="mb-3 row">
                            <label for="name" class="col-md-4 col-form-label text-right">Nombre columna</label>
                            <div class="col-md-8">
                                <input
                                    name="name" type="text" class="form-control"
                                    required
                                    title="Nombre columna" placeholder="Nombre columna"
                                    v-model="form_values.name"
                                >
                                <small class="form-text text-muted">Minúsculas, sin acentos ni ñ, Cambiar espacios por guión bajo.</small>
                            </div>
                        </div>

                        
                        
                        <div class="mb-3 row">
                            <div class="col-md-8 offset-md-4">
                                <button class="btn btn-primary w120p" type="submit">Guardar</button>
                            </div>
                        </div>
                    <fieldset>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
// Variables
//-----------------------------------------------------------------------------
var row = <?= json_encode($row) ?>;
row['cat_1'] = '0<?= $row->cat_1 ?>';
row['published_at'] = '<?= substr($row->published_at,0,10) ?>';

// VueApp
//-----------------------------------------------------------------------------
var edit_variables = new Vue({
    el: '#edit_variables',
    data: {
        form_values: row,
        loading: false,
        options_cat_1: <?= json_encode($options_cat_1) ?>,
    },
    methods: {
        send_form: function(){
            this.loading = true
            var form_data = new FormData(document.getElementById('edit_form'))
            axios.variables(URL_API + 'variabless/save/', form_data)
            .then(response => {
                if ( response.data.saved_id > 0 ) {
                    toastr['success']('Guardado')
                }
                this.loading = false
            })
            .catch( function(error) {console.log(error)} )
        },
    }
})
</script>