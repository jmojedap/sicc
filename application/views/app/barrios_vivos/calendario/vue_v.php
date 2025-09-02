<?php
    $colors = [];
    $colors[1] = '#dd3f2aff';
    $colors[2] = '#ebb728ff';
    $colors[3] = '#2785c0ff';
    $colors[4] = '#8125b3ff';

    $arrEvents = [];
    foreach ($events->result_array() as $rowEvent) {
        $event = $rowEvent;
        $num_fase = substr($rowEvent['fase_barrios_vivos'], 0, 1);
        $event['title'] = $rowEvent['nombre'];
        $event['start'] = $rowEvent['fecha'] . ' ' . $rowEvent['hora_inicio'];
        $event['end'] = $rowEvent['fecha'] . ' ' . $rowEvent['hora_fin'];
        $event['color'] = $colors[$num_fase] ?? '#e63946';

        $arrEvents[] = $event;
    }
?>

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
        textToClass: function(texto){
            return Pcrn.textToClass(texto)
        },
        formatDate: function(date) {
            // Date con moment.js
            return moment(date).format('DD MMMM YYYY HH:mm');
        },
        ago: function(date) {
            return moment(date).fromNow();
        }
    },
    mounted() {
        const calendarEl = document.getElementById('calendar');

        const calendar = new FullCalendar.Calendar(calendarEl, {
            themeSystem: 'bootstrap5',
            initialView: 'dayGridMonth',
            locale: 'es',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay list'
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