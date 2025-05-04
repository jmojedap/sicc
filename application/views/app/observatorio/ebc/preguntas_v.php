<div class="row">
    <div class="col-md-3">
        <div class="mini-titulo">MÓDULOS</div>
        <div class="d-flex mb-2">
            <button class="btn-modulo" v-for="modulo in modulos"
                v-bind:title="`Módulo: ` + modulo.nombre" v-bind:class="btnModuloClass(modulo)"
                v-on:click="setModulo(modulo.modulo_id)">
                {{ modulo.modulo_id }}
            </button>
        </div>
        <div class="mini-titulo">SECCIONES</div>
        <div class="list-group">
            <button type="button" class="list-group-item list-group-item-action d-flex"
                v-for="seccion in medicionData.secciones" v-on:click="setSeccion(seccion.num_seccion)"
                v-bind:class="{'active': medicionSeccionActiva == seccion.num_seccion }">
                <div class="me-3">
                    <strong class="">{{ seccion.num_seccion }}</strong>
                </div>
                <div>
                    {{ seccion.nombre_seccion }}
                </div>
            </button>
        </div>
    </div>
    <div class="col-md-9">
        <h3 class="text-center modulo-titulo" v-bind:class="`modulo-titulo-` + currentModulo.modulo_id">
            {{ currentModulo.nombre }}
        </h3>
        <div class="mini-titulo">preguntas</div>
        <div class="d-flex justify-content-center">
            <nav aria-label="Page navigation example">
                <ul class="pagination">
                    <li class="page-item w50p text-center" v-for="pregunta in medicionData.preguntas"
                        v-show="pregunta.num_seccion == medicionSeccionActiva" v-bind:title="pregunta.enunciado_1"
                        v-bind:class="{'active': pregunta.id == medicionPreguntaActiva }"
                        >
                        <a class="page-link" href="#" v-on:click="setPregunta(pregunta.id)">{{ pregunta.etiqueta_1 }}</a>
                    </li>
                </ul>
            </nav>
        </div>
        <iframe v-bind:src="`<?= URL_APP ?>mediciones/hc_resultados_pregunta/` + medicionPreguntaActiva" v-show="medicionPreguntaActiva > 0"
            frameborder="0" style="min-height: 600px;" class="w-100 rounded">
        </iframe>
        <div class="alert alert-light text-center" v-show="medicionPreguntaActiva == 0">
            Las preguntas de este módulo de la encuesta todavía se encuentra en proceso de recolección, procesamiento y análisis.
        </div>
    </div>
</div>