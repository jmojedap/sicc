<!-- Modal -->
<div class="modal fade" id="detailModal" tabindex="-1" role="dialog" aria-labelledby="detail_modal_label" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="detail_modal_label">{{ element.nombre_accion }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <table class="table table-borderless table-sm">
                    <tr>
                        <td>ID Acción</td>
                        <td>{{ element.id }}</td>
                    </tr>
                    <tr>
                        <td>Nombre</td>
                        <td>
                            {{ element.nombre_accion }}
                        </td>
                    </tr>
                    <tr>
                        <td>Descripcion</td>
                        <td>
                            {{ element.descripcion }}
                        </td>
                    </tr>
                    <tr>
                        <td>Fecha</td>
                        <td>
                            {{ element.fecha }} {{ element.hora_inicio }} {{ element.hora_fin }}
                        </td>
                    </tr>
                    <tr>
                        <td>Localidad</td>
                        <td>
                            {{ localidadName(element.cod_localidad) }}
                        </td>
                    </tr>
                    <tr>
                        <td>Lugar</td>
                        <td>
                            {{ element.nombre_lugar }} &middot; {{ element.direccion }}
                        </td>
                    </tr>
                    <tr>
                        <td>Área</td>
                        <td>
                            {{ element.dependencia }} &middot; {{ element.equipo_trabajo }}
                        </td>
                    </tr>
                    <tr>
                        <td>Estrategia</td>
                        <td>
                            {{ programaName(element.programa) }} &middot; {{ estrategiaName(element.estrategia) }}
                        </td>
                    </tr>
                    <tr>
                        <td>Link evidencia</td>
                        <td>
                            <a v-bind:href="element.url_evidencia" target="_blank">{{ element.url_evidencia }}</a>
                        </td>
                    </tr>
                </table>
            </div>
            <div class="modal-footer">
                    <a class="btn btn-primary w100p" v-bind:href="`<?= URL_APP . 'acciones/info/' ?>` + element.id">Abrir</a>
                    <button type="button" class="btn btn-light w100p" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>