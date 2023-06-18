<div id="editFormApp">
    <div class="center_box_920">
        <div class="card">
            <div class="card-body">
                <form accept-charset="utf-8" method="POST" id="generatedForm" @submit.prevent="handleSubmit">
                    <fieldset v-bind:disabled="loading">
                        <input type="hidden" name="id" value="<?= $row->id ?>">
                        <div class="mb-3 row">
                            <div class="col-md-8 offset-md-4">
                                <button class="btn btn-primary w120p" type="submit">Guardar</button>
                            </div>
                        </div>

                        <?php foreach ( $fields as $field ) : ?>
                            <?php if ( ! in_array($field->name, $hiddenFields) ) : ?>
                                <?php
                                    $fieldName = $field->name;
    
                                    $title = ucfirst(str_replace('_',' ',$fieldName));
                                    $numRows = 1;
                                    if ( strlen($row->$fieldName) > 50 ) {
                                        $numRows = intval(strlen($row->$fieldName) / 75) + 1;
                                    }
    
                                    $required = in_array($fieldName, $requiredFields);
                                ?>
                                <div class="mb-3 row">
                                    <label for="<?= $fieldName ?>" class="col-md-4 col-form-label text-right">
                                        <?= $title ?>
                                        <?php if ( $required ) : ?><span class="text-danger">*</span><?php endif; ?>
                                    </label>
                                    <div class="col-md-8">
                                        <!-- TEXTO -->
                                        <?php if ( in_array($field->type, ['varchar','text','datetime'])): ?>
                                            <?php if ( $numRows == 1 ) : ?>
                                                <input
                                                    name="<?= $fieldName ?>" type="text" class="form-control"
                                                    <?php if ($required) echo 'required'; ?>
                                                    v-model="formValues.<?= $fieldName ?>"
                                                >
                                            <!-- TEXTAREA -->
                                            <?php elseif ( $numRows > 1 ) : ?>
                                                <textarea
                                                    name="<?= $fieldName ?>" class="form-control" <?php if ($required) echo 'required'; ?>
                                                    rows="<?= $numRows ?>"
                                                    v-model="formValues.<?= $fieldName ?>"
                                                ></textarea>
                                            <?php endif; ?>
                                        <!-- ENTEROS -->
                                        <?php elseif ( in_array($field->type, ['int','smallint','tinyint','mediumint'])): ?>
                                            <input
                                                name="<?= $fieldName ?>" type="number" class="form-control"
                                                <?php if ($required) echo 'required'; ?>
                                                v-model="formValues.<?= $fieldName ?>"
                                            >
                                        <!-- DATE -->
                                        <?php elseif ( in_array($field->type, ['date'])): ?>
                                            <input
                                                name="<?= $fieldName ?>" type="date" class="form-control"
                                                <?php if ($required) echo 'required'; ?>
                                                v-model="formValues.<?= $fieldName ?>"
                                            >
                                        <?php endif; ?>
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
var editFormApp = new Vue({
    el: '#editFormApp',
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
            axios.post('<?= $formDestination ?>', formValues)
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