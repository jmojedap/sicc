<div v-show="section == 'mapa'" class="geofocus-priority-map-page">
    <section class="geofocus-base-content geofocus-priority-map-card"
        aria-labelledby="priority-map-title">
        <div class="geofocus-priority-map-controls">
            <button type="button" class="btn btn-sm"
                v-bind:class="mapMode == 'priorizacion' ? 'btn-primary' : 'btn-light'"
                v-on:click="showPrioritizationMap">
                <i class="fas fa-map-marked-alt me-1" aria-hidden="true"></i>
                Resultado general
            </button>

            <div>
                <label for="priority-map-theme" class="form-label">Tema</label>
                <select id="priority-map-theme" class="form-select form-select-sm"
                    v-model="currentTema" v-on:change="setTema">
                    <option value="">Todos los temas</option>
                    <option v-for="tema in mapTemas" v-bind:key="tema" v-bind:value="tema">
                        {{ tema }}
                    </option>
                </select>
            </div>

            <div class="geofocus-priority-map-variable-control">
                <label for="priority-map-variable" class="form-label">Variable</label>
                <select id="priority-map-variable" class="form-select form-select-sm"
                    v-model="currentVariableId" v-on:change="updateVariable"
                    v-bind:disabled="mapVariablesFiltradas.length === 0">
                    <option value="">[ Seleccione una variable ]</option>
                    <option v-for="variable in mapVariablesFiltradas"
                        v-bind:key="variable.id" v-bind:value="String(variable.id)">
                        {{ variable.nombre }}
                    </option>
                </select>
            </div>

            <div>
                <label for="priority-map-rotation" class="form-label">Orientaci&oacute;n</label>
                <select id="priority-map-rotation" class="form-select form-select-sm"
                    v-model="mapRotationKey" v-on:change="changeMapRotation"
                    v-bind:disabled="rotaciones.length === 0">
                    <option v-for="rotacion in rotaciones" v-bind:key="rotacion.key"
                        v-bind:value="String(rotacion.key)">
                        {{ rotacion.nombre }} ({{ rotacion.value }}&deg;)
                    </option>
                </select>
            </div>
        </div>

        <div v-if="mapError" class="alert alert-danger geofocus-priority-map-alert" role="alert">
            <i class="fas fa-exclamation-circle me-1" aria-hidden="true"></i>
            {{ mapError }}
        </div>

        <div class="geofocus-priority-map-layout">
            <div class="geofocus-priority-map-canvas-wrap">
                <div v-show="mapLoading" class="geofocus-priority-map-loading" role="status">
                    <i class="fas fa-spinner fa-spin" aria-hidden="true"></i>
                    <span>Cargando mapa...</span>
                </div>
                <div id="priorizacion-map-container" class="geofocus-priority-map-canvas"></div>

                <div v-if="mapScale" class="geofocus-priority-map-legend"
                    aria-label="Escala de valores">
                    <div class="geofocus-priority-map-legend-title">
                        {{ mapMode == 'priorizacion' ? 'Puntaje de priorizaci&oacute;n' : 'Valores de la variable' }}
                    </div>
                    <div class="geofocus-priority-map-legend-gradient"
                        v-bind:style="{ background: 'linear-gradient(90deg, #f4f6f9, ' + mapScale.baseColor + ', ' + mapScale.darkColor + ')' }">
                    </div>
                    <div class="geofocus-priority-map-legend-labels">
                        <span>{{ formatNumber(mapScale.min) }}</span>
                        <span>{{ formatNumber(mapScale.max) }}</span>
                    </div>
                    <small><span class="geofocus-priority-map-no-data-swatch"></span> Sin dato</small>
                </div>
            </div>

            <aside class="geofocus-priority-map-info">
                <div class="geofocus-priority-map-context">
                    <span class="geofocus-base-kicker">GeoFocus</span>
                    <h1 id="priority-map-title">Mapa de priorizaci&oacute;n</h1>
                    <p v-if="capaBase">
                        <strong>{{ capaBase.nombre }}</strong>
                        <small>
                            <i class="fas fa-draw-polygon me-1" aria-hidden="true"></i>
                            {{ capaBase.cantidad_poligonos }} pol&iacute;gonos
                        </small>
                    </p>
                </div>

                <div class="geofocus-priority-map-copy" v-if="mapMode == 'priorizacion'">
                    <h2>{{ priorizacion.nombre }}</h2>
                    <p>{{ priorizacion.descripcion }}</p>
                    <p v-if="descripcion.texto">
                        {{ descripcion.texto }}
                    </p>
                </div>

                <div class="geofocus-priority-map-copy" v-else-if="currentVariable">
                    <div class="d-flex flex-wrap gap-2 mb-2">
                        <span class="tema" v-bind:class="textToClass(currentVariable.tema, 'tema')">
                            {{ currentVariable.tema }}
                        </span>
                        <span class="badge bg-light text-dark border">{{ currentVariable.subtema }}</span>
                    </div>

                    <h2>{{ currentVariable.nombre }}</h2>
                    <p>{{ currentVariable.descripcion }}</p>

                    <dl class="geofocus-priority-map-details">
                        <dt>A&ntilde;o</dt>
                        <dd>{{ currentVariable.anio_valores || '-' }}</dd>

                        <dt>Unidad</dt>
                        <dd>{{ currentVariable.unidad_medida || '-' }}</dd>

                        <dt>Rango</dt>
                        <dd>
                            {{ formatNumber(currentVariable.minimo) }} a
                            {{ formatNumber(currentVariable.maximo) }}
                        </dd>

                        <dt>Media</dt>
                        <dd>{{ formatNumber(currentVariable.media) }}</dd>

                        <dt>Desviaci&oacute;n</dt>
                        <dd>{{ formatNumber(currentVariable.desviacion_estandar) }}</dd>

                        <dt>Entidad</dt>
                        <dd>{{ currentVariable.entidad || '-' }}</dd>
                    </dl>

                    <p class="geofocus-priority-map-notes" v-if="currentVariable.descripcion_calculo">
                        <span class="text-primary">C&aacute;lculo:</span>
                        {{ currentVariable.descripcion_calculo }}
                    </p>
                </div>
            </aside>
        </div>
    </section>
</div>
