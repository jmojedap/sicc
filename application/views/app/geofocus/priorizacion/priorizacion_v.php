<script src="https://code.highcharts.com/maps/highmaps.js"></script>
<script src="https://code.highcharts.com/maps/modules/exporting.js"></script>
<script src="https://code.highcharts.com/maps/modules/offline-exporting.js"></script>
<script src="https://code.highcharts.com/maps/modules/accessibility.js"></script>

<!-- <link rel="stylesheet" href="<?= URL_RESOURCES ?>css/sicc/geofocus.css"> -->
<?php $this->load->view('app/geofocus/priorizacion/test_style_v') ?>

<?php $this->load->view('app/geofocus/priorizacion/mapa_script_v') ?>

<div id="priorizacionApp">
    <div class="container">
        

        <ul class="nav nav-tabs mb-2">
            <li class="nav-item">
                <a class="nav-link pointer" href="<?= URL_APP ?>geofocus/priorizaciones/"><i class="fas fa-arrow-left"></i> Priorizaciones</a>
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

        <?php $this->load->view('app/geofocus/priorizacion/variables_v') ?>
        <?php $this->load->view('app/geofocus/priorizacion/territorios_v') ?>
        <?php $this->load->view('app/geofocus/priorizacion/mapa_v') ?>

    </div>

    <?php $this->load->view('app/geofocus/priorizacion/detalles_variable_v') ?>
</div>

<?php $this->load->view('app/geofocus/priorizacion/vue_v') ?>