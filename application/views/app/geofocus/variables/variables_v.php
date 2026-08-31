<?php $this->load->view('app/geofocus/geofocus_style_v') ?>


<div id="geofocusVariablesApp">
    <div v-show="section == 'lista'">
        <?php $this->load->view('app/geofocus/variables/variables_lista_v') ?>
    </div>

    <?php $this->load->view('app/geofocus/variables/variables_form_v') ?>
    <?php $this->load->view('app/geofocus/variables/variables_detalle_v') ?>
</div>

<?php $this->load->view('app/geofocus/variables/variables_vue_v') ?>
