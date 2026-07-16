<script>
   var chartApp = createApp({
        data() {
            return {
                title: 'Porcentaje de respuestas por:',
                loading: true,
                secciones: <?php echo json_decode($secciones); ?>,
                seccionSeleccionada: 1,
                preguntas: <?php echo json_decode($preguntas); ?>,
                preguntaIndice: 31,
                pregunta: {},
                variables: <?php echo json_decode($variables); ?>,
                variableIndice: 0,
                variable: {},
                sumatoriaFactor: 0,
                //section: 'chart',
                section: 'debug',
                // Datos inyectados desde PHP
                rawData: <?php echo json_decode($cubo); ?>,
                datosFiltrados: [],
                datosAgrupados: [],
                tablaDatos: [] 
            }
        },
        methods: {
            changePregunta() {
                this.loading = true;
                this.pregunta = this.preguntas.find(p => p.indice_pregunta === this.preguntaIndice);
                
                // Ya no seleccionamos una variable específica automáticamente,
                // pues se mostrarán todas en el gráfico.
                //this.variable = {};
                //this.variableIndice = null;

                this.processAndChart();
            },
            /*changeVariable() {
                this.loading = true;
                this.variable = this.variables.find(v => v.indice_variable === this.variableIndice);
                this.processAndChart();
            },*/
            processAndChart() {
                // Pequeño delay para asegurar que el DOM está listo y mostrar loading
                setTimeout(() => {
                    try {
                        if (!this.rawData || this.rawData.length === 0) {
                            console.warn("No hay datos para procesar");
                            this.loading = false;
                            return;
                        }

                        // 1) Crear DataFrame
                        const df = new dfd.DataFrame(this.rawData);

                        // 2) Filtrar SOLO por la PREGUNTA seleccionada (ignorar variableIndice)
                        // Aseguramos tipo numérico para la comparación si es necesario
                        const filtrado = df.query(df['indice_pregunta'].eq(this.preguntaIndice));
                        this.datosFiltrados = filtrado.values;

                        if (filtrado.shape[0] === 0) {
                            console.warn("El filtrado retornó 0 filas.");
                            this.loading = false;
                            // Limpiar gráfico si existe
                            if(Highcharts.chart("container")) Highcharts.chart("container").destroy();
                            return;
                        }

                        // 3) Agrupar por VARIABLE y RESPUESTA
                        const agrupado = filtrado.groupby(["indice_variable", "respuesta"]).col(["suma_factor"]).sum();
                        this.datosAgrupados = agrupado.values;

                        // Calcular sumatoria total para compatibilidad básica
                        this.sumatoriaFactor = filtrado["suma_factor"].sum();

                        // 4) Preparar ESTRUCTURA para Highcharts
                        // Eje X (Categorías): Las variables de la pregunta
                        // Series: Las posibles respuestas (ej: Si, No)
                        
                        // Obtenemos las variables relevantes desde nuestro computed property para mantener orden
                        const vars = this.variablesFiltradas;
                        const categories = vars.map(v => v.enunciado_2 || v.variable_nombre || v.codigo_variable || `Var ${v.indice_variable}`);
                        const catIds = vars.map(v => v.indice_variable);

                        // Extraemos todas las respuestas únicas encontradas en los datos agrupados
                        const allResponses = [...new Set(agrupado['respuesta'].values)].sort();

                        // Inicializar series
                        const seriesData = allResponses.map(resp => {
                            return { name: resp, data: [] };
                        });

                        // 5) Rellenar datos (Valores ABSOLUTOS, Highcharts calcula %)
                        // Extraemos columnas del DF agrupado para iterar rápido
                        const g_vars = agrupado['indice_variable'].values;
                        const g_resps = agrupado['respuesta'].values;
                        const g_vals = agrupado['suma_factor_sum'].values;

                        // Iterar por cada categoría (variable) del eje X
                        catIds.forEach((varId) => {
                            const currentVarData = {}; // Mapa: respuesta -> valor

                            for(let i=0; i < g_vars.length; i++) {
                                if(g_vars[i] == varId) {
                                    currentVarData[g_resps[i]] = g_vals[i];
                                }
                            }

                            console.log(`Datos para variable ${varId}:`, currentVarData);

                            // Push valor absoluto a cada serie
                            seriesData.forEach(serie => {
                                const val = currentVarData[serie.name] || 0;
                                serie.data.push(val);
                            });
                        });
                        // 5b) Preparar datos para la tabla (con porcentajes relativos)
                        const totalsPerVar = {};
                        // Primer paso: calcular totales por variable
                        for(let i=0; i<g_vars.length; i++) {
                            const v = g_vars[i];
                            if(!totalsPerVar[v]) totalsPerVar[v] = 0;
                            totalsPerVar[v] += g_vals[i];
                        }

                        this.actualizarTablaDatos(g_vars, g_resps, g_vals, totalsPerVar);
                        
                        // 6) Renderizar Highcharts
                        Highcharts.chart("container", {
                            chart: { type: "bar" },
                            title: { text: this.pregunta.nombre || this.title }, 
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
                }, 100);
            },
            numberFormat(number, decimals = 0) {
                // Formatear número con decimales
                // number = parseFloat(number);
                return number.toLocaleString('es-ES', { minimumFractionDigits: decimals });
            },
            actualizarTablaDatos(g_vars, g_resps, g_vals, totalsPerVar){
                this.tablaDatos = [];
                for(let i=0; i<g_vars.length; i++) {
                    const varIdx = g_vars[i];
                    const varObj = this.variables.find(v => v.indice_variable === varIdx);
                    let varName = varIdx;
                    if (varObj) {
                        varName = varObj.enunciado_2 || varObj.variable_nombre || varObj.codigo_variable;
                    }

                    const val = g_vals[i];
                    const total = totalsPerVar[varIdx] || 0;
                    const pct = total > 0 ? (val / total) * 100 : 0;

                    this.tablaDatos.push({
                        variable: varName,
                        respuesta: g_resps[i],
                        valor: val,
                        porcentaje: pct
                    });
                }
            }
        },
        computed: {
            variablesFiltradas() {
                return this.variables.filter(v => v.indice_pregunta === this.preguntaIndice);
            },
            preguntasFiltradas() {
                return this.preguntas.filter(p => p.num_seccion === this.seccionSeleccionada);
            }
        },
        mounted() {
            this.changePregunta();
        }
    }).mount('#chartApp');
</script>