<form accept-charset="utf-8" method="POST" id="searchForm" @submit.prevent="getList">
    <input name="q" type="hidden"  v-model="filters.q">
    <div class="grid-columns-15rem mb-3">
        <div>
            <label for="localidad">Localidad</label>
            <select name="localidad" v-model="filters.type" class="form-control">
                <option value="">[ Todas ]</option>
                <option v-for="optionLocalidad in arrLocalidad" v-bind:value="optionLocalidad.str_cod">{{ optionLocalidad.name }}</option>
            </select>
        </div>
        <div>
            <label for="m">Mes</label>
            <select name="m" v-model="filters.m" class="form-control">
                <option value="">[ Todos ]</option>
                <option v-for="optionMonth in arrMonth" v-bind:value="optionMonth.str_cod">{{ optionMonth.name }}</option>
            </select>
        </div>


        
        <!-- Botón ejecutar y limpiar filtros -->
        <div>
            <label for="" style="opacity: 0%">Enviar</label><br>
            <button class="btn btn-primary w100p" type="submit">Buscar</button>
            <button type="button" class="btn btn-light" title="Quitar los filtros de búsqueda"
                v-show="strFilters.length > 0" v-on:click="clearFilters">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
    </div>
</form>
