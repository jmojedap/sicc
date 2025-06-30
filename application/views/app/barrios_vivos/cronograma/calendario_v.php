<?php
    $arrEvents = [];
    foreach ($events->result_array() as $rowEvent) {
        $event = $rowEvent;
        $event['title'] = $rowEvent['nombre'];
        $event['start'] = $rowEvent['fecha'] . ' ' . $rowEvent['hora_inicio'];
        $event['end'] = $rowEvent['fecha'] . ' ' . $rowEvent['hora_fin'];

        $arrEvents[] = $event;
    }
?>

<!-- FullCalendar CDN -->
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>

<div id="bvCalendarApp">
    <div class="center_box_920">
        <div id="calendar">

        </div>
    </div>

    <!-- Modal Bootstrap 5 -->
    <div class="modal fade" id="eventModal" tabindex="-1" aria-labelledby="eventModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="eventModalLabel">{{ currentEvent.title }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <p><strong>Fecha:</strong> {{ currentEvent.start }}</p>
                    <p v-if="currentEvent.extendedProps?.descripcion">
                        <strong>Laboratorio:</strong>
                        {{ currentEvent.extendedProps.laboratorio_nombre }}
                        <br>
                        <strong>Descripción:</strong>
                        {{ currentEvent.extendedProps.descripcion }}
                        <br>
                        <strong>Lugar:</strong>
                        {{ currentEvent.extendedProps.direccion }} &middot; {{ currentEvent.extendedProps.lugar }}
                        <br>
                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

</div>

<script>
// Variables
//-----------------------------------------------------------------------------
const bvEvents = <?= json_encode($arrEvents) ?>;

// VueApp
//-----------------------------------------------------------------------------
var bvCalendarApp = createApp({
    data() {
        return {
            loading: false,
            currentEvent: {},
        }
    },
    methods: {

    },
    mounted() {
        const calendarEl = document.getElementById('calendar');

        const calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            locale: 'es',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay'
            },
            buttonText: {
                today:    'Hoy',
                month:    'Mes',
                week:     'Semana',
                day:      'Día',
                list:     'Lista'
            },
            events: bvEvents,
            eventClick: (info) => {
                this.currentEvent = {
                    title: info.event.title,
                    start: info.event.startStr,
                    extendedProps: info.event.extendedProps
                };
                
                // Mostrar el modal de Bootstrap 5
                const modal = new bootstrap.Modal(document.getElementById('eventModal'));
                modal.show();
            }
        });

        calendar.render();
    }
}).mount('#bvCalendarApp')
</script>