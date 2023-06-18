<div id="medicionApp">
    <div class="center_box_750">
        <h3 class="text-center">{{ row.nombre_medicion }}</h3>
        <h4 class="text-center">{{ row.subtitulo }}</h4>
        <table class="table bg-white">
            <tr>
                <td class="td-title">Código medición</td>
                <td>{{ row.codigo }}</td>
            </tr>
            <tr>
                <td class="td-title">Descripcion</td>
                <td>{{ row.descripcion }}</td>
            </tr>
            <tr>
                <td class="td-title">Tipo medición</td>
                <td>{{ typeName(row.tipo) }}</td>
            </tr>
            <tr>
                <td class="td-title">Palabras clave</td>
                <td>{{ row.palabras_clave }}</td>
            </tr>
            <tr>
                <td class="td-title">Unidad de observación</td>
                <td>{{ unidadObservacionName(row.unidad_observacion) }}</td>
            </tr>
            <tr>
                <td class="td-title">Inicio recolección</td>
                <td>{{ dateFormat(row.fecha_inicio) }}</td>
            </tr>
        </table>
    </div>
</div>

<script>
// Variables
//-----------------------------------------------------------------------------
var arrType = <?= json_encode($arrType); ?>;
var arrUnidadObservacion = <?= json_encode($arrUnidadObservacion) ?>;
var arrTematica1 = <?= json_encode($arrTematica1) ?>;

// VueApp
//-----------------------------------------------------------------------------
var medicionApp = createApp({
    data(){
        return{
            row: <?= json_encode($row) ?>,
            arrType: arrType,
            arrTematica1: arrTematica1,
            arrUnidadObservacion: arrUnidadObservacion,
        }
    },
    methods: {
        typeName: function(value, field = 'name'){
            var typeName = ''
            if (!value) typeName = '-'
            var item = arrType.find(row => row.cod == value)
            if ( item != undefined ) typeName = item[field]
            return typeName
        },
        unidadObservacionName: function(value, field = 'name'){
            var unidadObservacionName = ''
            if (!value) unidadObservacionName = '-'
            var item = arrUnidadObservacion.find(row => row.cod == value)
            if ( item != undefined ) unidadObservacionName = item[field]
            return unidadObservacionName
        },
        tematica1Name: function(value, field = 'name'){
            var tematica1Name = ''
            if (!value) tematica1Name = '-'
            var item = arrTematica1.find(row => row.cod == value)
            if ( item != undefined ) tematica1Name = item[field]
            return tematica1Name
        },
        ago: function(date){
            if (!date) return ''
            return moment(date, 'YYYY-MM-DD HH:mm:ss').fromNow()
        },
        dateFormat: function(date){
            if (!date) return ''
            return moment(date).format('D MMM YYYY')
        },
    },
    mounted(){
        //this.getList()
    }
}).mount('#medicionApp')
</script>