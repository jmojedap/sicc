<?php
    $options_parent = array();
?>

<form accept-charset="utf-8" id="itemForm" @submit.prevent="handleSubmit">
    <input name="category_id" type="hidden" v-model="currCategory.cod">

    <div class="mb-1 row">
        <div class="col-sm-8 offset-4">
            <button class="btn w120p" v-bind:class="appState.buttonClass" type="submit">
                {{ appState.buttonText }}
            </button>
        </div>
    </div>

    <div class="mb-1 row">
        <label for="cod" class="col-md-4 col-form-label text-end">
            Código numérico
        </label>
        <div class="col-md-8">
            <input
                name="cod"
                class="form-control"
                placeholder="Código numérico"
                title="Código numérico"
                required
                v-model="fields.cod"
                >
        </div>
    </div>

    <div class="mb-1 row">
        <label for="item_name" class="col-md-4 col-form-label text-end">
            <span class="">Nombre</span>
        </label>
        <div class="col-md-8">
            <input
                name="item_name" class="form-control" title="Nombre del parámetro"
                required
                v-model="fields.item_name" v-on:change="autocomplete"
                >
        </div>
    </div>

    <div class="mb-1 row">
        <label for="description" class="col-md-4 col-form-label text-end">Descripción</label>
        <div class="col-md-8">
            <textarea
                name="description" class="form-control"
                title="Descripción del ítem" required rows="3"
                v-model="fields.description"></textarea>
        </div>
    </div>

    <div class="mb-1 row">
        <label for="filters" class="col-md-4 col-form-label text-end">Filtros</label>
        <div class="col-md-8">
            <input type="text"
                name="filters" class="form-control"
                title="Filtros separados por guion"
                v-model="fields.filters">
        </div>
    </div>

    <div class="mb-1 row">
        <label for="abbreviation" class="col-md-4 col-form-label text-end">Abreviatura</label>
        <div class="col-md-8">
            <input name="abbreviation" class="form-control"
                placeholder="" title="Abreviatura de hasta 4 caracteres"
                v-model="fields.abbreviation"
                >
        </div>
    </div>

    <div class="mb-1 row">
        <label for="short_name" class="col-md-4 col-form-label text-end">
            <span class="">Nombre corto</span>
        </label>
        <div class="col-md-8">
            <input
                name="short_name" class="form-control" placeholder=""
                title="Nombre corto" required maxlength="25"
                v-model="fields.short_name"
                >
        </div>
    </div>

    <div class="mb-1 row">
        <label for="parent_id" class="col-md-4 col-form-label text-end">
            <span>ID Padre</span>
        </label>
        <div class="col-md-8">
            <input
                name="parent_id" class="form-control" placeholder=""
                title="ID del parámetro padre"
                v-model="fields.parent_id"
                >
        </div>
    </div>

    <div class="mb-1 row">
        <label for="ancestry" class="col-md-4 col-form-label text-end text-right">Ascendencia</label>
        <div class="col-md-8">
            <input
                name="ancestry" type="text" class="form-control"
                title="Ascendencia" placeholder="Ascendencia"
                v-model="fields.ancestry"
            >
        </div>
    </div>

    <div class="mb-1 row">
        <label for="level" class="col-md-4 col-form-label text-end text-right">Nivel</label>
        <div class="col-md-8">
            <input
                name="level" type="text" class="form-control"
                title="Nivel" placeholder="Nivel"
                v-model="fields.level"
            >
        </div>
    </div>

    <div class="mb-1 row">
        <label for="slug" class="col-md-4 col-form-label text-end">
            <span class="">Slug</span>
        </label>
        <div class="col-md-8">
            <input
                name="slug"
                class="form-control"
                placeholder=""
                title="Sin espacios y acentos"
                required
                v-model="fields.slug"

                >
        </div>
    </div>

    <div class="mb-1 row">
        <label for="item_group" class="col-md-4 col-form-label text-end text-right">Grupo del ítem</label>
        <div class="col-md-8">
            <input
                name="item_group" type="text" class="form-control"
                title="Grupo del ítem" placeholder="Grupo del ítem"
                v-model="fields.item_group"
            >
        </div>
    </div>
</form>