<style>
.variables-layer-page {
    width: 100%;
    max-width: none;
    margin-right: 0;
    margin-left: 0;
}

.variables-layer-page .geofocus-base-layout {
    grid-template-columns: minmax(280px, 330px) minmax(0, 1fr);
}

.variables-layer-option-count {
    display: inline-flex;
    min-width: 1.8rem;
    height: 1.8rem;
    flex: 0 0 auto;
    align-items: center;
    justify-content: center;
    border: 1px solid #e0e4ea;
    border-radius: 50%;
    background: #fff;
    color: #69727e;
    font-size: 0.72rem;
    font-weight: 700;
}

.variables-color-dot {
    display: inline-block;
    width: 1.15rem;
    height: 1.15rem;
    border: 2px solid #fff;
    border-radius: 50%;
    box-shadow: 0 0 0 1px #cbd2da;
    vertical-align: middle;
}
.geofocus-base-option.is-active .variables-layer-option-count {
    border-color: #cfc0e7;
    color: #654a98;
}

.variables-layer-count {
    color: #7a838e;
    font-size: 0.78rem;
    font-weight: 500;
    white-space: nowrap;
}

.variables-layer-heading {
    align-items: center;
    justify-content: flex-end;
}

.variables-layer-title {
    min-width: 0;
}

.variables-layer-title h1 {
    margin-bottom: 0.2rem;
    font-size: clamp(1.05rem, 1.6vw, 1.35rem);
}

.variables-layer-title small {
    display: block;
    color: #7a838e;
    font-size: 0.75rem;
}

.variables-layer-header-actions {
    display: flex;
    flex: 0 0 auto;
    align-items: center;
    justify-content: flex-end;
    gap: 0.55rem;
}

.variables-layer-heading-actions {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    justify-content: flex-end;
    gap: 0.65rem;
}

.variables-layer-heading-title {
    margin-left: auto;
    text-align: right;
}

.variables-layer-toolbar {
    display: flex;
    align-items: flex-end;
    gap: 0.65rem;
    margin-bottom: 0;
}

.variables-layer-toolbar > div {
    width: min(100%, 270px);
}

.variables-layer-toolbar .form-label {
    margin-bottom: 0.3rem;
    color: #707985;
    font-size: 0.78rem;
    font-weight: 600;
}

.variables-layer-table {
    overflow-x: auto;
}

.variables-layer-table thead th {
    padding: 0.55rem 0.65rem;
    font-size: 0.74rem;
}

.variables-layer-table tbody td {
    padding: 0.6rem 0.65rem;
    color: #59636e;
    font-size: 0.84rem;
}

.variables-layer-table code {
    color: #654a98;
    font-size: 0.78rem;
}

.variables-layer-active-status {
    color: #218653;
    font-size: 0.95rem;
}

.variables-layer-empty {
    padding: 2.5rem 1rem !important;
    text-align: center;
}

.variables-layer-empty-icon {
    display: inline-flex;
    width: 42px;
    height: 42px;
    align-items: center;
    justify-content: center;
    margin-bottom: 0.65rem;
    border-radius: 12px;
    background: #eeeaf7;
    color: #654a98;
}

.variables-layer-empty strong,
.variables-layer-empty small {
    display: block;
}

.variables-layer-empty strong {
    color: #3f4650;
    font-size: 0.92rem;
}

.variables-layer-empty small {
    max-width: 430px;
    margin: 0.3rem auto 0;
    color: #7a838e;
    font-size: 0.8rem;
}

/* Mapa de variables */
.variables-map-page {
    width: 100%;
    padding-bottom: 0.5rem;
}

.variables-map-card {
    overflow: hidden;
}

.variables-map-controls {
    display: grid;
    grid-template-columns: auto minmax(170px, 0.9fr) minmax(150px, 0.75fr) minmax(260px, 1.6fr) minmax(180px, 0.9fr);
    gap: 0.55rem;
    align-items: end;
    padding: 0.55rem 0.85rem;
    border-bottom: 1px solid #edf0f3;
    background: #fbfcfd;
}

.variables-map-back-control {
    align-self: end;
}

.variables-map-back-control .btn {
    white-space: nowrap;
}

.variables-map-controls .form-label {
    margin-bottom: 0.15rem;
    color: #68727e;
    font-size: 0.75rem;
    font-weight: 650;
}

.variables-map-alert {
    margin: 0.9rem 1.35rem 0;
}

.variables-map-layout {
    display: grid;
    grid-template-columns: minmax(0, 1fr) minmax(260px, 330px);
    min-height: max(560px, calc(100vh - 170px));
}

.variables-map-canvas-wrap {
    position: relative;
    min-width: 0;
    min-height: max(560px, calc(100vh - 170px));
    background: #eef1f4;
}

.variables-map-canvas {
    width: 100%;
    height: 100%;
    min-height: max(560px, calc(100vh - 170px));
}

.variables-map-canvas .maplibregl-map {
    width: 100%;
    height: 100%;
    min-height: max(560px, calc(100vh - 170px));
}

.variables-map-legend {
    position: absolute;
    z-index: 3;
    right: 1rem;
    bottom: 1rem;
    width: 190px;
    padding: 0.65rem 0.75rem;
    border: 1px solid rgba(213, 219, 225, 0.95);
    border-radius: 0.35rem;
    background: rgba(255, 255, 255, 0.94);
    color: #59636e;
    box-shadow: 0 0.2rem 0.75rem rgba(52, 58, 64, 0.14);
    font-size: 0.72rem;
}

.variables-map-legend-title {
    margin-bottom: 0.35rem;
    color: #3f4650;
    font-weight: 650;
}

.variables-map-legend-gradient {
    height: 0.65rem;
    border: 1px solid #cbd2da;
    border-radius: 0.2rem;
}

.variables-map-legend-labels {
    display: flex;
    justify-content: space-between;
    margin-top: 0.2rem;
    color: #707985;
    font-size: 0.68rem;
}

.variables-map-legend small {
    display: flex;
    align-items: center;
    gap: 0.3rem;
    margin-top: 0.35rem;
    color: #707985;
}

.variables-map-no-data-swatch {
    display: inline-block;
    width: 0.65rem;
    height: 0.65rem;
    border: 1px solid #bdc5cd;
    border-radius: 0.15rem;
    background: #dfe4e8;
}

.variables-map-loading {
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

.variables-map-info {
    overflow-y: auto;
    max-height: max(560px, calc(100vh - 170px));
    padding: 1rem;
    border-left: 1px solid #edf0f3;
    background: #fff;
}

.variables-map-context {
    margin-bottom: 0.9rem;
    padding-bottom: 0.8rem;
    border-bottom: 1px solid #edf0f3;
}

.variables-map-context h1 {
    margin: 0.15rem 0 0.45rem;
    font-size: 1.08rem;
    line-height: 1.25;
}

.variables-map-context p {
    margin: 0;
    color: #66717d;
    font-size: 0.8rem;
}

.variables-map-context p strong,
.variables-map-context p small {
    display: block;
}

.variables-map-context p small {
    margin-top: 0.2rem;
    color: #7a838e;
}

.variables-map-info h2 {
    margin-bottom: 0.65rem;
    font-size: 1.08rem;
    line-height: 1.35;
}

.variables-map-variable-description {
    color: #66717d;
    font-size: 0.84rem;
    line-height: 1.55;
}

.variables-map-details {
    display: grid;
    grid-template-columns: minmax(80px, auto) minmax(0, 1fr);
    gap: 0.45rem 0.8rem;
    margin: 1rem 0 0;
    padding-top: 0.9rem;
    border-top: 1px solid #edf0f3;
    font-size: 0.8rem;
}

.variables-map-details dt {
    color: #707985;
    font-weight: 650;
}

.variables-map-details dd {
    margin: 0;
    color: #343a40;
    overflow-wrap: anywhere;
}

@media (max-width: 991.98px) {
    .variables-layer-page .geofocus-base-layout {
        grid-template-columns: minmax(250px, 290px) minmax(0, 1fr);
    }

    .variables-layer-page .geofocus-base-summary-item-wide {
        grid-column: auto;
    }

    .variables-layer-heading {
        align-items: flex-start;
        flex-direction: column;
    }

    .variables-layer-heading-title {
        width: 100%;
        text-align: right;
    }

    .variables-layer-heading-actions {
        width: 100%;
        justify-content: space-between;
    }

    .variables-map-controls {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }

    .variables-map-back-control {
        grid-column: 1 / -1;
    }

    .variables-map-layout {
        grid-template-columns: 1fr;
    }

    .variables-map-info {
        max-height: none;
        border-top: 1px solid #edf0f3;
        border-left: 0;
    }
}

@media (max-width: 767.98px) {
    .variables-layer-page .geofocus-base-layout {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 575.98px) {
    .variables-layer-heading-actions,
    .variables-layer-toolbar {
        align-items: stretch;
        flex-direction: column;
    }

    .variables-layer-heading-actions .btn,
    .variables-layer-toolbar > div {
        width: 100%;
    }

    .variables-layer-toolbar .btn {
        align-self: flex-start;
    }

    .variables-layer-header-actions {
        align-items: stretch;
        flex-direction: column;
    }

    .variables-layer-header-actions .btn {
        width: 100%;
    }

    .variables-map-controls {
        grid-template-columns: 1fr;
        padding: 0.65rem 0.75rem;
    }

    .variables-map-back-control {
        grid-column: auto;
    }

    .variables-map-layout,
    .variables-map-canvas-wrap,
    .variables-map-canvas,
    .variables-map-canvas .maplibregl-map {
        min-height: 480px;
    }
}
</style>
