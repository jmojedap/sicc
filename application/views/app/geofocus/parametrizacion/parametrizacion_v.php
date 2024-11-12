<script src="https://code.highcharts.com/maps/highmaps.js"></script>
<script src="https://code.highcharts.com/maps/modules/exporting.js"></script>
<script src="https://code.highcharts.com/maps/modules/offline-exporting.js"></script>
<script src="https://code.highcharts.com/maps/modules/accessibility.js"></script>

<?php $this->load->view('app/geofocus/parametrizacion/style_v') ?>

<?php $this->load->view('app/geofocus/parametrizacion/mapa_script_v') ?>

<div id="parametrizacionApp">
    <div class="container">
        

        <ul class="nav nav-tabs mb-2">
            <li class="nav-item">
                <a class="nav-link pointer" href="<?= URL_APP ?>geofocus/priorizaciones/"><i class="fas fa-arrow-left"></i> Volver</a>
            </li>
            <li class="nav-item">
                <a class="nav-link pointer" v-on:click="setSection('variables')" v-bind:class="{'active': section == 'variables' }">Variables</a>
            </li>
            <li class="nav-item">
                <a class="nav-link pointer" v-on:click="setSection('territorios')" v-bind:class="{'active': section == 'territorios' }">Territorios</a>
            </li>
            <li class="nav-item">
                <a class="nav-link pointer" v-on:click="setSection('mapa')" v-bind:class="{'active': section == 'mapa' }">Mapa</a>
            </li>
        </ul>

        <?php $this->load->view('app/geofocus/parametrizacion/variables_v') ?>
        <?php $this->load->view('app/geofocus/parametrizacion/territorios_v') ?>
        <?php $this->load->view('app/geofocus/parametrizacion/mapa_v') ?>

    </div>

    <?php $this->load->view('app/geofocus/parametrizacion/detalles_variable_v') ?>
</div>

<?php $this->load->view('app/geofocus/parametrizacion/vue_v') ?>