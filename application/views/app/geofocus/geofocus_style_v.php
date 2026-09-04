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
}</style>