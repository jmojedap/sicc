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
}

.variables-layer-heading-actions {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    justify-content: flex-end;
    gap: 0.65rem;
}

.variables-layer-toolbar {
    display: flex;
    align-items: flex-end;
    gap: 0.65rem;
    margin-bottom: 0.9rem;
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

    .variables-layer-heading-actions {
        width: 100%;
        justify-content: space-between;
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
}
</style>