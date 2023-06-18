<script>
var pct_negativa = [];
var pct_neutral = [];
var pct_positiva = [];

Highcharts.theme = hc_sicc_theme;
Highcharts.setOptions(Highcharts.theme);

var chart = new Highcharts.Chart({
    chart: {
        zoomType: 'x',
        renderTo: "chart",
    },
    title: {
        text: 'Cantidad de noticias por mes'
    },
    subtitle: {
        text: 'elTiempo.com &middot; Media móvil anual'
    },
    tooltip: {
        xDateFormat: '%Y-%m',
        pointFormat: '{series.name}: <b>{point.y:.0f}</b>'
    },
    yAxis: {
        title: {
            text: '% noticias'
        },
        min:0,
    },
    xAxis: {
        title: {
            text: 'Fecha'
        },
        type: 'datetime',
    },
    credits: {
        enabled: false
    },
});

var serie1 = chart.addSeries({
    name: "Negativa",
    color: '#ee3248',
    data: pct_negativa,
});

var serie2 = chart.addSeries({
    name: "Neutral",
    color: '#999',
    data: pct_neutral,
});

var serie3 = chart.addSeries({
    name: "Positiva",
    color: '#30a338',
    data: pct_positiva,
});

var serie6 = chart.addSeries({
    name: "Prom. Negativa",
    color: '#f9b9c1',
    data: [
        [1480634639000,42],
        [1656717839000,42],
    ],
    dashStyle: 'Dot'
});


// Variables
//-----------------------------------------------------------------------------
var arrCat1 = <?= json_encode($arrCat1) ?>;

// VueApp
//-----------------------------------------------------------------------------
var vizApp = createApp({
    data() {
        return {
            cat_1: <?= intval($cat_1) ?>,
            loading: false,
            qtyRowsSeries: 0,
            arrCat1: arrCat1,
        }
    },
    methods: {
        updateSeries: function() {
            serie2.update({
                data: data3
            });
        },
        handleSubmit: function(){
            this.loading = true
            axios.get(URL_APP + 'noticias/get_qty_rows_series/' + this.cat_1)
            .then(response => {
                this.qtyRowsSeries = response.data.qty_rows_series
                if ( this.qtyRowsSeries == 0 ) {
                    this.updateSeries()
                } else {
                    this.getSeries()
                }
            })
            .catch(function(error) { console.log(error) })
        },
        updateSeries: function(){
            axios.get(URL_APP + 'noticias/update_series/' + this.cat_1)
            .then(response => {
                if ( response.data.qty_updated > 0 ) {
                    this.getSeries()
                }
            })
            .catch(function(error) { console.log(error) })
        },
        getSeries: function(){
            axios.get(URL_APP + 'noticias/get_series/' + this.cat_1)
            .then(response => {
                this.loading = false
                serie1.update({ data: response.data.cant_negativa })
                serie2.update({ data: response.data.cant_neutral })
                serie3.update({ data: response.data.cant_positiva })
            })
            .catch(function(error) { console.log(error) })
        },
        getQtyRowsSeries: function(){
            axios.get(URL_APP + 'noticias/get_qty_rows_series/' + this.cat_1)
            .then(response => {
                this.qtyRowsSeries = response.data.qty_rows_series
            })
            .catch(function(error) { console.log(error) })
        },
    },
    mounted() {
        this.getQtyRowsSeries()
        this.getSeries()
    }
}).mount('#vizApp')
</script>