<?php
    $arrLocalidad = $this->Item_model->arr_options('category_id = 121');
?>

<div id="editPost">
    <div class="card center_box_750">
        <div class="card-body">
            <form accept-charset="utf-8" method="POST" id="postForm" @submit.prevent="handleSubmit">
                <fieldset v-bind:disabled="loading">
                    <input type="hidden" name="id" value="<?= $row->id ?>">

                    <div class="mb-3 row">
                        <label for="post_name" class="col-md-4 col-form-label text-right">Nombre</label>
                        <div class="col-md-8">
                            <input
                                name="post_name" type="text" class="form-control"
                                required
                                title="Nombre" placeholder="Nombre"
                                v-model="fields.post_name"
                            >
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="text_1" class="col-md-4 col-form-label text-right">Dirección</label>
                        <div class="col-md-8">
                            <input
                                name="text_1" type="text" class="form-control"
                                required
                                title="Dirección" placeholder="Dirección"
                                v-model="fields.text_1"
                            >
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="related_1" class="col-md-4 col-form-label text-right">Localidad</label>
                        <div class="col-md-8">
                            <select name="related_1" v-model="fields.related_1" class="form-control" required>
                                <option v-for="optionLocalidad in arrLocalidad" v-bind:value="optionLocalidad.str_cod">{{ optionLocalidad.name }}</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="excerpt" class="col-md-4 col-form-label text-right">Notas</label>
                        <div class="col-md-8">
                            <textarea
                                name="excerpt" class="form-control" rows="3"
                                title="Descripción" placeholder="Descripción"
                                v-model="fields.excerpt"
                            ></textarea>
                        </div>
                    </div>
                    
                    <div class="mb-3 row">
                        <div class="col-md-8 offset-md-4">
                            <button class="btn btn-primary w120p" type="submit">Guardar</button>
                        </div>
                    </div>
                <fieldset>
            </form>
        </div>
    </div>
</div>

<script>
// Variables
//-----------------------------------------------------------------------------
var row = <?= json_encode($row) ?>;
row.related_1 = '0<?= $row->related_1 ?>';

// VueApp
//-----------------------------------------------------------------------------
var editPost = new Vue({
    el: '#editPost',
    data: {
        fields: row,
        loading: false,
        arrLocalidad: <?= json_encode($arrLocalidad) ?>,
    },
    methods: {
        handleSubmit: function(){
            this.loading = true
            var form_data = new FormData(document.getElementById('postForm'))
            axios.post(URL_API + 'posts/save/', form_data)
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