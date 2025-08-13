<form accept-charset="utf-8" method="POST" id="searchForm" @submit.prevent="getList">
    <div class="mb-2">
        <input name="q" type="hidden"  v-model="filters.q">
    </div>
    <div class="mb-2">        
        <div class="mb-1">
            <select name="type" v-model="filters.type" class="form-select" v-on:change="getList" title="Filtrar por tipo">
                <option value="-">[ Todos los tipos ]</option>
                <option v-for="optionTipoEq in arrTipoEq" v-bind:value="optionTipoEq.cod">{{ optionTipoEq.name }}</option>
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
