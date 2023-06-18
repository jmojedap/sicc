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

<?= $arrCat1 ?>

<div v-show="qtyUserChecked < checkGoal">
    <div class="section border-top pt-2">
        <p class="text-center">¿En la noticia (imagen o titular) aparece una o varias personas afrodescendientes?</p>
        <div class="float-end">
            <i class="far fa-circle text-muted" v-show="fields.cat_1 == 0"></i>
            <i class="fa fa-circle-check text-primary" v-show="fields.cat_1 != 0"></i>
        </div>
    </div>
    <div class="w100pc">
        <div class="btn-group w-100">
            <button class="btn w120p" v-for="optionCat1 in arrCat1"
                v-bind:class="{'btn-primary': fields.cat_1 == optionCat1.cod, 'btn-light': fields.cat_1 != optionCat1.cod }"
                v-on:click="setCat1(optionCat1.cod)"
                >
                {{ optionCat1.name }}
            </button>
        </div>
    </div>
    <div class="row" v-show="fields.cat_1 == '1'">
        <div class="col-md-4">
            <div class="section mt-3 pt-2 text-center">
                <i class="far fa-circle text-muted" v-show="fields.cat_2 == 0"></i>
                <i class="fa fa-circle-check text-primary" v-show="fields.cat_2 != 0"></i>
                <br>
                <p>
                    ¿Cuál de los siguientes <strong class="text-primary">roles</strong> tiene la o las personas afrodescendientes de la noticia?
                </p>
            </div>
            <div class="list-group mb-2">
                <button class="list-group-item list-group-item-action" v-for="optionCat2 in arrCat2"
                    v-bind:class="{'active': optionCat2.cod == fields.cat_2 }"
                    v-on:click="setCat2(optionCat2.cod)"
                    v-bind:title="optionCat2.name"
                    >
                    {{ optionCat2.name }}
                </button>
            </div>
            <input
                name="texto_1" type="text" class="form-control" v-show="fields.cat_2 == '990'"
                required
                title="¿Cuál?" placeholder="¿Cuál?"
                v-model="fields.texto_1"
            >
        </div>
        <div class="col-md-4">
            <div class="section mt-3 pt-2 text-center">
                <i class="far fa-circle text-muted" v-show="fields.cat_3 == 0"></i>
                <i class="fa fa-circle-check text-primary" v-show="fields.cat_3 != 0"></i>
                <br>
                <p>
                    ¿Cuál de las siguientes <strong class="text-primary">identidades de género</strong> parecen tener la o las personas afrodescendientes de la noticia?
                </p>
            </div>
            <div class="list-group">
                <button class="list-group-item list-group-item-action" v-for="optionCat3 in arrCat3"
                    v-bind:class="{'active': optionCat3.cod == fields.cat_3 }"
                    v-on:click="setCat3(optionCat3.cod)"
                    v-bind:title="optionCat3.name"
                    >
                    {{ optionCat3.name }}
                </button>
            </div>
        </div>
        <div class="col-md-4">
            <div class="section mt-3 pt-2 text-center">
                <i class="far fa-circle text-muted" v-show="fields.cat_4 == 0"></i>
                <i class="fa fa-circle-check text-primary" v-show="fields.cat_4 != 0"></i>
                <br>
                <p>
                    ¿Cuál de las siguientes <strong class="text-primary">orientaciones</strong> sexuales parecen tener la o las personas afrodescendientes de la noticia
                </p>
            </div>
            <div class="list-group">
                <button class="list-group-item list-group-item-action" v-for="optionCat4 in arrCat4"
                    v-bind:class="{'active': optionCat4.cod == fields.cat_4 }"
                    v-on:click="setCat4(optionCat4.cod)"
                    v-bind:title="optionCat4.name"
                    >
                    {{ optionCat4.name }}
                </button>
            </div>
        </div>
    </div>
    <form accept-charset="utf-8" method="POST" id="noticiaForm" @submit.prevent="sendForm">
        <fieldset v-bind:disabled="loading">
            <input name="actualizado_por" type="hidden" v-model="fields.actualizado_por">
            <input name="cat_1" type="hidden" v-model="fields.cat_1">
            <input name="cat_2" type="hidden" v-model="fields.cat_2">
            <input name="cat_3" type="hidden" v-model="fields.cat_3">
            <input name="cat_4" type="hidden" v-model="fields.cat_4">
            <input name="texto_1" type="hidden" v-model="fields.texto_1">
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
                    <p v-show="fields.updated_at.length > 0">
                        <i class="fa fa-check"></i> Clasificada por <a v-bind:href="`<?= URL_APP . 'noticias/explorar/?fe2=' ?>` + noticia.actualizado_por">{{ fields.actualizado_por }}</a>
                        &middot;
                        {{ ago(fields.updated_at) }} 
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
        <a class="btn btn-light w150p me-2" v-bind:href="`<?= URL_APP ?>noticias_afro/siguiente/` + (qtyUserChecked +20)">
            Voy por 20 más
        </a>
        <a class="btn btn-light w150p me-2" href="<?= URL_APP ?>noticias/salir">
            Finalizar
        </a>
    </div>

</div>