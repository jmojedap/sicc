<div id="actividades_app">
    <h1 class="text-center">Plan de acción</h1>    
    <p class="text-center">{{ actividades.length }} actividades</p>
    <table class="table bg-white">
        <thead>
            <th>Cód</th>
            <th>Tipo</th>
            <th>Actividad</th>
            <th>% Ejecutado</th>
            <th v-for="mes in meses">{{ mes.title }}</th>
        </thead>
        <tbody>
            <tr v-for="(actividad, key) in actividades">
                <td width="20px" class="text-center">{{ actividad.code }}</td>
                <td width="250px">
                    <strong class="text-main">{{ display_tipo(key) }}</strong>
                </td>
                <td>
                    <strong class="text-main">{{ actividad.titulo }}</strong>
                    <br>
                    {{ actividad.detalle }}
                </td>
                <td>{{ actividad.pct_ejecutado }} %</td>
                <td class="text-center" v-for="mes in meses" v-bind:class="{'table-warning': actividad.periodos_array.includes(mes.id) }">
                    <!-- <i class="fa fa-times" v-show="actividad.periodos_array.includes(mes.id)"></i> -->
                    <span class="text-muted" v-show="actividad.periodos_array.includes(mes.id)">{{ mes.title }}</span>
                </td>
            </tr>
        </tbody>
    </table>
</div>

<script>
var actividades_app = new Vue({
    el: '#actividades_app',
    created: function(){
        this.set_periodos()
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
        set_periodos: function(){
            this.actividades.forEach(actividad => {
                actividad['periodos_array'] = JSON.parse(actividad.periodos)
            });
        },
        display_tipo: function(key){
            tipo = this.actividades[key].tipo_actividad
            if ( key > 0 ) {
                previous_tipo = this.actividades[key - 1].tipo_actividad
                if ( tipo == previous_tipo ) {
                    tipo = ''
                }
            } 
            return tipo
        },
    }
})
</script>