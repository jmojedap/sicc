<?php $this->load->view('assets/recaptcha') ?>
<?php $this->load->view('redcultural/script_data_v') ?>
<div id="agendarmeApp">
    <div class="container">

        <div class="center_box_750">
            <h3 class="text-center">Agendarme</h3>

            <p class="lead text-center">
                A través de este formulario, podrá seleccionar las mesas de trabajo a las que se inscribirá en las jornadas del Encuentro Ciudades y Culturas en Iberoamérica:
            </p>
        </div>

        <div class="card" v-show="section == 'form'">
            <div class="card-body">

                <form accept-charset="utf-8" method="POST" id="agendarme-form" @submit.prevent="handleSubmit">
                    <!-- Campo para validación Google ReCaptcha V3 -->
                    <input type="hidden" id="g-recaptcha-response" name="g-recaptcha-response">
        
                    <fieldset v-bind:disabled="loading">

                        <!-- IDENTIFICACIÓN DE GRUPO -->

                        <div v-show="step == 'group'">
                            <p>
                                Seleccione el grupo de origen y de edad a los que pertenece. <b class="text-primary">Según esta selección</b> se activarán las opciones
                                de actividades en las que podrá agendarse.
                            </p>

                            <div class="mb-3 row">
                                <label for="grupo_origen" class="col-md-4 col-form-label text-end text-right">Yo soy</label>
                                <div class="col-md-8">
                                    <select name="grupo_origen" v-model="fields.grupo_origen" class="form-select form-control">
                                        <option v-for="optionGrupo in gruposOrigen" v-bind:value="optionGrupo.name">{{ optionGrupo.title }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label for="grupo_edad" class="col-md-4 col-form-label text-end text-right">Edad</label>
                                <div class="col-md-8">
                                    <select name="grupo_edad" v-model="fields.grupo_edad" class="form-select form-control" required>
                                        <option v-for="optionGrupoEdad in gruposEdad" v-bind:value="optionGrupoEdad.name">{{ optionGrupoEdad.title }}</option>
                                    </select>
                                </div>
                            </div>

                            <div class="mb-3 row">
                                <div class="col-md-8 offset-md-4">
                                    <button class="btn btn-primary w120p" type="button" v-on:click="setGrupos">Siguiente</button>
                                </div>
                            </div>
                        </div>

                        <!-- SELECCIÓN DE AGENDA -->

                        <div v-show="step == 'selections'">
                            <?php $this->load->view('redcultural/agenda/agendarme/selections_v') ?>
                        </div>
                    <fieldset>
                </form>
            </div>
        </div>

        <div class="card" v-show="section=='success'">
            <div class="card-body">
                <h3 class="text-center"><i class="fas fa-check text-success"></i></h3>
                <h3 class="text-center">¡Gracias!</h3>
                <p class="text-center">
                    Sus preferencias de la agenda fueron guardadas.
                </p>
            </div>
        </div>
    </div>
</div>

<?php $this->load->view('redcultural/agenda/agendarme/vue_v') ?>