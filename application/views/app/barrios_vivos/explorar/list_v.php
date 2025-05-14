<!-- VISTA TARJETAS -->
<div v-show="viewFormat == `cards`" class="center_box_750">
    <div class="card mb-2" v-for="(row,key) in list">
        <div class="card-body">
            <div class="d-flex justify-content-between">
                <h4 class="card-title">
                    <span class="badge bg-bv-rojo">{{ row.id }}</span>
                    
                    {{ row.nombre_laboratorio }}
                    
                </h4>
                <div v-show="appRid <= 8">
                    <button class="btn-circle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fa-solid fa-ellipsis-v"></i>
                    </button>
                    <ul class="dropdown-menu">
                        <li class="d-none">
                            <button class="dropdown-item" v-on:click="setCurrent(key)" data-bs-toggle="modal" data-bs-target="#detailModal">
                                Detalles
                            </button>
                        </li>
                        <li v-show="appRid <= 8">
                            <a class="dropdown-item" v-bind:href="`<?= URL_APP . "barrios_vivos/info/" ?>` + row.id">
                                Detalles
                            </a>
                        </li>
                        <li v-show="appRid <= 8">
                            <a class="dropdown-item" v-bind:href="`<?= URL_APP . "barrios_vivos/edit/" ?>` + row.id">
                                Editar
                            </a>
                        </li>
                        <li v-show="appRid <= 3">
                            <button class="dropdown-item" v-on:click="setCurrent(key)" data-bs-toggle="modal" data-bs-target="#deleteModal">
                                Eliminar
                            </button>
                        </li>
                    </ul>
                </div>
            </div>
            <p class="text-muted">
                {{ dateFormat(row.fecha_inicio, 'DD/MMMM') }} &middot;
                {{ ago(row.fecha_inicio) }} &middot;
                <span class="badge bg-info">{{ row.localidad }}</span>
            </p>
            <p>
                <strong>Relato barrial: </strong>
                {{ row.relato_barrial }}
            </p>
            
            <p>
                <strong>Descripción: </strong>
                {{ row.descripcion }}
            </p>
            <p>
                <span class="text-muted">
                    Dependencia líder:
                </span>
                {{ row.direccion_lider }} &middot;


                {{ row.equipo_lider_duplas }} &middot;
            </p>
            
        </div>
    </div>
</div>


<!-- VISTA TABLA -->
<div class="table-responsive" v-show="viewFormat == `table`">
    <table class="table bg-white table-sm">
        <thead>
            <th width="20px">ID</th>
            
            
            <th>Nombre</th>
            <th>Tipo / Categoría</th>
            <th v-if="displayPrivate">Gerente / Duplas</th>
            <th v-if="displayPrivate">Actualizado por</th>
            <th width="20px"></th>
            <th width="20px" v-show="appRid <= 8"></th>
        </thead>
        <tbody>
            <tr v-for="(row, key) in list" v-bind:id="`row_` + row.id" v-bind:class="{'table-info': selected.includes(row.id) }">
                <td class="text-center table-warning">{{ row.id }}</td>
                
                <td>
                    <a v-bind:href="`<?= URL_FRONT ?>barrios_vivos/info/` + row.id">
                        {{ row.nombre_laboratorio }}
                    </a>
                    <br>
                    <small class="text-muted">{{ row.barrio_ancla }}</small>
                </td>
                <td>
                    {{ row.tipo_laboratorio }}
                    <br>
                    <small class="text-muted">{{ row.categoria_laboratorio }}</small>
                </td>
                
                <td v-if="displayPrivate">
                    {{ row.gerente }}
                    <br>
                    <small class="text-muted">{{ row.duplas }}</small>
                </td>

                <td v-if="displayPrivate">
                    <span v-bind:title="row.updater_display_name">
                        {{ row.updater_username }}
                    </span>
                    
                    <br>
                    <small class="text-muted">{{ dateFormat(row.updated_at, 'DD/MMMM') }} &middot; {{ ago(row.updated_at) }}</small>
                </td>
                
                <td>
                    
                </td>
                <td v-show="appRid <= 8">
                    <button class="btn-circle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fa-solid fa-ellipsis-v"></i>
                    </button>
                    <ul class="dropdown-menu">
                        <li class="d-none">
                            <button class="dropdown-item" v-on:click="setCurrent(key)" data-bs-toggle="modal" data-bs-target="#detailModal">
                                Detalles
                            </button>
                        </li>
                        <li v-show="appRid <= 8">
                            <a class="dropdown-item" v-bind:href="`<?= URL_APP . "barrios_vivos/info/" ?>` + row.id">
                                Información
                            </a>
                        </li>
                        <li v-show="appRid <= 8">
                            <a class="dropdown-item" v-bind:href="`<?= URL_APP . "barrios_vivos/edit/" ?>` + row.id">
                                Editar
                            </a>
                        </li>
                        <li v-show="appRid <= 3">
                            <button class="dropdown-item" v-on:click="setCurrent(key)" data-bs-toggle="modal" data-bs-target="#deleteModal">
                                Eliminar
                            </button>
                        </li>
                    </ul>
                </td>
            </tr>
        </tbody>
    </table>
</div>