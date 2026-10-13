<link href="https://unpkg.com/maplibre-gl@2.4.0/dist/maplibre-gl.css" rel="stylesheet" />
<script src="https://unpkg.com/maplibre-gl@2.4.0/dist/maplibre-gl.js"></script>
<script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/dompurify@3.0.5/dist/purify.min.js"></script>
<!-- EFECTO ANIMATE.CSS: aparición simple de la descripción generada. -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">

<!-- <link rel="stylesheet" href="<?= URL_RESOURCES ?>css/sicc/geofocus.css"> -->
<?php $this->load->view('app/geofocus/geofocus_style_v') ?>

<div id="priorizacionApp" class="geofocus-priority-page">
    <div class="container-fluid geofocus-priority-shell">
        <nav class="geofocus-priority-navigation" aria-label="Secciones de la priorización">
        <ul class="nav nav-pills">
            <li class="nav-item">
                <a class="nav-link pointer" href="<?= URL_APP ?>geofocus/priorizaciones/"><i
                        class="fas fa-arrow-left"></i> Priorizaciones</a>
            </li>
            <li class="nav-item">
                <a class="nav-link pointer" v-on:click="setSection('variables')" v-bind:aria-disabled="loading"
                    v-bind:class="{'active': section == 'variables' }">Variables</a>
            </li>
            <li class="nav-item">
                <a class="nav-link pointer" v-on:click="setSection('mapa')" v-bind:aria-disabled="loading"
                v-bind:class="{'active': section == 'mapa' }">Mapa</a>
            </li>
            <li class="nav-item">
                <a class="nav-link pointer" v-on:click="setSection('resultados')" v-bind:aria-disabled="loading"
                    v-bind:class="{'active': section == 'resultados' }">Resultados</a>
            </li>
        </ul>
        <div class="geofocus-priority-navigation-context" aria-label="Contexto de la priorización">
            <strong>{{ priorizacion.nombre }}</strong>
            <small v-if="capaBase">
                <i class="fas fa-layer-group me-1" aria-hidden="true"></i>{{ capaBase.nombre }}
            </small>
        </div>
        </nav>

        <!-- 2026-10-12: el aviso de cambios pendientes se muestra mediante toastr, sin desplazar las secciones. -->

        <?php $this->load->view('app/geofocus/priorizacion/variables_v') ?>
        <?php $this->load->view('app/geofocus/priorizacion/resultados_v') ?>
        <?php $this->load->view('app/geofocus/priorizacion/mapa_v') ?>

    </div>

    <?php $this->load->view('app/geofocus/priorizacion/detalles_variable_v') ?>
</div>

<?php $this->load->view('app/geofocus/priorizacion/vue_v') ?>
