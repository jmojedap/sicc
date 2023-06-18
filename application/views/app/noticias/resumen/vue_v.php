<script>
// Libraries
//-----------------------------------------------------------------------------
/*Vue.use(HighchartsVue.default)
Highcharts.theme = hc_sicc_theme;
Highcharts.setOptions(Highcharts.theme);*/

// Variables
//-----------------------------------------------------------------------------
var arrClasificacion = <?= json_encode($arrClasificacion) ?>;

var resultadosApp = createApp({
    data(){
        return{
            loading: false,
            fields: {},
            resultadosClasificacion: <?= json_encode($resultadosClasificacion->result()) ?>,
            resultadosClasificacionSummary: <?= json_encode($resultadosClasificacionSummary) ?>,
            arrClasificacion: arrClasificacion,
            resultadosClasificador: <?= json_encode($resultadosClasificador->result()) ?>,
            resultadosClasificadorSummary: <?= json_encode($resultadosClasificadorSummary) ?>,
            resultadosAnio: <?= json_encode($resultadosAnio->result()) ?>,
            resultadosAnioSummary: <?= json_encode($resultadosAnioSummary) ?>,
        }
    },
    methods: {
        intPercent: function(value, sum){
            if (sum == 0) return 0
            return parseInt(100*value/sum)
        },
        clasificacionName: function(value = '', field = 'name'){
            var clasificacionName = ''
            var item = arrClasificacion.find(row => row.value == value)
            if ( item != undefined ) clasificacionName = item[field]
            return clasificacionName
        },
    },
    mounted(){
        //this.getList()
    }
}).mount('#resultadosApp')
</script>