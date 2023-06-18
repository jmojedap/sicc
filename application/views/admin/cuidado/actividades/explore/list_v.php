<div class="table-responsive">
    <table class="table bg-white">
        <thead>
            <th width="10px">
                <input type="checkbox" @change="selectAll" v-model="allSelected">
            </th>
            <th width="50px" class="table-warning">ID</th>
            
            <th>Nombre</th>
            <th class="only-lg"></th>
            

            <th width="50px"></th>
        </thead>
        <tbody>
            <tr v-for="(element, key) in list" v-bind:id="`row_` + element.id" v-bind:class="{'table-info': selected.includes(element.id) }">
                <td>
                    <input type="checkbox" v-bind:id="`check_` + element.id" v-model="selected" v-bind:value="element.id">
                </td>
                <td class="table-warning">{{ element.id }}</td>
                    
                <td width="360px">
                    <a v-bind:href="`<?= URL_ADMIN ?>cuidado/details/` + element.id">
                        {{ element.nombre_actividad }}
                    </a>
                    <br>
                    {{ localidadName(element.localidad_cod) }}
                    <br>
                    <span class="text-muted">Fecha: </span> {{ dateFormat(element.inicio) }}
                    <span class="text-muted"></span> {{ ago(element.inicio) }}
                    <br>
                    {{ timeFormat(element.inicio) }} a {{ timeFormat(element.fin) }}
                    <br>
                    {{ element.descripcion }}
                </td>
                <td class="only-lg">
                    <p>
                        <span class="text-primary">En manzana:</span> {{ element.en_manzana }} &middot;
                        <span class="text-primary">Tipo:</span> {{ element.tipo_actividad }} &middot;
                        <span class="text-primary">Modalidad:</span> {{ element.modalidad }} &middot;
                        <span class="text-primary">Lugar:</span> {{ element.nombre_lugar }} &middot;
                        <span class="text-primary">Facilitadores:</span> {{ element.facilitadores }} &middot;
                    </p>
                    <p>
                        <span class="text-primary">Mujeres:</span> {{ element.cantidad_mujeres }} &middot;
                        <span class="text-primary">Hombres:</span> {{ element.cantidad_hombres }} &middot;
                        <span class="text-primary">Total:</span> {{ parseInt(element.cantidad_hombres) + parseInt(element.cantidad_mujeres) }}
                    </p>
                </td>
                
                <td>
                    <button class="a4" data-toggle="modal" data-target="#detail_modal" @click="setCurrent(key)">
                        <i class="fa fa-info"></i>
                    </button>
                </td>
            </tr>
        </tbody>
    </table>
</div>