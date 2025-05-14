<?php $this->load->view('app/barrios_vivos/style_v') ?>

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
            <?php if ( in_array($this->session->userdata('role'),[1,2,3,8]) ) : ?>
                <div>
                    <a href="<?= URL_APP . "barrios_vivos/add" ?>" class="btn btn-primary w120p me-3"><i class="fa fa-plus"></i> Nuevo</a>
                </div>
            <?php endif; ?>
            <div>
                <a v-bind:href="`<?= URL_ADMIN . "{$controller}export/?" ?>` + strFilters"
                    class="btn btn-light only-lg me-1 d-none" v-bind:title="`Exportar ` + qtyResults + ` registros encontrados a Excel`">
                    <i class="fa fa-download"></i>
                </a>
                <button class="btn btn-warning" title="Eliminar elementos seleccionados"
                    data-toggle="modal" data-target="#modal_delete"
                    v-show="selected.length > 0"
                    >
                    <i class="fa fa-trash"></i>
                </button>
                <button class="btn me-1" v-bind:class="{'btn-light': viewFormat == `table` }" title="Ver como tabla" v-on:click="viewFormat = `table`">
                    <i class="fa fa-solid fa-table-list"></i>
                </button>
                <button class="btn" v-bind:class="{'btn-light': viewFormat == `cards` }" title="Ver como tarjetas" v-on:click="viewFormat = `cards`">
                    <i class="fa fa-solid fa-newspaper"></i>
                </button>
            </div>
        </div>
        
        <div class="col-md-3 mb-2">
            <?php $this->load->view('common/bs5/pagination_v'); ?>
        </div>
    </div>

    <div class="row">
        <div v-bind:class="{'col-md-2': showFilters, 'd-none': !showFilters }">
            <?php $this->load->view($views_folder . 'search_form_v'); ?>
        </div>
        <div v-bind:class="{'col-md-10': showFilters, 'col-md-12': !showFilters }">
            <div v-show="!loading" class="mt-3">
                <?php $this->load->view($views_folder . 'list_v'); ?>
            </div>
        
            <div v-show="loading" class="text-center mb-2">
                <div class="spinner-border text-secondary" role="status">
                    <span class="sr-only">Cargando...</span>
                </div>
            </div>
        </div>
    </div>


    <?php $this->load->view($views_folder . 'detail_v'); ?>
    <?php $this->load->view('common/bs5/modal_single_delete_v'); ?>
</div>

<?php $this->load->view($views_folder . 'vue_v') ?>