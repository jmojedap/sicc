<!-- FullCalendar CDN -->
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>

<?php $this->load->view('app/barrios_vivos/style_v') ?>
<?php $this->load->view('app/barrios_vivos/calendario/style_v') ?>

<div id="bvCalendarApp">
    <div class="container">
        <div id="calendar"></div>
    </div>

    <!-- Modal Bootstrap 5 -->
    <div class="modal fade" id="eventModal" tabindex="-1" aria-labelledby="eventModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="eventModalLabel">{{ currentEvent.title }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <table class="table">
                        <tr v-if="currentEvent.extendedProps?.laboratorio_nombre">
                            <td class="td-title">Laboratorio</td>
                            <td>
                                <a v-bind:href="'<?= URL_APP ?>barrios_vivos/actividades/' + currentEvent.extendedProps.laboratorio_id">
                                    {{ currentEvent.extendedProps.laboratorio_nombre }}
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <td class="td-title">Fecha</td>
                            <td>{{ formatDate(currentEvent.start) }}
                                <br>
                                <small class="text-muted">{{ ago(currentEvent.start) }}</small>
                            </td>
                        </tr>
                        <tr v-if="currentEvent.extendedProps?.direccion">
                            <td class="td-title">
                                Dirección
                            </td>
                            <td>
                                <i class="color-text-6 fas fa-map-marker-alt me-2"></i>
                                <a :href="'https://www.google.com/maps/search/?api=1&query=' + encodeURIComponent(currentEvent.extendedProps.direccion + ' ' + currentEvent.extendedProps.lugar)" target="_blank">
                                    {{ currentEvent.extendedProps.direccion }} &middot;
                                    {{ currentEvent.extendedProps.lugar }}
                                </a>
                            </td>
                        </tr>
                        
                        <tr v-if="currentEvent.extendedProps?.fase_barrios_vivos">
                            <td class="td-title">Fase</td>
                            <td>
                                <span class="fase" v-bind:class="`fase-` + textToClass(currentEvent.extendedProps.fase_barrios_vivos)">
                                    {{ currentEvent.extendedProps.fase_barrios_vivos }}
                                </span>
                                <br>
                                <small class="text-muted">{{ currentEvent.extendedProps.fase_laboratorio }}</small>
                            </td>
                        </tr>
                        <tr v-if="currentEvent.extendedProps?.descripcion">
                            <td class="td-title">Descripción</td>
                            <td>{{ currentEvent.extendedProps.descripcion }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>

<?php $this->load->view('app/barrios_vivos/calendario/vue_v') ?>