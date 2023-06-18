<style>
    #mapView {
    padding: 0;
    margin: 0;
    height: calc(100vh - 150px);
    min-height: 600px;
    width: 100%;
    }
</style>

<link rel="stylesheet" href="https://js.arcgis.com/4.25/esri/themes/light/main.css">
<script src="https://js.arcgis.com/4.25/"></script>


<div id="actividadesMapaApp">
    <div id="mapView"></div>
</div>

<script>
    var actividades = <?= json_encode($actividades['list']) ?>;

    require(
        [
            "esri/config", "esri/Map", "esri/views/MapView",
            "esri/Graphic",
            "esri/layers/GraphicsLayer",
            "esri/layers/FeatureLayer",
        ],
        function(esriConfig, Map, MapView, Graphic, GraphicsLayer, FeatureLayer) {
            esriConfig.apiKey = '<?= K_ARCGIS ?>';

            const map = new Map({
                basemap: 'arcgis-light-gray' // Basemap layer service
                //basemap: 'arcgis-navigation' // Basemap layer service
            });

            const view = new MapView({
                map: map,
                center: [-74.10,4.55], // Longitude, latitude
                zoom: 10, // Zoom level
                container: "mapView" // Div element
            });

            const graphicsLayer = new GraphicsLayer();
            map.add(graphicsLayer);

            const simpleMarkerSymbol = {
                type: "simple-marker",
                color: '#e6a000',
                size: "6px",
                outline: {
                    color: '#a87600',
                    width: 1
                }
            };

            var actividadPoint = {}

            actividades.forEach(actividad => {
                actividadPoint = {
                    type: "point",
                    longitude: actividad.longitud,
                    latitude: actividad.latitud
                }
                var actividadGraphic = new Graphic({
                    geometry: actividadPoint,
                    symbol: simpleMarkerSymbol
                })
                graphicsLayer.add(actividadGraphic);
            });

            
        }
    );
</script>