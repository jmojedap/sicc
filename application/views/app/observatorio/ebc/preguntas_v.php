<div class="row">
    <div class="col-md-3">
        <div class="list-group">
            <button type="button" class="list-group-item list-group-item-action"
                v-for="seccion in medicionData.secciones" v-on:click="setSeccion(seccion.num_seccion)"
                v-bind:class="{'active': medicionSeccionActiva == seccion.num_seccion }">
                <strong class="me-3">{{ seccion.num_seccion }}</strong>
                {{ seccion.nombre_seccion }}
            </button>
        </div>
    </div>
    <div class="col-md-9">
        <div class="d-flex justify-content-center">
            <nav aria-label="Page navigation example">
                <ul class="pagination">
                    <li class="page-item w50p text-center" v-for="pregunta in medicionData.preguntas"
                        v-show="pregunta.num_seccion == medicionSeccionActiva"
                        v-bind:class="{'active': pregunta.id == medicionPreguntaActiva }"
                        >
                        <a class="page-link" href="#" v-on:click="setPregunta(pregunta.id)">{{ pregunta.etiqueta_1 }}</a>
                    </li>
                </ul>
            </nav>
        </div>
        <iframe v-bind:src="`<?= URL_APP ?>mediciones/hc_resultados_pregunta/` + medicionPreguntaActiva" frameborder="0" style="min-height: 600px;"
            class="w-100 rounded"></iframe>
    </div>
</div>