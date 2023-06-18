<div class="d-flex pb-2 mb-3 border-bottom" v-for="(element, key) in list" v-bind:id="`row_` + element.id">
    <div class="me-2">
        <a v-bind:href="`<?= URL_FRONT ?>contenidos/revisar/` + element.id + `/` + element.slug">
            <img v-bind:src="element.url_thumbnail" class="rounded w120p mr-3" alt="imagen post"
                onerror="this.src='<?= URL_IMG ?>app/sm_nd_square.png'">
        </a>
    </div>
    <div class="">
        <h5 class="mt-0">
            <a v-bind:href="`<?= URL_FRONT ?>contenidos/leer/` + element.id + `/` + element.slug">
                {{ element.post_name }}
            </a>
        </h5>
        <p>{{ element.excerpt }}</p>
        <p>
            <small class="badge" v-bind:class="documentTypeLabel(element.tipo_documento, 'slug')">
            {{ documentTypeLabel(element.tipo_documento, 'name') }}
        </small>
        </p>
        <small class="text-muted">
            {{ dateFormat(element.published_at) }} &middot; {{ ago(element.published_at) }}
        </small>
    </div>
</div>