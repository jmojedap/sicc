<?php
    $dataNegativa = [];
    $dataPositiva = [];

    $listCat1 = [];
    $listCantNoticias = [];
    foreach ($arrCat1 as $cat1) {
        $catSummary = $this->Noticia_model->category_summary($cat1['id']);
        $dataNegativa[] = -1 * $catSummary['pct_negativa'];
        $dataPositiva[] = $catSummary['pct_positiva'];
        $listCantNoticias[] = $catSummary['cant_noticias'] . ' noticias';
        $listCat1[] = $cat1['name'];
    }
?>

<script>
var arrCat1 = <?= json_encode($arrCat1) ?>;
var listCat1 = <?= json_encode($listCat1) ?>;
var listCantNoticias = <?= json_encode($listCantNoticias) ?>;
var dataNegativa = <?= json_encode($dataNegativa) ?>;
var dataPositiva = <?= json_encode($dataPositiva) ?>;

Highcharts.theme = hc_sicc_theme;
Highcharts.setOptions(Highcharts.theme);

var chart = new Highcharts.Chart({
    chart: {
        type: 'bar',
        renderTo: "chart",
    },
    title: {
        text: 'Clasificación de noticias por Tema'
    },
    accessibility: {
        point: {
            valueDescriptionFormat: '{index}. Age {xDescription}, {value}%.'
        }
    },
    xAxis: [{
        categories: listCat1,
        reversed: false,
        labels: {
            step: 1
        },
        accessibility: {
            description: 'Age (male)'
        },
    }, { // mirror axis on right side
        opposite: true,
        reversed: false,
        categories: listCat1,
        linkedTo: 0,
        labels: {
            step: 1
        },
        accessibility: {
            description: 'Age (female)'
        }
    }],
    yAxis: {
        min:-100,
        max:100,
        title: {
            text: null
        },
        labels: {
            formatter: function () {
                return Math.abs(this.value) + '%';
            }
        },
        accessibility: {
            description: 'Percentage population',
            rangeDescription: 'Range: 0 to 5%'
        }
    },
    plotOptions: {
        series: {
            stacking: 'normal',
            dataLabels: {
                enabled: true,
                align: 'center',
                formatter: function () {
                    return Highcharts.numberFormat(Math.abs(this.point.y), 0) + '%';
                }
            },
        },
    },

    tooltip: {
        formatter: function () {
            return '<b>' + this.series.name + '<br>' + this.point.category + ': </b>' +
                Highcharts.numberFormat(Math.abs(this.point.y), 1) + '%';
        }
    },
    credits: {
        enabled: false
    },
});

var serieNegativa = chart.addSeries({
    name: "Negativa",
    color: '#ee3248',
    data: dataNegativa,
});

var seriePositiva = chart.addSeries({
    name: "Positiva",
    color: '#30a338',
    data: dataPositiva,
});


// Variables
//-----------------------------------------------------------------------------
var arrCat1 = <?= json_encode($arrCat1) ?>;

// VueApp
//-----------------------------------------------------------------------------
var vizApp = createApp({
    data() {
        return {
            years: [
                {name: 'Total', value:'0'},
                {name: '2016', value:'2016'},
                {name: '2017', value:'2017'},
                {name: '2018', value:'2018'},
                {name: '2019', value:'2019'},
                {name: '2020', value:'2020'},
                {name: '2021', value:'2021'},
                {name: '2022', value:'2022'},
            ],
            year: <?= intval($year) ?>,
            loading: false,
            qtyRowsSeries: 0,
            arrCat1: arrCat1,
        }
    },
    methods: {
        handleSubmit: function(){
            this.loading = true
            this.getSeries()
        },
        getSeries: function(){
            axios.get(URL_APP + 'noticias/get_resultados_categoria/' + this.year)
            .then(response => {
                this.loading = false
                serieNegativa.update({ data: response.data.pct_negativa })
                seriePositiva.update({ data: response.data.pct_positiva })
            })
            .catch(function(error) { console.log(error) })
        },
        setYear: function(value){
            this.year = value
            this.getSeries()
        },
    },
    mounted() {
        this.getSeries()
    }
}).mount('#vizApp')
</script>