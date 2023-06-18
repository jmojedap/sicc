<form accept-charset="utf-8" method="POST" id="searchForm" @submit.prevent="getList">
    <input name="q" type="hidden"  v-model="filters.q">
    <div class="grid-columns-15rem mb-3">
        <div class="mb-2">
            <label for="type">Tipo medición</label>
            <select name="type" v-model="filters.type" class="form-select">
                <option value="">[ Todos los tipos ]</option>
                <option v-for="optionType in arrType" v-bind:value="optionType.str_cod">{{ optionType.name }}</option>
            </select>
        </div>

        <div class="mb-2">
            <label for="cat_1">Temática</label>
            <select name="cat_1" v-model="filters.cat_1" class="form-select">
                <option value="">[ Todas ]</option>
                <option v-for="optionTematica1 in arrTematica1" v-bind:value="optionTematica1.cod">{{ optionTematica1.name }}</option>
            </select>
        </div>

        <div class="mb-2">
            <label for="cat_2">Unidad de observación</label>
            <select name="cat_2" v-model="filters.cat_2" class="form-select">
                <option value="">[ Todos ]</option>
                <option v-for="optionUnidadObservacion in arrUnidadObservacion" v-bind:value="optionUnidadObservacion.str_cod">{{ optionUnidadObservacion.name }}</option>
            </select>
        </div>

        <div class="mb-2">
            <label for="y">Año</label>
            <input
                name="y" type="number" class="form-control" min="2020" max="<?= intval(date('Y')) + 1 ?>"
                v-model="filters.y"
            >
        </div>
        
        <!-- Botón ejecutar y limpiar filtros -->
        <div class="d-flex justify-content-end">
            <button type="button" class="btn btn-light me-1" title="Quitar los filtros de búsqueda"
                v-show="strFilters.length > 0" v-on:click="clearFilters">
                <i class="fa-solid fa-xmark"></i>
            </button>
            <button class="btn btn-primary w120p" type="submit">Buscar</button>
        </div>
    </div>
</form>
