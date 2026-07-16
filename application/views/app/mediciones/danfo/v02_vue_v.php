<script>
const colorsPallete = ['#003366',  '#00AEEF',  '#F9A825',  '#D32F2F',  '#388E3C',   '#7B1FA2'];
const variablesPre = <?= json_decode($variables); ?>;
const respuestasPre = <?= json_decode($respuestas); ?>;

// Obtener sección inicial desde get
const urlParams = new URLSearchParams(window.location.search);
let numSeccionInicial = parseInt(urlParams.get('seccion')) || 1;
let indicePreguntaInicial = parseInt(urlParams.get('pregunta_index')) || 0;

/**
 * Calcula la promedio de una variable numérica en función de los factores de expansión
 */
function promedioPonderado(respuestas) {
    let sumaPonderada = 0;
    let sumaFactores = 0;

    respuestas.forEach(r => {
        const val = parseFloat(r.respuesta);
        const f = parseFloat(r.suma_factor);
        if (!isNaN(val) && !isNaN(f)) {
            sumaPonderada += val * f;
            sumaFactores += f;
        }
    });

    return sumaFactores > 0 ? (sumaPonderada / sumaFactores) : null;
}

// Config
//-----------------------------------------------------------------------------
Highcharts.setOptions({
  colors: colorsPallete,
  chart: {
    style: {
      fontFamily: 'Work Sans, sans-serif',
    }
  }
});

// VueApp
//-----------------------------------------------------------------------------
var chartApp = createApp({
    data() {
        return {
            loading: true,
            secciones: <?= json_decode($secciones); ?>,
            medicionInfo: {
                'nombre_medicion': 'Medición de Prueba',
                'fecha_inicio': '2024-01-01',
                'tamano_muestra': 4513,
                'sumatoria_factor': 6980578.293
            },
            numSeccion: numSeccionInicial,
            currentSeccion: {
                'num_seccion': numSeccionInicial,
                'nombre_seccion': '',
                'descripcion': ''
            },
            preguntas: <?= json_decode($preguntas); ?>,
            preguntaIndice: indicePreguntaInicial,
            pregunta: {},
            variables: <?= json_decode($variables); ?>,
            variableIndice: 0,
            variable: {},
            sumatoriaFactor: 0,
            //section: 'chart',
            section: 'debug',
            // Datos inyectados desde PHP
            //tablaDatos: [],
            tablasVariables: [],
            respuestas: [],
            posiblesRespuestas: [],
            seriesData: []
        }
    },
    methods: {
        numberFormat(value, decimals = 0) {
            const number = Number(value);
            if (!Number.isFinite(number)) {
                return '-';
            }

            return number.toLocaleString('es-CO', {
                minimumFractionDigits: decimals,
                maximumFractionDigits: decimals
            });
            //return value;
        },
        // Actualizar dinámicamente la URL, con los parámetros
        updateUrl: function(){
            const url = new URL(window.location);
            url.searchParams.set('seccion', this.numSeccion);
            url.searchParams.set('pregunta_index', this.preguntaIndice);
            window.history.pushState({}, '', url);
        },
        // Establecer sección actual
        setSeccion: function(newNumSeccion = null){
            console.log(newNumSeccion, 'newNumSeccion');
            
            if (newNumSeccion !== null) this.numSeccion = newNumSeccion;
            this.currentSeccion = this.secciones.find(s => s.num_seccion == this.numSeccion);
            this.updateUrl();
        },
        // Establecer pregunta actual
        setPregunta(newPreguntaIndice = null){
            if (newPreguntaIndice !== null) this.preguntaIndice = newPreguntaIndice;
            this.loading = true;
            this.pregunta = this.preguntas.find(p => p.indice_pregunta === this.preguntaIndice);
            this.updateUrl();
            this.respuestas = respuestasPre.filter(r => r.indice_pregunta === this.preguntaIndice);

            this.updateVariables();
            this.updateRespuestas();
            
            this.updateTablasVariables();
            this.updateChart();
            
        },
        updateChart: function(){
            // Pequeño delay para asegurar que el DOM está listo y mostrar loading
            setTimeout(() => {
                if ( this.pregunta.dataviz_chart_type == 'bar-multiple') {
                    this.setBarMultipleChart();
                } else if (this.pregunta.dataviz_chart_type == 'column') {
                    this.setColumnChart();
                } else if (this.pregunta.dataviz_chart_type == 'donut') {
                    this.setDonutChart();
                } else {
                    //Destroy chart si existe
                    if(Highcharts.chart("container")) Highcharts.chart("container").destroy();
                    console.warn("Tipo de visualización no soportado:", this.pregunta.dataviz_chart_type);
                    this.loading = false;
                }
            }, 200);
        },
        // Actualizar variables de la pregunta actual, y calcular columnas adicionales
        updateVariables: function(){
            // Filtrar variables por pregunta actual
            this.variables = variablesPre.filter(v => v.indice_pregunta === this.preguntaIndice);

            // Agregar la columna de suma de factor a cada variable para facilitar cálculos posteriores
            this.variables.forEach(variable => {
                // Sumatoria, respuestas ya están filtradas
                variable.suma_factor = this.respuestas
                    .filter(r => r.indice_variable === variable.indice_variable)
                    .reduce((acc, r) => acc + r.suma_factor, 0);
                // Promedio ponderado
                variable.promedio = promedioPonderado(this.respuestas.filter(r => r.indice_variable === variable.indice_variable));
            });

            // Asignar la sumatoria del factor, al máximo entre las variables
            this.sumatoriaFactor = Math.max(...this.variables.map(v => v.suma_factor), 0);
        },
        // Actualizar datos calculados de respuestas
        updateRespuestas: function(){
            // Crear array asociativo de indice_variable -> suma_factor para acceso rápido
            const factorMap = {};
            this.variables.forEach(v => {
                factorMap[v.indice_variable] = v.suma_factor;
            });
            // Agregar la columna de porcentaje a cada respuesta para facilitar cálculos posteriores
            this.respuestas.forEach(respuesta => {
                respuesta.porcentaje = factorMap[respuesta.indice_variable] ? (respuesta.suma_factor / factorMap[respuesta.indice_variable]) * 100 : 0;
            });
        },
        updateTablasVariables: function(){
            this.tablasVariables = [];

            //Calcular posibles respuestas sin repetidos
            this.posiblesRespuestas = [...new Set(this.respuestas.map(r => r.respuesta_v2))];

            //Ordenar posibles respuestas
            this.posiblesRespuestas = [...new Set(
                this.respuestas
                    .map(r => r.respuesta_v2)
                    .filter(r => r != null)
                    .map(r => String(r))
            )];

            // Inicializar series con posibles respuestas
            this.seriesData = this.posiblesRespuestas.map(resp => {
                return { name: resp, data: [] };
            });

            //Recorrer respuestas y llenar calor de cada variable
            this.posiblesRespuestas.forEach(posibleRespuesta => {
                var respuestas = this.respuestas.filter(r => r.respuesta_v2 === posibleRespuesta);
                //llenar series.data con 0 inicialmente recorriendo las variables
                this.variables.forEach(variable => {
                    var sumaFactor = respuestas.find(r => r.indice_variable === variable.indice_variable)?.suma_factor || 0;
                    this.seriesData.find(s => s.name === posibleRespuesta).data.push(sumaFactor);
                });
            });
            
            // Recorrer variables
            this.variables.forEach(variable => {
                // Solo respuestas de la variable en el ciclo
                var respuestas = this.respuestas.filter(r => r.indice_variable === variable.indice_variable);
                
                this.tablasVariables.push({
                    indice_variable: variable.indice_variable,
                    codigo_variable: variable.codigo_variable,
                    enunciado_2: variable.enunciado_2,
                    suma_factor: variable.suma_factor,
                    promedio: variable.promedio,
                    unidad_medida: variable.unidad_medida,
                    respuestas: respuestas,
                });
            });

            //console.log('Tablas Variables::');
            //console.log(this.tablasVariables);
        },
        setDonutChart() {
            try{
                //Extraer los valores de la suma_factor para cada categoría
                const serieData = this.respuestas.map(d => {
                    return { name: d.respuesta_v2, y: parseFloat(d.suma_factor) };
                });

                Highcharts.chart('container', {
                    chart: {
                        type: 'pie',
                    },
                    accessibility: {
                        point: {
                            valueSuffix: '%'
                        }
                    },
                    title: {
                        text: this.pregunta.nombre
                    },
                    subtitle: {
                        text: this.pregunta.enunciado_1 || ''
                    },
                    tooltip: {
                        pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
                    },
                    legend: {
                        enabled: true,
                        layout: 'horizontal',
                        align: 'center',
                    },
                    plotOptions: {
                        series: {
                            allowPointSelect: true,
                            cursor: 'pointer',
                            borderRadius: 8,
                            dataLabels:
                            [
                                {
                                    enabled: true,
                                    distance: 20,
                                    format: '{point.name}'
                                },
                                {
                                    enabled: true,
                                    distance: -30,
                                    format: '{point.percentage:.1f}%',
                                    style: {
                                        fontSize: '1.2em'
                                    }
                                }
                            ],
                            showInLegend: true
                        }
                    },
                    series: [{
                        name: 'Porcentaje',
                        colorByPoint: true,
                        innerSize: '50%',
                        data: serieData
                    }]
                });

            } catch (error) {
                console.error("Error al procesar/graficar:", error);
            } finally {
                this.loading = false;
            }
        },
        setColumnChart() {
            try {
                //Extraer categorías de datos filtrados
                const categorias = this.respuestas.map(d => d.respuesta_v2);

                //Extraer los valores de la suma_factor para cada categoría
                const serieData = this.respuestas.map(r  => {
                    const val = r.suma_factor;
                    const pct = this.sumatoriaFactor > 0 ? (val / this.sumatoriaFactor) * 100 : 0;
                    return parseFloat(pct.toFixed(2)); // Retornar porcentaje con 2 decimales
                });

                Highcharts.chart('container', {
                    chart: {
                        type: 'bar'
                    },
                    title: {
                        text: this.pregunta.nombre || ''
                    },
                    subtitle: {
                        text: this.pregunta.enunciado_1 || ''
                    },
                    xAxis: {
                        categories: categorias,
                        crosshair: true,
                        accessibility: {
                            description: 'Countries'
                        }
                    },
                    yAxis: {
                        min: 0,
                        title: {
                            text: 'Porcentaje (%)'
                        }
                    },
                    tooltip: {
                        valueSuffix: '%'
                    },
                    legend: {
                        enabled: false
                    },
                    plotOptions: {
                        bar: {
                            pointPadding: 0.05,
                            borderWidth: 0.5,
                            dataLabels: {
                                enabled: true,
                                format: '{point.y:.1f}%' // Mostrar porcentaje con 1 decimal
                            }
                        }
                    },
                    series: [
                        {
                            name: 'Porcentaje',
                            color: colorsPallete[1],
                            data: serieData
                        },
                    ],
                    credits: { enabled: false }
                });

            } catch (error) {
                console.error("Error al procesar/graficar:", error);
            } finally {
                this.loading = false;
            }
        },
        setBarMultipleChart() {
            try {
                //Categories son las variables
                const categories = this.variables.map(v => v.enunciado_2 || v.variable_nombre || v.codigo_variable || `Var ${v.indice_variable}`);
                
                //Series: Opciones de respuesta
                const seriesData = this.seriesData;

                console.log('seriesData::');
                console.log(seriesData);
                
                // 6) Renderizar Highcharts
                Highcharts.chart("container", {
                    chart: { type: "bar" },
                    title: { text: this.pregunta.nombre || '' }, 
                    subtitle: {
                        text: this.pregunta.enunciado_1 || ''
                    },
                    xAxis: { 
                        categories: categories, 
                        title: { text: null }, // Variables
                        crosshair: true
                    },
                    yAxis: { 
                        min: 0, 
                        max: 100,
                        title: { text: "Porcentaje (%)" } 
                    },
                    tooltip: {
                        shared: true,
                        pointFormat: '<span style="color:{series.color}">{series.name}</span>: <b>{point.percentage:.1f}%</b> ({point.y:,.0f})<br/>',
                        useHTML: true
                    },
                    legend: {
                        layout: 'horizontal',
                        align: 'center',
                        verticalAlign: 'top'
                    },
                    plotOptions: {
                        bar: {
                            stacking: 'percent',
                            pointPadding: 0.2,
                            borderWidth: 0,
                            dataLabels: {
                                enabled: true,
                                format: '{point.percentage:.1f}%',
                                style: { textOutline: 'none' }
                            }
                        }
                    },
                    series: seriesData, //Respuestas como series
                    credits: { enabled: false }
                });

            } catch (error) {
                console.error("Error al procesar/graficar:", error);
            } finally {
                this.loading = false;
            }
        },
        setBarMultipleChartOrg() {
            try {
                if (!this.respuestas || this.respuestas.length === 0) {
                    console.warn("No hay datos para procesar");
                    this.loading = false;
                    return;
                }

                // 1) Crear DataFrame
                const df = new dfd.DataFrame(this.respuestas);

                // 2) Filtrar SOLO por la PREGUNTA seleccionada (ignorar variableIndice)
                // Aseguramos tipo numérico para la comparación si es necesario
                const filtrado = df.query(df['indice_pregunta'].eq(this.preguntaIndice));

                if (filtrado.shape[0] === 0) {
                    console.warn("El filtrado retornó 0 filas.");
                    this.loading = false;
                    // Limpiar gráfico si existe
                    if(Highcharts.chart("container")) Highcharts.chart("container").destroy();
                    return;
                }

                // 3) Agrupar por VARIABLE y RESPUESTA
                const agrupado = filtrado.groupby(["indice_variable", "respuesta_v2"]).col(["suma_factor"]).sum();

                // 4) Preparar ESTRUCTURA para Highcharts
                // Eje X (Categorías): Las variables de la pregunta
                // Series: Las posibles respuestas (ej: Sí, No)
                
                // Obtenemos las variables relevantes desde nuestro computed property para mantener orden
                const vars = this.variables;
                const categories = vars.map(v => v.enunciado_2 || v.variable_nombre || v.codigo_variable || `Var ${v.indice_variable}`);
                const catIds = vars.map(v => v.indice_variable);

                // Extraemos todas las respuestas únicas encontradas en los datos agrupados
                const allResponses = [...new Set(agrupado['respuesta_v2'].values)].sort();
                console.log(allResponses);

                // Inicializar series
                const seriesData = allResponses.map(resp => {
                    return { name: resp, data: [] };
                });

                // 5) Rellenar datos (Valores ABSOLUTOS, Highcharts calcula %)
                // Extraemos columnas del DF agrupado para iterar rápido
                const g_vars = agrupado['indice_variable'].values;
                const g_resps = agrupado['respuesta_v2'].values;
                const g_vals = agrupado['suma_factor_sum'].values;

                // Iterar por cada categoría (variable) del eje X
                catIds.forEach((varId) => {
                    const currentVarData = {}; // Mapa: respuesta -> valor

                    for(let i=0; i < g_vars.length; i++) {
                        if(g_vars[i] == varId) {
                            currentVarData[g_resps[i]] = g_vals[i];
                        }
                    }

                    // Push valor absoluto a cada serie
                    seriesData.forEach(serie => {
                        const val = currentVarData[serie.name] || 0;
                        serie.data.push(val);
                    });
                });
                
                // 6) Renderizar Highcharts
                Highcharts.chart("container", {
                    chart: { type: "bar" },
                    title: { text: this.pregunta.nombre || '' }, 
                    subtitle: {
                        text: this.pregunta.enunciado_1 || ''
                    },
                    xAxis: { 
                        categories: categories, 
                        title: { text: null }, // Variables
                        crosshair: true
                    },
                    yAxis: { 
                        min: 0, 
                        max: 100,
                        title: { text: "Porcentaje (%)" } 
                    },
                    tooltip: {
                        shared: true,
                        pointFormat: '<span style="color:{series.color}">{series.name}</span>: <b>{point.percentage:.1f}%</b> ({point.y:,.0f})<br/>',
                        useHTML: true
                    },
                    legend: {
                        layout: 'horizontal',
                        align: 'center',
                        verticalAlign: 'top'
                    },
                    plotOptions: {
                        bar: {
                            stacking: 'percent',
                            pointPadding: 0.2,
                            borderWidth: 0,
                            dataLabels: {
                                enabled: true,
                                format: '{point.percentage:.1f}%',
                                style: { textOutline: 'none' }
                            }
                        }
                    },
                    series: seriesData, //Respuestas como series
                    credits: { enabled: false }
                });

            } catch (error) {
                console.error("Error al procesar/graficar:", error);
            } finally {
                this.loading = false;
            }
        },
    },
    computed: {
        preguntasFiltradas() {
            // Filtrar preuntas por sección y preguntas con dataviz_display = 1
            var preguntasFiltradas = this.preguntas.filter(p => p.num_seccion === this.numSeccion && p.dataviz_display === 1);
            return preguntasFiltradas;
        },
        // Porcentaje de la muestra representada por la sumatoria de la pregunta acual
        // respecto a la sumatoria total de la medición
        porcentajeMuestra() {
            if (this.sumatoriaFactor > 0 && this.medicionInfo.sumatoria_factor > 0) {
                return (this.sumatoriaFactor / this.medicionInfo.sumatoria_factor) * 100;
            }
            return 0;
        }
    },
    async mounted() {
        this.setSeccion(numSeccionInicial);
        this.setPregunta(indicePreguntaInicial);
    }
}).mount('#chartApp');
</script>