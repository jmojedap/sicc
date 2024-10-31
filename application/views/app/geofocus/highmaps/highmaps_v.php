<script src="https://code.highcharts.com/maps/highmaps.js"></script>
<script src="https://code.highcharts.com/maps/modules/exporting.js"></script>
<script src="https://code.highcharts.com/maps/modules/offline-exporting.js"></script>
<script src="https://code.highcharts.com/maps/modules/accessibility.js"></script>

<?php $this->load->view('app/geofocus/highmaps/style_v') ?>

<script>
    // Declara la variable en el ámbito global, accesible para VueApp
    let mapChartBogota;
    let barrios;
    const URL_CONTENT = '<?= URL_CONTENT ?>';

    // Preparación del mapa
    //-----------------------------------------------------------------------------
    (async () => {
        const mapData = await fetch(URL_CONTENT + 'maps/barrios_bogota_geofocus_urbano.json')
        .then(response => response.json());
        barrios = Highcharts.geojson(mapData, 'map');

        let territoriosData = await fetch(
            URL_API + 'geofocus/get_variable_valores/priorizacion_id/1'
        )
        .then(response => response.json())
        .then(data => data.valores);

        // Initialize the chart
        mapChartBogota = Highcharts.mapChart('map-container', {
            chart: {
                map: mapData,
                height: '80%',
                backgroundColor: '#FCFCFC'  // Cambiar el color de fondo
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
                min: 0,
                max: 9,
                tickInterval: 0.5,
                /*stops: [[0, '#F1EEF6'], [0.65, '#900037'], [1, '#500007']],*/
                stops: [[0, '#F1EEF6'], [0.65, '#AA0066']],
                labels: {
                    format: '{value}'
                }
            },

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
            ]
        });
    })();
</script>

<div id="highMapsApp">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-6">
                <table class="table bg-white table-sm table-striped">
                    <thead>
                        <th>Variable</th>
                        <th width="10px"></th>
                    </thead>
                    <tbody>
                        <tr v-for="(variable, key) in variables" v-show="variable.estado == 'Cargada'">
                            <td>{{ variable.nombre }}</td>
                            <td>
                                <button class="a4" v-on:click="actualizarCapa(variable.id)">
                                    <i class="fas fa-arrow-right"></i>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="col-md-6">
                <div id="map-container"></div>
            </div>
        </div>
    </div>
</div>

<script>
var highMapsApp = createApp({
    data(){
        return{
            variableId: 28,
            variables: <?= json_encode($variables) ?>,
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
            console.log(this.puntos)
            mapChartBogota.series[1].update({data: this.puntos})
            //console.log(barrios)
            //console.log('Cantidad barrios: ', barrios.length)
        },
        actualizarCapa: function(newVariableId){
            this.variableId = newVariableId
            axios.get(URL_API + 'geofocus/get_variable_valores/variable_id/' + this.variableId)
            .then(response => {
                console.log(typeof(response.data.summary['min']))
                mapChartBogota.series[0].update({data: response.data['valores']})
                // Actualizar el valor 'max' de colorAxis
                mapChartBogota.colorAxis[0].update({
                    min: parseFloat(response.data['summary']['min']),
                    max: parseFloat(response.data['summary']['max']),
                    tickInterval: (parseFloat(response.data['summary']['max']) - parseFloat(response.data['summary']['min']))/5,
                });
                
                console.log(mapChartBogota.colorAxis[0].min);
            })
            .catch(function(error) { console.log(error) })
        },
    },
    mounted(){
        //this.getList()
    }
}).mount('#highMapsApp')
</script>
