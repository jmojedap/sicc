<form accept-charset="utf-8" method="POST" id="searchForm" @submit.prevent="getList">
    <input name="q" type="hidden"  v-model="filters.q">
    <div class="grid-columns-15rem mb-3">
        <div class="mb-3">
            <label for="cat_1" class="form-label">Categoría</label>
            <select name="cat_1" v-model="filters.cat_1" class="form-select" v-on:change="clearCat2">
                <option value="">[ Todas ]</option>
                <option v-for="optionCat1 in arrCat1" v-bind:value="optionCat1.cod">{{ optionCat1.name }}</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="cat_2" class="form-label">Subcategoría</label>
            <select name="cat_2" v-model="filters.cat_2" class="form-select">
                <option value="">[ Todas ]</option>
                <option v-for="optionCat2 in arrCat2Filtered" v-bind:value="optionCat2.cod">
                    {{ optionCat2.name }}
                </option>
            </select>
        </div>
        
        <!-- Botón ejecutar y limpiar filtros -->
        <div class="d-flex justify-content-end">
            <button type="button" class="btn btn-light btn-sqr me-1" title="Quitar los filtros de búsqueda"
                v-show="strFilters.length > 0" v-on:click="clearFilters">
                <i class="fa-solid fa-xmark"></i>
            </button>
            <button class="btn btn-primary w120p" type="submit">Buscar</button>
        </div>
    </div>
</form>
