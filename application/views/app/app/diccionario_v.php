<div id="diccionarioDatosApp">
    <ul class="nav nav-pills mb-3 justify-content-center">
        <li class="nav-item" v-for="table in tables">
            <button class="nav-link" aria-current="page" v-bind:class="{'active': table.name == currentTable.name }"
                v-on:click="setCurrentTable(table.name)">
                {{ table.name }}
            </button>
        </li>
    </ul>
    <div class="text-center center_box_750">
        <h1>{{ currentTable.nombre }}</h1>
        <p class="text-muted lead">{{ fields.length }} campos</p>
        <p>
            {{ currentTable.description }}
        </p>
    </div>
    <div class="text-center" v-show="loading">
        <div class="spinner-border text-secondary" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>
    <table class="table bg-white" v-show="!loading">
        <thead>
            <th>No.</th>
            <th>Campo</th>
            <th>Información</th>
            <th>Opciones</th>
        </thead>
        <tbody>
            <tr v-for="(field, key) in fields">
                <td width="20px" class="text-center">{{ key + 1 }}</td>
                <td>{{ field.name }} </td>
                <td>
                    <span>
                        {{ field['Descripción'] }}
                    </span>
                    <br>
                    <div>
                        Título:
                        <strong class="text-primary">
                            {{ field['Título'] }}
                        </strong>
                        <br>
                        <span v-for="(column, key_column) in columns" v-show="field[column]">
                            {{ column }}:
                            <strong>{{ field[column] }}</strong>
                            <span class="text-muted"> &middot; </span>
                        </span>
                    </div>
                </td>
                <td>
                    <a v-bind:href="`<?= URL_APP . "parametros/valores/" ?>` + field['ID parámetro']" class="btn btn-light btn-sm" target="_blank" v-show="field['ID parámetro']">
                        Valores
                    </a>
                </td>
            </tr>
        </tbody>
    </table>
</div>

<script>
var diccionarioDatosApp = createApp({
    data(){
        return{
            tables: <?= json_encode($tables) ?>,
            fileId: '<?= $file_id ?>',
            currentTable: {},
            columns: [
                'Tipo', 'Nota', 'type', 'size', 'index', 'relation', 'ID parámetro'
            ],
            loading: true,
            fields: {},
        }
    },
    methods: {
        setCurrentTable: function(tableName){
            this.currentTable = this.tables.find(item => item.name == tableName)
            this.getFields(this.currentTable.gid)
        },
        getFields: function(gid){
            this.loading = true
            axios.get(URL_API + 'app/googlesheet_array/' + this.fileId + '/' + gid)
            .then(response => {
                this.fields = response.data
                this.loading = false
            })
            .catch(function(error) { console.log(error) })
        },
    },
    mounted(){
        this.setCurrentTable('<?= $table ?>')
    }
}).mount('#diccionarioDatosApp')
</script>