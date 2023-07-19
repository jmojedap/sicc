<style>
    body{
        background-color: #1f1f1f;
        color: #E5E5E5;
    }

    .container-fluid {
        padding-right: 0px;
        padding-left: 0px;
    }

    .contenido-destacado {
        background-color: #111111;
    }

    #repositorio-inicio{
        color: #e5e5e5;
    }

    #repositorio-inicio h1,h2,h3,h4,h5,h6{
        color: #e5e5e5;
    }

    #repositorio-inicio .thumbnail-container{
        margin-right: 1em;
    }

    #repositorio-inicio .thumbnail-container img{
        width: 220px;
    }
</style>

<link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/slick.css'>

<div id="repositorioInicioApp" style="">
    <div id="repositorio-inicio">
        <div class="contenido-destacado py-3">
            <div class="center_box_920">
                <div class="row">
                    <div class="col-md-4">
                        <a v-bind:href="`<?= URL_APP . "repositorio/ver/" ?>` + currentContenido.id + `/` + currentContenido.slug">
                            <img
                                v-bind:src="currentContenido.url_image"
                                class="rounded w-100"
                                v-bind:alt="`Portada ` + currentContenido.titulo"
                                v-bind:onerror="`this.src='<?= URL_CONTENT ?>repositorio/entidades/`+ currentContenido.entidad_sigla +`.jpg'`"
                            >
                        </a>
                    </div>
                    <div class="col-md-8">
                        <p>
                            {{ subtemaName(currentContenido.subtema_1) }}
                            <span v-show="currentContenido.subtema_2 > 0">
                                &middot;
                                {{ subtemaName(currentContenido.subtema_2) }}
                            </span>
                            <span v-show="currentContenido.sector_area > 0">
                                &middot;
                                {{ areaName(currentContenido.sector_area) }}
                            </span>
                        </p>
                        <h3>{{ currentContenido.titulo }}</h3>
                        <p><span class="badge bg-warning">{{ currentContenido.anio_publicacion }}</span></p>
                        <div style="max-height: 200px; overflow: hidden;" class="mb-1">
                            <p>{{ currentContenido.descripcion }}</p>
                        </div>
                        <div>
                            <a v-bind:href="`<?= URL_APP . "repositorio/ver/" ?>` + currentContenido.id + `/` + currentContenido.slug" class="btn btn-light btn-sm w120p">
                                Abrir
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="p-3">
            <h3>Secretaría de Cultura, Recreación y Deporte</h3>
            <div class="repo-carousel d-flex">
                <div v-for="contenido in contenidos" class="thumbnail-container" v-on:click="setCurrent(contenido.id)">
                    <center>
                        <img
                            v-bind:src="contenido.url_thumbnail"
                            class="rounded pointer"
                            v-bind:alt="`Portada ` + contenido.titulo"
                            v-bind:onerror="`this.src='<?= URL_CONTENT ?>repositorio/entidades/`+ contenido.entidad_sigla +`.jpg'`">
                    </center>
                </div>
            </div>
        </div>
        
    </div>
</div>

<?php $this->load->view('app/repositorio/inicio/vue_v') ?>