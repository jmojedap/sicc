<div v-show="section == 'form'">
    <div class="card">
        <div class="card-body">
            <form accept-charset="utf-8" method="POST" id="priorizacionForm" @submit.prevent="saveElement">
                <fieldset v-bind:disabled="loading">
                    <input type="hidden" name="id" v-bind:value="currentElement['id']">
                    <div class="mb-3 row">
                        <label for="nombre" class="col-md-4 col-form-label text-end text-right">Nombre</label>
                        <div class="col-md-8">
                            <input
                                name="nombre" type="text" class="form-control"
                                required
                                title="Nombre" placeholder="Nombre"
                                v-model="currentElement['nombre']"
                            >
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="descripcion" class="col-md-4 col-form-label text-end text-right">Descripción</label>
                        <div class="col-md-8">
                            <textarea
                                name="descripcion" class="form-control" rows="3" required
                                title="Descripción" placeholder="Descripción"
                                v-model="currentElement['descripcion']"
                            ></textarea>
                        </div>
                    </div>
                    
                    <div class="mb-3 row">
                        <div class="col-md-8 offset-md-4">
                            <button class="btn btn-primary w120p me-2" type="submit">Guardar</button>
                            <button class="btn btn-light w120p" type="button" v-on:click="setSection('lista')">Volver</button>
                        </div>
                    </div>
                <fieldset>
            </form>
        </div>
    </div>
</div>