<style >
    .sqr-selector {
        display: flex;
        align-items: center;
        justify-content: center;
        border: 1px solid #FCDEA0;
        width: 3em;
        height: 2em;
        font-size: 0.7em;
        margin-right: 2px;
        margin-bottom: 2px;
        cursor: pointer;
        text-align: center;
    }

    .sqr-selector:hover {
        color: #FFF;
        background-color: #FFB80C;
    }

    .sqr-selector.active {
        color: #FFF;
        background-color: #FFB80C;
    }

    .fecha-dia {
        font-size: 1.5em;
    }

    .ficha-investigacion {
        border: 1px solid #f1f1f1;
        border-radius: 6px;
        padding: 1em;
    }

    .label-linea-investigacion {
        display: inline-block;
        border-radius: 10px;
        font-size: 0.9em;
        padding: 0 0.5em;
    }

    .label-linea-investigacion.linea-sector-cultura{
        background-color: #E6CFF2;
        color: #5A3286;
    }
    .label-linea-investigacion.linea-cultura-ciudadana{
        background-color: #FFE5A0;
        color: #473821;
    }

    span.grupo-investigacion{
        font-size: 1.5em;
    }

    .producto {
        border-radius: 0.3em;
        padding: 0.3em;
    }

    .producto:hover{
        background-color: #442976;
        cursor: pointer;
        color: white;
    }

    .producto a:hover {
        color: white;
    }

    .icon-container {
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: #e0e0e0;
        width: 1.9em;
        height: 1.9em;
        border: 1px solid #FFF;
        border-radius: 50%;
        font-size: 1.5em;
        margin-bottom: .2em;
        margin-left: 0.2em;
        text-align: center
    }

    .icon-container:hover {
        background-color: #fff;
        border-color: #ffb80c
    }

    .producto-general {
        color: #50328c
    }

    .producto-pdf,.producto-audiovisual {
        color: #ea4335
    }

    .producto-db {
        color: #30a338
    }

    .producto-presentacion {
        color: #cc9111
    }

    .producto-cuantitativo {
        color: #1450b3
    }

    .producto-dataviz {
        color: #c99e05
    }

    .entidad {
        border-radius: 3px;
        padding: 0.2em 0.5em;
    }

    .entidad-scrd { background-color: #5D4293; color: white; }
    .entidad-fuga { background-color: #F10096; color: white; }
    .entidad-idartes { background-color: #FFA800; color: white; }
    .entidad-idrd { background-color: #03A9F4; color: white; }
    .entidad-ofb { background-color: #5A9BD4; color: white; }
    .entidad-idpc { background-color: #EC407A; color: white; }
    .entidad-canal-capital { background-color: #03A9F4; color: white; }
    .entidad-idipyba { background-color: #7AC36A; color: white; }
    .entidad-metro { background-color: #DB3737; color: white; }
</style>