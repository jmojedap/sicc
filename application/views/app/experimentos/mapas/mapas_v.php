<script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>
<script src="https://unpkg.com/maplibre-gl/dist/maplibre-gl.js"></script>
<link href="https://unpkg.com/maplibre-gl/dist/maplibre-gl.css" rel="stylesheet">
<style>
    body { margin: 0; padding: 0; }
    #app { width: 100vw; height: 100vh; }
    #map { width: 100%; height: 100%; }
</style>

<div id="app">
    <div id="map"></div>
</div>

<script>
const app = Vue.createApp({
    data() {
        return {
            map: null,
            geojsonURL: "<?= URL_CONTENT ?>maps/localidades_bogota_urbano.geojson" // Reemplaza con la URL de tu archivo GeoJSON
        };
    },
    mounted() {
        this.iniciarMapa();
    },
    methods: {
        iniciarMapa() {
            const accessToken = "Klhn3YJflM9DdzM43OXV"; // Reemplaza con tu token

            this.map = new maplibregl.Map({
                container: 'map',
                //style: `https://api.maptiler.com/maps/streets/style.json?key=${accessToken}`,
                style: "https://basemaps.cartocdn.com/gl/positron-gl-style/style.json", // Mapa en escala de grises (Carto)
                center: [-74.0817, 4.6097], // Bogotá
                zoom: 11,
                pitch: 0, // Inclinación 3D opcional
                bearing: 100 // Rotación del mapa (100 grados)
            });

            this.map.addControl(new maplibregl.NavigationControl());

            this.map.on('load', () => {
                this.cargarGeoJSON();
            });

            this.map.on('load', () => {
                this.cargarGeoJSON();
            });
        },
        async cargarGeoJSON() {
            try {
                const response = await fetch(this.geojsonURL);
                const geojsonData = await response.json();

                this.map.addSource('poligonos', {
                    type: 'geojson',
                    data: geojsonData
                });

                this.map.addLayer({
                    id: 'poligonos-fill',
                    type: 'fill',
                    source: 'poligonos',
                    paint: {
                        'fill-color': '#AA0066', // Color de relleno rojo
                        'fill-opacity': 0.3 // Transparencia
                    }
                });

                this.map.addLayer({
                    id: 'poligonos-borde',
                    type: 'line',
                    source: 'poligonos',
                    paint: {
                        'line-color': '#000',
                        'line-width': 2
                    }
                });

            } catch (error) {
                console.error("Error cargando GeoJSON:", error);
            }
        }
    }
});

app.mount("#app");
</script>
