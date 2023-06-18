<form accept-charset="utf-8" method="POST" id="searchForm" @submit.prevent="getList">
    <input name="q" type="hidden"  v-model="filters.q">
    <div class="grid-columns-15rem mb-3">
        <div>
            <label for="type">Tipo post</label>
            <select name="type" v-model="filters.type" class="form-control">
                <option value="">[ Todos los tipos ]</option>
                <option v-for="optionType in optionsType" v-bind:value="optionType.str_cod">{{ optionType.name }}</option>
            </select>
        </div>
    
        <div>
            <label for="u">Id usuario creador</label>
            <input
            name="u" type="number" class="form-control"
            title="ID usuario creador" placeholder="ID usuario creador"
            v-model="filters.u"
            >
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
