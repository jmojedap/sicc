<div id="actividades_app">
    <h1 class="text-center">Bitácora de actividades</h1>    
    <p class="text-center">{{ actividades.length }} actividades</p>
    <table class="table bg-white">
        <thead>
            <th>Cód</th>
            <th>Fecha</th>
            <th>Título</th>
            <th>Actividad</th>
        </thead>
        <tbody>
            <tr v-for="(actividad, key) in actividades">
                <td width="20px" class="text-center">{{ actividad.actividad_id }}</td>
                <td>{{ actividad.fecha | date_format }}</td>
                <td width="250px">
                    <strong class="text-main">{{ actividad.titulo }}</strong>
                </td>
                <td>
                    {{ actividad.descripcion }}
                </td>
            </tr>
        </tbody>
    </table>
</div>

<script>
// Filters
//-----------------------------------------------------------------------------
    Vue.filter('date_format', function (date) {
        if (!date) return ''
        return moment(date).format('D MMM YYYY')
    });

    Vue.filter('ago', function (date) {
        if (!date) return ''
        return moment(date, 'YYYY-MM-DD HH:mm:ss').fromNow()
    });

// VueApp
//-----------------------------------------------------------------------------
var actividades_app = new Vue({
    el: '#actividades_app',
    created: function(){
        //this.set_periodos()
    },
    data: {
        actividades: <?= json_encode($actividades->result()) ?>,
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
        loading: false,
    },
    methods: {
        
    }
})
</script>