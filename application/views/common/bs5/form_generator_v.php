<?php
    $fields = $formSettings['fields'];
?>

<div id="formGeneratorApp">
    <div class="center_box_920">
        <div class="card">
            <div class="card-body">
                <form accept-charset="utf-8" method="POST" id="generatedForm" @submit.prevent="handleSubmit">
                    <fieldset v-bind:disabled="loading">
                        <input type="hidden" name="id" value="<?= $row->id ?>">

                        <?php foreach ( $fields as $field ) : ?>
                            <?php if ( $field['type'] == 'text' ) : ?>
                                <div class="mb-3 row">
                                    <label for="<?= $field['name'] ?>" class="col-md-4 col-form-label text-right"><?= $field['title'] ?></label>
                                    <div class="col-md-8">
                                        <input
                                            name="<?= $field['title'] ?>" type="text" class="form-control"
                                            title="<?= $field['title'] ?>" placeholder="<?= $field['title'] ?>"
                                            v-model="formValues.<?= $field['name']?>"
                                        >
                                    </div>
                                </div>
                            <?php elseif ( $field['type'] == 'textarea' ) : ?>
                                <div class="mb-3 row">
                                    <label for="<?= $field['name'] ?>" class="col-md-4 col-form-label text-right"><?= $field['title'] ?></label>
                                    <div class="col-md-8">
                                        <textarea
                                            name="<?= $field['title'] ?>" type="text" class="form-control"
                                            title="<?= $field['title'] ?>" placeholder="<?= $field['title'] ?>"
                                            v-model="formValues.<?= $field['name']?>" rows="<?= $field['rows'] ?>"
                                        ></textarea>
                                    </div>
                                </div>
                            <?php endif; ?>
                        <?php endforeach ?>


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
</div>

<script>
var formGeneratorApp = new Vue({
    el: '#formGeneratorApp',
    created: function(){
        //this.get_list()
    },
    data: {
        formValues: <?= json_encode($row) ?>,
        loading: false,
    },
    methods: {
        handleSubmit: function(){
            this.loading = true
            var formValues = new FormData(document.getElementById('generatedForm'))
            axios.post(URL_API + '<?= $formSettings['destination'] ?>', formValues)
            .then(response => {
                if ( response.data.saved_id > 0 ) {
                    toastr['success']('Guardado')
                } else {
                    toastr['warning']('No se guardaron los datos')
                }
                this.loading = false
            })
            .catch( function(error) {console.log(error)} )
        },
    }
})
</script>