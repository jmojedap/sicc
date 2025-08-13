<!-- VISTA TARJETAS -->
<div v-show="viewFormat == `cards`" class="center_box_750">
    <div class="card mb-2" v-for="(row,key) in list">
        <div class="card-body">
            <div class="d-flex justify-content-between">
                <h4 class="card-title">
                    {{ row.nombre }}
                </h4>
                <div v-show="appRid <= 8">
                    <button class="btn-circle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fa-solid fa-ellipsis-v"></i>
                    </button>
                    <ul class="dropdown-menu">
                        <li v-show="appRid <= 8">
                            <a class="dropdown-item" v-bind:href="`<?= URL_APP . "equipamientos/info/" ?>` + row.id">
                                Detalles
                            </a>
                        </li>
                        <!-- <li v-show="appRid <= 8">
                            <a class="dropdown-item" v-bind:href="`<?= URL_APP . "equipamientos/edit/" ?>` + row.id">
                                Editar
                            </a>
                        </li>
                        <li v-show="appRid <= 3">
                            <button class="dropdown-item" v-on:click="setCurrent(key)" data-bs-toggle="modal" data-bs-target="#deleteModal">
                                Eliminar
                            </button>
                        </li> -->
                    </ul>
                </div>
            </div>
            
            <p>
                <strong>Descripción: </strong>
                {{ row.descripcion }}
            </p>
            <p>
                <span v-show="row.tipo_entidad" class="text-muted">Tipo entidad: </span>
                <span v-show="row.tipo_entidad">{{ row.tipo_entidad }}&middot;</span>

                <span v-show="row.tipo_entidad" class="text-muted">Localidad: </span>
                <span v-show="row.tipo_entidad">{{ row.localidad }}&middot;</span>

                <span v-show="row.direccion" class="text-muted">Dirección: </span>
                <span v-show="row.direccion">{{ row.direccion }}&middot;</span>
                
            </p>
            <p>
                <strong>Observaciones: </strong>
                {{ row.observaciones }}
            </p>
        </div>
    </div>
</div>


<!-- VISTA TABLA -->
<div class="table-responsive" v-show="viewFormat == `table`">
    <table class="table bg-white table-sm">
        <thead>
            <th width="20px">ID</th>
            <th width="30%">Nombre</th>
            <th>Categoría / Tipo</th>
            <th>Localización</th>
            <th width="20px" v-show="appRid <= 8"></th>
        </thead>
        <tbody>
            <tr v-for="(row, key) in list" v-bind:id="`row_` + row.id" v-bind:class="{'table-info': selected.includes(row.id) }">
                <td class="text-center table-warning">{{ row.id }}</td>
                
                <td>
                    <a v-bind:href="`<?= URL_FRONT ?>equipamientos/info/` + row.id" class="ms-1">
                        {{ row.nombre }}
                    </a>
                </td>
                <td>
                    {{ row.categoria_equipamiento }}
                    <br>
                    {{ row.tipo_equipamiento }}
                </td>
                <td>
                    <span v-show="row.localidad" class="text-muted">Localidad: </span>
                    <span v-show="row.localidad">{{ row.localidad }}</span>
                    
                    <br v-show="row.direccion">
                    <span v-show="row.direccion" class="text-muted">Dirección: </span>
                    <span v-show="row.direccion">{{ row.direccion }}</span>
                    
                </td>
                
                
                
                <td v-show="appRid <= 8">
                    <button class="btn-circle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fa-solid fa-ellipsis-v"></i>
                    </button>
                    <ul class="dropdown-menu">
                        <li v-show="appRid <= 8">
                            <a class="dropdown-item" v-bind:href="`<?= URL_APP . "equipamientos/info/" ?>` + row.id">
                                Información
                            </a>
                        </li>
                        <!-- <li v-show="appRid <= 8">
                            <a class="dropdown-item" v-bind:href="`<?= URL_APP . "equipamientos/edit/" ?>` + row.id">
                                Editar
                            </a>
                        </li>
                        <li v-show="appRid <= 3">
                            <button class="dropdown-item" v-on:click="setCurrent(key)" data-bs-toggle="modal" data-bs-target="#deleteModal">
                                Eliminar
                            </button>
                        </li> -->
                    </ul>
                </td>
            </tr>
        </tbody>
    </table>
</div>