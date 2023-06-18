<div id="usuariosApp">
    <p>
        Usuarios participantes registrados: <strong class="text-primary">{{ users.length }}</strong>
    </p>
    <table class="table bg-white table-sm">
        <thead>
            <th>num_documento</th>
            <th>nombre</th>
            <th>apellidos</th>
            <th>email</th>
            <th>celular</th>
            <th>fecha_nacimiento</th>
            <th>sexo</th>
            <th>identidad_genero</th>
            <th>orientacion_sexual</th>
            <th>ocupacion</th>
            <th>localidad</th>
            <th>direccion</th>
            <th>estrato</th>
            <th>user_id</th>
        </thead>
        <tbody>
            <tr v-for="(user, key) in users">
                <td>{{ user.num_documento }}</td>
                <td>{{ user.nombre }}</td>
                <td>{{ user.apellidos }}</td>
                <td>{{ user.email }}</td>
                <td>{{ user.celular }}</td>
                <td>{{ user.fecha_nacimiento }}</td>
                <td>{{ sexoName(user.sexo) }}</td>
                <td>{{ user.identidad_genero }}</td>
                <td>{{ user.orientacion_sexual }}</td>
                <td>{{ user.ocupacion }}</td>
                <td>{{ user.localidad }}</td>
                <td>{{ user.direccion }}</td>
                <td>{{ user.estrato }}</td>
                <td>{{ user.id }}</td>
            </tr>
        </tbody>
    </table>
</div>

<script>
var usuariosApp = createApp({
    data(){
        return{
            loading: false,
            fields: {},
            users: <?= json_encode($users['list']) ?>,
            arrSexos: <?= json_encode($arrSexos) ?>,
        }
    },
    methods: {
        sexoName: function(value = '', field = 'name'){
            var sexoName = ''
            var item = this.arrSexos.find(row => row.cod == value)
            if ( item != undefined ) sexoName = item[field]
            return sexoName
        },
    },
    mounted(){
        //this.getList()
    }
}).mount('#usuariosApp')
</script>