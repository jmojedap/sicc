<div id="appExplore">
    <div class="row">
        <div class="col-md-5">
            <?php $this->load->view($views_folder . 'search_form_v'); ?>
        </div>

        <div class="col-md-3">
            <a v-bind:href="`<?= URL_ADMIN . "{$controller}/export/?" ?>` + str_filters" class="btn btn-light only-lg me-1" v-bind:title="`Exportar ` + qtyResults + ` registros encontrados a Excel`">
                <i class="fa fa-download"></i>
            </a>
            <button class="btn btn-warning"
                title="Eliminar elementos seleccionados"
                data-toggle="modal" data-target="#modal_delete"
                v-show="selected.length > 0"
                >
                <i class="fas fa-trash"></i>
            </button>
        </div>
        
        <div class="col-md-4 mb-2 text-right">
            <?php $this->load->view('common/bs5/pagination_v'); ?>
        </div>
    </div>

    <?php $this->load->view($views_folder . 'list_v'); ?>
    <?php $this->load->view($views_folder . 'detail_v'); ?>
    <?php $this->load->view('common/modal_delete_v'); ?>
</div>

<?php $this->load->view($views_folder . 'vue_v') ?>