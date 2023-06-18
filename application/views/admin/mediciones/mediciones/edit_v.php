<div id="editMedicion">
    <form accept-charset="utf-8" method="POST" id="edit_form" @submit.prevent="send_form">
        <input type="hidden" name="id" value="<?= $row->id ?>">
        <fieldset v-bind:disabled="loading">
            <div class="mb-2 text-right">
                <button class="btn btn-primary w150p" type="submit">
                    Guardar
                </button>
            </div>
            <div class="row">
                <div class="col-md-5">
                    <div class="card">
                        <div class="card-body">

                            
                            <div class="mb-3">
                                <label for="nombre_medicion">Nombre
                                    medición</label>
                                <input name="nombre_medicion" type="text" class="form-control" required
                                    title="Nombre medición" placeholder="Nombre medición"
                                    v-model="fields.nombre_medicion">
                            </div>


                            <div class="mb-3">
                                <label for="contenido">Descripción</label>
                                <textarea name="descripcion" class="form-control" rows="4" maxlength="280"
                                    title="Descripción" placeholder="Descripción"
                                    v-model="fields.descripcion"></textarea>
                            </div>

                            <div class="mb-3">
                                <label for="palabras_clave">Palabras clave</label>
                                <textarea
                                    name="palabras_clave" type="text" class="form-control" rows="2"
                                    title="Palabras clave" placeholder="Palabras clave"
                                    v-model="fields.palabras_clave"
                                ></textarea>
                            </div>

                            <div class="mb-3">
                                <label for="realizada_por">Realizada por</label>
                                <input
                                    name="realizada_por" type="text" class="form-control"
                                    title="Realizada por" placeholder="Equipo o empresa que aplicó la encuesta"
                                    v-model="fields.realizada_por"
                                >
                            </div>

                            <div class="mb-3">
                                <label for="notas">Notas privadas</label>
                                <textarea
                                    name="notas" class="form-control" rows="3" v-model="fields.notas"></textarea>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="col-md-7">
                    <div class="card">
                        <div class="card-body">
                            <div class="mb-3 row">
                                <label for="codigo" class="col-md-4 col-form-label text-right">Código medición</label>
                                <div class="col-md-8">
                                    <input name="codigo" type="text" class="form-control" required
                                        title="Código medición" placeholder="Código medición"
                                        v-model="fields.codigo">
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label for="anio" class="col-md-4 col-form-label text-right">Año</label>
                                <div class="col-md-8">
                                    <input
                                        name="anio" type="number" class="form-control" min="1980" max="<?= date('Y') + 1 ?>"
                                        required
                                        v-model="fields.anio"
                                    >
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label for="tipo" class="col-md-4 col-form-label text-right">Tipo medición</label>
                                <div class="col-md-8">
                                    <select name="tipo" v-model="fields.tipo" class="form-control" required>
                                        <option v-for="optionTipo in arrTipo" v-bind:value="optionTipo.str_cod">
                                            {{ optionTipo.name }}</option>
                                    </select>
                                </div>
                            </div>

                            <div class="mb-3 row">
                                <label for="unidad_observacion" class="col-md-4 col-form-label text-right">Unidad de
                                    observación</label>
                                <div class="col-md-8">
                                    <select name="unidad_observacion" v-model="fields.unidad_observacion"
                                        class="form-control" required>
                                        <option v-for="optionUnidadObservacion in arrUnidadObservacion"
                                            v-bind:value="optionUnidadObservacion.str_cod">
                                            {{ optionUnidadObservacion.name }}
                                        </option>
                                    </select>
                                </div>
                            </div>

                            <div class="mb-3 row">
                                <label for="metodologia" class="col-md-4 col-form-label text-right">Metodología</label>
                                <div class="col-md-8">
                                    <select name="metodologia" v-model="fields.metodologia" class="form-control"
                                        required>
                                        <option v-for="(optionMetodologia, key_metodologia) in arrMetodologia"
                                            v-bind:value="optionMetodologia.str_cod">{{ optionMetodologia.name }}
                                        </option>
                                    </select>
                                </div>
                            </div>

                            <div class="mb-3 row">
                                <label for="tema_cod" class="col-md-4 col-form-label text-right">Tema</label>
                                <div class="col-md-8">
                                    <select name="tema_cod" v-model="fields.tema_cod" class="form-control">
                                        <option value="">[ Ninguno ]</option>
                                        <option v-for="optionTema in arrTema" v-bind:value="optionTema.str_cod">{{ optionTema.name }}</option>
                                    </select>
                                </div>
                            </div>

                            <div class="mb-3 row">
                                <label for="subtema_1" class="col-md-4 col-form-label text-right">Subtemática 1</label>
                                <div class="col-md-8">
                                    <select name="subtema_1" v-model="fields.subtema_1" class="form-control">
                                        <option value="">[ Ninguno ]</option>
                                        <option v-for="optionSubtema1 in arrSubtema" v-bind:value="optionSubtema1.str_cod">{{ optionSubtema1.name }}</option>
                                    </select>
                                </div>
                            </div>

                            <div class="mb-3 row">
                                <label for="subtema_2" class="col-md-4 col-form-label text-right">Subtemática 2</label>
                                <div class="col-md-8">
                                    <select name="subtema_2" v-model="fields.subtema_2" class="form-control">
                                        <option value="">[ Todos ]</option>
                                        <option v-for="optionSubtema2 in arrSubtema" v-bind:value="optionSubtema2.str_cod">{{ optionSubtema2.name }}</option>
                                    </select>
                                </div>
                            </div>

                            <div class="mb-3 row">
                                <label for="cant_encuestas" class="col-md-4 col-form-label text-right">Encuestas aplicadas</label>
                                <div class="col-md-8">
                                    <input
                                        name="cant_encuestas" type="number" class="form-control" min="0"
                                        v-model="fields.cant_encuestas"
                                    >
                                </div>
                            </div>

                            <div class="mb-3 row">
                                <label for="cant_variables" class="col-md-4 col-form-label text-right">Cantidad de variables</label>
                                <div class="col-md-8">
                                    <input
                                        name="cant_variables" type="text" class="form-control"
                                        required
                                        v-model="fields.cant_variables"
                                    >
                                    <small class="form-text text-muted">Columnas del dataset resultante final</small>
                                </div>
                            </div>

                            <div class="mb-3 row">
                                <label for="fecha_inicio" class="col-md-4 col-form-label text-right">Fecha
                                    inicio</label>
                                <div class="col-md-8">
                                    <input name="fecha_inicio" type="date" class="form-control"
                                        v-model="fields.fecha_inicio">
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label for="fecha_fin" class="col-md-4 col-form-label text-right">Fecha fin</label>
                                <div class="col-md-8">
                                    <input name="fecha_fin" type="date" class="form-control"
                                        v-model="fields.fecha_fin">
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label for="fecha_publicacion" class="col-md-4 col-form-label text-right">Fecha
                                    publicación</label>
                                <div class="col-md-8">
                                    <input name="fecha_publicacion" type="date" class="form-control"
                                        v-model="fields.fecha_publicacion">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </fieldset>
    </form>
</div>

<script>
// Variables
//-----------------------------------------------------------------------------
var row = <?= json_encode($row) ?>;
row.tipo = '0<?= $row->tipo ?>';
row.unidad_observacion = '0<?= $row->unidad_observacion ?>';
row.metodologia = '0<?= $row->metodologia ?>';
row.tema_cod = '0<?= $row->tema_cod ?>';
row.subtema_1 = '0<?= $row->subtema_1 ?>';
row.subtema_2 = '0<?= $row->subtema_2 ?>';
row.published_at = '<?= substr($row->published_at,0,10) ?>';

// VueApp
//-----------------------------------------------------------------------------
var editMedicion = new Vue({
    el: '#editMedicion',
    data: {
        fields: row,
        loading: false,
        arrTipo: <?= json_encode($arrTipo) ?>,
        arrUnidadObservacion: <?= json_encode($arrUnidadObservacion) ?>,
        arrMetodologia: <?= json_encode($arrMetodologia) ?>,
        arrCodEstrategia: <?= json_encode($arrCodEstrategia) ?>,
        arrTema: <?= json_encode($arrTema) ?>,
        arrSubtema: <?= json_encode($arrSubtema) ?>,
    },
    methods: {
        send_form: function() {
            this.loading = true
            var form_data = new FormData(document.getElementById('edit_form'))
            axios.post(URL_API + 'mediciones/save/', form_data)
                .then(response => {
                    if (response.data.saved_id > 0) {
                        toastr['success']('Guardado')
                    }
                    this.loading = false
                })
                .catch(function(error) {
                    console.log(error)
                })
        },
        get_options_cat_2: function() {
            var form_data = new FormData
            form_data.append('condition', 'category_id = 21 AND level = 1 AND parent_id = ' + parseInt(this
                .fields.cat_1))
            form_data.append('empty_value', 'Seleccione la subcategoría')
            axios.post(URL_API + 'items/get_options/', form_data)
                .then(response => {
                    this.options_cat_2 = response.data.options
                })
                .catch(function(error) {
                    console.log(error)
                })
        },
    }
})
</script>