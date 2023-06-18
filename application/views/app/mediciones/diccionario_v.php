<div id="diccionarioApp">
    <ul class="nav nav-pills mb-3 justify-content-center">
        <li class="nav-item" v-for="table in tables">
            <a class="nav-link" aria-current="page" v-bind:class="{'active': table.name == currentTable.name }"
                v-bind:href="`<?= URL_APP ?>mediciones/diccionario_de_datos/` + table.name">
                {{ table.title }}
            </a>
        </li>
    </ul>
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
                        <td>{{ field.name }} </td>
                        <td>
                            Título:
                            <strong class="text-primary">
                                {{ field['Título columna'] }}
                            </strong>
                            <br>
                            {{ field['Descripción'] }}
                            <br>
                            <p>
                                <span v-for="(columna, key_columna) in columnas" v-show="field[columna]">
                                    {{ columna }}:
                                    <strong>{{ field[columna] }}</strong>
                                    <span class="text-muted"> | </span>
                                </span>
                            </p>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
var diccionarioApp = createApp({
    data(){
        return{
            tables: <?= json_encode($tables->result()) ?>,
            currentTable: {},
            columnas: [
                'Tipo', 'ID parámetro', 'Nota', 'type', 'size', 'index', 'relacion'
            ],
            diccionario: <?= $diccionario ?>,
            loading: false,
        }
    },
    methods: {
        setCurrentTable: function(value){
            this.currentTable = this.tables.find(item => item.name == value)
        },
    },
    mounted(){
        this.setCurrentTable('<?= $table ?>')
    }
}).mount('#diccionarioApp')
</script>