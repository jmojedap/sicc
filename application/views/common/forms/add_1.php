<div id="addFormApp">
    <div class="center_box_920">
        <div class="card" v-show="savedId == 0">
            <div class="card-body">
                <form accept-charset="utf-8" method="POST" id="addRowForm" @submit.prevent="handleSubmit">
                    <fieldset v-bind:disabled="loading">
                        <?php if ( count($requiredFields) > 10 ) : ?>
                        <div class="mb-3 row">
                            <div class="col-md-8 offset-md-4">
                                <button class="btn btn-primary w120p" type="submit">Guardar</button>
                            </div>
                        </div>
                        <?php endif; ?>

                        <?php foreach ( $fields as $field ) : ?>
                            <?php if ( in_array($field->name, $requiredFields) ) : ?>
                            <?php
                                $fieldName = $field->name;

                                $title = ucfirst(str_replace('_',' ',$fieldName));
                                $numRows = 1;
                                if ( $field->max_length > 140 && in_array($field->type, ['varchar','text','datetime']) ) {
                                    $numRows = intval(strlen($field->max_length) / 75) + 1;
                                }

                                $required = in_array($fieldName, $requiredFields);
                            ?>
                            <div class="mb-3 row">
                                <label for="<?= $fieldName ?>" class="col-md-4 col-form-label text-right">
                                    <?= $title ?>
                                </label>
                                <div class="col-md-8">
                                    <!-- TEXTO -->
                                    <?php if ( in_array($field->type, ['varchar','text','datetime']) ): ?>
                                        <!-- INPUT TEXT -->
                                        <?php if ( $numRows == 1 ) : ?>
                                            <input name="<?= $fieldName ?>" type="text" class="form-control" required>
                                        <!-- TEXTAREA -->
                                        <?php elseif ( $numRows > 1 ) : ?>
                                            <textarea name="<?= $fieldName ?>" class="form-control"
                                                rows="<?= $numRows ?>" required></textarea>
                                        <?php endif; ?>
                                    <!-- ENTEROS -->
                                    <?php elseif ( in_array($field->type, ['int','smallint','tinyint','mediumint'])): ?>
                                        <input name="<?= $fieldName ?>" type="number" class="form-control" required>
                                    <!-- DATE -->
                                    <?php elseif ( in_array($field->type, ['date'])): ?>
                                        <input name="<?= $fieldName ?>" type="date" class="form-control" required>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <?php endif; ?>
                        <?php endforeach ?>

                        <div class="mb-3 row">
                            <div class="col-md-8 offset-md-4">
                                <button class="btn btn-success w120p" type="submit">Crear</button>
                            </div>
                        </div>

                        <fieldset>
                </form>
            </div>
        </div>
        <div class="card" v-show="savedId > 0">
            <div class="card-body">
                <h4 class="text-center"><i class="fa fa-check text-success"></i> Registro creado</h4>
                <div class="d-flex justify-content-around">
                    <a v-bind:href="`<?= $editionLink ?>` + savedId" class="btn btn-primary w120p">Editar</a>
                    <button v-on:click="resetForm" class="btn btn-light w120p">Crear otro</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
var addFormApp = new Vue({
    el: '#addFormApp',
    data: {
        loading: false,
        savedId: 0,
    },
    methods: {
        handleSubmit: function() {
            this.loading = true
            var formValues = new FormData(document.getElementById('addRowForm'))
            axios.post('<?= $formDestination ?>', formValues)
                .then(response => {
                    if (response.data.saved_id > 0) {
                        this.savedId = response.data.saved_id
                        toastr['success']('Guardado')
                    } else {
                        toastr['warning']('No se guardaron los datos')
                    }
                    this.loading = false
                })
                .catch(function(error) {
                    this.loading = false
                    toastr['error']('Ocurrió un error al crear el registro')
                    console.log(error)
                })
        },
        resetForm: function(){
            this.savedId = 0
            document.getElementById('addRowForm').reset()
        },

    }
})
</script>