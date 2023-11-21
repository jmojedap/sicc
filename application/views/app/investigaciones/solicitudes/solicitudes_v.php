<div id="solicitudesApp">
    <div class="container">
        <h1 class="fs-5 text-center">
            Solicitud de investigaciones a la Dirección Observatorio y Gestión del Conocimiento Cultural 2024
        </h1>
        <div class="center_box_750">
            <div class="card">
                <div class="card-body">
                    <form accept-charset="utf-8" method="POST" id="solicitudForm" @submit.prevent="handleSubmit">
                        <fieldset v-bind:disabled="loading">
                            <div class="mb-3 row">
                                <label for="email" class="col-md-4 col-form-label text-end text-right">Correo electrónico</label>
                                <div class="col-md-8">
                                    <input
                                        name="email" type="email" class="form-control"
                                        required
                                        title="Correo electrónico" placeholder=""
                                        v-model="fields.email"
                                    >
                                    <span class="form-text">Correo electrónico del funcionario solicitante</span>
                                </div>
                            </div>

                            <div class="mb-3 row">
                                <label for="entidad_sigla" class="col-md-4 col-form-label text-end text-right">Entidad solicitante</label>
                                <div class="col-md-8">
                                    <select name="entidad_sigla" v-model="fields.entidad_sigla" class="form-select form-control" required>
                                        <option v-for="optionEntidad in arrEntidades" v-bind:value="optionEntidad.cod">{{ optionEntidad.name }}</option>
                                    </select>
                                </div>
                            </div>

                            <div class="mb-3 row">
                                <label for="dependencia" class="col-md-4 col-form-label text-end text-right">Dependencia</label>
                                <div class="col-md-8">
                                    <input
                                        name="dependencia" type="text" class="form-control"
                                        required title="Dependencia"
                                        v-model="fields.dependencia"
                                    >
                                    <span class="form-text">Área, dirección, subdirección, gerencia, dependencia solicitante</span>
                                </div>
                            </div>
                            
                            <div class="mb-3 row">
                                <div class="col-md-8 offset-md-4">
                                    <button class="btn btn-primary w120p" type="submit">Enviar</button>
                                </div>
                            </div>
                        <fieldset>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>

<?php $this->load->view('app/investigaciones/solicitudes/vue_v') ?>