<div class="table-responsive">
    <table class="table bg-white">
        <thead>
            <th width="10px">
                <input type="checkbox" @change="selectAll" v-model="allSelected">
            </th>
            <th width="10px" class="table-warning">ID</th>
            <th></th>
            <th>Contenido</th>

            <th width="50px"></th>
        </thead>
        <tbody>
            <tr v-for="(element, key) in list" v-bind:id="`row_` + element.id" v-bind:class="{'table-info': selected.includes(element.id) }">
                <td>
                    <input type="checkbox" v-bind:id="`check_` + element.id" v-model="selected" v-bind:value="element.id">
                </td>
                <td class="table-warning">{{ element.id }}</td>

                <td>
                    <a v-bind:href="`<?= URL_ADMIN ?>repositorio/info/` + element.id + `/` + element.slug">
                        <img
                            v-bind:src="element.url_thumbnail"
                            class="rounded w50p"
                            alt="portada contenido"
                            onerror="this.src='<?= URL_IMG ?>app/repo_contenido_nd.png'"
                        >
                    </a>
                </td>
                    

                
                <td>
                    <span class="badge badge-success" v-bind:class="classEstadoPublicacion(element.estado_publicacion)">
                        {{ estadoPublicacionName(element.estado_publicacion) }}
                    </span>                    

                    <br>
                    <a v-bind:href="`<?= URL_ADMIN ?>repositorio/info/` + element.id">
                        {{ element.titulo }}
                    </a>
                    <br>
                    <strong v-show="element.anio_publicacion > 1950">{{ element.anio_publicacion }}</strong>
                    <strong v-show="element.anio_publicacion <= 1950">Año no disponible</strong>
                    <div class="only-lg">{{ element.descripcion }}</div>
                    <p>
                        <b>ARCHIVO DISCO DURO:</b> {{ element.revision_ruta_disco }}
                    </p>
                    <p><b>Palabras clave:</b> {{ element.palabras_clave }}</p>
                    <p><b>URL:</b> {{ element.url_contenido_externo }}</p>
                    <p><b>Entidad:</b> {{ element.entidad }}</p>
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