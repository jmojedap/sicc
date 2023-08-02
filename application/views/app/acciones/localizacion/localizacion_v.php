<script src="https://unpkg.com/maplibre-gl@2.4.0/dist/maplibre-gl.js"></script>
<link href="https://unpkg.com/maplibre-gl@2.4.0/dist/maplibre-gl.css" rel="stylesheet" />

<style>
#map {
    width: 100%;
    min-height: calc(100vh - 250px);
    border-radius: 0.25rem;
}
</style>

<div id="localizacionApp">
    <div class="row">
        <div class="col-md-3">
            <div class="py-1 d-flex justify-content-between">
                <div>
                    <span class="text-muted">Dirección: </span>
                    <span>
                        {{ accion.direccion }}
                    </span>
                </div>
                <button class="btn btn-sm btn-light ms-1" v-on:click="setSearch(accion.direccion)">
                    Buscar
                </button>
            </div>
            <div class="py-1 d-flex justify-content-between">
                <div>
                    <span class="text-muted">Lugar: </span>
                    <span>
                        {{ accion.nombre_lugar }}
                    </span>
                </div>
                <button class="btn btn-sm btn-light ms-1" v-on:click="setSearch(accion.nombre_lugar)">
                    Buscar
                </button>
            </div>
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
                            <button class="btn btn-light" type="submit">
                                <i class="fa fa-search"></i>
                            </button>
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
                    <span class="text-primary">{{ places.length }}</span> opciones posibles
                </p>
            </div>
            <div class="list-group">
                <a v-for="(place,key) in places" href="#" class="list-group-item list-group-item-action" v-on:click="setCurrentPlace(key)">
                    {{ place.address }}
                </a>
            </div>
        </div>
        <div class="col-md-9">
            <div class="d-flex justify-content-between mb-2">
                <div>
                    <button class="btn btn-primary w120p" v-on:click="saveLocation">
                        Guardar
                    </button>
                </div>
                <div>
                    <span v-show="savingStatus == 0">
                        <i class="far fa-circle text-danger"></i>
                        Sin geolocalización
                    </span>
                    <span v-show="savingStatus == 1">
                        <i class="fa fa-circle-check text-success"></i>
                        Geolocalización guardada
                    </span>
                    <span v-show="savingStatus == 3">
                        <i class="fa fa-circle-info text-warning"></i>
                        Sin guardar
                    </span>
                </div>
                <div>
                    <span>
                        
                        Lat: <strong class="text-primary">{{ accion.latitud }}</strong>
                        &middot;
                        Long: <strong class="text-primary">{{ accion.longitud }}</strong> 
                    </span>
                </div>
                <div style="width:300px;">
                    <input type="range" class="w100pc" min="10" max="17" v-on:change="setZoom" v-model="zoom" step="0.1">
                </div>
            </div>
            <div id="map" class="border"></div>
        </div>
    </div>
</div>

<?php $this->load->view('app/acciones/localizacion/script_v') ?>