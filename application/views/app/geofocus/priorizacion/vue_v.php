<?php
    // Estado actual de las variables de la priorización
    $variablesParametrizadas = '[]';
    if ( strlen($row->configuracion) > 0 ) {
        $variablesParametrizadas = $row->configuracion;
    }
?>

<script>
// VueApp
//-----------------------------------------------------------------------------
var priorizacionApp = createApp({
    data(){
        return{
            section: 'variables',
            //section: 'mapa',
            loading: false,
            priorizacion: <?= json_encode($row) ?>,
            display: {
                descripcion: false
            },
            variablesParametrizadas: <?= $variablesParametrizadas ?>,
            currentTema: 'Cultura',
            currentVariableId: 0,
            currentVariable: {
                'id': 0, 'nombre': '', 'descripcion': '', 'tema': '', 'anio_valores': '', 'descripcion_calculo': '', 'entidad': '', 'unidad_medida': '', 'minimo': '', 'media': '', 'desviacion_estandar': ''
            },
            tipoInformacion: 'variable',
            variables: <?= json_encode($variables) ?>,
            allSelected: false,
            territorios: <?= json_encode($territorios->result()) ?>,
            localidades: <?= json_encode($localidades) ?>,
            descripcion: {
                texto: `<?= $row->descripcion_generada ?>`,
                active: false,
                loading: false
            },
            arrTemas: <?= json_encode($arrTemas) ?>,
            userRole: APP_RID,
            userId: APP_UID,
        }
    },
    methods: {
        validateSubmit: function(){
            if ( this.validateForm() ) {
                this.submitForm()
            } else {
                toastr['warning']('No se pudo procesar la petición')
            }
        },
        submitForm: function(){
            this.loading = true
            this.section = 'territorios'
            this.territorios = []
            this.descripcion.texto = ''
            var payload = {
                'priorizacion': this.priorizacion,
                'variables': this.variablesActivas
            }
            axios.post(URL_API + 'geofocus/calcular_priorizacion/', payload, {
                headers: { 'Content-Type': 'application/json'}
            })
            .then(response => {
                this.loading = false
                this.mostrarTerritorios(response.data.territorios)
                this.actualizarMapa('territorios')
                this.descripcion.active = true //Para poder generar descripción
            })
            .catch( function(error) {console.log(error)} )
        },
        // Al recibir los territorios mostrarlos secuencialmente en tabla
        mostrarTerritorios: function(newTerritorios){
            // Índice para seguir el progreso de los elementos agregados
            let index = 0;

            // Utilizamos setInterval para agregar un elemento cada 250 ms
            const intervalo = setInterval(() => {
                // Verifica si hay elementos restantes en newTerritorios
                if (index < newTerritorios.length) {
                    // Agrega el elemento actual al array territorios
                    this.territorios.push(newTerritorios[index]);
                    console.log(`Agregado: ${JSON.stringify(newTerritorios[index])}`);
                    
                    // Incrementa el índice
                    index++;
                } else {
                    // Si se han agregado todos los elementos, detén el intervalo
                    clearInterval(intervalo);
                    console.log('Todos los elementos han sido agregados.');
                }
            }, 250); // Intervalo de 250 ms
        },
        updateVariable: function(){
            this.setVariable(this.currentVariableId)
        },
        setVariable: function(variableId, newSection = 'mapa'){
            this.currentVariableId = variableId
            this.currentVariable = this.variables.find(variable => variable.id == variableId)
            this.currentTema = this.currentVariable.tema
            this.actualizarCapa()
            this.section = newSection
        },
        startVariables: function(){
            this.variables.forEach(variable => {
                //Valores por defecto
                variable.active = false;
                variable.tipo_priorizacion = 1;

                //Buscar variable por clave en configuración guardada (variableParametrizda)
                //Si la encuentra asignarle los valores de configuración guardados.
                variableConfig = this.variablesParametrizadas.find(variableParametrizada => variableParametrizada['clave'] == variable['clave'])
                if ( variableConfig != undefined ) {
                    variable.puntaje = variableConfig.puntaje
                    variable.active = variableConfig.active
                    variable.tipo_priorizacion = variableConfig.tipo_priorizacion
                }
            });
        },
        validateForm: function(){
            if ( this.variablesActivas.length == 0 ) return false
            return true
        },
        toggleSelectAll: function(){
            this.variables.forEach(variable => {
                variable.active = this.allSelected;
            });
        },
        toggleActivateVariable: function(index){
            this.variables[index].active = !this.variables[index].active
        },
        setTipoPriorizacion: function(index, newValue){
            this.variables[index].tipo_priorizacion = newValue
        },
        normalizarVariable: function(variable){
            axios.get(URL_API + 'geofocus/normalizar_variable/' + variable.id)
            .then(response => {
                toastr['info']('Variable normalizada')
                console.log(response.data)
            })
            .catch(function(error) { console.log(error) })
        },
        setSection: function(newSection){
            this.section = newSection
        },
        localidadValor: function(codLocalidad = '', field = 'nombre'){
            var localidadValor = '-'
            var localidad = this.localidades.find(row => row.cod_localidad == codLocalidad)
            if ( localidad != undefined ) localidadValor = localidad[field]
            return localidadValor
        },
        classSector: function(codLocalidad = ''){
            var sector = this.localidadValor(codLocalidad, field = 'sector')
            return this.textToClass(sector, 'sector')
        },
        variableClass: function(variable){
            if ( variable.active == true ) {
                if ( variable.tipo_priorizacion == 1 ) return 'table-info'
                if ( variable.tipo_priorizacion == -1 ) return 'table-warning'
            }
            return ''
        },
        textToClass: function(text, prefix = null){
            if ( prefix == null) {
                return Pcrn.textToClass(text)
            }
            return prefix + '-' + Pcrn.textToClass(text)
        },
        // Mapas
        //-----------------------------------------------------------------------------
        actualizarMapa: function(newSection){
            this.section = newSection
            this.tipoInformacion = 'priorizacion'
            this.currentTema = ''
            axios.get(URL_API + 'geofocus/get_variable_valores/priorizacion_id/' + this.priorizacion.id)
            .then(response => {
                console.log(typeof(response.data.summary['min']))
                mapChartBogota.setTitle({ text: this.priorizacion.nombre });
                mapChartBogota.series[0].update({data: response.data['valores']})
                mapChartBogota.colorAxis[0].update({
                    min: parseFloat(response.data['summary']['min']),
                    max: parseFloat(response.data['summary']['max']),
                    tickInterval: (parseFloat(response.data['summary']['max']) - parseFloat(response.data['summary']['min']))/5,
                });
            })
            .catch(function(error) { console.log(error) })
        },
        actualizarCapa: function(){
            this.tipoInformacion = 'variable'
            axios.get(URL_API + 'geofocus/get_variable_valores/variable_id/' + this.currentVariable.id)
            .then(response => {
                console.log(typeof(response.data.summary['min']))
                mapChartBogota.setTitle({ text: this.currentVariable.nombre });
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
        setTema: function(){
            var newVariableId = this.variables.find(variable => variable.tema == this.currentTema).id
            console.log(newVariableId)
            this.setVariable(newVariableId)
        },
        // Descripción de la priorización
        //-----------------------------------------------------------------------------
        getDescripcionPriorizacion: function(){
            this.loading = true
            var formValues = new FormData()
            formValues.append('texto_parametrizacion', this.textoParametrizacion)
            axios.post(URL_API + 'geofocus/get_descripcion/' + this.priorizacion.id, formValues)
            .then(response => {
                //this.descripcion.texto = response.data.candidates[0].content.parts[0].text
                console.log(response.data.descripcion_generada)
                this.descripcion.texto = response.data.descripcion_generada
                this.descripcion.active = false
                typeText(this.descripcion.texto, 10);
                this.loading = false
            })
            .catch( function(error) {console.log(error)} )
        },
        formatNumber: function(value){
            return Pcrn.round(value)
        },
        isEditable: function(){
            console.log(this.userRole)
            console.log(this.priorizacion.creator_id)
            if ( this.userRole <= 2 ) return true
            if ( this.priorizacion.creator_id == this.userId ) return true
            return false
        },

    },
    computed: {
        variablesActivas: function(){
            return this.variables.filter(variable => variable.active == true)
        },
        textoParametrizacion: function(){
            var texto = 'Variables: '
            this.variables.forEach((variable,index) => {
                if ( variable.active ) {
                    texto += "Variable " + (index+1) + ': '
                    texto += `Nombre: ${variable.nombre}. `;
                    texto += `Tema: ${variable.tema}. `;
                    texto += `Descripción: ${variable.descripcion}. `;
                    if ( variable.tipo_priorizacion == 1 ) {
                        texto += `Tipo de priorización: Priorizar territorios con valores altos. `;
                    } else {
                        texto += `Tipo de priorización: Priorizar territorios con valores bajos. `;
                    }
                    texto += `Ponderación de la variable: ${variable.puntaje}. `;
                    texto += '. ---';
                    texto += "\n"
                }
            });
            return texto
        },
    },
    mounted(){
        this.startVariables()
        this.actualizarMapa('variables')
    }
}).mount('#priorizacionApp');


// Show text in a container, one character at a time
//-----------------------------------------------------------------------------
function typeText(text, interval) {
    const container = document.getElementById('typing-respuesta');
    let index = 0;
    container.textContent = ''
    
    function showText() {
        if (index < text.length) {
            container.textContent += text[index];
            index++;
        }
    }
    
    const intervalId = setInterval(showText, interval);
}
</script>