<div
    class="modal fade"
    id="variableDetalleModal"
    tabindex="-1"
    aria-labelledby="variableDetalleModalLabel"
    aria-hidden="true"
>
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content variable-detail-modal">
            <div class="modal-header">
                <div>
                    <div class="d-flex flex-wrap gap-2 mb-2">
                        <span class="badge bg-primary" v-show="currentVariable.tema">
                            {{ currentVariable.tema }}
                        </span>
                        <span class="badge bg-light text-dark border" v-show="currentVariable.anio_valores">
                            Año {{ currentVariable.anio_valores }}
                        </span>
                    </div>
                    <h2 class="modal-title h4 mb-0" id="variableDetalleModalLabel">
                        {{ displayValue(currentVariable.nombre) }}
                    </h2>
                    <small class="text-muted" v-show="currentVariable.clave">
                        {{ currentVariable.clave }}
                    </small>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>

            <div class="modal-body">
                <section class="mb-4">
                    <h3 class="detail-section-title">Descripción</h3>
                    <p class="mb-0 detail-description">
                        {{ displayValue(currentVariable.descripcion) }}
                    </p>
                </section>

                <section class="mb-4">
                    <h3 class="detail-section-title">Información general</h3>
                    <dl class="row detail-list mb-0">
                        <dt class="col-sm-4 col-lg-3">Entidad</dt>
                        <dd class="col-sm-8 col-lg-9">{{ displayValue(currentVariable.entidad) }}</dd>

                        <dt class="col-sm-4 col-lg-3">Datos de origen</dt>
                        <dd class="col-sm-8 col-lg-9">{{ displayValue(currentVariable.datos_origen) }}</dd>

                        <dt class="col-sm-4 col-lg-3">Capa de origen</dt>
                        <dd class="col-sm-8 col-lg-9">{{ displayValue(currentVariable.capa_origen) }}</dd>

                        <dt class="col-sm-4 col-lg-3">Capa proyectada</dt>
                        <dd class="col-sm-8 col-lg-9">{{ displayValue(currentVariable.key_capa) }}</dd>

                        <dt class="col-sm-4 col-lg-3">Unidad de medida</dt>
                        <dd class="col-sm-8 col-lg-9">{{ displayValue(currentVariable.unidad_medida) }}</dd>

                        <dt class="col-sm-4 col-lg-3">Palabras clave</dt>
                        <dd class="col-sm-8 col-lg-9">{{ displayValue(currentVariable.palabras_clave) }}</dd>
                    </dl>
                </section>

                <section class="mb-4">
                    <h3 class="detail-section-title">Método de cálculo</h3>
                    <p class="mb-0 detail-description">
                        {{ displayValue(currentVariable.descripcion_calculo) }}
                    </p>
                </section>

                <section class="mb-4">
                    <h3 class="detail-section-title">Resumen estadístico</h3>
                    <div class="row g-2">
                        <div class="col-6 col-lg-3">
                            <div class="stat-card">
                                <span>Mínimo</span>
                                <strong>{{ displayValue(currentVariable.minimo) }}</strong>
                            </div>
                        </div>
                        <div class="col-6 col-lg-3">
                            <div class="stat-card">
                                <span>Media</span>
                                <strong>{{ displayValue(currentVariable.media) }}</strong>
                            </div>
                        </div>
                        <div class="col-6 col-lg-3">
                            <div class="stat-card">
                                <span>Desviación estándar</span>
                                <strong>{{ displayValue(currentVariable.desviacion_estandar) }}</strong>
                            </div>
                        </div>
                        <div class="col-6 col-lg-3">
                            <div class="stat-card">
                                <span>Máximo</span>
                                <strong>{{ displayValue(currentVariable.maximo) }}</strong>
                            </div>
                        </div>
                    </div>
                </section>

                <section v-show="currentVariable.notas">
                    <h3 class="detail-section-title">Notas</h3>
                    <p class="mb-0 detail-description">{{ currentVariable.notas }}</p>
                </section>
            </div>

            <div class="modal-footer">
                <a
                    v-if="hasDataLink(currentVariable.link_archivo_datos)"
                    class="btn btn-outline-primary"
                    v-bind:href="currentVariable.link_archivo_datos"
                    target="_blank"
                    rel="noopener noreferrer"
                >
                    <i class="fas fa-external-link-alt me-1" aria-hidden="true"></i>
                    Abrir archivo de datos
                </a>
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
