<?php $this->load->view('app/geofocus/parametrizacion/style_v') ?>

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
        </ul>

        <?php $this->load->view('app/geofocus/parametrizacion/variables_v') ?>
        <?php $this->load->view('app/geofocus/parametrizacion/territorios_v') ?>
    </div>

    <?php $this->load->view('app/geofocus/parametrizacion/detalles_variable_v') ?>
</div>

<?php $this->load->view('app/geofocus/parametrizacion/vue_v') ?>