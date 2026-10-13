<!-- 2026-10-12: artículo del escenario calculado; comparte los resultados y la descripción con el mapa. -->
<article class="geofocus-priority-section geofocus-base-content geofocus-results-article"
    v-show="section == 'resultados'" aria-labelledby="priority-results-title">
    <header class="geofocus-results-header">
        <div>
            <span class="geofocus-base-kicker">GeoFocus · Resultados</span>
            <h1 id="priority-results-title">{{ priorizacion.nombre }}</h1>
            <p v-if="priorizacion.descripcion" class="geofocus-results-intro">{{ priorizacion.descripcion }}</p>
        </div>
        <div class="geofocus-priority-actions">
            <button type="button" class="btn btn-light btn-sm" v-on:click="actualizarMapa('mapa')"
                v-bind:disabled="loading">
                <i class="fas fa-map-marked-alt me-1" aria-hidden="true"></i> Ver mapa
            </button>
            <a v-if="!priorizacionPendienteDeCalculo && !loading && resultadosTop.length"
                class="btn btn-success btn-sm" href="<?= URL_APP . "geofocus/export/{$row->id}" ?>">
                <i class="fas fa-download me-1" aria-hidden="true"></i> Descargar Excel
            </a>
            <span v-else class="btn btn-success btn-sm disabled" aria-disabled="true">
                <i class="fas fa-download me-1" aria-hidden="true"></i> Descargar Excel
            </span>
        </div>
    </header>

    <div v-if="loading" class="geofocus-priority-loading" role="status">
        <span class="spinner-border spinner-border-sm" aria-hidden="true"></span>
        Calculando priorización…
    </div>
    <p v-else-if="priorizacionPendienteDeCalculo" class="geofocus-results-empty" role="status">
        Hay cambios pendientes. Pulsa <strong>Calcular</strong> en Variables para actualizar este artículo.
    </p>
    <p v-else-if="!resultadosTop.length" class="geofocus-results-empty">
        Esta priorización aún no tiene resultados. Selecciona las variables y pulsa <strong>Calcular</strong>.
    </p>

    <!-- 2026-10-12: presentar solamente la configuración confirmada, sin mezclarla con ediciones pendientes. -->
    <div v-else class="geofocus-results-body">
        <dl class="geofocus-results-summary">
            <div v-if="capaBase"><dt>Ámbito territorial</dt><dd>{{ capaBase.nombre }}</dd></div>
            <div><dt>Criterios seleccionados</dt><dd>{{ resultadosVariables.length }} variables</dd></div>
            <div><dt>Territorios destacados</dt><dd>{{ resultadosTop.length }} resultados</dd></div>
        </dl>

        <section class="geofocus-results-section" aria-labelledby="results-criteria-title">
            <div class="geofocus-results-section-heading">
                <span aria-hidden="true">01</span>
                <h2 id="results-criteria-title">Variables y criterios</h2>
            </div>
            <p class="geofocus-results-note">Configuración utilizada en el cálculo. Los pesos se expresan en puntos.</p>
            <ul class="geofocus-results-criteria">
                <li v-for="variable in resultadosVariables" v-bind:key="variable.id || variable.clave"
                    class="geofocus-results-criterion" v-bind:style="{ borderLeftColor: variable.color || '#654a98' }">
                    <div class="geofocus-results-criterion-copy">
                        <div class="geofocus-results-variable-theme" v-if="variable.tema">
                            {{ variable.tema }}<span v-if="variable.subtema"> · {{ variable.subtema }}</span>
                        </div>
                        <h3>{{ variable.nombre }}</h3>
                        <p v-if="variable.descripcion">{{ variable.descripcion }}</p>
                        <dl class="geofocus-results-variable-data">
                            <div v-if="variable.anio_valores"><dt>Año</dt><dd>{{ variable.anio_valores }}</dd></div>
                            <div v-if="variable.unidad_medida"><dt>Unidad</dt><dd>{{ variable.unidad_medida }}</dd></div>
                            <div v-if="variable.entidad"><dt>Entidad</dt><dd>{{ variable.entidad }}</dd></div>
                            <div v-if="variable.minimo != null && variable.maximo != null">
                                <dt>Rango</dt><dd>{{ formatNumber(variable.minimo) }} – {{ formatNumber(variable.maximo) }}</dd>
                            </div>
                        </dl>
                    </div>
                    <dl class="geofocus-results-criterion-settings">
                        <div><dt>Peso</dt><dd class="geofocus-results-weight">{{ formatNumber(variable.puntaje) }} <small>puntos</small></dd></div>
                        <div><dt>Sentido</dt><dd>{{ Number(variable.tipo_priorizacion) === 1 ? 'Priorizar valores altos' : 'Priorizar valores bajos' }}</dd></div>
                    </dl>
                </li>
            </ul>
        </section>

        <section class="geofocus-results-section" aria-labelledby="results-description-title"
            aria-live="polite" v-bind:aria-busy="descripcion.loading">
            <div class="geofocus-results-section-heading">
                <span aria-hidden="true">02</span>
                <h2 id="results-description-title">Lectura de la priorización</h2>
            </div>
            <p class="geofocus-results-note">Descripción de los criterios generada con IA.</p>
            <p v-if="descripcion.error" class="text-danger small" role="alert">{{ descripcion.error }} Puedes reintentar desde Mapa.</p>
            <div v-if="descripcion.loading" class="geofocus-priority-description-loading" role="status">
                <span class="spinner-border spinner-border-sm" aria-hidden="true"></span> Generando análisis…
            </div>
            <div v-else-if="descripcion.texto" class="geofocus-priority-markdown geofocus-results-analysis"
                v-html="descripcionHtml"></div>
            <p v-else class="geofocus-results-note">La descripción aún no se ha generado. Puedes solicitarla desde Mapa.</p>
        </section>

        <section class="geofocus-results-section" aria-labelledby="results-territories-title">
            <div class="geofocus-results-section-heading">
                <span aria-hidden="true">03</span>
                <h2 id="results-territories-title">Territorios con mayor prioridad</h2>
            </div>
            <p class="geofocus-results-note">Los primeros 15 territorios disponibles, ordenados por su puntaje de priorización.</p>
            <div class="table-responsive geofocus-priority-results-table">
                <table class="table table-hover align-middle mb-0">
                    <caption class="visually-hidden">Top 15 territorios de {{ priorizacion.nombre }}</caption>
                    <thead>
                        <tr>
                            <th scope="col">Orden</th><th scope="col">Territorio</th><th scope="col">UPZ</th>
                            <th scope="col">Localidad</th><th scope="col">Sector</th><th scope="col" class="text-end">Puntaje</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(territorio, key) in resultadosTop" v-bind:key="territorio.id || territorio.territorio_id || key">
                            <td><span class="geofocus-results-rank">{{ territorio.orden || key + 1 }}</span></td>
                            <th scope="row">{{ territorio.nombre }}</th>
                            <td>{{ territorio.upz || '—' }}</td><td>{{ territorio.localidad || '—' }}</td>
                            <td>
                                <span class="sector" v-bind:class="classSector(territorio.cod_localidad)">
                                    {{ localidadValor(territorio.cod_localidad, 'sector') || '—' }}
                                </span>
                            </td>
                            <td class="text-end geofocus-results-score">{{ formatNumber(territorio.valor) }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>
    </div>
</article>
