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
.tema-recreacion-y-deporte { background-color: #e22b49; color: #FFF; }
.tema-equipamientos { background-color: #772099; color: #FFF; }

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

/* 2026-10-12: contenido de Variables en proporción 30/70.
   En solo lectura, la tabla utiliza todo el ancho al no estar disponible el formulario de IA. */
.geofocus-priority-variables-layout {
    display: grid;
    grid-template-columns: minmax(0, 1fr);
    align-items: start;
    gap: 1.5rem;
    padding: 1.25rem 1.35rem 1.35rem;
}

.geofocus-priority-variables-layout.has-assistant {
    grid-template-columns: minmax(0, 3fr) minmax(0, 7fr);
}

.geofocus-priority-variables-layout > * { min-width: 0; }
.geofocus-priority-variables-layout > .geofocus-base-variables { padding: 0; }

.geofocus-priority-assistant {
    padding: 0;
}

.geofocus-priority-assistant h2 {
    margin-bottom: 0.35rem;
    font-size: 1rem;
}

.geofocus-priority-assistant-input {
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    gap: 0.75rem;
}

.geofocus-priority-assistant-input textarea {
    min-height: 160px;
    resize: vertical;
    font-size: 0.9rem;
    line-height: 1.5;
    background-color: #f8f6fc;
}

.geofocus-priority-assistant-input textarea:focus {
    border-color: #a28bc6;
    box-shadow: 0 0 0 0.2rem rgba(114, 88, 168, 0.12);
}

.geofocus-priority-assistant-input .btn { white-space: nowrap; }

/* 2026-10-12: icono de ejemplo compacto a la izquierda y selección con IA a la derecha. */
.geofocus-priority-assistant-buttons {
    display: flex;
    flex-wrap: wrap;
    gap: 0.75rem;
    align-items: center;
    width: 100%;
}

.geofocus-priority-assistant-buttons > button[type="submit"] { margin-left: auto; }
.geofocus-priority-assistant-buttons .geofocus-priority-example {
    display: inline-flex;
    width: 34px;
    height: 34px;
    flex: 0 0 34px;
    align-items: center;
    justify-content: center;
    padding: 0;
    font-size: 0.85rem;
}

.geofocus-priority-assistant-info .geofocus-priority-help { margin-top: 0; }
.geofocus-priority-assistant-info { margin-top: 1rem; }

.geofocus-priority-assistant-feedback {
    margin-top: 0.85rem;
    color: #59636e;
    font-size: 0.84rem;
    line-height: 1.5;
    overflow-wrap: anywhere;
}

.geofocus-priority-assistant-feedback p { margin: 0; }
.geofocus-priority-assistant-feedback p + p { margin-top: 0.35rem; }

@media (max-width: 991.98px) {
    .geofocus-priority-variables-layout.has-assistant { grid-template-columns: minmax(0, 1fr); }
}

@media (max-width: 767.98px) {
    .geofocus-priority-variables-layout { padding: 1rem; }
}

/* Selección de variables para una priorización */
.geofocus-priority-variables {
    margin: 1rem 0 2rem;
    min-width: 0;
}

/* 2026-10-12: filtros a la izquierda y acción principal a la derecha, sin encabezado adicional. */
.geofocus-priority-table-toolbar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 0.75rem 1rem;
}

.geofocus-priority-table-filters {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 0.5rem;
    min-width: 0;
}

.geofocus-priority-table-filters .btn { white-space: nowrap; }
.geofocus-priority-table-filters .form-select { width: 190px; max-width: 100%; }
.geofocus-priority-table-toolbar > .geofocus-priority-calculate { margin-left: auto; flex-shrink: 0; }

/* 2026-10-11: jerarquía principal para Calcular; la selección con IA conserva un botón secundario. */
.geofocus-priority-calculate {
    --bs-btn-bg: #654a98;
    --bs-btn-border-color: #654a98;
    --bs-btn-hover-bg: #513a80;
    --bs-btn-hover-border-color: #513a80;
    --bs-btn-active-bg: #46326e;
    --bs-btn-active-border-color: #46326e;
    --bs-btn-disabled-bg: #654a98;
    --bs-btn-disabled-border-color: #654a98;
    --bs-btn-focus-shadow-rgb: 101, 74, 152;
    min-width: 180px;
    min-height: 54px;
    padding: 0.85rem 1.75rem;
    font-size: 1.1rem;
    font-weight: 700;
}

.geofocus-priority-calculate:focus-visible {
    outline: 3px solid rgba(101, 74, 152, 0.4);
    outline-offset: 3px;
}

.geofocus-priority-count {
    margin: 0.65rem 0 0.75rem;
    color: #707985;
    font-size: 0.78rem;
    font-weight: 400;
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

.geofocus-priority-name-cell {
    cursor: pointer;
    line-height: 1.3;
    transition: background-color 140ms ease, box-shadow 140ms ease;
}

.geofocus-priority-name-cell:hover {
    background: #f7f5fb;
}

/* 2026-10-12: el color identifica la fila mediante un borde izquierdo de 3px. */
.geofocus-priority-table tbody tr[data-variable-id] > td:first-child {
    border-left: 3px solid var(--variable-color, #0084bf);
}

.geofocus-priority-types {
    display: inline-flex;
    gap: 0.2rem;
}

.geofocus-priority-type {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 40px;
    height: 34px;
    padding: 0;
    border: 1px solid transparent;
    border-radius: 0.65rem;
    line-height: 1;
    transition: background-color 180ms ease, border-color 180ms ease, color 180ms ease, box-shadow 180ms ease, transform 180ms ease;
}

/* 2026-10-12: azul para valores altos, amarillo para bajos; giro de 180° sin sustituir el icono. */
.geofocus-priority-type.is-direct { background: #eaf6fc; border-color: #b4dff2; color: #0084bf; }
.geofocus-priority-type.is-inverse { background: #fff6d9; border-color: #efcd6e; color: #a87500; }
.geofocus-priority-direction-arrow { font-size: 1rem; transform: rotate(0deg); transition: transform 280ms cubic-bezier(0.22, 0.61, 0.36, 1); }
.geofocus-priority-type.is-inverse .geofocus-priority-direction-arrow { transform: rotate(180deg); }
.geofocus-priority-type:not(:disabled):hover { box-shadow: 0 2px 6px rgba(32, 49, 67, 0.12); transform: translateY(-1px); }
.geofocus-priority-type.is-direct:not(:disabled):hover { background: #d9effa; border-color: #0084bf; }
.geofocus-priority-type.is-inverse:not(:disabled):hover { background: #ffedb3; border-color: #d5aa36; }
.geofocus-priority-type:not(:disabled):active { transform: scale(0.94); box-shadow: none; }
.geofocus-priority-type:disabled { opacity: 0.5; cursor: not-allowed; }

@media (prefers-reduced-motion: reduce) {
    .geofocus-priority-type,
    .geofocus-priority-direction-arrow { transition: none; }
    .geofocus-priority-type:not(:disabled):hover,
    .geofocus-priority-type:not(:disabled):active { transform: none; }
}

.geofocus-priority-name-cell:focus-visible,
.geofocus-priority-type:focus-visible,
.geofocus-priority-table .range:focus-visible {
    outline: 3px solid rgba(114, 88, 168, 0.5);
    outline-offset: 3px;
}

.geofocus-priority-weight {
    width: clamp(180px, 18vw, 320px);
    min-width: 180px;
}

.geofocus-priority-col-select { width: 44px; }
.geofocus-priority-col-name { width: 32%; }
.geofocus-priority-col-theme { width: 16%; }
.geofocus-priority-col-type { width: 84px; }
.geofocus-priority-col-weight { width: clamp(180px, 18vw, 320px); }
.geofocus-priority-col-score { width: 86px; }
.geofocus-priority-col-actions { width: 52px; }

.geofocus-priority-table table {
    min-width: 1050px;
    table-layout: fixed;
    border-collapse: separate;
    border-spacing: 0;
}

/* 2026-10-12: columnas compactas para la tabla en el 70%; el desplazamiento queda dentro de la tabla. */
.geofocus-priority-variables-layout .geofocus-priority-table { position: relative; }
.geofocus-priority-variables-layout .geofocus-priority-table table { min-width: 900px; }
.geofocus-priority-variables-layout .geofocus-priority-col-select { width: 36px; }
.geofocus-priority-variables-layout .geofocus-priority-col-type { width: 70px; }
.geofocus-priority-variables-layout .geofocus-priority-col-score { width: 70px; }
.geofocus-priority-variables-layout .geofocus-priority-col-actions { width: 44px; }
.geofocus-priority-variables-layout .geofocus-priority-col-weight,
.geofocus-priority-variables-layout .geofocus-priority-weight {
    width: clamp(140px, 12vw, 200px);
    min-width: 140px;
}

.geofocus-priority-table .range {
    display: block;
    cursor: pointer;
}

.geofocus-priority-score {
    display: inline-block;
    min-width: 2.5rem;
    padding: 0.22rem 0.45rem;
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
    .geofocus-priority-calculate { width: 100%; }
    .geofocus-priority-table-filters { width: 100%; }
    .geofocus-priority-table-filters .form-select { flex: 1 1 140px; width: auto; }
}
/* 2026-10-11: detalle de priorización sin marcos, sombras ni separadores decorativos.
   Estas reglas se limitan a esta pantalla para conservar las otras vistas de GeoFocus. */
.geofocus-priority-page {
    padding-bottom: 2.5rem;
}

.geofocus-priority-page .geofocus-base-content {
    border: 0;
    border-radius: 0;
    box-shadow: none;
}

.geofocus-priority-page .geofocus-base-content-header {
    border-bottom: 0;
    padding-bottom: 0.5rem;
}

.geofocus-priority-page .geofocus-base-count {
    padding: 0;
    border: 0;
    border-radius: 0;
}

.geofocus-priority-shell {
    width: 100%;
    max-width: 1920px;
    padding-right: clamp(1rem, 2vw, 2rem);
    padding-left: clamp(1rem, 2vw, 2rem);
}

.geofocus-priority-navigation {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1rem;
    margin: 1.25rem 0 1rem;
    padding: 0;
    overflow: hidden;
}

.geofocus-priority-navigation .nav {
    flex-wrap: nowrap;
    flex: 0 1 auto;
    gap: 0.25rem;
    min-width: 0;
    overflow-x: auto;
}

.geofocus-priority-navigation-context {
    min-width: 0;
    max-width: 48%;
    margin-left: auto;
    padding: 0.15rem 0.65rem;
    text-align: right;
}

.geofocus-priority-navigation-context strong,
.geofocus-priority-navigation-context small {
    display: block;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.geofocus-priority-navigation-context strong {
    color: #343a40;
    font-size: 0.82rem;
    font-weight: 700;
}

.geofocus-priority-navigation-context small {
    margin-top: 0.1rem;
    color: #7a838e;
    font-size: 0.7rem;
}
.geofocus-priority-navigation .nav-link {
    white-space: nowrap;
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

/* 2026-10-12: mantener la pestaña del mapa visible durante la animación y el cálculo. */
.geofocus-priority-navigation .nav-link[aria-disabled="true"] { opacity: 0.6; cursor: wait; }

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

/* 2026-10-12: Resultados como artículo, con jerarquía tipográfica y acentos de color discretos. */
.geofocus-results-article {
    max-width: 1140px;
    margin: 1rem auto 2rem;
    padding: clamp(1.25rem, 3vw, 3rem);
}

.geofocus-results-header {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 1.5rem;
}

.geofocus-results-header > div:first-child { flex: 1; min-width: 0; }
.geofocus-results-header .geofocus-priority-actions { flex-shrink: 0; }
.geofocus-results-header h1 {
    max-width: 760px;
    margin: 0.75rem 0;
    font-size: clamp(1.6rem, 2.4vw, 2.4rem);
    font-weight: 700;
    letter-spacing: -0.035em;
    line-height: 1.2;
    overflow-wrap: anywhere;
}
.geofocus-results-intro { max-width: 760px; margin: 0; color: #657080; line-height: 1.75; }
.geofocus-results-summary { display: flex; flex-wrap: wrap; gap: 1rem 2.5rem; margin: 2rem 0 0; }
.geofocus-results-summary dt,
.geofocus-results-criterion-settings dt { color: #7a8290; font-size: 0.73rem; font-weight: 500; }
.geofocus-results-summary dd { margin: 0.3rem 0 0; color: #4e3c7e; font-size: 0.9rem; font-weight: 600; }
.geofocus-results-section { margin-top: 2.75rem; }
.geofocus-results-section-heading { display: flex; align-items: baseline; gap: 0.8rem; margin-bottom: 0.6rem; }
.geofocus-results-section-heading > span { color: #aa0066; font-size: 0.75rem; font-weight: 600; }
.geofocus-results-section-heading h2 { margin: 0; color: #29313d; font-size: 1.2rem; font-weight: 650; letter-spacing: -0.02em; }
.geofocus-results-note { margin-bottom: 1.25rem; color: #78808d; font-size: 0.84rem; line-height: 1.6; }
.geofocus-results-criteria { padding: 0; margin: 0; list-style: none; }
.geofocus-results-criterion {
    display: grid;
    grid-template-columns: minmax(0, 1fr) 210px;
    gap: 1rem 2rem;
    padding: 0.15rem 0 0.15rem 1rem;
    border-left: 3px solid #654a98;
}
.geofocus-results-criterion + .geofocus-results-criterion { margin-top: 1.65rem; }
.geofocus-results-variable-theme { margin-bottom: 0.35rem; color: #654a98; font-size: 0.73rem; }
.geofocus-results-criterion h3 { margin: 0 0 0.4rem; color: #303947; font-size: 1rem; font-weight: 600; line-height: 1.5; }
.geofocus-results-criterion-copy { min-width: 0; overflow-wrap: anywhere; }
.geofocus-results-criterion-copy > p { margin: 0 0 0.6rem; color: #657080; font-size: 0.88rem; line-height: 1.65; }
.geofocus-results-variable-data { display: flex; flex-wrap: wrap; gap: 0.3rem 1rem; margin: 0; color: #76808c; font-size: 0.75rem; }
.geofocus-results-variable-data > div { display: flex; gap: 0.35rem; }
.geofocus-results-variable-data dt { font-weight: 500; }
.geofocus-results-variable-data dd { margin: 0; color: #515e6c; }
.geofocus-results-criterion-settings { display: flex; flex-direction: column; gap: 0.6rem; margin: 0; }
.geofocus-results-criterion-settings dd { margin: 0.15rem 0 0; color: #4e3c7e; font-size: 0.82rem; }
.geofocus-results-criterion-settings .geofocus-results-weight { color: #aa0066; font-size: 1.35rem; font-weight: 600; font-variant-numeric: tabular-nums; }
.geofocus-results-weight small { color: #7a8290; font-size: 0.73rem; font-weight: 400; }
.geofocus-results-analysis { max-width: 880px; }
.geofocus-results-empty { margin: 2rem 0 0; padding: 1rem 1.2rem; background: #f7f5fa; color: #65587b; font-size: 0.9rem; line-height: 1.7; }
.geofocus-results-article .geofocus-priority-results-table tbody th { color: #344152; font-size: 0.85rem; font-weight: 500; padding: 0.8rem 0.7rem; }
.geofocus-results-article .geofocus-priority-results-table tbody td { padding: 0.8rem 0.7rem; }
.geofocus-results-rank { display: inline-flex; min-width: 1.8rem; min-height: 1.8rem; align-items: center; justify-content: center; border-radius: 50%; background: #f3eef8; color: #654a98; font-size: 0.75rem; }
.geofocus-results-article .geofocus-priority-results-table .geofocus-results-score { color: #aa0066; font-weight: 600; }

@media (max-width: 991.98px) {
    .geofocus-results-header { flex-direction: column; }
    .geofocus-results-header .geofocus-priority-actions { width: auto; }
}
@media (max-width: 575.98px) {
    .geofocus-results-criterion { grid-template-columns: minmax(0, 1fr); }
    .geofocus-results-criterion-settings { flex-direction: row; flex-wrap: wrap; gap: 0.75rem 2rem; }
    .geofocus-results-section { margin-top: 2rem; }
    .geofocus-results-summary { gap: 1rem 1.5rem; }
}

.geofocus-priority-markdown {
    color: #303840;
    font-size: 0.86rem;
    line-height: 1.6;
    overflow-wrap: anywhere;
}

.geofocus-priority-markdown > :first-child {
    margin-top: 0;
}

.geofocus-priority-markdown > :last-child {
    margin-bottom: 0;
}

.geofocus-priority-markdown h1,
.geofocus-priority-markdown h2,
.geofocus-priority-markdown h3,
.geofocus-priority-markdown h4 {
    margin: 1rem 0 0.45rem;
    color: #3f315f;
    font-weight: 700;
    line-height: 1.3;
}

.geofocus-priority-markdown h1 { font-size: 1.2rem; }
.geofocus-priority-markdown h2 { font-size: 1.08rem; }
.geofocus-priority-markdown h3,
.geofocus-priority-markdown h4 { font-size: 0.96rem; }

.geofocus-priority-markdown p,
.geofocus-priority-markdown ul,
.geofocus-priority-markdown ol,
.geofocus-priority-markdown blockquote,
.geofocus-priority-markdown pre,
.geofocus-priority-markdown table {
    margin-bottom: 0.75rem;
}

.geofocus-priority-markdown ul,
.geofocus-priority-markdown ol {
    padding-left: 1.35rem;
}

.geofocus-priority-markdown li + li {
    margin-top: 0.2rem;
}

.geofocus-priority-markdown blockquote {
    padding: 0.65rem 0.8rem;
    color: #59636e;
}

.geofocus-priority-markdown hr { display: none; }

.geofocus-priority-markdown code {
    padding: 0.1rem 0.3rem;
    border-radius: 0.25rem;
    background: #ece8f3;
    color: #4e3c7e;
}

.geofocus-priority-markdown pre {
    overflow-x: auto;
    padding: 0.75rem;
    border-radius: 0.4rem;
    background: #2f2938;
    color: #f8f9fa;
}

.geofocus-priority-markdown pre code {
    padding: 0;
    background: transparent;
    color: inherit;
}

.geofocus-priority-markdown table {
    display: block;
    width: 100%;
    overflow-x: auto;
    border-collapse: collapse;
}

.geofocus-priority-markdown th,
.geofocus-priority-markdown td {
    padding: 0.4rem 0.5rem;
    border: 1px solid #dfe3e8;
    text-align: left;
    vertical-align: top;
}

.geofocus-priority-markdown a {
    overflow-wrap: anywhere;
}

/* EFECTO ANIMATE.CSS: estado de espera previo a la aparición del contenido. */
.geofocus-priority-description-loading {
    display: flex;
    min-height: 90px;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    color: #654a98;
    font-size: 0.84rem;
    font-weight: 650;
}

@media (prefers-reduced-motion: reduce) {
    .geofocus-priority-markdown.animate__animated {
        animation: none !important;
    }
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
    border: 0;
    border-radius: 0;
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

.geofocus-priority-table thead th {
    padding: 0.38rem 0.5rem;
}

.geofocus-priority-table tbody td {
    padding: 0.34rem 0.5rem;
    line-height: 1.25;
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
}

.geofocus-priority-map-page {
    margin: 1rem 0 0.5rem;
}

.geofocus-priority-map-card {
    overflow: hidden;
}

.geofocus-priority-map-controls {
    display: grid;
    grid-template-columns: max-content minmax(150px, 200px) minmax(280px, 1fr) minmax(150px, 190px) minmax(190px, 250px);
    gap: 0.55rem;
    align-items: end;
    padding: 0.55rem 0.85rem;
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
    grid-template-columns: minmax(0, 1fr) clamp(300px, 22vw, 400px);
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

/* 2026-10-12: etiqueta del cálculo y del revelado de puntajes, sin cubrir los polígonos. */
.geofocus-priority-calculation-status {
    position: absolute;
    z-index: 6;
    top: 0.75rem;
    left: 0.75rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    max-width: calc(100% - 4.5rem);
    padding: 0.45rem 0.6rem;
    border-radius: 0.35rem;
    background: rgba(255, 255, 255, 0.9);
    color: #707985;
    font-size: 0.8rem;
    font-weight: 500;
    pointer-events: none;
}

/* 2026-10-12: loader de tres barras compacto y discreto, adaptado de CSS Loaders al violeta de GeoFocus. */
.geofocus-priority-process-loader {
    width: 22px;
    flex: 0 0 22px;
    aspect-ratio: 1;
    color: #654a98;
    opacity: 0.8;
    --c: no-repeat linear-gradient(currentColor 0 0);
    background:
        var(--c) 0% 50%,
        var(--c) 50% 50%,
        var(--c) 100% 50%;
    background-size: 20% 100%;
    animation: geofocus-map-bars 1s infinite linear;
}

@keyframes geofocus-map-bars {
    0%, 100% { background-size: 20% 100%, 20% 100%, 20% 100%; }
    33% { background-size: 20% 10%, 20% 100%, 20% 100%; }
    50% { background-size: 20% 100%, 20% 10%, 20% 100%; }
    66% { background-size: 20% 100%, 20% 100%, 20% 10%; }
}

@media (prefers-reduced-motion: reduce) {
    .geofocus-priority-process-loader { animation: none; }
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

.geofocus-priority-map-top-summary {
    color: #654a98 !important;
    font-weight: 650;
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
    background: #fff;
}

.geofocus-priority-map-context {
    margin-bottom: 0.9rem;
    padding-bottom: 0.8rem;
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
    border: 0;
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
    .geofocus-priority-navigation-context {
        display: none;
    }

    .geofocus-priority-navigation .nav {
        width: 100%;
    }

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
