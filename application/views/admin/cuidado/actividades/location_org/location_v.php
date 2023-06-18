<script src="https://unpkg.com/maplibre-gl@2.4.0/dist/maplibre-gl.js"></script>
<link href="https://unpkg.com/maplibre-gl@2.4.0/dist/maplibre-gl.css" rel="stylesheet" />

<style>
#map {
    width: 100%;
    min-height: 600px;
    border-radius: 0.25rem;
}
</style>

<div id="locationApp">
    <div class="row">
        <div class="col-md-4">
            <form accept-charset="utf-8" method="POST" id="locationForm" @submit.prevent="searchLocation">
                <fieldset v-bind:disabled="loading">
                    <div class="mb-3">
                        <div class="input-group">
                            <input
                                name="q" type="text" class="form-control"
                                required
                                title="Dirección" placeholder="Buscar lugar o direccion"
                                v-model="filters.q"
                            >
                            <div class="input-group-append">
                                <button class="btn btn-light" type="submit">
                                    <i class="fa fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                <fieldset>
            </form>
            <div class="text-center" v-show="loading">
                <div class="spinner-border text-primary" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
            </div>
            <div class="text-center" v-show="!loading">
                <p v-show="noPlaces == 1">
                    No se encontraron lugares
                </p>
                <p>
                    <span class="text-primary">{{ places.length }}</span> resultados
                </p>
            </div>
            <div class="list-group">
                <a v-for="(place,key) in places" href="#" class="list-group-item list-group-item-action" v-on:click="setCurrentPlace(key)">
                    {{ place.properties.display_name }}
                </a>
            </div>
        </div>
        <div class="col-md-8">
            <div class="d-flex justify-content-between mb-2">
                <div>
                    <span v-show="!withLocation">
                        <i class="far fa-circle text-danger"></i>
                        Sin geolocalización
                    </span>
                    <span v-show="withLocation">
                        <i class="fa fa-circle-check text-success"></i>
                        Con geolocalización
                    </span>
                </div>
                <div>
                    <button class="btn btn-primary w120p" v-on:click="saveLocation">
                        Guardar
                    </button>
                </div>
            </div>
            <input type="range" class="w100pc" min="10" max="17" v-on:change="setZoom" v-model="zoom" step="0.1">
            <div>
                <small>
                    Longitud: {{ actividad.longitud }} &middot;
                    Latitud: {{ actividad.latitud }} 
                </small>
            </div>
            <div id="map" class="border"></div>
        </div>
    </div>
</div>

<?php $this->load->view('admin/cuidado/actividades/location/script_v') ?>