<form accept-charset="utf-8" method="POST" id="searchForm" @submit.prevent="getList">
    <fieldset v-bind:disabled="loading">
        <div class="input-group mb-2">
            <input
                type="text" name="q" class="form-control"
                placeholder="Buscar" title="Buscar"
                autofocus
                v-model="filters.q" v-on:change="getList"
                >
            <button type="submit" class="btn btn-light">
                <i class="fa fa-search"></i>
            </button>
        </div>
        <div class="mb-3">
            <label for="cat_1" class="form-label">Categoría</label>
            <select name="cat_1" v-model="filters.cat_1" class="form-select">
                <option v-for="optionCat1 in arrCat1" v-bind:value="optionCat1.cod">{{ optionCat1.name }}</option>
            </select>
        </div>
        <div class="mb-3 d-flex justify-content-end">
            <button class="btn btn-light me-2 w100p" v-on:click="removeFilters" type="button" title="Quitar filtros">Todos</button>
            <button class="btn btn-primary w100p" type="submit">Buscar</button>
        </div>
    <fieldset>
</form>