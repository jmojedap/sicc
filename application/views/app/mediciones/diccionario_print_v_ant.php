<div id="diccionario_app">
    <div class="text-center center_box_750">
        <h1>{{ currentTable.title }}</h1>
        <p class="text-muted lead">{{ diccionario.length }} campos</p>
        <p>
            {{ currentTable.description }}
        </p>
    </div>
    <div class="row">
        <div class="col-md-12">
            <table class="table bg-white">
                <thead>
                    <th>No.</th>
                    <th>Campo</th>
                    <th>Información</th>
                </thead>
                <tbody>
                    <tr v-for="(field, key) in diccionario">
                        <td width="20px" class="text-center">{{ key + 1 }}</td>
                        <td>
                            {{ field.name }}
                        </td>
                        <td>
                            Título:
                            <strong class="text-primary">
                                {{ field['Título columna'] }}
                            </strong>
                            <br>
                            {{ field['Descripción'] }}
                            <br>
                            <div>
                                <span v-for="(columna, key_columna) in columnas" v-show="field[columna].length">
                                    {{ columna }}:
                                    <strong>{{ field[columna] }}</strong>
                                    <span class="text-muted"> | </span>
                                </span>
                            </div>
                            <div>
                                Obligatorio: {{ field['Obligatorio'] | obligatorio_name }}
                            </div>
                            <div v-show="field['Ejemplo'].length > 0">
                                Ejemplo: <em>{{ field['Ejemplo'] }}</em> 
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
Vue.filter('obligatorio_name', function (value) {
    if (!value) return 'No'
    if (parseInt(value) == 1) return 'Sí'
    return 'No'
})

var diccionario_app = new Vue({
    el: '#diccionario_app',
    created: function() {
        this.setCurrentTable('<?= $table ?>')
    },
    data: {
        tables: <?= json_encode($tables->result()) ?>,
        currentTable: {},
        columnas: [
            'Tipo', 'ID parámetro', 'Nota', 'type', 'size', 'index', 'Relación'
        ],
        diccionario: <?= $diccionario ?>,
        loading: false,
    },
    methods: {
        setCurrentTable: function(value){
            this.currentTable = this.tables.find(item => item.name == value)
        },
    }
})
</script>