<script>
    var charlasBarrialesApp = createApp({
        data(){
            return{
                loading: false,
                localidadCod: 13,
                compromisos: <?php echo json_encode($compromisos) ?>,
                /*compromisos: varCompromisos,*/
                localidades: varLocalidades,
                currentLocalidad: {
                    localidad_cod:13,
                    nombre:'Teusaquillo',
                    cantidad_compromisos: 3,
                },
                currentCompromiso: {
                    id: "11901",
                    localidad_cod: "19",
                    necesidad: "Articulación con los líderes de las bibliotecas comunitarias  de Ciudad Bolívar para la articulación con la Política de Lectura Escritura y Oralidad",
                    nombres: "Lorena Montes: Fundación Blanca Luz - Biblioteca Senderos del Progreso (Red Capir)",
                    tema: "Política pública: Lectura Escritura y Oralidad",
                    estado: "Sin iniciar",
                    descripcion_compromiso: "-Promover este diálogo e integración: generar redes de bibliotecas lideradas por los habitantes del barrio los cuales ayudarán a construir un relato común\n-Mejorar la aplicación de la Política Pública de Lectura y Bibliotecas. Los líderes de las bibliotecas comunitarias requieren mayor visibilización de sus acciones y mecanismos de incorporación en la política pública.",
                    compromiso_comunicacion: "Se creará una mesa de trabajo con la comunidad y servidores de la SCRD con temática de GOBERNANZA, donde se trabajarán los temas de política, gobernanza y fomento",
                    responsable: "DLB",
                    avance: 15,
                    archivos: ""
                },
            }
        },
        methods: {
            setCurrent: function(localidadCod){
                console.log(localidadCod)
                this.localidadCod = localidadCod
                var selector = '#localidad-' + localidadCod + ' path'
                var pathCurrentLocalidad = $(selector);
                $('.poligono-localidad path').attr("fill", "#FFFFFF");
                pathCurrentLocalidad.attr("fill", "#c53c99");
                this.currentLocalidad = this.localidades.find(item => item.localidad_cod == this.localidadCod)
            },
            setCompromiso: function(key){
                this.currentCompromiso = this.compromisos[key]
            },
        },
        mounted(){
            //this.setCurrent(13)
        }
    }).mount('#charlasBarrialesApp')
</script>