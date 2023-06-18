<form accept-charset="utf-8" method="POST" id="searchForm" @submit.prevent="getList">
    <input name="q" type="hidden"  v-model="filters.q">
    <div class="grid-columns-15rem mb-3">
        <div>
            <label for="y">Año publicación</label>
            <input type="number" name="y" v-model="filters.y" class="form-control" min="1950" max="2023">
        </div>
        <div>
            <label for="status">Estado publicación</label>
            <select name="status" v-model="filters.status" class="form-control">
                <option value="">[ Todos los estados ]</option>
                <option v-for="optionEstadoPublicacion in arrEstadoPublicacion" v-bind:value="optionEstadoPublicacion.str_cod">{{ optionEstadoPublicacion.name }}</option>
            </select>
        </div>
        <div>
            <label for="repo_tipo">Tipo contenido</label>
            <select name="repo_tipo" v-model="filters.repo_tipo" class="form-control">
                <option value="">[ Todos los tipos ]</option>
                <option v-for="optionTipo in arrTipo" v-bind:value="optionTipo.str_cod">{{ optionTipo.name }}</option>
            </select>
        </div>
        <div>
            <label for="repo_formato">Formato</label>
            <select name="repo_formato" v-model="filters.repo_formato" class="form-control">
                <option value="">[ Todos los tipos ]</option>
                <option v-for="optionFormato in arrFormato" v-bind:value="optionFormato.str_cod">{{ optionFormato.name }}</option>
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
