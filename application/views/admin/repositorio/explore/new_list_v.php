<div class="table-responsive">
    <table class="table bg-white">
        <thead>
            <th width="10px">
                <input type="checkbox" @change="selectAll" v-model="allSelected">
            </th>
            <th width="10px" class="table-warning">ID</th>
            <th></th>
            <th width="500px">Contenido</th>
            <th>Año</th>
            <th>Link/Ruta</th>
            <th>Entidad</th>

            <th width="50px"></th>
        </thead>
        <tbody>
            <tr v-for="(element, key) in list" v-bind:id="`row_` + element.id" v-bind:class="{'table-info': selected.includes(element.id) }">
                <td>
                    <input type="checkbox" v-bind:id="`check_` + element.id" v-model="selected" v-bind:value="element.id">
                </td>
                <td class="table-warning">{{ element.id }}</td>
                <td>{{ element.titulo }}</td>
                <td>{{ element.descripcion }}</td>
                <td>{{ element.anio_publicacion }}</td>
                <td>
                    {{ element.url_contenido_externo }}
                    {{ element.revision_ruta_disco }}
                </td>
                <td>{{ element.entidad }}</td>
            </tr>
        </tbody>
    </table>
</div>