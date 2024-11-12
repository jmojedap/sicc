<script>
    // Declara la variable en el ámbito global, accesible para VueApp
    let mapChartBogota;
    let barrios;
    let variableId = 1;
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
            ]
        });
    })();
</script>