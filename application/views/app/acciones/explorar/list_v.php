<div v-show="viewFormat == `cards`">
    <div class="card mb-2" v-for="(row,key) in list">
        <div class="card-body">
            <div class="d-flex justify-content-between">
                <h4 class="card-title"><span class="badge bg-primary">{{ row.id }}</span>
                    <a v-bind:href="`<?= URL_APP . "acciones/edit/" ?>` + row.id" class="ms-2">
                        {{ row.nombre_accion }}
                    </a>
                </h4>
                <div v-show="appRid <= 8">
                    <a v-bind:href="`<?= URL_APP . "acciones/edit/" ?>` + row.id" class="a4">
                        <i class="fas fa-pencil"></i>
                    </a>
                </div>
            </div>
            <p class="text-muted">
                {{ dateFormat(row.fecha, 'DD/MMMM') }} &middot;
                {{ ago(row.fecha) }} &middot;
                <span class="badge bg-info">{{ localidadName(row.cod_localidad) }}</span>
            </p>
            <p>{{ row.descripcion }}</p>
            <p>
                {{ row.dependencia }} &middot;
                {{ row.equipo_trabajo }} &middot;
                <span class="text-muted">Participantes:</span>
                {{ row.participantes_equipo }}
            </p>
            <p>
                Evidencia: 
                <a v-bind:href="row.url_evidencia" target="_blank" v-bind:title="row.url_evidencia" v-show="row.url_evidencia.length > 0">
                    {{ row.url_evidencia }}
                </a>
                <span class="text-muted" v-show="row.url_evidencia.length == 0">
                    ND
                </span>
            </p>
            <p>
                <span class="text-muted">Lugar:</span>
                {{ row.nombre_lugar }} &middot;
                {{ row.direccion }} &middot;
                <span class="text-muted">Coordenadas</span>
                {{ row.latitud }}, {{ row.longitud }}
                &middot;
                <span class="text-muted">Mujeres:</span>
                {{ row.cantidad_mujeres }}
                &middot;
                <span class="text-muted">Hombres:</span>
                {{ row.cantidad_hombres }}
                &middot;
                <span class="text-muted">Cant Sexo ND/NR:</span>
                {{ row.cantidad_sexo_nd }}
                &middot;
                <span class="text-muted">Total asistentes</span>
                <span class="badge bg-primary ms-1">
                    {{ parseInt(row.cantidad_mujeres) + parseInt(row.cantidad_hombres) + parseInt(row.cantidad_sexo_nd) }}
                </span>
            </p>
        </div>
    </div>
</div>

<div class="table-responsive" v-show="viewFormat == `table`">
    <table class="table bg-white table-sm">
        <thead>
            <th width="20px">ID</th>
            <th>Fecha</th>
            <th>Mes</th>
            <th>Nombre</th>
            <th>Estrategia</th>
            <th>Localidad</th>
            <th>Link evidencia</th>
            <th width="20px"></th>
            <th width="20px" v-show="appRid <= 8"></th>
        </thead>
        <tbody>
            <tr v-for="(row, key) in list" v-bind:id="`row_` + row.id" v-bind:class="{'table-info': selected.includes(row.id) }">
                <td class="text-center table-warning">{{ row.id }}</td>
                <td width="20px" class="text-center">
                    {{ dateFormat(row.fecha, 'DD') }}
                </td>
                <td width="120px">{{ dateFormat(row.fecha,'MMMM') }}</td>
                <td>
                    <a v-bind:href="`<?= URL_FRONT ?>acciones/index/` + row.id">
                        {{ row.nombre_accion }}
                    </a>
                </td>
                <td>
                    {{ estrategiaName(row.estrategia) }}
                </td>
                <td>
                    {{ localidadName(row.cod_localidad) }}
                </td>
                <td>
                    <a v-bind:href="row.url_evidencia" target="_blank" v-bind:title="row.url_evidencia">
                        {{ row.url_evidencia.substring(0,20) }}...
                    </a>
                </td>
                <td>
                    <button class="a4" v-on:click="setCurrent(key)" data-bs-toggle="modal" data-bs-target="#detailModal">
                        <i class="fa-solid fa-ellipsis-h"></i>
                    </button>
                </td>
                <td v-show="appRid <= 8">
                    <button class="a4" v-on:click="setCurrent(key)" data-bs-toggle="modal" data-bs-target="#deleteModal">
                        <i class="fa-solid fa-trash"></i>
                    </button>
                </td>
            </tr>
        </tbody>
    </table>
</div>