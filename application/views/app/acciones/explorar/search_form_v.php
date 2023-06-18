<form accept-charset="utf-8" method="POST" id="searchForm" @submit.prevent="getList">
    <div class="mb-2">
        <input name="q" type="hidden"  v-model="filters.q">
    </div>
    <div class="grid-columns-15rem mb-2">
        <div class="mb-2">
            <label for="mes" class="form-label">Mes</label>
            <select name="m" v-model="filters.m" class="form-select" v-on:change="getList">
                <option value="">[ Todos los meses ]</option>
                <option v-for="optionPeriodo in arrPeriodo" v-bind:value="optionPeriodo.id">{{ optionPeriodo.name }}</option>
            </select>
        </div>
        <div class="mb-2">
            <label for="estrategia" class="form-label">Estrategia</label>
            <select name="estrategia" v-model="filters.estrategia" class="form-select" v-on:change="getList">
                <option value="">[ Todas las estrategias ]</option>
                <option v-for="optionEstrategia in arrEstrategia" v-bind:value="optionEstrategia.cod">{{ optionEstrategia.name }}</option>
            </select>
        </div>
        <div class="mb-2">
            <label for="localidad" class="form-label">Localidad</label>
            <select name="localidad" v-model="filters.localidad" class="form-select" v-on:change="getList">
                <option value="">[ Todas las localidades ]</option>
                <option v-for="optionLocalidad in arrLocalidad" v-bind:value="optionLocalidad.cod">{{ optionLocalidad.name }}</option>
            </select>
        </div>
        
        <div class="mb-2">
            <label for="d1" class="form-label">Rango de fechas</label>
            <input name="d1" v-model="filters.d1" class="form-select" v-on:change="getList" type="date" title="Fecha desde">
            <input name="d2" v-model="filters.de" class="form-select" v-on:change="getList" type="date" title="Fecha hasta">
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
