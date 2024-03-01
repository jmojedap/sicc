<div id="testApp">
    {{ medicion }}
    <hr>
    {{ pregunta }}
    <hr>
    <table class="table bg-white">
        <thead>
            <th>categories</th>
        </thead>
        <tbody>
            <tr v-for="(category, key) in categories">
                <td>{{ category }}</td>
            </tr>
        </tbody>
    </table>
    <hr>

    <table class="table bg-white">
        <thead>
            <th>name</th>
            <th>data</th>
        </thead>
        <tbody>
            <tr v-for="(serie, key) in series">
                <td>{{ serie.name }}</td>
                <td>{{ serie.data }}</td>
            </tr>
        </tbody>
    </table>
    <hr>

    {{ arrayValores(1) }}
</div>

<script>
// Preparación de variables
//-----------------------------------------------------------------------------
const sumatoriaEncuestados = <?= $sumatoria_encuestados ?>;
const variables = <?= json_encode($variables->result()) ?>;
var frecuencias = <?= json_encode($frecuencias->result()) ?>;
var opcionesInicial = <?= json_encode($opciones->result()) ?>;
var categories = variables.map(variable => variable.etiqueta_enunciado);
var series = [];

//Cargar valores
opcionesInicial.forEach(opcion => {
    var serie = {}
    serie['name'] = opcion.texto_opcion
    serie['data'] = valoresOpcion(opcion.codigo_opcion)
    series.push(serie)
});

// Funciones
//-----------------------------------------------------------------------------
function valoresOpcion(codigoOpcion)
{
    var frecuenciasFiltradas = frecuencias.filter(item => item.codigo_opcion == codigoOpcion)
    var arrayValores = frecuenciasFiltradas.map(item => item.frecuencia_ponderada / sumatoriaEncuestados)
    return arrayValores
}

// VueApp
//-----------------------------------------------------------------------------
var testApp = createApp({
    data(){
        return{
            loading: false,
            sumatoriaEncuestados: <?= $sumatoria_encuestados ?>,
            medicion: <?= json_encode($medicion) ?>,
            pregunta: <?= json_encode($pregunta) ?>,
            variables: <?= json_encode($variables->result()) ?>,
            opciones: opcionesInicial,
            categories: categories,
            series: series,
            frecuencias: <?= json_encode($frecuencias->result()) ?>,
        }
    },
    methods: {
        arrayValores: function(codigoOpcion){
            var frecuenciasFiltradas = this.frecuencias.filter(item => item.codigo_opcion == codigoOpcion)
            var arrayValores = frecuenciasFiltradas.map(item => item.frecuencia_ponderada / this.sumatoriaEncuestados)
            return arrayValores
        },
    },
    mounted(){
        //this.getList()
    }
}).mount('#testApp')
</script>