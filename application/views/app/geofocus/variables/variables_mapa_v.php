<div v-show="section == 'mapa'" class="variables-map-page">
    <section class="geofocus-base-content variables-map-card" aria-labelledby="variables-map-title">
        <div class="variables-map-controls">
            <div class="variables-map-back-control">
                <button type="button" class="btn btn-light btn-sm"
                    v-on:click="setSection('lista')" title="Volver al listado">
                    <i class="fas fa-arrow-left me-1" aria-hidden="true"></i>
                    Volver
                </button>
            </div>

            <div>
                <label for="mapa-capa-base" class="form-label">Capa base</label>
                <select id="mapa-capa-base" class="form-select form-select-sm"
                    v-model="currentKeyCapa" v-on:change="changeMapCapa">
                    <option v-for="capa in capasBase" v-bind:key="capa.key_capa"
                        v-bind:value="capa.key_capa">
                        {{ capa.nombre }}
                    </option>
                </select>
            </div>

            <div>
                <label for="mapa-tema" class="form-label">Tema</label>
                <select id="mapa-tema" class="form-select form-select-sm"
                    v-model="mapTemaFiltro" v-on:change="changeMapTema">
                    <option value="">Todos los temas</option>
                    <option v-for="tema in mapTemas" v-bind:key="tema" v-bind:value="tema">
                        {{ tema }}
                    </option>
                </select>
            </div>

            <div class="variables-map-variable-control">
                <label for="mapa-variable" class="form-label">Variable</label>
                <select id="mapa-variable" class="form-select form-select-sm"
                    v-model="mapVariableId" v-on:change="changeMapVariable"
                    v-bind:disabled="variablesMapaFiltradas.length === 0">
                    <option value="">[ Seleccione una variable ]</option>
                    <option v-for="variable in variablesMapaFiltradas"
                        v-bind:key="variable.id" v-bind:value="String(variable.id)">
                        {{ variable.nombre }}
                    </option>
                </select>
            </div>

            <div class="variables-map-orientation">
                <label for="mapa-rotacion" class="form-label">Orientaci&oacute;n</label>
                <select id="mapa-rotacion" class="form-select form-select-sm"
                    v-model="mapRotationKey" v-on:change="changeMapRotation"
                    v-bind:disabled="rotaciones.length === 0">
                    <option v-for="rotacion in rotaciones" v-bind:key="rotacion.key"
                        v-bind:value="String(rotacion.key)">
                        {{ rotacion.nombre }} ({{ rotacion.value }}&deg;)
                    </option>
                </select>
            </div>

        </div>

        <div v-if="mapError" class="alert alert-danger variables-map-alert" role="alert">
            <i class="fas fa-exclamation-circle me-1" aria-hidden="true"></i>
            {{ mapError }}
        </div>

        <div class="variables-map-layout">
            <div class="variables-map-canvas-wrap">
                <div v-show="mapLoading" class="variables-map-loading" role="status">
                    <i class="fas fa-spinner fa-spin" aria-hidden="true"></i>
                    <span>Cargando mapa...</span>
                </div>
                <div id="variables-map-container" class="variables-map-canvas"></div>
                <div v-if="mapScale" class="variables-map-legend" aria-label="Escala de valores">
                    <div class="variables-map-legend-title">Valores de la variable</div>
                    <div class="variables-map-legend-gradient"
                        v-bind:style="{ background: 'linear-gradient(90deg, #f4f6f9, ' + mapScale.baseColor + ', ' + mapScale.darkColor + ')' }">
                    </div>
                    <div class="variables-map-legend-labels">
                        <span>{{ formatMapNumber(mapScale.min) }}</span>
                        <span>{{ formatMapNumber(mapScale.max) }}</span>
                    </div>
                    <small><span class="variables-map-no-data-swatch"></span> Sin dato</small>
                </div>
            </div>

            <aside class="variables-map-info">
                <div class="variables-map-context">
                    <span class="geofocus-base-kicker">GeoFocus</span>
                    <h1 id="variables-map-title">Mapa de variables</h1>
                    <p v-if="currentCapa">
                        <strong>{{ currentCapa.nombre }}</strong>
                        <small>
                            <i class="fas fa-draw-polygon me-1" aria-hidden="true"></i>
                            {{ currentCapa.cantidad_poligonos }} pol&iacute;gonos
                        </small>
                    </p>
                </div>

                <template v-if="currentMapVariable">
                    <div class="d-flex flex-wrap gap-2 mb-2">
                        <span class="tema" v-bind:class="textToClass(currentMapVariable.tema, 'tema')">
                            {{ currentMapVariable.tema }}
                        </span>
                        <span class="badge bg-light text-dark border">{{ currentMapVariable.subtema }}</span>
                    </div>

                    <h2>{{ currentMapVariable.nombre }}</h2>
                    <p class="variables-map-variable-description">{{ currentMapVariable.descripcion }}</p>

                    <dl class="variables-map-details">
                        <dt>A&ntilde;o</dt>
                        <dd>{{ displayValue(currentMapVariable.anio_valores) }}</dd>

                        <dt>Unidad</dt>
                        <dd>{{ displayValue(currentMapVariable.unidad_medida) }}</dd>

                        <dt>Rango</dt>
                        <dd>
                            {{ formatMapNumber(currentMapVariable.minimo) }} a
                            {{ formatMapNumber(currentMapVariable.maximo) }}
                        </dd>

                        <dt>Media</dt>
                        <dd>{{ formatMapNumber(currentMapVariable.media) }}</dd>

                        <dt>Valores</dt>
                        <dd>{{ displayValue(currentMapVariable.cantidad_valores) }}</dd>

                        <dt>Entidad</dt>
                        <dd>{{ displayValue(currentMapVariable.entidad) }}</dd>
                    </dl>
                </template>
            </aside>
        </div>
    </section>
</div>
