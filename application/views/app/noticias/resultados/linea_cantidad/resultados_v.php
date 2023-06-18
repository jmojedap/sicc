<?php $this->load->view('assets/highcharts') ?>

<div id="vizApp" class="">
    <div class="d-none">
        <p>{{ qtyRowsSeries }} :: {{ cat_1 }}</p>
    </div>
    <form accept-charset="utf-8" method="POST" id="seriesForm" @submit.prevent="handleSubmit">
        <fieldset v-bind:disabled="loading">
            <div class="mb-2 row">
                <label for="cat_1" class="col-md-2 col-form-label">Categoría</label>
                <div class="col-md-3">
                    <select name="cat_1" v-model="cat_1" class="form-select">
                        <option value="">[ Todas ]</option>
                        <option v-for="optionCat1 in arrCat1" v-bind:value="`0` + optionCat1.id">{{ optionCat1.name }}</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <button class="btn btn-warning w120p" type="submit" v-bind:disabled="loading">
                        <i class="fa fa-spin fa-spinner" v-show="loading"></i>
                        <span v-show="!loading">Actualizar</span>
                    </button>
                </div>
            </div>
        <fieldset>
    </form>
</div>
<figure class="highcharts-figure">
    <div id="chart" style="min-height: calc(100vh - 180px);" class="border"></div>
</figure>

<?php $this->load->view('app/noticias/resultados/linea_cantidad/script_v') ?>