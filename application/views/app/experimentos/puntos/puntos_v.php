<style>
    /* Estilos básicos para el contenedor */
    #survey-container {
        display: flex;
        flex-wrap: wrap;
        justify-content: start;
        gap: 2px;
    }

    /* Estilos básicos para cada cuadrado */
    .survey-square {
        background-color: #DEAFD0;
        height: 3px;
    }

    .sexo-hombre{background-color: #00ADC2;}
    .sexo-mujer{background-color: #D9D2E9;}
    .sexo-intersexual{background-color: #E39800;}

    .span-repeat{
        display: inline-block;
        border: 1px solid red;
    }

</style>

<div id="puntosApp">
    <div class="container-fluid">
        <h3>Encuesta Bienal de Culturas 2023</h3>
        <div id="survey-container">
            <div class="survey-square" v-for="point in encuestados" :style="pointStyle(point)" :title="point.factor_expansion"
                :class="getPointClass(point)"
            >

            </div>
        </div>
        <!-- <div id="d-flex flex-wrap">
            <div v-for="point in encuestados" :title="point.factor_expansion" class="span-repeat"
            >{{ getPointContent(point) }}</div>
        </div> -->
    </div>
</div>

<script>
// Ejemplo de datos (puedes reemplazarlo con tu propio array de objetos con factores de expansión)
const surveyData = Array.from({ length: 435 }, () => ({
    expansionFactor: Math.random() * 10 + 1 // Genera un factor de expansión aleatorio entre 1 y 10
}));


var puntosApp = createApp({
    data(){
        return{
            loading: false,
            fields: {},
            points: surveyData,
            encuestados: []
        }
    },
    methods: {
        pointStyle(point) {
            // Calcula el tamaño basado en el factor de expansión
            const size = point.factor_expansion / 20; // Puedes ajustar el multiplicador según sea necesario

            // Devuelve los estilos dinámicos para tamaño y posición
            return {
                width: `${size}px`,
            };
        },
        getPointClass: function(point){
            return this.textToClass(point.sexo, 'sexo')
            //return ''
        },
        getPointContent: function(point){
            var qtyRepeat = point.factor_expansion / 75;
            qtyRepeat = Math.floor(point.factor_expansion / 75);
            return '*'.repeat(qtyRepeat)
        },
        getList: function(){
            axios.get('<?= URL_CONTENT ?>datasets/mediciones/m162/m162_encuestados.json')
            .then(response => {
                this.encuestados = response.data
                this.encuestados.sort((a, b) => a.sexo.localeCompare(b.sexo));
            })
            .catch(function(error) { console.log(error) })
        },
        textToClass: function(text, prefix = null){
            if ( prefix == null) {
                return Pcrn.textToClass(text)
            }
            return prefix + '-' + Pcrn.textToClass(text)
        },
    },
    mounted(){
        this.getList()
    }
}).mount('#puntosApp')
</script>