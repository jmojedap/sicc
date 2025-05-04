<?php $this->load->view('assets/highcharts') ?>

<figure class="highcharts-figure" style="margin-bottom: 0px;">
    <div id="chart" style="min-height: calc(100vh - 0px);"></div>
</figure>

<script>
// Preparación de variables
//-----------------------------------------------------------------------------
const colors = {
    base:'#0084bf',
    si:'#00ad7c',
};
const pregunta = <?= json_encode($pregunta) ?>;
const sumatoriaEncuestados = <?= $sumatoria_encuestados ?>;
const variables = <?= json_encode($variables->result()) ?>;
const frecuencias = <?= json_encode($frecuencias->result()) ?>;

var opciones = <?= json_encode($opciones->result()) ?>;
var categories = [];
var series = [];
var serie = {name:'Porcentaje', data: [], pointPadding: 0.05, color: colors.base};

frecuencias.forEach(frecuencia => {
    categories.push(frecuencia.texto_opcion)
    serie.data.push(100 * frecuencia.frecuencia_ponderada / sumatoriaEncuestados)
});

series.push(serie)

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
        enabled: false,
        reversed: true
    },
    plotOptions: {
        series: {
            dataLabels: {
                enabled: true,
                format: '{point.y:.0f}%'
            },
            groupPadding: 0.1,
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