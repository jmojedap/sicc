<?php $this->load->view('assets/recaptcha') ?>
<?php $this->load->view('redcultural/script_data_v') ?>
<div id="agendarmeApp">
    <div class="container">
        <div class="card" v-show="section == 'form'">
            <div class="card-body">


                <div class="center_box_750">
                    <h3 class="text-center">Agendarme</h3>
    
                    <p class="lead text-center">
                        A través de este formulario, podrá seleccionar las mesas de trabajo a las que se inscribirá en las jornadas del Encuentro Ciudades y Culturas en Iberoamérica:
                    </p>
                </div>

                <form accept-charset="utf-8" method="POST" id="agendarme-form" @submit.prevent="handleSubmit">
                    <!-- Campo para validación Google ReCaptcha V3 -->
                    <input type="hidden" id="g-recaptcha-response" name="g-recaptcha-response">
        
                    <fieldset v-bind:disabled="loading">
                        <div class="mb-3 row">
                            <label for="email" class="col-md-4 col-form-label text-end text-right">Correo electrónico</label>
                            <div class="col-md-8">
                                <input
                                    name="email" type="email" class="form-control"
                                    required v-on:change="setDisplayName"
                                    title="Correo electrónico" placeholder="Correo electrónico"
                                    v-model="fields.email"
                                >
                                <small class="text-muted">Con el que se registró en el directorio</small>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="display_name" class="col-md-4 col-form-label text-end text-right">Nombre y apellido</label>
                            <div class="col-md-8">
                                <input
                                    name="display_name" type="text" class="form-control"
                                    required
                                    title="Nombre y apellido" placeholder="Nombre y apellido"
                                    v-model="fields.display_name"
                                >
                            </div>
                        </div>

                        <hr>

                        <div class="mb-3 row">
                            <label for="viernes_tarde" class="col-md-4 col-form-label text-end text-right color-text-1">
                                Viernes 19 sept.
                            </label>
                            <div class="col-md-8">
                                Seleccione una de las siguientes opciones de mesas temáticas en las que desea participar:
                            </div>
                        </div>

                        
                        <div class="mb-3 row">
                            <label for="viernes_tarde" class="col-md-4 col-form-label text-end text-right">Opción 1</label>
                            <div class="col-md-8">
                                <select name="viernes_tarde" v-model="fields.viernes_tarde" class="form-select form-control" required>
                                    <option v-for="optionViernesTarde in mesasViernesTarde" v-bind:value="optionViernesTarde.title">{{ optionViernesTarde.title }}</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label for="viernes_tarde_opcion_2" class="col-md-4 col-form-label text-end text-right">Opción 2</label>
                            <div class="col-md-8">
                                <select name="viernes_tarde_opcion_2" v-model="fields.viernes_tarde_opcion_2" class="form-select form-control" required>
                                    <option v-for="optionViernesTarde in mesasViernesTarde" v-bind:value="optionViernesTarde.title">{{ optionViernesTarde.title }}</option>
                                </select>
                                <small class="text-muted">En caso que el aforo de su primera selección se complete</small>

                            </div>
                        </div>

                        <hr>

                        <div class="mb-3 row">
                            <label for="viernes_tarde" class="col-md-4 col-form-label text-end text-right color-text-1">
                                Sábado 20 sept.
                            </label>
                            <div class="col-md-8">
                                Seleccione una de las siguientes opciones de mesas temáticas en las que desea participar:
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label for="sabado_manana_opcion_1" class="col-md-4 col-form-label text-end text-right">Opción 1</label>
                            <div class="col-md-8">
                                <select name="sabado_manana_opcion_1" v-model="fields.sabado_manana_opcion_1" class="form-select form-control" required>
                                    <option v-for="optionSabado in mesasSabado" v-bind:value="optionSabado.title">{{ optionSabado.title }}</option>
                                </select>
                            </div>
                        </div>
                        

                        <div class="mb-3 row">
                            <label for="sabado_manana_opcion_2" class="col-md-4 col-form-label text-end text-right">Opción 2</label>
                            <div class="col-md-8">
                                <select name="sabado_manana_opcion_2" v-model="fields.sabado_manana_opcion_2" class="form-select form-control" required>
                                    <option v-for="optionSabado in mesasSabado" v-bind:value="optionSabado.title">{{ optionSabado.title }}</option>
                                </select>
                                <small class="text-muted">En caso que el aforo de su primera selección se complete</small>
                            </div>
                        </div>

                        <hr>

                        <div class="mb-3 row">
                            <label for="recorrido_domingo" class="col-md-4 col-form-label text-end text-right color-text-1">
                                Recorrido del domingo 21
                            </label>
                            <div class="col-md-8">
                                <select name="recorrido_domingo" v-model="fields.recorrido_domingo" class="form-select form-control" required>
                                    <option v-for="optionDomingo in recorridosDomingo" v-bind:value="optionDomingo.title">{{ optionDomingo.title }}</option>
                                </select>
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