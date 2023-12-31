<script>
const arcGisApiKey = '<?= K_ARCGIS ?>';
var actividad = {
        id: <?= $row->id ?>,
        nombre_lugar: <?= json_encode($row->nombre_lugar) ?>,
        direccion: <?= json_encode($row->direccion) ?>,
        longitud: <?= $row->longitud ?>,
        latitud: <?= $row->latitud ?>,
    };
var withLocation = true;
if ( actividad.latitud == 0 ) withLocation = false;
if ( actividad.longitud == 0 ) withLocation = false;

var startPoint = [-74.247,4.778];
var centerPoint = [-74.10,4.70];
var startZoom = 10.2;
var markerColor = '#ee3248';

if ( withLocation == true ) {
    centerPoint = [actividad.longitud,actividad.latitud];
    startZoom = 10.2;
}


var locationApp = new Vue({
    el: '#locationApp',
    created: function() {
        //this.get_list()
    },
    data: {
        actividad: actividad,
        withLocation: withLocation,
        filters:{
            q: actividad.nombre_lugar,
        },
        zoom: startZoom,
        places: [],
        currentPlace: {},
        noPlaces: -1,
        loading: false,
    },
    methods: {
        saveLocation: function(){
            this.loading = true
            var formValues = new FormData()
            formValues.append('id',this.actividad.id)
            formValues.append('longitud', this.actividad.longitud)
            formValues.append('latitud', this.actividad.latitud)
            axios.post(URL_API + 'cuidado/save/', formValues)
            .then(response => {
                if ( response.data.saved_id > 0 ) {
                    toastr['success']('Geolocalización guardada')
                    this.withLocation = true
                    marker.remove()
                    savedMarker.setLngLat([this.actividad.longitud,this.actividad.latitud])
                    savedMarker.addTo(map)
                }
                this.loading = false
            })
            .catch( function(error) {console.log(error)} )
        },
        setZoom: function(){
            console.log(this.zoom)
            map.setZoom(this.zoom)
        },
        searchLocation: function(){
            this.loading = true
            var qSearch = encodeURIComponent(this.filters.q + ' bogota colombia');
            axios.get('https://geocode-api.arcgis.com/arcgis/rest/services/World/GeocodeServer/findAddressCandidates?f=json&singleLine='+ qSearch +'&token='+arcGisApiKey)
            .then(response => {
                console.log(response.data)
                this.places = response.data.candidates
                if ( this.places.length == 0 ) this.noPlaces = 1
                if ( this.places.length > 0 ) this.noPlaces = 0
                this.loading = false
            })
            .catch(function(error) {
                toastr['error']('Ocurrió un error en la búsqueda de lugares')
                this.loading = false
                console.log(error) 
            })
        },
        setCurrentPlace: function(placeKey){
            this.currentPlace = this.places[placeKey]
            var point = [this.currentPlace.location.x,this.currentPlace.location.y]
            this.addMarker()
            marker.setLngLat(point)
            savedMarker.setLngLat(point)
            map.setCenter(point)
            this.zoom = 16
            this.setZoom()
            this.actividad.longitud = point[0]
            this.actividad.latitud = point[1]
        },
        addMarker: function(){
            if ( this.withLocation == false ) {
                marker.addTo(map)
            }
        },
    }
})

// Map functions
//-----------------------------------------------------------------------------
var map = new maplibregl.Map({
    container: 'map',
    style: 'https://api.maptiler.com/maps/streets/style.json?key=HhMwo0fS2MiZbvfIKcNZ',
    center: centerPoint,
    zoom: startZoom
});

var marker = new maplibregl.Marker({
        draggable: true,
        color: '#ee3248',
    })
    .setLngLat(startPoint);

var savedMarker = new maplibregl.Marker({
        draggable: true,
        color: '#30a338',
    })
    .setLngLat([actividad.longitud,actividad.latitud]);

if ( withLocation == true ) {
    savedMarker.addTo(map)
}

function onDragEndMarker() {
    var lngLat = marker.getLngLat();
    locationApp.actividad.longitud = lngLat.lng
    locationApp.actividad.latitud = lngLat.lat
}

function onDragEndSavedMarker() {
    var lngLat = savedMarker.getLngLat();
    locationApp.actividad.longitud = lngLat.lng
    locationApp.actividad.latitud = lngLat.lat
}

marker.on('dragend', onDragEndMarker);
savedMarker.on('dragend', onDragEndSavedMarker);
</script>