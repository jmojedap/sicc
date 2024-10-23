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
    let barrios;

    (async () => {
        const mapData = await fetch(
            '<?= URL_CONTENT ?>maps/barrios_bogota_geofocus_urbano.json'
        ).then(response => response.json());
        barrios = Highcharts.geojson(mapData, 'map');

        const territoriosData = await fetch(
            'http://localhost/sicc/api/geofocus/get_variable_valores/priorizacion_id/1'
        ).then(response => response.json());

        // Initialize the chart
        mapChartBogota = Highcharts.mapChart('container', {
            chart: {
                map: mapData,
                height: '80%'
            },

            title: {
                text: 'Barrios de Bogotá',
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

            colorAxis: {
                min: -1,
                max: 4,
                tickInterval: 1,
                /*stops: [[0, '#F1EEF6'], [0.65, '#900037'], [1, '#500007']],*/
                stops: [[0, '#F1EEF6'], [0.65, '#AA0066']],
                labels: {
                    format: '{value}'
                }
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
                /*{
                    type: 'tiledwebmap',
                    name: 'Basemap Tiles',
                    provider: {
                        type: 'Esri',
                        theme: 'WorldGrayCanvas',
                    },
                    showInLegend: false
                },*/
                {
                    data: territoriosData,
                    joinBy: ['ID_BARRIO', 'code'],
                    name: 'Puntaje',
                    tooltip: {
                        valueSuffix: ''
                    },
                    borderWidth: 0.5,
                    shadow: false,
                    accessibility: {
                        enabled: false
                    }
                },
                {
                    type: 'mapline',
                    name: 'Barrios',
                    color: 'white',
                    shadow: false,
                    borderWidth: 5,
                    accessibility: {
                        enabled: false
                    }
                }
                /*{
                    name: 'Barrios',
                    data: barrios,
                    borderColor: '#FFF',
                    nullColor: 'rgba(200, 200, 200, 0.3)',
                    showInLegend: true,
                    color: Highcharts.color("#D9D2E9")
                        .setOpacity(0.75)
                        .get(),
                    states: {
                        hover: {
                            color: '#C53C99',
                            borderColor: '#FFF',
                        }
                    },
                    dataLabels: {
                        enabled: false,
                        format: '{point.properties.BARRIO}',
                        style: {
                            width: '80px', // force line-wrap
                            textTransform: 'uppercase',
                            fontWeight: 'normal',
                            textOutline: 'none',
                            color: '#888'
                        }
                    },
                    tooltip: {
                        pointFormat: '{point.properties.BARRIO}'
                    }
                }*/
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
        {{ barrios.length }}
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
            barrios: [],
        }
    },
    methods: {
        setPuntos: function(){
            /*console.log(this.puntos)
            mapChartBogota.series[1].update({data: this.puntos})
            console.log(barrios)*/
            console.log('Cantidad barrios: ', barrios.length)
        },
    },
    mounted(){
        //this.getList()
    }
}).mount('#highMapsApp')
</script>
