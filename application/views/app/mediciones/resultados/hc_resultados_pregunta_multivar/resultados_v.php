<?php $this->load->view('assets/highcharts') ?>

<figure class="highcharts-figure" style="margin-bottom: 0px;">
    <div id="chart" style="min-height: calc(100vh - 0px);"></div>
</figure>

<script>
// Preparación de variables
//-----------------------------------------------------------------------------
const pregunta = <?= json_encode($pregunta) ?>;
const sumatoriaEncuestados = <?= $sumatoria_encuestados ?>;
const variables = <?= json_encode($variables->result()) ?>;
const frecuencias = <?= json_encode($frecuencias->result()) ?>;
var opciones = <?= json_encode($opciones->result()) ?>;
var categories = variables.map(variable => variable.etiqueta_enunciado);
var series = [];

//Cargar valores
opciones.forEach(opcion => {
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
    var arrayValores = frecuenciasFiltradas.map(item => 100 * item.frecuencia_ponderada / sumatoriaEncuestados)
    return arrayValores
}

// Construcción del gráfico
//-----------------------------------------------------------------------------
Highcharts.theme = hc_crb_theme;
Highcharts.setOptions(Highcharts.theme);

Highcharts.chart({
    chart: {
        type: 'bar',
        renderTo: "chart",
    },
    title: {
        text: pregunta.enunciado_1,
        style: {
            color: '#777' // Cambia el color del título aquí
        }
    },
    xAxis: {
        categories: categories
    },
    yAxis: {
        min: 0,
        max: 100,
        title: {
            text: 'Porcentaje'
        }
    },
    legend: {
        reversed: true
    },
    plotOptions: {
        series: {
            stacking: 'normal',
            dataLabels: {
                enabled: true,
                format: '{point.y:.0f}%'
            }
        }
    },
    tooltip: {
        formatter: function () {
            return '<b>' + this.x + '</b><br/>' +
                this.series.name + ': ' + Highcharts.numberFormat(this.y, 1) + '%';
        }
    },
    series: series,
    credits: {
        enabled: false
    },

});
</script>