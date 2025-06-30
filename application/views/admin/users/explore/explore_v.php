<div id="appExplore">
    <div class="row">
        <div class="col-md-2">
            <div class="input-group mb-2">
                <input
                    type="text" name="q" class="form-control" placeholder="Buscar"
                    v-model="filters.q" v-on:change="getList"
                    >
                    <button type="button" class="btn" title="Mostrar filtros para búsqueda avanzada"
                        v-on:click="toggleFilters"
                        v-bind:class="{'btn-secondary': showFilters, 'btn-light': !showFilters }"
                        >
                        <i class="fa-solid fa-sliders"></i>
                    </button>
            </div>
        </div>

        <div class="col-md-7 d-flex">
            <div>
                <a v-bind:href="`<?= URL_ADMIN . "{$controller}/export/?" ?>` + strFilters"
                    class="btn btn-light only-lg me-1" v-bind:title="`Exportar ` + qtyResults + ` registros encontrados a Excel`">
                    <i class="fa fa-download"></i>
                </a>
                <button class="btn btn-warning me-1" title="Eliminar elementos seleccionados"
                    data-bs-toggle="modal" data-bs-target="#deleteModal"
                    v-show="selected.length > 0"
                    >
                    <i class="fas fa-trash"></i>
                </button>
                <button class="btn me-1 d-none" v-bind:class="{'btn-light': viewFormat == `table` }" title="Ver como tabla" v-on:click="viewFormat = `table`">
                    <i class="fas fa-table-list"></i>
                </button>
                <button class="btn d-none" v-bind:class="{'btn-light': viewFormat == `cards` }" title="Ver como tarjetas" v-on:click="viewFormat = `cards`">
                    <i class="fas fa-newspaper"></i>
                </button>
            </div>
        </div>
        
        <div class="col-md-3 mb-2">
            <?php $this->load->view('common/bs5/pagination_v'); ?>
        </div>
    </div>

    <div v-show="showFilters">
        <?php $this->load->view($views_folder . 'search_form_v'); ?>
    </div>

    <div v-show="!loading">
        <?php $this->load->view($views_folder . 'list_v'); ?>
    </div>

    <div v-show="loading" class="text-center mb-2">
        <div class="spinner-border text-secondary" role="status">
            <span class="sr-only">Cargando...</span>
        </div>
    </div>


    <?php $this->load->view($views_folder . 'detail_v'); ?>
    <?php $this->load->view('common/bs5/modal_delete_v'); ?>
</div>

<?php $this->load->view($views_folder . 'vue_v') ?>