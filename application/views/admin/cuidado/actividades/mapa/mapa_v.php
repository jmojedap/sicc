<script src="https://unpkg.com/maplibre-gl@2.4.0/dist/maplibre-gl.js"></script>
<link href="https://unpkg.com/maplibre-gl@2.4.0/dist/maplibre-gl.css" rel="stylesheet" />

<style>
#map {
    width: 100%;
    height: calc(100vh - 130px);
    min-height: 600px;
    border-radius: 0.25rem;
}
</style>

<div id="map"></div>
<script>
var map = new maplibregl.Map({
    container: 'map',
    style: 'https://api.maptiler.com/maps/streets/style.json?key=HhMwo0fS2MiZbvfIKcNZ',
    center: [-74.10, 4.6],
    zoom: 10.5
});

map.on('load', function() {
    map.addSource('source-actividades', {
        'type': 'geojson',
        'data': {
            'type': 'FeatureCollection',
            'features': [
                <?php foreach ( $actividades['list'] as $actividad ) : ?> {
                    <?php
                        $popUpContent = '<strong><a href="' . URL_ADMIN . "cuidado/details/{$actividad->id}" . '">' . $actividad->nombre_actividad .  '</a></strong><p>' . $actividad->descripcion . '</p>';
                        $popUpContent .= '<b class="text-primary">Lugar:</b> ' . $actividad->nombre_lugar . '<br>';
                        $popUpContent .= '<a href="' . URL_ADMIN . "cuidado/details/{$actividad->id}" . '">Ir a detalles</a>';
                        $popUpContent = json_encode($popUpContent);
                    ?>
                    'type': 'Feature',
                    'properties': {
                        'description': <?= $popUpContent ?>,
                        'icon': 'park'
                    },
                    'geometry': {
                        'type': 'Point',
                        'coordinates': [<?= $actividad->longitud ?>, <?= $actividad->latitud ?>]
                    }
                },
                <?php endforeach ?>
            ]
        }
    });

    map.addLayer({
        'id': 'layer-actividades',
        'type': 'circle',
        'source': 'source-actividades',
        'paint': {
            'circle-radius': 6,
            'circle-color': '#ee3248'
        },
        'filter': ['==', '$type', 'Point']
    });
        

    // When a click event occurs on a feature in the source-actividades layer, open a popup at the
    // location of the feature, with description HTML from its properties.
    map.on('click', 'layer-actividades', function(e) {
        var coordinates = e.features[0].geometry.coordinates.slice();
        var description = e.features[0].properties.description;

        // Ensure that if the map is zoomed out such that multiple
        // copies of the feature are visible, the popup appears
        // over the copy being pointed to.
        while (Math.abs(e.lngLat.lng - coordinates[0]) > 180) {
            coordinates[0] += e.lngLat.lng > coordinates[0] ? 360 : -360;
        }

        new maplibregl.Popup()
            .setLngLat(coordinates)
            .setHTML(description)
            .addTo(map);
    });

    // Change the cursor to a pointer when the mouse is over the source-actividades layer.
    map.on('mouseenter', 'layer-actividades', function() {
        map.getCanvas().style.cursor = 'pointer';
    });

    // Change it back to a pointer when it leaves.
    map.on('mouseleave', 'layer-actividades', function() {
        map.getCanvas().style.cursor = '';
    });
});
</script>