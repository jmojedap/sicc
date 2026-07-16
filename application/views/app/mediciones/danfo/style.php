<style>
    @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@400;700;800&family=Inter:wght@400;600;700&display=swap');

    #chart-container { 
        height: calc(100vh - 320px);
        width: 100%;
        border: 1px solid #f0f0f0;
        border-radius: 0px;
        margin-top: 1rem;
        padding: 0.5rem;
        background-color: #fff;
    }
    .loading { color: #666; font-style: italic; margin-bottom: 1rem; }

    #lista-preguntas {
        height: calc(100vh - 150px);
        overflow-y: auto;
        border-bottom: 1px solid #AAA;
    }

    /* LISTS BASE STYLES */

    #sections-collapser {
        border: 1px solid #e2e8f0;
        background: #fff;
        padding: 12px 16px;
        border-radius: 8px;
        color: #003366;
        font-size: 0.875rem;
        font-weight: 700;
        transition: all 0.2s ease;
        text-align: left;
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: space-between;
        cursor: pointer;
        margin-bottom: 0.5rem;
    }

    #sections-collapser:hover {
        background-color: #f8fafc;
        border-color: #cbd5e1;
    }

    #sections-collapser::after {
        content: '\25BC'; /* Chevron down */
        font-size: 0.7rem;
        transition: transform 0.3s ease;
    }

    #sections-collapser[aria-expanded="true"]::after {
        transform: rotate(180deg);
    }

    .section-list, .questions-list {
        display: flex;
        flex-direction: column;
        gap: 4px;
        background: transparent;
    }

    .section-item, .questions-item {
        border: 1px solid transparent;
        background: #fff;
        padding: 12px 16px;
        border-radius: 8px;
        color: #64748b;
        font-size: 0.875rem;
        font-weight: 500;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        text-align: left;
        width: 100%;
        display: flex;
        align-items: center;
        gap: 12px;
        cursor: pointer;
        margin-bottom: 2px;
    }

    .section-item:hover, .questions-item:hover {
        background-color: #f8fafc;
        border-color: #e2e8f0;
        color: #1e293b;
    }

    /* SECTION SPECIFIC (NAVY) */
    .section-item.active {
        background-color: #ffffff !important;
        border-color: #003366 !important;
        color: #003366 !important;
        font-weight: 700;
        box-shadow: 0 4px 12px -2px rgba(0, 51, 102, 0.12);
    }

    /* QUESTIONS SPECIFIC (CYAN) */
    .questions-item.active {
        background-color: #ffffff !important;
        border-color: #00AEEF !important;
        color: #003366 !important; /* Mantener navy para texto por legibilidad, o usar cian si se prefiere */
        font-weight: 700;
        box-shadow: 0 4px 12px -2px rgba(0, 174, 239, 0.15);
    }

    .bg-response-0 { background-color: #003366; }
    .bg-response-1 { background-color: #00AEEF; }
    .bg-response-2 { background-color: #F9A825; }
    .bg-response-3 { background-color: #D32F2F; }
    .bg-response-4 { background-color: #388E3C; }
    .bg-response-5 { background-color: #7B1FA2; }
    .bg-response-6 { background-color: #FF0000; }
    .bg-response-7 { background-color: #FF7F00; }
    .bg-response-8 { background-color: #FFFF00; }
    .bg-response-9 { background-color: #00FF00; }
    .bg-response-10 { background-color: #0000FF; }

    .response-circle {
        width: 10px;
        height: 10px;
        border-radius: 50%;
    }

    /* KPI STYLES */
    #kpi {
        background: #ffffff;
        border-radius: 0.5em;
        padding: 0.8rem 1rem;
        display: inline-flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        border: 1px solid rgba(0, 51, 102, 0.05);
        min-width: 180px;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
    }

    /*#kpi::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        height: 4px;
        background: linear-gradient(90deg, #003366, #00AEEF);
        opacity: 0.8;
    }*/

    #kpi:hover {
        transform: translateY(-4px);
        box-shadow: 0 20px 25px -5px rgba(0, 51, 102, 0.15), 0 10px 10px -5px rgba(0, 51, 102, 0.1);
    }

    #kpi .value {
        font-size: 1.5rem;
        font-weight: 700;
        line-height: 1;
        margin: 0;
        color: #666;
        /*letter-spacing: -2px;*/
    }

    #kpi .measure-unit {
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.15em;
        color: #64748b;
        font-weight: 700;
        margin-top: 0.5rem;
    }

    #kpi .trend {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        font-size: 0.875rem;
        font-weight: 600;
        margin-top: 0.75rem;
        padding: 0.25rem 0.75rem;
        border-radius: 2rem;
    }

    #kpi .trend-up { background-color: #f0fdf4; color: #16a34a; }
    #kpi .trend-down { background-color: #fef2f2; color: #dc2626; }
</style>