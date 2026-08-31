<div class="variables-header d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2 mb-2">
    <div class="d-flex align-items-start gap-2">
        <button type="button" class="btn btn-primary btn-lg mt-1" v-on:click="openCreateForm">
            <i class="fas fa-plus me-1" aria-hidden="true"></i>NUEVO
        </button>
    </div>
    <span class="badge rounded-pill bg-light text-dark border align-self-start align-self-md-center">
        {{ variablesFiltradas.length }}<span v-if="temaFiltro"> de {{ variables.length }}</span> variables
    </span>
</div>

<div class="row g-2 align-items-end mb-2">
    <div class="col-sm-6 col-md-4 col-lg-3">
        <label for="filtro-tema" class="form-label small text-muted mb-1">Filtrar por tema</label>
        <select id="filtro-tema" class="form-select form-select-sm" v-model="temaFiltro">
            <option value="">Todos los temas</option>
            <option v-for="tema in temas" v-bind:key="tema" v-bind:value="tema">{{ tema }}</option>
        </select>
    </div>
    <div class="col-auto">
        <button v-if="temaFiltro" type="button" class="btn btn-light btn-sm" v-on:click="temaFiltro = ''">
            <i class="fas fa-times me-1" aria-hidden="true"></i>
            Limpiar
        </button>
    </div>
</div>

<div class="table-responsive variables-table-container">
    <table class="table table-sm table-hover align-middle bg-white mb-0">
        <thead>
            <tr>
                <th scope="col" class="text-center">ID</th>
                <th scope="col">Clave</th>
                <th scope="col">Nombre</th>
                <th scope="col">Tema</th>
                <th scope="col" class="text-center">Estado</th>
                <th scope="col">Capa proyectada</th>
                <th scope="col" class="text-center">Año</th>
                <th scope="col">Unidad</th>
                <th scope="col" class="text-end"><span class="visually-hidden">Acciones</span></th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="variable in variablesFiltradas" v-bind:key="variable.id">
                <td class="text-center">
                    <strong class="variable-id">{{ variable.id }}</strong>
                </td>
                <td>
                    <code>{{ displayValue(variable.clave) }}</code>
                </td>
                <td>
                    <span class="variable-name">{{ displayValue(variable.nombre) }}</span>
                </td>
                <td>
                    <span class="tema" v-bind:class="textToClass(variable.tema, 'tema')">
                        {{ displayValue(variable.tema) }}
                    </span>
                </td>
                <td class="text-center">
                    <span v-if="String(variable.estado) === '1'" class="text-success" title="Activo" aria-label="Activo">✅</span>
                    <span v-else>{{ estadoNombre(variable.estado) }}</span>
                </td>
                <td><code>{{ displayValue(variable.key_capa) }}</code></td>
                <td class="text-center">{{ displayValue(variable.anio_valores) }}</td>
                <td>{{ displayValue(variable.unidad_medida) }}</td>
                <td class="text-end text-nowrap">
                    <button
                        type="button"
                        class="a4 me-1"
                        v-on:click="openForm(variable.id)"
                        v-bind:aria-label="'Editar ' + variable.nombre"
                        title="Editar variable"
                    >
                        <i class="fas fa-pencil-alt" aria-hidden="true"></i>
                    </button>
                    <button
                        type="button"
                        class="a4"
                        v-on:click="setCurrent(variable.id)"
                        data-bs-toggle="modal"
                        data-bs-target="#variableDetalleModal"
                        v-bind:aria-label="'Ver detalles de ' + variable.nombre"
                        title="Ver detalles"
                    >
                        <i class="fas fa-eye" aria-hidden="true"></i>
                    </button>
                </td>
            </tr>
            <tr v-if="variablesFiltradas.length === 0">
                <td colspan="9" class="text-center text-muted py-4">
                    No hay variables para el filtro seleccionado.
                </td>
            </tr>
        </tbody>
    </table>
</div>
