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
</style>
