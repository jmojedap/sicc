<?php $this->load->view('assets/summernote_bs5') ?>

<div id="editMedicionApp">
    <div class="center_box_920">
        <div class="card">
            <div class="card-body">
                <form accept-charset="utf-8" method="POST" id="medicionForm" @submit.prevent="handleSubmit">
                    <fieldset v-bind:disabled="loading">
                        <input type="hidden" name="id" value="<?= $row->id ?>">

                        <div class="mb-3 row">
                            <div class="col-md-8 offset-md-4">
                                <button class="btn btn-warning w120p" type="submit">Guardar</button>
                            </div>
                        </div>
                        
                        <div class="mb-3 row">
                            <label for="nombre_medicion" class="col-md-4 col-form-label text-end">Nombre</label>
                            <div class="col-md-8">
                                <input
                                    name="nombre_medicion" type="text" class="form-control"
                                    required
                                    title="Nombre" placeholder="Nombre"
                                    v-model="fields.nombre_medicion"
                                >
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label for="subtitulo" class="col-md-4 col-form-label text-end">Subtítulo</label>
                            <div class="col-md-8">
                                <input
                                    name="subtitulo" type="text" class="form-control"
                                    v-model="fields.subtitulo"
                                >
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label for="codigo" class="col-md-4 col-form-label text-end">Código medición</label>
                            <div class="col-md-8">
                                <input
                                    name="codigo" type="text" class="form-control"
                                    title="Código medición" placeholder="Código medición"
                                    v-model="fields.codigo"
                                >
                            </div>
                        </div>



                        <div class="mb-3 row">
                            <label for="contenido" class="col-md-4 col-form-label text-end">Descripción</label>
                            <div class="col-md-8">
                                <textarea
                                    name="descripcion" class="form-control" rows="6" maxlength="280"
                                    title="Descripción" placeholder="Descripción"
                                    v-model="fields.descripcion"
                                ></textarea>
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label for="tipo" class="col-md-4 col-form-label text-end">Tipo medición</label>
                            <div class="col-md-8">
                                <select name="tipo" v-model="fields.tipo" class="form-select">
                                    <option v-for="option_tipo in options_tipo" v-bind:value="option_tipo.cod">{{ option_tipo.name }}</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label for="unidad_observacion" class="col-md-4 col-form-label text-end">Unidad de observación</label>
                            <div class="col-md-8">
                                <select name="unidad_observacion" v-model="fields.unidad_observacion" class="form-select">
                                    <option v-for="option_unidad_observacion in options_unidad_observacion"
                                        v-bind:value="option_unidad_observacion.cod">{{ option_unidad_observacion.name }}
                                    </option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label for="metodologia" class="col-md-4 col-form-label text-end">Metodología</label>
                            <div class="col-md-8">
                                <select name="metodologia" v-model="fields.metodologia" class="form-select">
                                    <option v-for="option_metodologia in options_metodologia" v-bind:value="option_metodologia.cod">{{ option_metodologia.name }}</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label for="fecha_inicio" class="col-md-4 col-form-label text-end">Fecha inicio</label>
                            <div class="col-md-8">
                                <input name="fecha_inicio" type="date" class="form-control" v-model="fields.fecha_inicio">
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="fecha_fin" class="col-md-4 col-form-label text-end">Fecha fin</label>
                            <div class="col-md-8">
                                <input name="fecha_fin" type="date" class="form-control" v-model="fields.fecha_fin">
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="fecha_publicacion" class="col-md-4 col-form-label text-end">Fecha publicación</label>
                            <div class="col-md-8">
                                <input name="fecha_publicacion" type="date" class="form-control" v-model="fields.fecha_publicacion">
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label for="cod_estrategia" class="col-md-4 col-form-label text-end">Códigos programa | Estrategia</label>
                            <div class="col-md-4">
                                <input
                                    name="cod_programa" type="text" class="form-control"
                                    title="Código programa" placeholder="Código programa"
                                    v-model="fields.cod_programa"
                                >
                            </div>
                            <div class="col-md-4">
                                <input
                                    name="cod_estrategia" type="text" class="form-control"
                                    title="Código estrategia" placeholder="Código estrategia"
                                    v-model="fields.cod_estrategia"
                                >
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="contenido">Contenido</label>
                            <textarea
                                name="contenido" id="summernote"
                                title="Resumen" placeholder="Resumen"
                            ><?= $row->contenido ?></textarea>
                        </div>
                    <fieldset>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
var editMedicionApp = createApp({
    data(){
        return{
            loading: false,
            fields: <?= json_encode($row) ?>,
            options_tipo: <?= json_encode($options_tipo) ?>,
            options_unidad_observacion: <?= json_encode($options_unidad_observacion) ?>,
            options_metodologia: <?= json_encode($options_metodologia) ?>,
            options_cod_estrategia: <?= json_encode($options_cod_estrategia) ?>,
        }
    },
    methods: {
        handleSubmit: function(){
            this.loading = true
            var formValues = new FormData(document.getElementById('medicionForm'))
            axios.post(URL_API + 'mediciones/save/', formValues)
            .then(response => {
                if ( response.data.saved_id > 0 ) {
                    toastr['success']('Guardado')
                }
                this.loading = false
            })
            .catch( function(error) {console.log(error)} )
        },
    },
    mounted(){
        //this.getList()
    }
}).mount('#editMedicionApp')
</script>