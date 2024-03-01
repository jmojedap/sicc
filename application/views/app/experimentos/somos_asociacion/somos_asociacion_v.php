<script src="<?= URL_CONTENT ?>observatorio/experimentos/somos_asociacion/sa_opciones.js"></script>

<div id="somosAsociacionApp">
    <div class="container">
        <div class="text-center my-5" v-show="status == 'preparacion'">
            <button class="btn btn-primary btn-lg" v-on:click="iniciar">
                INICIAR
            </button>
        </div>
        <div v-show="status == 'respondiendo'">
            <div class="fase-1" style="margin-top: 5em;">
                <h3 class="text-muted text-center mb-3">{{ faseActiva }}/{{ fases.length }}</h3>
                <div class="d-flex justify-content-between mb-1">
                    <button class="btn btn-light btn-lg w320p" v-on:click="responder('Favorable')">
                        Favorable
                    </button>
                    <button class="btn btn-light btn-lg w320p" v-on:click="responder('Desfavorable')">
                        Desfavorable
                    </button>
                </div>
                <div class="d-flex justify-content-between mb-5">
                    <button class="btn btn-light btn-lg w320p" v-on:click="responder('Transgénero')">
                        Transgénero
                    </button>
                    <button class="btn btn-light btn-lg w320p" v-on:click="responder('Cisgénero')">
                        Cisgénero
                    </button>
                </div>
                <div class="text-center" v-show="opcionActiva.file_id.length > 0">
                    <img
                        v-bind:src="`<?= URL_CONTENT ?>observatorio/experimentos/somos_asociacion/personas/` + opcionActiva.numero + `.jpg`"
                        class="rounded"
                        v-bind:alt="`Imagen ` + opcionActiva.opcion"
                        onerror="this.src='<?= URL_IMG ?>app/sm_nd_square.png'"
                    >
                </div>
                <h1 class="text-center my-1">{{ opcionActiva.opcion }}</h1>
            </div>
        </div>
        <table class="table bg-white mt-5">
            <thead>
                <th>opcion</th>
                <th>respuesta_correcta</th>
                <th>respuesta_usuario</th>
                <th>milisegundos</th>
            </thead>
            <tbody>
                <tr v-for="(respuesta, key) in respuestas">
                    <td>{{ respuesta.opcion }}</td>
                    <td>{{ respuesta.respuesta_correcta }}</td>
                    <td>{{ respuesta.respuesta_usuario }}</td>
                    <td>{{ respuesta.milisegundos }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<?php $this->load->view('app/experimentos/somos_asociacion/vue_v') ?>