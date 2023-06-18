            <form accept-charset="utf-8" method="POST" id="obligacionForm" @submit.prevent="handleSubmit">
                <fieldset v-bind:disabled="loading">
                    <div class="mb-3 row">
                        <label for="num_obligacion" class="col-md-4 col-form-label text-end">Núm.</label>
                        <div class="col-md-8">
                            <input
                                name="num_obligacion" type="text" class="form-control"
                                required
                                title="Número obligación" placeholder="Número obligación"
                                v-model="fields.num_obligacion"
                            >
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="nombre_obligacion" class="col-md-4 col-form-label text-end">Nombre</label>
                        <div class="col-md-8">
                            <input
                                name="nombre_obligacion" type="text" class="form-control"
                                required
                                title="Título" placeholder="Título"
                                v-model="fields.nombre_obligacion"
                            >
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="descripcion" class="col-md-4 col-form-label text-end">Contenido</label>
                        <div class="col-md-8">
                            <textarea
                                name="descripcion" class="form-control" rows="3" required
                                title="Contenido" placeholder="Contenido"
                                v-model="fields.descripcion"
                            ></textarea>
                        </div>
                    </div>
                    
                    <div class="mb-3 row">
                        <div class="col-md-8 offset-md-4">
                            <button class="btn btn-primary w120p me-2" type="submit">Guardar</button>
                            <button class="btn btn-light w120p" type="button" v-on:click="setSection('list')">Cancelar</button>
                        </div>
                    </div>
                <fieldset>
            </form>