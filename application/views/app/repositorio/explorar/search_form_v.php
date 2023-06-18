<form accept-charset="utf-8" method="POST" id="searchForm" @submit.prevent="getList">
    <input name="q" type="hidden"  v-model="filters.q">
    <div class="grid-columns-15rem mb-3">
        <div class="mb-2">
            <label for="y">Año</label>
            <input
                name="y" type="number" class="form-control" min="1950" max="<?= intval(date('Y')) + 1 ?>"
                v-model="filters.y"
            >
        </div>
        <div class="mb-2">
            <label for="type">Entidad</label>
            <select name="fe1" v-model="filters.fe1" class="form-select">
                <option value="">[ Todas las entidades ]</option>
                <option v-for="optionEntidad in arrEntidad" v-bind:value="optionEntidad.abbreviation">{{ optionEntidad.name }}</option>
            </select>
        </div>
        <div class="mb-2">
            <label for="type">Tipo contenido</label>
            <select name="repo_tipo" v-model="filters.repo_tipo" class="form-select">
                <option value="">[ Todos los tipos ]</option>
                <option v-for="optionTipo in arrTipo" v-bind:value="optionTipo.str_cod">{{ optionTipo.name }}</option>
            </select>
        </div>
        <div class="mb-2">
            <label for="type">Tema específico</label>
            <select name="repo_subtema" v-model="filters.repo_subtema" class="form-select">
                <option value="">[ Todos los temas ]</option>
                <option v-for="optionSubtema in arrSubtema" v-bind:value="optionSubtema.cod">{{ optionSubtema.name }}</option>
            </select>
        </div>

        <div class="mb-2">
            <label for="type">Contenido/Archivo disponible</label>
            <select name="fe2" v-model="filters.fe2" class="form-select">
                <option value="">[ Todos ]</option>
                <option v-for="optionSiNoNa in arrSiNoNa" v-bind:value="optionSiNoNa.cod">{{ optionSiNoNa.name }}</option>
            </select>
        </div>

        <div class="mb-2">
            <label for="type">Estado de publicación</label>
            <select name="status" v-model="filters.status" class="form-select">
                <option value="">[ Todos los estados ]</option>
                <option v-for="optionEstado in arrEstadoPublicacion" v-bind:value="optionEstado.str_cod">{{ optionEstado.name }}</option>
            </select>
        </div>

        <div class="mb-2">
            <label for="type">Formato</label>
            <select name="repo_formato" v-model="filters.repo_formato" class="form-select">
                <option value="">[ Todos los estados ]</option>
                <option v-for="optionFormato in arrFormato" v-bind:value="optionFormato.str_cod">{{ optionFormato.name }}</option>
            </select>
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
