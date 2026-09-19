<div class="geofocus-base-page variables-layer-page">
    <div class="geofocus-base-layout">
        <aside class="geofocus-base-sidebar" aria-labelledby="variables-layer-list-title">
            <div class="geofocus-base-sidebar-header">
                <h2 id="variables-layer-list-title">Capas geográficas base</h2>
                <p>
                    Selecciona una capa para consultar las variables calculadas sobre sus polígonos.
                </p>
            </div>

            <div class="geofocus-base-options" role="list">
                <button
                    v-for="capa in capasBase"
                    v-bind:key="capa.key_capa"
                    type="button"
                    class="geofocus-base-option"
                    v-bind:class="{ 'is-active': isCurrentCapa(capa) }"
                    v-on:click="selectCapa(capa)"
                    v-bind:aria-pressed="isCurrentCapa(capa)"
                >
                    <span class="geofocus-base-option-icon" aria-hidden="true">
                        <i class="fas fa-layer-group"></i>
                    </span>
                    <span class="geofocus-base-option-copy">
                        <strong>{{ capa.nombre }}</strong>
                        <small>{{ capa.key_capa }}</small>
                    </span>
                    <span class="variables-layer-option-count">
                        {{ cantidadVariablesCapa(capa) }}
                    </span>
                    <i class="fas fa-chevron-right geofocus-base-option-arrow" aria-hidden="true"></i>
                </button>

                <p v-if="capasBase.length === 0" class="geofocus-base-empty">
                    No hay capas base disponibles.
                </p>
            </div>
        </aside>

        <main v-if="currentCapa" class="geofocus-base-content">
            <header class="geofocus-base-content-header">
                <div class="variables-layer-title">
                    <h1>{{ currentCapa.nombre }}</h1>
                    <small>
                        <i class="fas fa-draw-polygon me-1" aria-hidden="true"></i>
                        {{ currentCapa.cantidad_poligonos }} pol&iacute;gonos
                    </small>
                </div>
                <div class="variables-layer-header-actions">
                    <button type="button" class="btn btn-outline-primary"
                        v-on:click="openMap()" v-bind:disabled="variablesDeCapa.length === 0">
                        <i class="fas fa-map-marked-alt me-1" aria-hidden="true"></i>
                        Ver mapa
                    </button>
                    <button v-if="canEditVariables" type="button" class="btn btn-primary" v-on:click="openCreateForm">
                        <i class="fas fa-plus me-1" aria-hidden="true"></i>
                        Nueva variable
                    </button>
                </div>
            </header>

            <section class="geofocus-base-variables variables-layer-content" aria-labelledby="variables-layer-title">
                <div class="geofocus-base-section-heading variables-layer-heading">
                    <div class="variables-layer-toolbar">
                        <div>
                            <label for="filtro-tema" class="form-label">Filtrar por tema</label>
                            <select id="filtro-tema" class="form-select form-select-sm" v-model="temaFiltro">
                                <option value="">Todos los temas</option>
                                <option v-for="tema in temas" v-bind:key="tema" v-bind:value="tema">
                                    {{ tema }}
                                </option>
                            </select>
                        </div>
                        <button
                            v-if="temaFiltro"
                            type="button"
                            class="btn btn-light btn-sm"
                            v-on:click="temaFiltro = ''"
                        >
                            <i class="fas fa-times me-1" aria-hidden="true"></i>
                            Limpiar filtro
                        </button>
                    </div>
                    <div class="variables-layer-heading-title">
                        <h2 id="variables-layer-title">Variables incluidas <span class="variables-layer-count">({{ variablesFiltradas.length }} de {{ variablesDeCapa.length }})</span></h2>
                    </div>
                </div>

                <div class="table-responsive variables-layer-table">
                    <table class="table table-sm table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th scope="col" class="text-center"></th>
                                <th scope="col">Clave</th>
                                <th scope="col">Nombre</th>
                                <th scope="col">Tema</th>
                                <th scope="col">Subtema</th>
                                <th scope="col" class="text-center">Estado</th>
                                <th scope="col" class="text-center">Año</th>
                                <th scope="col" class="text-center">Cantidad de valores</th>
                                <th scope="col">Unidad</th>
                                <th scope="col" class="text-end">
                                    <span class="visually-hidden">Acciones</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="variable in variablesFiltradas" v-bind:key="variable.id">
                                <td class="text-center">
                                    <span
                                        class="variables-color-dot"
                                        v-bind:style="{ backgroundColor: variable.color || '#0084bf' }"
                                        v-bind:title="variable.color || '#0084bf'"
                                        v-bind:aria-label="'Color ' + (variable.color || '#0084bf')"
                                        role="img"
                                    ></span>
                                </td>
                                <td><code>{{ displayValue(variable.clave) }}</code></td>
                                <td>
                                    <span class="variable-name">{{ displayValue(variable.nombre) }}</span>
                                </td>
                                <td>
                                    <span class="tema" v-bind:class="textToClass(variable.tema, 'tema')">
                                        {{ displayValue(variable.tema) }}
                                    </span>
                                </td>
                                <td>{{ displayValue(variable.subtema) }}</td>
                                <td class="text-center">
                                    <span
                                        v-if="String(variable.estado) === '1'"
                                        class="variables-layer-active-status"
                                        title="Activo"
                                    >
                                        <i class="fas fa-check-circle" aria-hidden="true"></i>
                                        <span class="visually-hidden">Activo</span>
                                    </span>
                                    <span v-else>{{ estadoNombre(variable.estado) }}</span>
                                </td>
                                <td class="text-center">{{ displayValue(variable.anio_valores) }}</td>
                                <td class="text-center text-nowrap">
                                    {{ displayValue(variable.cantidad_valores) }}
                                    <i
                                        v-if="hasIncompleteValues(variable)"
                                        class="fas fa-exclamation-triangle text-warning ms-1"
                                        title="Faltan valores para algunos polígonos"
                                        aria-label="Faltan valores para algunos polígonos"
                                    ></i>
                                </td>
                                <td>{{ displayValue(variable.unidad_medida) }}</td>
                                <td class="text-end text-nowrap">
                                    <button
                                        type="button"
                                        class="a4 me-1"
                                        v-on:click="openMap(variable.id)"
                                        v-bind:aria-label="'Ver ' + variable.nombre + ' en el mapa'"
                                        title="Ver en mapa"
                                    >
                                        <i class="fas fa-map-marked-alt" aria-hidden="true"></i>
                                    </button>
                                    <button
                                        type="button"
                                        v-if="canEditVariables"
                                        class="a4 me-1"
                                        v-on:click="openForm(variable.id)"
                                        v-bind:aria-label="'Editar ' + variable.nombre"
                                        title="Editar variable"
                                    >
                                        <i class="fas fa-pencil-alt" aria-hidden="true"></i>
                                    </button>
                                    <a
                                        v-if="canEditVariables"
                                        class="a4 me-1"
                                        v-bind:href="`<?= URL_APP . "geofocus/variables_importar_valores/" ?>` + variable.id"
                                        v-bind:aria-label="'Importar datos de ' + variable.nombre"
                                        title="Importar datos"
                                    >
                                        <i class="fas fa-file-import" aria-hidden="true"></i>
                                    </a>
                                    <button
                                        type="button"
                                        class="a4 me-1"
                                        v-on:click="setCurrent(variable.id)"
                                        data-bs-toggle="modal"
                                        data-bs-target="#variableDetalleModal"
                                        v-bind:aria-label="'Ver detalles de ' + variable.nombre"
                                        title="Ver detalles"
                                    >
                                        <i class="fas fa-eye" aria-hidden="true"></i>
                                    </button>
                                    <button
                                        type="button"
                                        class="a4"
                                        v-on:click="recalcularVariable(variable)"
                                        v-bind:aria-label="'Recalcular variable ' + variable.nombre"
                                        title="Recalcular variable"
                                    >
                                        <i class="fas fa-sync-alt" aria-hidden="true"></i>
                                    </button>
                                </td>
                            </tr>
                            <tr v-if="variablesFiltradas.length === 0">
                                <td colspan="10" class="variables-layer-empty">
                                    <span class="variables-layer-empty-icon" aria-hidden="true">
                                        <i class="fas fa-chart-pie"></i>
                                    </span>
                                    <strong>
                                        {{ temaFiltro ? 'No hay variables para este tema' : 'Esta capa aún no tiene variables' }}
                                    </strong>
                                    <small>
                                        {{ temaFiltro
                                            ? 'Prueba con otro tema o limpia el filtro.'
                                            : 'Las variables asociadas aparecerán aquí cuando compartan la clave de la capa.'
                                        }}
                                    </small>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </section>
        </main>

        <main v-else class="geofocus-base-content geofocus-base-no-selection">
            <i class="fas fa-layer-group" aria-hidden="true"></i>
            <h1>Sin capas base</h1>
            <p>No hay una capa territorial disponible para consultar variables.</p>
        </main>
    </div>
</div>
