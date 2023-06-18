<div class="d-flex pb-2 mb-3 border-bottom" v-for="(element, key) in list" v-bind:id="`row_` + element.id">
    <div class="me-2">
        <a v-bind:href="`<?= URL_FRONT ?>noticias/clasificar/` + element.id + `/` + element.aleatorio">
            <img v-bind:src="element.url_thumbnail" class="rounded w180p mr-3" alt="imagen post"
                onerror="this.src='<?= URL_IMG ?>app/sm_nd_square.png'">
        </a>
    </div>
    <div class="">
        <h5 class="mt-0">
            <a v-bind:href="`<?= URL_FRONT ?>noticias/clasificar/` + element.id + `/` + element.aleatorio">
                {{ element.titular }}
            </a>
        </h5>
        <p>{{ element.epigrafe }}</p>
        <small class="text-muted">
            {{ dateFormat(element.fecha_publicacion) }} &middot; {{ ago(element.fecha_publicacion) }}
        </small>
        
        <div class="py-2 border-top" v-if="element.status == 1">
            <p>
                <span class="badge me-1" v-bind:class="clasificacionClass(element.clasificacion)">{{ clasificationName(element.clasificacion) }}</span>
                <span class="badge bg-info">
                    {{ catName(element.cat_1)  }}
                </span>
            </p>
            <p>
                <i class="fa fa-check"></i> Clasificada por <a v-bind:href="`<?= URL_APP . 'noticias/explorar/?fe2=' ?>` + element.actualizado_por">{{ element.actualizado_por }}</a>
            </p>
        </div>
        <div v-else class="text-center">
            Sin revisión
        </div>
        
    </div>
</div>