<div class="alert alert-info" role="alert" v-show="qtyResults==0">
    <i class="fa fa-info-circle"></i>
    No hay resultados para la búsqueda realizada
</div>

<div class="grid-columns-12rem" v-show="viewFormat == `grid`">
    <div v-for="(contenido,key) in list">
        <a v-bind:href="`<?= URL_APP ?>repositorio/informacion/` + contenido.id + `/` + contenido.slug">
            <img v-bind:src="contenido.url_thumbnail" class="w100pc rounded"
                v-bind:alt="`portada contenido` + contenido.titulo" v-bind:title="contenido.titulo"
                v-bind:onerror="`this.src='<?= URL_CONTENT ?>repositorio/entidades/`+ contenido.entidad_sigla +`.jpg'`">
        </a>
    </div>
</div>

<div class="pb-2 mb-3 border-bottom" v-for="(row, key) in list" v-bind:id="`row_` + row.id"
    v-show="viewFormat == `list`">
    <div class="d-flex">
        <div class="me-3">
            <a v-bind:href="`<?= URL_APP ?>repositorio/informacion/` + row.id + `/` + row.slug">
                <img v-bind:src="row.url_thumbnail" class="rounded w150p" alt="portada contenido"
                    v-bind:onerror="`this.src='<?= URL_CONTENT ?>repositorio/entidades/`+ row.entidad_sigla +`.jpg'`">
            </a>
        </div>
        <div>
            <div class="d-flex justify-content-between">
                <h4 class="mt-0">
                    <a v-bind:href="`<?= URL_FRONT ?>repositorio/index/` + row.id" class="">
                        {{ row.titulo }}
                    </a>
                </h4>
                <div>
                    <a v-bind:href="`<?= URL_APP . "repositorio/edit/" ?>` + row.id" class="a4">
                        <i class="fa-solid fa-pencil"></i>
                    </a>
                </div>
            </div>
            <p>
                <span class="badge bg-secondary me-1">{{ row.anio_publicacion }}</span>
                <span class="badge bg-warning text-dark">{{ subtemaName(row.subtema_1) }}</span>
            </p>
            <div style="max-height: 150px; overflow: hidden;">
                <p>{{ row.descripcion.substring(0,500) }}<span v-show="row.descripcion.length > 500">...</span></p>
            </div>
            <div v-show="showDetails">
                <p>
                    <span class="text-primary">Palabras clave</span>: {{ row.palabras_clave }}
                    &middot;

                    &middot;
                    <span class="text-muted">Tipo:</span> {{ tipoName(row.tipo_contenido) }}
                    &middot;
                    <span class="text-muted">Estado publicación:</span>
                    {{ estadoPublicacionName(row.estado_publicacion) }}
                </p>
                <p class="text-break">
                    <span class="text-primary">Link externo: </span>
                    <a v-bind:href="row.url_contenido_externo" target="_blank">{{ row.url_contenido_externo }}</a>
                    <span class="text-muted" v-show="row.url_contenido_externo.length == 0">No disponible</span>
                    &middot;
                    <span class="text-primary" v-if="row.revision_ruta_disco.length > 0">Ruta disco local: </span>
                    {{ row.revision_ruta_disco }}
                </p>
            </div>
        </div>
    </div>

</div>