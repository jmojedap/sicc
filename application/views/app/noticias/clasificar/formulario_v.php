<div>
    <div class="row">
        <div class="col-md-4 text-center">
            <a v-bind:href="noticia.url_publicacion" target="_blank">
                <img
                    v-bind:src="noticia.url_thumbnail"
                    class="rounded me-2 mb-2 w100pc"
                    alt="imagen noticia"
                    onerror="this.src='<?= URL_IMG ?>app/nd.png'"
                >
            </a>
        </div>
        <div class="col-md-8">
            <a v-bind:href="noticia.url_publicacion" target="_blank">
                <h3>{{ noticia.titular }}</h3>
            </a>
            <p>{{ noticia.epigrafe }} <a v-bind:href="noticia.url_publicacion" target="_blank">Ver más...</a></p>
            <p class="text-muted">
                {{ dateFormat(noticia.fecha_publicacion) }}
                &middot;
                {{ ago(noticia.fecha_publicacion) }}
            </p>
            <p>
                
            </p>
        </div>
    </div>
</div>

<div v-show="qtyUserChecked < checkGoal">
    <h3 class="section">
        CLASIFICACIÓN
        <div class="float-end">
            <i class="far fa-circle text-muted" v-show="fields.clasificacion == 0"></i>
            <i class="fa fa-circle-check text-primary" v-show="fields.clasificacion != 0"></i>
        </div>
    </h3>
    <div class="w100pc border-bottom">
        <div class="btn-group w-100">
            <button class="btn w120p" v-for="optionClasificacion in optionsClasificacion"
                v-bind:class="btnClasificacionClass(optionClasificacion.value)"
                v-on:click="setClasificacion(optionClasificacion.value)"
                >
                {{ optionClasificacion.name }}
            </button>
        </div>
    </div>
    <h3 class="section my-2">
        TEMA
        <div class="float-end">
            <i class="far fa-circle text-muted" v-show="fields.cat_1 == 0"></i>
            <i class="fa fa-circle-check text-primary" v-show="fields.cat_1 != 0"></i>
        </div>
    </h3>
    <div class="cat-grid">
        <button class="cat" v-for="optionCat1 in optionsCat"
            v-bind:class="{'active': optionCat1.id == fields.cat_1 }"
            v-on:click="setCat1(optionCat1.id)"
            v-bind:title="optionCat1.name"
            >
            {{ optionCat1.name }}
        </button>
    </div>
    <h3 class="section my-3">
        ¿LA COMPARTIRÍAS EN REDES?
        <div class="float-end">
            <i class="far fa-circle text-muted" v-show="fields.compartible == 0"></i>
            <i class="fa fa-circle-check text-primary" v-show="fields.compartible != 0"></i>
        </div>
    </h3>
    <div class="compartible-grid">
        <button class="cat w-100" v-on:click="setCompartible(1)" v-bind:class="{'active': fields.compartible == 1 }">
            {{ optionsCompartible[0].name }}
        </button>
        <button class="cat w-100" v-on:click="setCompartible(2)" v-bind:class="{'active': fields.compartible == 2 }">
            {{ optionsCompartible[1].name }}
        </button>
    </div>
    <form accept-charset="utf-8" method="POST" id="noticiaForm" @submit.prevent="sendForm">
        <fieldset v-bind:disabled="loading">
            <input name="actualizado_por" type="hidden" v-model="fields.actualizado_por">
            <input name="cat_1" type="hidden" v-model="fields.cat_1">
            <input name="clasificacion" type="hidden" v-model="fields.clasificacion">
            <input name="compartible" type="hidden" v-model="fields.compartible">
            <div class="text-center py-3">
                <button class="btn btn-light btn-lg w150p me-2" type="button" v-on:click="goToNext">
                    SALTAR
                </button>
                <button class="btn btn-lg w150p"
                    type="submit" v-bind:disabled="submitDisabled"
                    v-bind:class="{'btn-outline-primary': submitDisabled, 'btn-primary': !submitDisabled }"
                    >
                    GUARDAR
                </button>
                <div class="my-2">
                    <p v-show="noticia.actualizado_por.length > 0">
                        <i class="fa fa-check"></i> Clasificada por <a v-bind:href="`<?= URL_APP . 'noticias/explorar/?fe2=' ?>` + noticia.actualizado_por">{{ noticia.actualizado_por }}</a>
                        &middot;
                        {{ ago(noticia.updated_at) }} 
                    </p>
                </div>
            </div>
        <fieldset>
    </form>
</div>

<div class="py-3" v-show="qtyUserChecked >= checkGoal">
    <h2 class="text-primary text-success"></h2>
    <div class="alert alert-success text-center">
        ¡Gracias! Has clasificado {{ qtyUserChecked }} noticias
    </div>
    <div class="text-center">
        <h3>¿Quieres continuar?</h3>
        <br>
        <a class="btn btn-light w150p me-2" v-bind:href="`<?= URL_APP ?>noticias/siguiente/` + (qtyUserChecked +20)">
            Voy por 20 más
        </a>
        <a class="btn btn-light w150p me-2" href="<?= URL_APP ?>noticias/salir">
            Finalizar
        </a>
        <a class="btn btn-light w150p" href="<?= URL_APP ?>noticias/resumen">
            Resultados
        </a>
    </div>

</div>