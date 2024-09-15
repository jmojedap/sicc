<script src="https://cdnjs.cloudflare.com/ajax/libs/proj4js/2.10.0/proj4.js"
    integrity="sha512-e3rsOu6v8lmVnZylXpOq3DO/UxrCgoEMqosQxGygrgHlves9HTwQzVQ/dLO+nwSbOSAecjRD7Y/c4onmiBVo6w=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://code.highcharts.com/maps/highmaps.js"></script>
<script src="https://code.highcharts.com/maps/modules/exporting.js"></script>
<script src="https://code.highcharts.com/maps/modules/tiledwebmap.js"></script>
<script src="https://code.highcharts.com/maps/modules/offline-exporting.js"></script>
<script src="https://code.highcharts.com/maps/modules/accessibility.js"></script>

<?php $this->load->view('app/geofocus/highmaps/style_v') ?>

<script>
    // Declara la variable en el ámbito global, accesible para VueApp
    let mapChartBogota;
    let localidades;

    (async () => {
        const mapData = await fetch(
            '<?= URL_CONTENT ?>maps/localidades_bogota_urbano.json'
        ).then(response => response.json());
        localidades = Highcharts.geojson(mapData, 'map');

        // Initialize the chart
        mapChartBogota = Highcharts.mapChart('container', {

            title: {
                text: 'Localidades de Bogotá',
                align: 'left'
            },

            legend: {
                align: 'left',
                layout: 'vertical',
                floating: true
            },

            accessibility: {
                point: {
                    valueDescriptionFormat: '{xDescription}.'
                }
            },

            mapNavigation: {
                enabled: true
            },

            /*tooltip: {
                // Cambiamos el formato para que muestre el nombre del polígono
                pointFormat: '{point.properties.NOMBRE}'
            },*/

            plotOptions: {
                series: {
                    marker: {
                        fillColor: '#FFFFFF',
                        lineWidth: 2,
                        lineColor: Highcharts.getOptions().colors[1]
                    }
                }
            },

            series: [
                {
                    type: 'tiledwebmap',
                    name: 'Basemap Tiles',
                    provider: {
                        type: 'Esri',
                        theme: 'WorldGrayCanvas',
                    },
                    showInLegend: false
                },
                {
                    name: 'Localidades',
                    data: localidades,
                    borderColor: '#FFF',
                    nullColor: 'rgba(200, 200, 200, 0.3)',
                    showInLegend: true,
                    color: Highcharts.color("#D9D2E9")
                        .setOpacity(0.75)
                        .get(),
                    states: {
                        hover: {
                            color: '#C53C99'
                        }
                    },
                    dataLabels: {
                        enabled: true,
                        format: '{point.properties.NOMBRE}',
                        style: {
                            width: '80px', // force line-wrap
                            textTransform: 'uppercase',
                            fontWeight: 'normal',
                            textOutline: 'none',
                            color: '#888'
                        }
                    },
                    tooltip: {
                        pointFormat: '{point.properties.NOMBRE}'
                    }
                },
                {
                    // Specify cities using lat/lon
                    type: 'mappoint',
                    name: 'Barrios',
                    dataLabels: {
                        format: '{point.id}'
                    },
                    // Use id instead of name to allow for referencing points later
                    // using
                    // chart.get
                    data: []
                }
            ]
        });
    })();
</script>

<div id="highMapsApp">
    <div class="d-flex">
        <button class="btn btn-light" v-on:click="setPuntos" id="get-points">
            Actualizar
        </button>
    </div>
    <div id="container"></div>
    <div>
        {{ puntos }}
    </div>
</div>

<script>
var highMapsApp = createApp({
    data(){
        return{
            loading: false,
            fields: {},
            puntos: [
                { id: 'Centro', lat: 4.5, lon: -74.1 },
                { id: 'La playa', lat: 4.5, lon: -74.2 }
            ],
            localidades: [],
        }
    },
    methods: {
        setPuntos: function(){
            console.log(this.puntos)
            mapChartBogota.series[1].update({data: this.puntos})
            console.log(localidades)
        },
    },
    mounted(){
        //this.getList()
    }
}).mount('#highMapsApp')
</script>
