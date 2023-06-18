<div id="appExplore">
    <div class="row mb-2">
        <div class="col-md-4">
            
        </div>

        <div class="col-md-4">
            <a v-bind:href="`<?= URL_APP . "{$controller}/export/?" ?>` + strFilters" class="btn btn-light only-lg" v-bind:title="`Exportar ` + qtyResults + ` registros encontrados a Excel`">
                <i class="fa fa-download"></i>
            </a>
        </div>
        
        <div class="col-md-4 mb-2">
            <div class="d-flex justify-content-between">
                <div class="pt-2">
                    <span class="text-primary">{{ qtyResults }}</span>
                     resultados &middot; Pág {{ numPage }} / {{ maxPage }}
                </div>
                <?php $this->load->view('common/bs5/vue_pagination_v'); ?>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-3">
            <?php $this->load->view($views_folder . 'search_form_v'); ?>
        </div>
        <div class="col-md-9">
            <?php $this->load->view($views_folder . 'list_v'); ?>
        </div>
    </div>
    <?php $this->load->view($views_folder . 'detail_v'); ?>
</div>

<?php $this->load->view($views_folder . 'vue_v') ?>