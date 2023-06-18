<div class="table-responsive">
    <table class="table bg-white">
        <thead>
            <th width="10px">
                <input type="checkbox" @change="selectAll" v-model="allSelected">
            </th>
            <th width="50px"></th>
            
            <th>Nombre</th>
            <th>Preguntas</th>
            <th>Encuestas</th>
            <th>Año</th>
            

            <th width="50px"></th>
        </thead>
        <tbody>
            <tr v-for="(element, key) in list" v-bind:id="`row_` + element.id" v-bind:class="{'table-info': selected.includes(element.id) }">
                <td>
                    <input type="checkbox" v-bind:id="`check_` + element.id" v-model="selected" v-bind:value="element.id">
                </td>
                <td>M{{ element.id }}</td>
                    
                <td>
                    <a v-bind:href="`<?= URL_ADMIN ?>mediciones/info/` + element.id">
                        {{ element.nombre_medicion }}
                    </a><br>
                    <p>{{ element.descripcion }}</p>
                </td>
                <td class="text-center">{{ element.cant_preguntas }}</td>
                <td class="text-center">{{ element.cant_encuestas }}</td>
                <td>{{ element.anio }}</td>
                
                <td>
                    <button class="a4" data-toggle="modal" data-target="#detail_modal" @click="set_current(key)">
                        <i class="fa fa-info"></i>
                    </button>
                </td>
            </tr>
        </tbody>
    </table>
</div>