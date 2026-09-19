<link href="https://unpkg.com/maplibre-gl@2.4.0/dist/maplibre-gl.css" rel="stylesheet" />
<script src="https://unpkg.com/maplibre-gl@2.4.0/dist/maplibre-gl.js"></script>

<!-- <link rel="stylesheet" href="<?= URL_RESOURCES ?>css/sicc/geofocus.css"> -->
<?php $this->load->view('app/geofocus/geofocus_style_v') ?>

<div id="priorizacionApp" class="geofocus-priority-page">
    <div class="container geofocus-priority-shell">
        <nav class="geofocus-priority-navigation" aria-label="Secciones de la priorización">
        <ul class="nav nav-pills">
            <li class="nav-item">
                <a class="nav-link pointer" href="<?= URL_APP ?>geofocus/priorizaciones/"><i
                        class="fas fa-arrow-left"></i> Priorizaciones</a>
            </li>
            <li class="nav-item">
                <a class="nav-link pointer" v-on:click="setSection('variables')"
                    v-bind:class="{'active': section == 'variables' }">Variables</a>
            </li>
            <li class="nav-item">
                <a class="nav-link pointer" v-on:click="setSection('territorios')"
                    v-bind:class="{'active': section == 'territorios' }">Territorios</a>
            </li>
            <li class="nav-item">
                <a class="nav-link pointer" v-on:click="setSection('mapa')"
                    v-bind:class="{'active': section == 'mapa' }">Mapa</a>
            </li>
        </ul>
        </nav>

        <?php $this->load->view('app/geofocus/priorizacion/variables_v') ?>
        <?php $this->load->view('app/geofocus/priorizacion/territorios_v') ?>
        <?php $this->load->view('app/geofocus/priorizacion/mapa_v') ?>

    </div>

    <?php $this->load->view('app/geofocus/priorizacion/detalles_variable_v') ?>
</div>

<?php $this->load->view('app/geofocus/priorizacion/vue_v') ?>
