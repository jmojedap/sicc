<script>
// Filters
//-----------------------------------------------------------------------------
Vue.filter('date_format', function (date) {
    if (!date) return ''
    return moment(date).format('D MMM YYYY')
});

// VueApp
//-----------------------------------------------------------------------------
var reporte_plan_app = new Vue({
    el: '#reporte_plan_app',
    created: function(){
        this.set_periodos()
    },
    data: {
        loading: false,
        obligaciones: <?= json_encode($obligaciones->result()) ?>,
        actividades: <?= json_encode($actividades->result()) ?>,
        bitacora: <?= json_encode($bitacora->result()) ?>,
        meses: [
            {'id':202202, 'title': 'Feb'},
            {'id':202203, 'title': 'Mar'},
            {'id':202204, 'title': 'Abr'},
            {'id':202205, 'title': 'May'},
            {'id':202206, 'title': 'Jun'},
            {'id':202207, 'title': 'Jul'},
            {'id':202208, 'title': 'Ago'},
            {'id':202209, 'title': 'Sep'},
            {'id':202210, 'title': 'Oct'},
            {'id':202211, 'title': 'Nov'},
        ],
    },
    methods: {
        set_periodos: function(){
            this.actividades.forEach(actividad => {
                actividad['periodos_array'] = JSON.parse(actividad.periodos)
            });
        },
    }
})
</script>