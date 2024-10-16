    <!-- Modal detalles de la variable actual -->
    <div class="modal fade" id="detallesModal" tabindex="-1" aria-labelledby="detallesModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="detallesModalLabel">{{ currentVariable.nombre }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>
                        {{ currentVariable.descripcion }}
                    </p>
                    <table class="table table-borderless">
                        <tr>
                            <td class="td-title">Tema</td>
                            <td>{{ currentVariable.tema }}</td>
                        </tr>
                        <tr>
                            <td class="td-title">Año datos</td>
                            <td>{{ currentVariable.anio_valores }}</td>
                        </tr>
                        <tr>
                            <td class="td-title">Cálculo</td>
                            <td>{{ currentVariable.descripcion_calculo }}</td>
                        </tr>
                        <tr>
                            <td class="td-title">Entidad</td>
                            <td>{{ currentVariable.entidad }}</td>
                        </tr>
                        <tr>
                            <td class="td-title">Min</td>
                            <td>
                                {{ currentVariable.min }}
                            </td>
                        </tr>
                        <tr>
                            <td class="td-title">Media</td>
                            <td>
                                {{ currentVariable.media }}
                            </td>
                        </tr>
                        <tr>
                            <td class="td-title">Desviación estándar</td>
                            <td>
                                {{ currentVariable.desviacion_estandar }}
                            </td>
                        </tr>
                        <tr>
                            <td class="td-title">Máx</td>
                            <td>
                                {{ currentVariable.max }}
                            </td>
                        </tr>
                        <tr>
                            <td class="td-title">Datos origen</td>
                            <td>{{ currentVariable.datos_origen }}</td>
                        </tr>
                        <tr>
                            <td class="td-title">key</td>
                            <td>{{ currentVariable.key }}</td>
                        </tr>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light w120p" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>