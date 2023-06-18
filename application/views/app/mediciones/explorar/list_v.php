<div class="pb-2 mb-3 border-bottom" v-for="(row, key) in list" v-bind:id="`row_` + row.id">
    <h5 class="mt-0">
        <a v-bind:href="`<?= URL_FRONT ?>mediciones/detalles/` + row.id">
            <span class="badge bg-primary">{{ row.id }}</span>
            {{ row.nombre_medicion }}
        </a>
    </h5>
    <p>{{ row.palabras_clave }}</p>
    <p>
        <span class="text-muted">Temática: </span>
        {{ tematica1Name(row.tematica_1) }}
        <span> &middot; {{ tematica1Name(row.tematica_2) }}</span>
    </p>
    <small class="text-muted">
        {{ dateFormat(row.fecha_publicacion) }} &middot; {{ ago(row.fecha_publicacion) }}
    </small>
</div>