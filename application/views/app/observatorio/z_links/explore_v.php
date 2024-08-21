<div id="appExplore">
    <div class="row">
        <div class="col-md-3">
            <div class="input-group mb-2">
                <input
                    type="text" name="q" class="form-control" placeholder="Buscar"
                    v-model="filters.q" v-on:change="getList"
                    >
                    <button type="button" class="btn btn-light" title="Mostrar filtros para búsqueda avanzada"
                        v-on:click="toggleFilters"
                        v-bind:class="{'btn-light': showFilters, 'btn-light': !showFilters }"
                        >
                        <i class="fa-solid fa-sliders"></i>
                    </button>
            </div>
        </div>

        <div class="col-md-6">
            <a v-bind:href="`<?= URL_ADMIN . "{$controller}/export/?" ?>` + strFilters"
                class="btn btn-light only-lg me-1" v-bind:title="`Exportar ` + qtyResults + ` registros encontrados a Excel`">
                <i class="fa fa-download"></i>
            </a>
            <button class="btn btn-light me-1" v-bind:class="{'text-primary': viewFormat == `list` }" title="Ver como lista" v-on:click="viewFormat = `list`">
                <i class="fa fa-solid fa-list"></i>
            </button>
            <button class="btn btn-light" v-bind:class="{'text-primary': viewFormat == `grid` }" title="Ver como cuadrícula" v-on:click="viewFormat = `grid`">
                <i class="fa fa-solid fa-grip"></i>
            </button>
        </div>
        
        <div class="col-md-3 mb-2">
            <?php $this->load->view('common/bs5/pagination_v'); ?>
        </div>
    </div>

    <div class="row">
        <div v-bind:class="{'col-md-3': showFilters, 'd-none': !showFilters }">
            <?php $this->load->view($views_folder . 'search_form_v'); ?>
        </div>
        <div v-bind:class="{'col-md-9': showFilters, 'col-md-12': !showFilters }">
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
    <?php $this->load->view('common/modal_delete_selected_v'); ?>
</div>

<?php $this->load->view($views_folder . 'vue_v') ?>