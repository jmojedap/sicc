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

        <?php if ( in_array($this->session->userdata('role'), [1,2]) ) : ?>
            <br>

            <h4>Eliminar datos</h4>
        
            <button class="btn btn-light me-1" v-for="table in tables"  v-on:click="cleanMedicion(table)">
                {{ table }}
            </button>
        <?php endif; ?>
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
            tables: ['med_seccion', 'med_pregunta', 'med_variable', 'med_opcion', 'med_respuesta']
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
        cleanMedicion: function(tableName){
            axios.get(URL_API + 'mediciones/clean_medicion/' + tableName + '/' + this.row.id)
            .then(response => {
                toastr['info']('Registros eliminados de ' + tableName + ': ' + response.data.qty_deleted)
            })
            .catch(function(error) { console.log(error) })
        },

    },
    mounted(){
        //this.getList()
    }
}).mount('#medicionApp')
</script>