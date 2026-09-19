<style>
/* Etiquetas de temas de GeoFocus */
.tema {
    display: inline-block;
    padding: 0.1em 0.5em;
    border-radius: 10px;
    background-color: #FAFAFA;
    font-size: 0.9em;
    line-height: 1.35;
}

.tema-cultura-ciudadana { background-color: #FFE2A6; }
.tema-cultura { background-color: #D9D2E9; }
.tema-vivienda { background-color: #19A18D; color: #FFF; }
.tema-uso-del-suelo { background-color: #FFD7AE; }
.tema-movilidad { background-color: #DAE283; }
.tema-seguridad { background-color: #F4A8C5; }
.tema-ambiente { background-color: #8CBE23; color: #FFF; }
.tema-consumo-de-agua { background-color: #1361C4; color: #FFF; }
.tema-recreacion-y-deporte { background-color: #F6B26B; }
.tema-equipamientos { background-color: #A4C2F4; }

/* Vista de variables */
#geofocusVariablesApp {
    padding-bottom: 2rem;
}

.variables-header .badge {
    padding: 0.55rem 0.8rem;
    font-size: 0.85rem;
    font-weight: 500;
}

.variables-table-container {
    border: 1px solid #e4e7eb;
    border-radius: 0.6rem;
    box-shadow: 0 0.15rem 0.6rem rgba(24, 39, 75, 0.06);
}

.variables-table-container thead th {
    padding: 0.45rem 0.65rem;
    border-bottom-width: 1px;
    background: #f7f8fa;
    color: #5d6670;
    font-size: 0.78rem;
    font-weight: 700;
    letter-spacing: 0.03em;
    text-transform: uppercase;
    white-space: nowrap;
}

.variables-table-container tbody td {
    padding: 0.45rem 0.65rem;
}

.variable-name {
    color: #263238;
    font-weight: 600;
}

.variable-detail-modal .modal-header,
.variable-detail-modal .modal-footer {
    padding: 1.25rem 1.5rem;
}

.variable-detail-modal .modal-body {
    padding: 1.5rem;
}

.detail-section-title {
    margin-bottom: 0.75rem;
    color: #59636e;
    font-size: 0.8rem;
    font-weight: 700;
    letter-spacing: 0.04em;
    text-transform: uppercase;
}

.detail-description {
    color: #303840;
    line-height: 1.65;
    white-space: pre-line;
}

.detail-list dt {
    color: #6c757d;
    font-weight: 600;
}

.detail-list dd {
    margin-bottom: 0.75rem;
}

.stat-card {
    height: 100%;
    padding: 0.9rem 1rem;
    border: 1px solid #e4e7eb;
    border-radius: 0.5rem;
    background: #f8f9fa;
}

.stat-card span,
.stat-card strong {
    display: block;
}

.stat-card span {
    min-height: 2.4em;
    margin-bottom: 0.35rem;
    color: #6c757d;
    font-size: 0.78rem;
}

.stat-card strong {
    color: #263238;
    font-size: 1.05rem;
    overflow-wrap: anywhere;
}

@media (max-width: 767.98px) {
    .variables-table-container {
        border-radius: 0;
    }

    .variable-detail-modal .modal-header,
    .variable-detail-modal .modal-body,
    .variable-detail-modal .modal-footer {
        padding: 1rem;
    }
}


/* Vista de esquemas territoriales */
.geofocus-base-page {
    max-width: 1240px;
    margin: 0 auto;
    padding: 1.5rem 0 2.5rem;
}

.geofocus-base-layout {
    display: grid;
    grid-template-columns: minmax(240px, 290px) minmax(0, 1fr);
    gap: 1rem;
    align-items: start;
}

.geofocus-base-sidebar,
.geofocus-base-content {
    border: 1px solid #e4e7eb;
    border-radius: 0.7rem;
    background: #fff;
    box-shadow: 0 0.15rem 0.6rem rgba(24, 39, 75, 0.06);
}

.geofocus-base-sidebar {
    position: sticky;
    top: 60px;
    overflow: hidden;
}

.geofocus-base-sidebar-header,
.geofocus-base-content-header {
    padding: 1.25rem 1.35rem;
}

.geofocus-base-sidebar-header {
    border-bottom: 1px solid #edf0f3;
}

.geofocus-base-kicker {
    display: block;
    margin-bottom: 0.35rem;
    color: #6d59a4;
    font-size: 0.72rem;
    font-weight: 700;
    letter-spacing: 0.1em;
    text-transform: uppercase;
}

.geofocus-base-sidebar h2,
.geofocus-base-content h1,
.geofocus-base-content h2 {
    color: #252a32;
    font-weight: 700;
}

.geofocus-base-sidebar h2 {
    margin-bottom: 0.5rem;
    font-size: 1.15rem;
}

.geofocus-base-sidebar-header p,
.geofocus-base-content-header p {
    margin-bottom: 0;
    color: #707985;
    font-size: 0.86rem;
    line-height: 1.5;
}

.geofocus-base-options {
    padding: 0.55rem;
}

.geofocus-base-option {
    display: flex;
    width: 100%;
    align-items: center;
    gap: 0.7rem;
    padding: 0.75rem;
    border: 1px solid transparent;
    border-radius: 0.55rem;
    background: transparent;
    color: #343a40;
    text-align: left;
    transition: background-color 160ms ease, border-color 160ms ease, color 160ms ease;
}

.geofocus-base-option + .geofocus-base-option {
    margin-top: 0.25rem;
}

.geofocus-base-option:hover {
    background: #f7f5fb;
    color: #4e3c7e;
}

.geofocus-base-option.is-active {
    border-color: #d8cbed;
    background: #f1edf9;
    color: #4e3c7e;
}

.geofocus-base-option:focus-visible {
    outline: 3px solid rgba(114, 88, 168, 0.28);
    outline-offset: 1px;
}

.geofocus-base-option-icon {
    display: inline-flex;
    width: 36px;
    height: 36px;
    flex: 0 0 36px;
    align-items: center;
    justify-content: center;
    border-radius: 10px;
    background: #eeeaf7;
    color: #654a98;
}

.geofocus-base-option-copy {
    min-width: 0;
    flex: 1 1 auto;
}

.geofocus-base-option-copy strong,
.geofocus-base-option-copy small {
    display: block;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.geofocus-base-option-copy strong {
    font-size: 0.88rem;
    font-weight: 650;
}

.geofocus-base-option-copy small {
    margin-top: 0.15rem;
    color: #7a838e;
    font-size: 0.7rem;
}

.geofocus-base-option-arrow {
    color: #9aa2ac;
    font-size: 0.7rem;
}

.geofocus-base-option.is-active .geofocus-base-option-arrow {
    color: #654a98;
}

.geofocus-base-empty,
.geofocus-base-no-selection {
    color: #707985;
    text-align: center;
}

.geofocus-base-empty {
    margin: 0.75rem;
    font-size: 0.85rem;
}

.geofocus-base-content-header {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 1rem;
    border-bottom: 1px solid #edf0f3;
}

.geofocus-base-content-header h1 {
    margin-bottom: 0.45rem;
    font-size: clamp(1.35rem, 2.2vw, 1.8rem);
}

.geofocus-base-status {
    display: inline-flex;
    flex: 0 0 auto;
    align-items: center;
    gap: 0.35rem;
    padding: 0.35rem 0.65rem;
    border-radius: 50rem;
    background: #e7f5ee;
    color: #218653;
    font-size: 0.75rem;
    font-weight: 700;
}

.geofocus-base-summary {
    display: grid;
    grid-template-columns: minmax(120px, 0.8fr) minmax(200px, 1.4fr) minmax(150px, 1fr);
    gap: 0.7rem;
    padding: 1rem 1.35rem;
    border-bottom: 1px solid #edf0f3;
}

.geofocus-base-summary-item {
    min-width: 0;
    padding: 0.85rem 0.9rem;
    border-radius: 0.5rem;
    background: #f7f8fa;
}

.geofocus-base-summary-item-accent {
    background: #f1edf9;
}

.geofocus-base-summary-item span,
.geofocus-base-summary-item strong {
    display: block;
}

.geofocus-base-summary-item span {
    margin-bottom: 0.3rem;
    color: #707985;
    font-size: 0.75rem;
}

.geofocus-base-summary-item strong {
    overflow: hidden;
    color: #303840;
    font-size: 0.95rem;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.geofocus-base-summary-item-accent strong {
    color: #654a98;
    font-size: 1.2rem;
}

.geofocus-base-variables {
    padding: 1.25rem 1.35rem 1.35rem;
}

.geofocus-base-section-heading {
    display: flex;
    align-items: flex-end;
    justify-content: space-between;
    gap: 1rem;
    margin-bottom: 0.8rem;
}

.geofocus-base-section-heading h2 {
    margin-bottom: 0;
    font-size: 1.15rem;
}

.geofocus-base-count {
    padding: 0.35rem 0.65rem;
    border: 1px solid #e0e4ea;
    border-radius: 50rem;
    color: #69727e;
    font-size: 0.78rem;
    white-space: nowrap;
}

.geofocus-base-table-container {
    border: 1px solid #e4e7eb;
    border-radius: 0.55rem;
}

.geofocus-base-table-container thead th {
    padding: 0.55rem 0.65rem;
    border-bottom-width: 1px;
    background: #f7f8fa;
    color: #5d6670;
    font-size: 0.75rem;
    font-weight: 700;
    letter-spacing: 0.03em;
    text-transform: uppercase;
    white-space: nowrap;
}

.geofocus-base-table-container tbody td {
    padding: 0.65rem;
    color: #59636e;
    font-size: 0.85rem;
}

.geofocus-base-table-container code {
    color: #654a98;
    font-size: 0.78rem;
}

.geofocus-base-variable-name {
    color: #263238;
    font-weight: 600;
}

.geofocus-base-no-selection {
    min-height: 360px;
    padding: 3rem 1rem;
}

.geofocus-base-no-selection > i {
    margin-bottom: 1rem;
    color: #b9aecf;
    font-size: 2rem;
}

.geofocus-base-no-selection h1 {
    font-size: 1.35rem;
}

.geofocus-base-no-selection p {
    margin-bottom: 0;
}

@media (max-width: 991.98px) {
    .geofocus-base-layout {
        grid-template-columns: minmax(210px, 250px) minmax(0, 1fr);
    }

    .geofocus-base-summary {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }

    .geofocus-base-summary-item-wide {
        grid-column: span 2;
    }
}

@media (max-width: 767.98px) {
    .geofocus-base-page {
        padding-top: 1rem;
    }

    .geofocus-base-layout {
        grid-template-columns: 1fr;
    }

    .geofocus-base-sidebar {
        position: static;
    }

    .geofocus-base-content-header {
        display: block;
    }

    .geofocus-base-status {
        margin-top: 0.9rem;
    }

    .geofocus-base-summary,
    .geofocus-base-summary-item-wide {
        grid-template-columns: 1fr;
        grid-column: auto;
    }

    .geofocus-base-variables {
        padding: 1rem;
    }

    .geofocus-base-section-heading {
        align-items: flex-start;
        flex-direction: column;
    }

    .geofocus-base-table-container {
        border-radius: 0;
    }
}

/* Controles, carga y mapas de priorizacion */
.selector-tipo{
    font-size: 1.2em;
}

.text-off{
    color: #ADB3B8;
}

.range {
    width: 100%;
    height: 15px;
    -webkit-appearance: none;
    background: #FAFAFA;
    outline: none;
    border-radius: 15px;
    overflow: hidden;
    /*box-shadow: inset 0 0 2px rgba(0, 0, 0, 1);*/
    border: 1px solid #AAA;
}
.range::-webkit-slider-thumb {
    -webkit-appearance: none;
    width: 13px;
    height: 13px;
    border-radius: 50%;
    background: #5E4296;
    cursor: pointer;
    /*border: 2px solid #20124D;*/
    box-shadow: -407px 0 0 400px #D9D2E9;
}


.loader {
    --c:no-repeat linear-gradient(orange 0 0);
    background:
        var(--c),var(--c),var(--c),
        var(--c),var(--c),var(--c),
        var(--c),var(--c),var(--c);
    background-size: 16px 16px;
    animation:
        l32-1 1s infinite,
        l32-2 1s infinite;
    }
    @keyframes l32-1 {
    0%,100% {width:45px;height: 45px}
    35%,65% {width:65px;height: 65px}
    }
    @keyframes l32-2 {
    0%,40%  {background-position: 0 0,0 50%, 0 100%,50% 100%,100% 100%,100% 50%,100% 0,50% 0,  50% 50% }
    60%,100%{background-position: 0 50%, 0 100%,50% 100%,100% 100%,100% 50%,100% 0,50% 0,0 0,  50% 50% }
    }

/* Sectores de la ciudad */
/*-----------------------------------------------------------------------------*/

.sector {
    display: inline-block;
    font-size: 0.9em;
    background-color: #FAFAFA;
    padding: 0.1em 0.5em;
    border-radius: 10px;
}

.sector-1-nororiente { background-color: #E791BE; }
.sector-4-oriente { background-color: #FFE2A6; }
.sector-5-centro { background-color: #FF9AA2; }
.sector-6-sur-oriente { background-color: #B5EAD7; }
.sector-7-sur-occidente { background-color: #B5EAD7; }
.sector-3-occidente { background-color: #FFD5B8; }
.sector-2-noroccidente { background-color: #EAEBFF; }
.sector-8-rural { background-color: #BEC7B4; }
.sector-9-no-aplica { background-color: #FFE2A6; }
.sector-9-no-disponible { background-color: #FFE2A6; }


/* Mapas */
/*-----------------------------------------------------------------------------*/

#map-container {
    /*height: calc(100vh - 150px);*/
    min-width: 480px;
    max-width: 920px;
    width: 70%;
    margin: 0 auto;
    /*border: 1px solid red;*/
}

#map-info {
    min-width: 300px;
    width: 30%;
    padding: 10px;
}

#map-info h2 {
    font-size: 1.2em;
    margin-bottom: 0.5em;
    color: var(--color-main-app);
}

.hidden-map {
    height: 10px;         /* Establece la altura en 10px */
    overflow: hidden;     /* Oculta el contenido que se desborda */
    opacity: 0;           /* Hace que el div sea completamente transparente */
    pointer-events: none; /* Opcional: evita la interacción con el div */
}

/* Selección de variables para una priorización */
.geofocus-priority-variables {
    margin: 1rem 0 2rem;
    min-width: 0;
}

.geofocus-priority-heading {
    align-items: center;
}

.geofocus-priority-heading > .btn {
    flex-shrink: 0;
}

.geofocus-priority-count {
    color: #7a838e;
    font-size: 0.78rem;
    font-weight: 500;
    white-space: nowrap;
}

.geofocus-priority-help {
    margin: 0.45rem 0 0.8rem;
    color: #707985;
    font-size: 0.84rem;
}

.geofocus-priority-table thead th {
    padding: 0.55rem 0.65rem;
    background: #f7f8fa;
    color: #5d6670;
    font-size: 0.74rem;
    white-space: nowrap;
}

.geofocus-priority-table tbody td {
    padding: 0.6rem 0.65rem;
    color: #59636e;
    font-size: 0.84rem;
}

.geofocus-priority-table code {
    margin-top: 0.2rem;
    color: #654a98;
    font-size: 0.78rem;
    overflow-wrap: anywhere;
}

.geofocus-priority-name {
    min-width: 190px;
}

.geofocus-priority-name-button {
    padding: 0;
    border: 0;
    background: transparent;
    text-align: left;
    line-height: 1.5;
}

.geofocus-priority-color {
    display: inline-block;
    width: 1.15rem;
    height: 1.15rem;
    border: 2px solid #fff;
    border-radius: 50%;
    box-shadow: 0 0 0 1px #cbd2da;
    vertical-align: middle;
}

.geofocus-priority-types {
    display: inline-flex;
    gap: 0.2rem;
}

.geofocus-priority-type {
    width: 30px;
    height: 30px;
    padding: 0;
    border: 1px solid transparent;
    border-radius: 0.4rem;
    background: transparent;
}

.geofocus-priority-type[aria-pressed="true"] {
    border-color: #dce2e8;
    background: #f7f8fa;
}

.geofocus-priority-name-button:focus-visible,
.geofocus-priority-type:focus-visible,
.geofocus-priority-table .range:focus-visible {
    outline: 3px solid rgba(114, 88, 168, 0.5);
    outline-offset: 3px;
}

.geofocus-priority-weight {
    width: 200px;
    min-width: 150px;
}

.geofocus-priority-table .range {
    display: block;
    cursor: pointer;
}

.geofocus-priority-score {
    display: inline-block;
    min-width: 2.5rem;
    padding: 0.3rem 0.55rem;
    border-radius: 0.45rem;
    background: #f1edf9;
    color: #654a98;
    font-weight: 700;
    font-variant-numeric: tabular-nums;
}

@media (max-width: 1199.98px) {
    .geofocus-priority-table {
        overflow-x: auto;
        min-height: 280px;
    }
}

@media (max-width: 767.98px) {
    .geofocus-priority-heading {
        align-items: stretch;
    }
}
/* Aplicación de detalle de una priorización */
.geofocus-priority-page {
    padding-bottom: 2.5rem;
}

.geofocus-priority-shell {
    max-width: 1240px;
}

.geofocus-priority-navigation {
    margin: 1.25rem 0 1rem;
    padding: 0.4rem;
    overflow-x: auto;
    border: 1px solid #e4e7eb;
    border-radius: 0.7rem;
    background: #fff;
    box-shadow: 0 0.15rem 0.6rem rgba(24, 39, 75, 0.06);
}

.geofocus-priority-navigation .nav {
    flex-wrap: nowrap;
    gap: 0.25rem;
    min-width: max-content;
}

.geofocus-priority-navigation .nav-link {
    padding: 0.55rem 0.85rem;
    border-radius: 0.5rem;
    color: #59636e;
    font-size: 0.85rem;
    font-weight: 600;
}

.geofocus-priority-navigation .nav-link:hover {
    background: #f7f5fb;
    color: #4e3c7e;
}

.geofocus-priority-navigation .nav-link.active {
    background: #654a98;
    color: #fff;
}

.geofocus-priority-navigation .nav-link:focus-visible {
    outline: 3px solid rgba(114, 88, 168, 0.28);
    outline-offset: 1px;
}

.geofocus-priority-section {
    margin: 1rem 0 2rem;
    min-width: 0;
}

.geofocus-priority-section-header {
    align-items: center;
}

.geofocus-priority-actions {
    display: flex;
    flex-wrap: wrap;
    justify-content: flex-end;
    gap: 0.5rem;
}

.geofocus-priority-actions .btn {
    margin-right: 0 !important;
    white-space: nowrap;
}

.geofocus-priority-ai-icon {
    width: 20px;
    height: 20px;
    object-fit: contain;
}

.geofocus-priority-section-body {
    padding: 1.25rem 1.35rem 1.35rem;
}

.geofocus-priority-description {
    max-width: 900px;
    margin: 0 auto 1rem;
    padding: 1rem 1.1rem;
    border: 1px solid #e4def0;
    border-left: 4px solid #7258a8;
    border-radius: 0.55rem;
    background: #f8f6fb;
    color: #303840;
}

.geofocus-priority-description > strong {
    display: block;
    margin-bottom: 0.45rem;
    color: #654a98;
    font-size: 0.82rem;
}

.geofocus-priority-description p {
    margin-bottom: 0;
    line-height: 1.65;
}

.geofocus-priority-loading {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.75rem;
    min-height: 90px;
    color: #69727e;
    font-size: 0.88rem;
}

.geofocus-priority-loading .spinner-grow {
    width: 1.5rem;
    height: 1.5rem;
}

.geofocus-priority-results-table,
.geofocus-priority-table {
    border: 1px solid #e4e7eb;
    border-radius: 0.55rem;
}

.geofocus-priority-results-table thead th,
.geofocus-priority-table thead th {
    padding: 0.6rem 0.7rem;
    border-bottom-width: 1px;
    background: #f7f8fa;
    color: #5d6670;
    font-size: 0.74rem;
    font-weight: 700;
    letter-spacing: 0.03em;
    text-transform: uppercase;
    white-space: nowrap;
}

.geofocus-priority-results-table tbody td {
    padding: 0.65rem 0.7rem;
    color: #59636e;
    font-size: 0.84rem;
}

.geofocus-priority-results-table tbody td:first-child,
.geofocus-priority-results-table tbody td:last-child {
    font-variant-numeric: tabular-nums;
}

.geofocus-priority-map-copy h2 {
    margin-bottom: 0.65rem;
    color: #252a32;
    font-size: 1.2rem;
    font-weight: 700;
}

.geofocus-priority-map-copy > p {
    color: #59636e;
    font-size: 0.86rem;
    line-height: 1.6;
}

.geofocus-priority-map-details {
    margin: 0.75rem 0 1rem;
}

.geofocus-priority-map-details td {
    padding: 0.35rem 0;
    color: #59636e;
    font-size: 0.82rem;
    vertical-align: top;
}

.geofocus-priority-map-details .td-title {
    width: 48%;
    padding-right: 0.75rem;
    color: #707985;
    font-weight: 600;
}

.geofocus-priority-map-notes {
    padding-top: 0.9rem;
    border-top: 1px solid #edf0f3;
}

.geofocus-priority-map-page {
    margin: 1rem 0 0.5rem;
}

.geofocus-priority-map-card {
    overflow: hidden;
}

.geofocus-priority-map-controls {
    display: grid;
    grid-template-columns: auto minmax(150px, 0.75fr) minmax(280px, 1.6fr) minmax(190px, 0.9fr);
    gap: 0.55rem;
    align-items: end;
    padding: 0.55rem 0.85rem;
    border-bottom: 1px solid #edf0f3;
    background: #fbfcfd;
}

.geofocus-priority-map-controls > .btn {
    white-space: nowrap;
}

.geofocus-priority-map-controls .form-label {
    margin-bottom: 0.15rem;
    color: #68727e;
    font-size: 0.75rem;
    font-weight: 650;
}

.geofocus-priority-map-alert {
    margin: 0.75rem 0.85rem 0;
}

.geofocus-priority-map-layout {
    display: grid;
    grid-template-columns: minmax(0, 1fr) minmax(280px, 340px);
    min-height: max(560px, calc(100vh - 210px));
}

.geofocus-priority-map-canvas-wrap {
    position: relative;
    min-width: 0;
    min-height: max(560px, calc(100vh - 210px));
    background: #eef1f4;
}

.geofocus-priority-map-canvas,
.geofocus-priority-map-canvas .maplibregl-map {
    width: 100%;
    height: 100%;
    min-height: max(560px, calc(100vh - 210px));
}

.geofocus-priority-map-loading {
    position: absolute;
    z-index: 5;
    inset: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.55rem;
    background: rgba(255, 255, 255, 0.82);
    color: #654a98;
    font-size: 0.9rem;
    font-weight: 650;
}

.geofocus-priority-map-legend {
    position: absolute;
    z-index: 3;
    right: 1rem;
    bottom: 1rem;
    width: 200px;
    padding: 0.65rem 0.75rem;
    border: 1px solid rgba(213, 219, 225, 0.95);
    border-radius: 0.35rem;
    background: rgba(255, 255, 255, 0.94);
    color: #59636e;
    box-shadow: 0 0.2rem 0.75rem rgba(52, 58, 64, 0.14);
    font-size: 0.72rem;
}

.geofocus-priority-map-legend-title {
    margin-bottom: 0.35rem;
    color: #3f4650;
    font-weight: 650;
}

.geofocus-priority-map-legend-gradient {
    height: 0.65rem;
    border: 1px solid #cbd2da;
    border-radius: 0.2rem;
}

.geofocus-priority-map-legend-labels {
    display: flex;
    justify-content: space-between;
    margin-top: 0.2rem;
    color: #707985;
    font-size: 0.68rem;
}

.geofocus-priority-map-legend small {
    display: flex;
    align-items: center;
    gap: 0.3rem;
    margin-top: 0.35rem;
    color: #707985;
}

.geofocus-priority-map-no-data-swatch {
    display: inline-block;
    width: 0.65rem;
    height: 0.65rem;
    border: 1px solid #bdc5cd;
    border-radius: 0.15rem;
    background: #dfe4e8;
}

.geofocus-priority-map-info {
    overflow-y: auto;
    max-height: max(560px, calc(100vh - 210px));
    padding: 1rem;
    border-left: 1px solid #edf0f3;
    background: #fff;
}

.geofocus-priority-map-context {
    margin-bottom: 0.9rem;
    padding-bottom: 0.8rem;
    border-bottom: 1px solid #edf0f3;
}

.geofocus-priority-map-context h1 {
    margin: 0.15rem 0 0.45rem;
    color: #252a32;
    font-size: 1.08rem;
    line-height: 1.25;
}

.geofocus-priority-map-context p {
    margin: 0;
    color: #66717d;
    font-size: 0.8rem;
}

.geofocus-priority-map-context p strong,
.geofocus-priority-map-context p small {
    display: block;
}

.geofocus-priority-map-context p small {
    margin-top: 0.2rem;
    color: #7a838e;
}

.geofocus-priority-map-info .geofocus-priority-map-details {
    display: grid;
    grid-template-columns: minmax(90px, auto) minmax(0, 1fr);
    gap: 0.45rem 0.8rem;
    margin: 1rem 0 0;
    padding-top: 0.9rem;
    border-top: 1px solid #edf0f3;
    font-size: 0.8rem;
}

.geofocus-priority-map-info .geofocus-priority-map-details dt {
    color: #707985;
    font-weight: 650;
}

.geofocus-priority-map-info .geofocus-priority-map-details dd {
    margin: 0;
    color: #343a40;
    overflow-wrap: anywhere;
}

.geofocus-priority-modal {
    overflow: hidden;
    border: 0;
    border-radius: 0.7rem;
    box-shadow: 0 1rem 2.5rem rgba(24, 39, 75, 0.18);
}

.geofocus-priority-modal .modal-header,
.geofocus-priority-modal .modal-footer {
    padding: 1.1rem 1.35rem;
    border-color: #edf0f3;
}

.geofocus-priority-modal .modal-title {
    color: #252a32;
    font-weight: 700;
}

.geofocus-priority-modal .modal-body {
    padding: 1.35rem;
}

.geofocus-priority-modal-description {
    margin-bottom: 1rem;
    color: #303840;
    line-height: 1.65;
}

.geofocus-priority-modal-table {
    margin-bottom: 0;
}

.geofocus-priority-modal-table td {
    padding: 0.5rem 0.6rem;
    border-bottom: 1px solid #f0f2f4;
    color: #59636e;
    vertical-align: top;
}

.geofocus-priority-modal-table tr:last-child td {
    border-bottom: 0;
}

.geofocus-priority-modal-table .td-title {
    width: 34%;
    color: #707985;
    font-weight: 600;
}

@media (max-width: 991.98px) {
    .geofocus-priority-map-controls {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }

    .geofocus-priority-map-controls > .btn {
        justify-self: start;
    }

    .geofocus-priority-map-layout {
        grid-template-columns: 1fr;
        min-height: 0;
    }

    .geofocus-priority-map-info {
        max-height: none;
        border-top: 1px solid #edf0f3;
        border-left: 0;
    }

    .geofocus-priority-section-header {
        align-items: flex-start;
        flex-direction: column;
    }

    .geofocus-priority-actions {
        width: 100%;
        justify-content: flex-start;
    }

}

@media (max-width: 767.98px) {
    .geofocus-priority-map-controls {
        grid-template-columns: 1fr;
        padding: 0.65rem 0.75rem;
    }

    .geofocus-priority-map-canvas-wrap,
    .geofocus-priority-map-canvas,
    .geofocus-priority-map-canvas .maplibregl-map {
        min-height: 430px;
    }

    .geofocus-priority-page {
        padding-bottom: 1.5rem;
    }

    .geofocus-priority-navigation {
        margin-top: 1rem;
    }

    .geofocus-priority-section-body {
        padding: 1rem;
    }

    .geofocus-priority-actions .btn {
        flex: 1 1 auto;
    }

    .geofocus-priority-modal .modal-header,
    .geofocus-priority-modal .modal-body,
    .geofocus-priority-modal .modal-footer {
        padding: 1rem;
    }
}

/* Listado y formulario de priorizaciones */
.geofocus-prioritizations-page {
    padding: 1.25rem 0 2.5rem;
}

.geofocus-prioritizations-shell {
    max-width: 1240px;
}

.geofocus-prioritizations-loading {
    display: flex;
    min-height: 320px;
    align-items: center;
    justify-content: center;
    gap: 0.75rem;
    color: #69727e;
    font-size: 0.88rem;
}

.geofocus-prioritizations-loading .spinner-border {
    width: 1.75rem;
    height: 1.75rem;
}

.geofocus-prioritizations-list,
.geofocus-prioritizations-form {
    margin-bottom: 2rem;
}

.geofocus-prioritizations-header {
    align-items: center;
}

.geofocus-prioritizations-header .btn {
    flex: 0 0 auto;
}

.geofocus-prioritizations-body {
    padding: 1.25rem 1.35rem 1.35rem;
}

.geofocus-prioritizations-toolbar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1rem;
    margin-bottom: 1rem;
}

.geofocus-prioritizations-result-count {
    color: #7a838e;
    font-size: 0.78rem;
    white-space: nowrap;
}

.geofocus-prioritizations-result-count strong {
    color: #654a98;
    font-weight: 700;
}

.geofocus-prioritizations-search {
    position: relative;
    width: min(100%, 400px);
}

.geofocus-prioritizations-search-icon {
    position: absolute;
    top: 50%;
    left: 0.85rem;
    z-index: 1;
    color: #8a939e;
    font-size: 0.82rem;
    pointer-events: none;
    transform: translateY(-50%);
}

.geofocus-prioritizations-search-input {
    width: 100%;
    height: 40px;
    padding: 0.5rem 2.4rem 0.5rem 2.25rem;
    border: 1px solid #e0e4e9;
    border-radius: 0.45rem;
    background: #f8f9fa;
    color: #303840;
    font-size: 0.86rem;
    transition: background-color 160ms ease, border-color 160ms ease, box-shadow 160ms ease;
}

.geofocus-prioritizations-search-input::placeholder {
    color: #929aa4;
}

.geofocus-prioritizations-search-input::-webkit-search-cancel-button {
    display: none;
}

.geofocus-prioritizations-search-input:focus {
    border-color: #7258a8;
    background: #fff;
    outline: 0;
    box-shadow: 0 0 0 2px rgba(114, 88, 168, 0.12);
}

.geofocus-prioritizations-search-clear {
    position: absolute;
    top: 50%;
    right: 0.4rem;
    display: inline-flex;
    width: 28px;
    height: 28px;
    align-items: center;
    justify-content: center;
    padding: 0;
    border: 0;
    border-radius: 50%;
    background: transparent;
    color: #7a838e;
    font-size: 0.75rem;
    transform: translateY(-50%);
}

.geofocus-prioritizations-search-clear:hover {
    background: #f1edf9;
    color: #654a98;
}

.geofocus-prioritizations-search-clear:focus-visible {
    outline: 3px solid rgba(114, 88, 168, 0.28);
}

.geofocus-prioritizations-results {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 0.75rem;
}

.geofocus-prioritization-card {
    display: grid;
    grid-template-columns: auto minmax(0, 1fr) auto;
    gap: 0.8rem;
    align-items: start;
    min-width: 0;
    padding: 1rem;
    border: 1px solid #e4e7eb;
    border-radius: 0.6rem;
    background: #fff;
    transition: border-color 160ms ease, box-shadow 160ms ease, transform 160ms ease;
}

.geofocus-prioritization-card:hover {
    border-color: #d8cbed;
    box-shadow: 0 0.35rem 1rem rgba(24, 39, 75, 0.08);
    transform: translateY(-1px);
}

.geofocus-prioritization-id .badge {
    min-width: 2rem;
    padding: 0.4rem 0.5rem;
    background: #eeeaf7 !important;
    color: #654a98;
    font-variant-numeric: tabular-nums;
}

.geofocus-prioritization-copy {
    min-width: 0;
}

.geofocus-prioritization-copy h2 {
    margin-bottom: 0.35rem;
    font-size: 1rem;
    line-height: 1.35;
}

.geofocus-prioritization-copy h2 a {
    color: #303840;
    text-decoration: none;
}

.geofocus-prioritization-copy h2 a:hover {
    color: #654a98;
    text-decoration: underline;
    text-underline-offset: 0.18em;
}

.geofocus-prioritization-copy p {
    display: -webkit-box;
    margin-bottom: 0.55rem;
    overflow: hidden;
    color: #59636e;
    font-size: 0.84rem;
    line-height: 1.55;
    -webkit-box-orient: vertical;
    -webkit-line-clamp: 3;
}

.geofocus-prioritization-copy small {
    display: block;
    color: #7a838e;
    font-size: 0.75rem;
}

.geofocus-prioritization-meta {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    gap: 0.4rem 0.75rem;
}

.geofocus-prioritization-creator {
    color: #654a98;
    font-weight: 700;
}

.geofocus-prioritization-layer {
    display: inline-flex !important;
    max-width: 100%;
    align-items: center;
    padding: 0.22rem 0.5rem;
    border-radius: 50rem;
    background: #f1edf9;
    color: #654a98 !important;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.geofocus-prioritization-actions {
    display: flex;
    gap: 0.25rem;
}

.geofocus-prioritizations-empty {
    grid-column: 1 / -1;
    display: flex;
    min-height: 220px;
    align-items: center;
    justify-content: center;
    flex-direction: column;
    gap: 0.4rem;
    padding: 2rem 1rem;
    border: 1px dashed #dce1e7;
    border-radius: 0.6rem;
    color: #707985;
    text-align: center;
}

.geofocus-prioritizations-empty > i {
    margin-bottom: 0.35rem;
    color: #b9aecf;
    font-size: 1.8rem;
}

.geofocus-prioritizations-empty strong {
    color: #303840;
}

.geofocus-prioritizations-empty span {
    font-size: 0.84rem;
}

.geofocus-prioritizations-form {
    max-width: 920px;
    margin-right: auto;
    margin-left: auto;
}

.geofocus-prioritizations-form .card {
    overflow: hidden;
}

.geofocus-prioritizations-form-body {
    padding: 1.5rem;
}

.geofocus-prioritizations-form-body form {
    max-width: 760px;
    margin: 0 auto;
}

.geofocus-prioritizations-form-body .col-form-label {
    color: #59636e;
    font-size: 0.86rem;
    font-weight: 600;
}

.geofocus-prioritizations-form-actions {
    display: flex;
    gap: 0.5rem;
}

.geofocus-prioritizations-form-actions .btn {
    min-width: 120px;
}

@media (max-width: 991.98px) {
    .geofocus-prioritizations-results {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 767.98px) {
    .geofocus-prioritizations-page {
        padding-top: 1rem;
    }

    .geofocus-prioritizations-header {
        align-items: stretch;
    }

    .geofocus-prioritizations-header .btn {
        width: 100%;
        margin-top: 0.9rem;
    }

    .geofocus-prioritizations-body,
    .geofocus-prioritizations-form-body {
        padding: 1rem;
    }

    .geofocus-prioritizations-toolbar {
        align-items: stretch;
        flex-direction: column;
    }

    .geofocus-prioritizations-search {
        width: 100%;
    }

    .geofocus-prioritizations-result-count {
        align-self: flex-start;
    }

    .geofocus-prioritization-card {
        grid-template-columns: auto minmax(0, 1fr);
    }

    .geofocus-prioritization-actions {
        grid-column: 2;
    }

    .geofocus-prioritizations-form-body .col-form-label {
        text-align: left !important;
    }

    .geofocus-prioritizations-form-actions .btn {
        flex: 1 1 0;
        min-width: 0;
    }
}

</style>
