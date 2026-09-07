<div v-show="section == 'form'" class="geofocus-prioritizations-form">
    <div class="card geofocus-base-content">
        <header class="geofocus-base-content-header">
            <div>
                <h1>{{ currentElement && currentElement.id ? 'Editar priorización' : 'Nueva priorización' }}</h1>
                <p>Define el nombre, propósito y capa territorial base del ejercicio.</p>
            </div>
            <span v-if="currentElement && currentElement.id" class="geofocus-base-count">ID {{ currentElement.id }}</span>
        </header>
        <div class="card-body geofocus-prioritizations-form-body">
            <form accept-charset="utf-8" method="POST" id="priorizacionForm" @submit.prevent="saveElement">
                <fieldset v-bind:disabled="loading">
                    <input type="hidden" name="id" v-bind:value="currentElement['id']">
                    <div class="mb-3 row">
                        <label for="nombre" class="col-md-4 col-form-label text-end">Nombre <span class="text-danger">*</span></label>
                        <div class="col-md-8">
                            <input
                                id="nombre" name="nombre" type="text" class="form-control"
                                required
                                title="Nombre" placeholder="Nombre"
                                v-model="currentElement['nombre']"
                            >
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="descripcion" class="col-md-4 col-form-label text-end">Descripción <span class="text-danger">*</span></label>
                        <div class="col-md-8">
                            <textarea
                                id="descripcion" name="descripcion" class="form-control" rows="4" required
                                title="Descripción" placeholder="Descripción"
                                v-model="currentElement['descripcion']"
                            ></textarea>
                        </div>
                    </div>
                    

                    <div class="mb-3 row">
                        <label for="key_capa" class="col-md-4 col-form-label text-end">Capa base <span class="text-danger">*</span></label>
                        <div class="col-md-8">
                            <select
                                id="key_capa"
                                name="key_capa"
                                class="form-select"
                                required
                                v-model="currentElement['key_capa']"
                            >
                                <option value="">[ Seleccione una capa base ]</option>
                                <option
                                    v-for="capaBase in capasBase"
                                    v-bind:key="capaBase.key_capa"
                                    v-bind:value="capaBase.key_capa"
                                >
                                    {{ capaBase.nombre }} · {{ capaBase.key_capa }}
                                </option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <div class="col-md-8 offset-md-4">
                            <div class="geofocus-prioritizations-form-actions">
                                <button class="btn btn-primary" type="submit">Guardar</button>
                                <button class="btn btn-light" type="button" v-on:click="setSection('lista')">Volver</button>
                            </div>
                        </div>
                    </div>
                </fieldset>
            </form>
        </div>
    </div>
</div>
