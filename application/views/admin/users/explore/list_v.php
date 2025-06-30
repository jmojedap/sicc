<div class="text-center mb-2" v-show="loading">
    <div class="spinner-border text-primary" role="status"><span class="sr-only">Cargando...</span></div>
</div>

<div class="table-responsive">
    <table class="table bg-white">
        <thead>
            <th width="10px"><input type="checkbox" @change="selectAll" v-model="allSelected"></th>
            <th width="50px"></th>
            <th>Usuario</th>
            <th>Username</th>
            <th>Rol</th>
            <th>Equipo</th>
            <th>Actividad</th>
            <th width="50px"></th>
        </thead>
        <tbody>
            <tr v-for="(element, key) in list" v-bind:id="`row_` + element.id" v-bind:class="{'table-info': selected.includes(element.id) }">
                <td><input type="checkbox" v-bind:id="`check_` + element.id" v-model="selected" v-bind:value="element.id"></td>
                
                <td>
                    <a v-bind:href="`<?= URL_ADMIN . "users/profile/" ?>` + element.id">
                        <img
                            v-bind:src="element.url_thumbnail"
                            class="rounded-circle w40p"
                            v-bind:alt="element.id"
                            onerror="this.src='<?= URL_IMG ?>users/sm_user.png'"
                        >
                    </a>
                </td>
                <td>
                    <a v-bind:href="`<?= URL_ADMIN ?>users/profile/` + element.id + `/` + element.username">
                        {{ element.display_name }}
                    </a>
                    <br>
                    <span class="text-muted">
                        {{ element.email }}
                    </span>
                </td>
                <td>{{ element.username }}</td>
                <td>
                    <i class="fa fa-check-circle text-success" v-show="element.status == 1"></i>
                    <i class="fa fa-check-circle text-warning" v-show="element.status == 2"></i>
                    <i class="fa fa-circle-o text-danger" v-show="element.status == 0"></i>
                    {{ roleName(element.role) }}
                </td>
                <td>
                    {{ element.team_1 }}
                    <br>
                    {{ element.team_2 }}
                </td>
                <td>
                    {{ element.job_role }}
                </td>
                <td>
                    <button class="a4" data-bs-toggle="modal" data-bs-target="#detailsModal" @click="setCurrent(key)">
                        <i class="fa fa-info"></i>
                    </button>
                </td>
            </tr>
        </tbody>
    </table>
</div>