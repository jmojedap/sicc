<style>
    #panelGeofocusApp {
        max-width: 1080px;
        margin: 0 auto;
        padding: 1.5rem 0 2.5rem;
    }

    .geofocus-panel-header {
        margin-bottom: 1.5rem;
    }

    .geofocus-panel-kicker {
        margin-bottom: 0.35rem;
        color: #6d59a4;
        font-size: 0.72rem;
        font-weight: 700;
        letter-spacing: 0.12em;
        text-transform: uppercase;
    }

    .geofocus-panel-title {
        margin-bottom: 0.4rem;
        color: #252a32;
        font-size: clamp(1.35rem, 2.2vw, 1.8rem);
        font-weight: 700;
    }

    .geofocus-panel-description {
        max-width: 600px;
        margin-bottom: 0;
        color: #69727e;
        line-height: 1.55;
    }

    .geofocus-section-list {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        border-top: 1px solid #e4e8ee;
    }

    .geofocus-section {
        position: relative;
        display: flex;
        min-height: 104px;
        align-items: center;
        gap: 1rem;
        padding: 1.25rem 1rem;
        border-bottom: 1px solid #e4e8ee;
        color: #252a32;
        text-decoration: none;
        transition: background-color 160ms ease, box-shadow 160ms ease;
    }

    .geofocus-section:nth-child(odd) {
        border-right: 1px solid #e4e8ee;
    }

    .geofocus-section:hover {
        z-index: 1;
        background-color: #f7f5fb;
        box-shadow: inset 3px 0 0 #7258a8;
        color: #252a32;
    }

    .geofocus-section:focus-visible {
        z-index: 2;
        outline: 3px solid rgba(114, 88, 168, 0.3);
        outline-offset: -3px;
    }

    .geofocus-section-icon {
        display: inline-flex;
        width: 50px;
        height: 50px;
        flex: 0 0 50px;
        align-items: center;
        justify-content: center;
        border-radius: 14px;
        background-color: #eeeaf7;
        color: #654a98;
        font-size: 1.2rem;
    }

    .geofocus-section--variables .geofocus-section-icon {
        background-color: #e2f3ef;
        color: #168b78;
    }

    .geofocus-section--priorizaciones .geofocus-section-icon {
        background-color: #fff0d8;
        color: #b97618;
    }

    .geofocus-section--info .geofocus-section-icon {
        background-color: #e5effb;
        color: #3472b7;
    }

    .geofocus-section-copy {
        min-width: 0;
        flex: 1 1 auto;
    }

    .geofocus-section-label {
        display: block;
        margin-bottom: 0.25rem;
        font-size: 1rem;
        font-weight: 650;
    }

    .geofocus-section-description {
        display: block;
        color: #707985;
        font-size: 0.82rem;
        line-height: 1.4;
    }

    .geofocus-section-action {
        display: inline-flex;
        width: 30px;
        height: 30px;
        flex: 0 0 30px;
        align-items: center;
        justify-content: center;
        border: 1px solid #dfe3e9;
        border-radius: 50%;
        color: #7c8490;
        font-size: 0.75rem;
        transition: background-color 160ms ease, border-color 160ms ease, color 160ms ease;
    }

    .geofocus-section:hover .geofocus-section-action {
        border-color: #c7b9e2;
        background-color: #fff;
        color: #654a98;
    }

    @media (max-width: 767.98px) {
        #panelGeofocusApp {
            padding-top: 1rem;
        }

        .geofocus-section-list {
            grid-template-columns: 1fr;
        }

        .geofocus-section:nth-child(odd) {
            border-right: 0;
        }
    }
</style>

<div id="panelGeofocusApp">
    <header class="geofocus-panel-header">
        <div class="geofocus-panel-kicker">Herramienta territorial</div>
        <h2 class="geofocus-panel-title">Explora Geofocus</h2>
        <p class="geofocus-panel-description">
            Consulta, organiza y analiza información geográfica para orientar la priorización territorial.
        </p>
    </header>

    <nav class="geofocus-section-list" aria-label="Secciones de Geofocus">
        <a
            v-for="section in sections"
            :key="section.key"
            class="geofocus-section"
            :class="'geofocus-section--' + section.key"
            :href="section.url"
        >
            <span class="geofocus-section-icon" aria-hidden="true">
                <i :class="section.icon"></i>
            </span>
            <span class="geofocus-section-copy">
                <span class="geofocus-section-label">{{ section.label }}</span>
                <span class="geofocus-section-description">{{ section.description }}</span>
            </span>
            <span class="geofocus-section-action" aria-hidden="true">
                <i class="fas fa-arrow-right"></i>
            </span>
        </a>
    </nav>
</div>

<script>
var panelGeofocusApp = createApp({
    data(){
        return{
            loading: false,
            sections: [
                {key: 'capas_base', label: 'Capas geográficas base', description: 'Tipos de mapas: polígonos —barrios, localidades, UPZ u otros — sobre los que se proyectan las variables.', icon: 'fas fa-layer-group', url: '<?= URL_APP . "geofocus/capas_base" ?>'},
                {key: 'variables', label: 'Variables', description: 'Consulta fenómenos de ciudad calculados por polígono. Los suscriptores solo leen; los editores pueden crear, modificar y cargar valores.', icon: 'fas fa-chart-pie', url: '<?= URL_APP . "geofocus/variables" ?>'},
                {key: 'priorizaciones', label: 'Priorizaciones', description: 'Selecciona variables, asigna pesos, ejecuta el cálculo y obtén resultados territoriales.', icon: 'fas fa-list', url: '<?= URL_APP . "geofocus/priorizaciones" ?>'},
                {key: 'info', label: 'Acerca de', description: 'Conoce el propósito y el funcionamiento de Geofocus.', icon: 'fas fa-map', url: '<?= URL_APP . "geofocus/info" ?>'},
            ],
        }
    },
    methods: {
        
    },
}).mount('#panelGeofocusApp')
</script>