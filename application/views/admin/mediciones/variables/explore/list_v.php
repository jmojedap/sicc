<div class="table-responsive">
    <table class="table bg-white">
        <thead>
            <th width="10px">
                <input type="checkbox" @change="selectAll" v-model="allSelected">
            </th>
            <th width="50px" classs="table-warning">ID</th>
            
            
            <th>Nombre</th>
            <th>Enunciado</th>
            <th width="50px">Medición</th>
            <th width="50px">Pregunta</th>

            <th width="50px"></th>
        </thead>
        <tbody>
            <tr v-for="(element, key) in list" v-bind:id="`row_` + element.id" v-bind:class="{'table-info': selected.includes(element.id) }">
                <td>
                    <input type="checkbox" v-bind:id="`check_` + element.id" v-model="selected" v-bind:value="element.id">
                </td>
                <td class="table-warning">{{ element.id }}</td>
                
                <td>
                    <a v-bind:href="`<?= URL_ADMIN ?>variables/info/` + element.id">
                        {{ element.nombre }}
                    </a>
                </td>
                
                <td>{{ element.enunciado_2 }}</td>
                <td>
                    <a v-bind:href="`<?= URL_ADMIN . "mediciones/info/" ?>` + element.medicion_id" class="">
                        {{ element.medicion_id }}
                    </a>
                </td>
                <td>
                    <a v-bind:href="`<?= URL_ADMIN . "preguntas/info/" ?>` + element.pregunta_id" class="">
                        {{ element.pregunta_id }}
                    </a>
                </td>
                
                <td>
                    <button class="a4" data-toggle="modal" data-target="#detail_modal" @click="set_current(key)">
                        <i class="fa fa-info"></i>
                    </button>
                </td>
            </tr>
        </tbody>
    </table>
</div>