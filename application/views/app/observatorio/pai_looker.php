<div
    style='display: flex; justify-content: center; align-items: center; height: 100%; border: 1px solid "FEFEFE; padding: 0 5%'>
    <div
        style="width: 30%; text-align: center; padding: 10px; display: flex; justify-content: center; align-items: center; flex-direction: column;">
        <h1
            style="background-color: #E39800; color: #FFF; width: 3em; font-weight: bolder; text-align: center; border-radius: 0.2em;">
            {{num}}
        </h1>
        <h2>{{nombre_corto}}</h2>
    </div>
    <table style="width: 70%; border: 0px solid #000; margin: 0 5%; padding: 0 5%">
        <tbody>
            <tr>
                <td style="width: 30%; text-align: right; padding: 10px;">
                    <strong style="color: #999">Entidad</strong>

                </td>
                <td style="width: 70%; text-align: left; padding: 10px;">
                    {{entidad}}
                    <br>
                    <small style="color: #BBB">{{dependencia_1}}</small>
                </td>
            </tr>
            <tr>
                <td style="width: 30%; text-align: right; padding: 10px;">
                    <strong style="color: #999">Descripción</strong>
                </td>
                <td style="width: 70%; text-align: left; padding: 10px;">
                    {{descripción}}
                </td>
            </tr>
            <tr>
                <td style="width: 30%; text-align: right; padding: 10px;">
                    <strong style="color: #999">Estado selección</strong>
                </td>
                <td style="width: 70%; text-align: left; padding: 10px;">
                    <strong style="font-size: 1.1em; color: #FFA800">{{seleccionada}}</strong>
                </td>
            </tr>
            <tr>
                <td style="width: 30%; text-align: right; padding: 10px;">
                    <strong style="color: #999">Observaciones</strong>
                </td>
                <td style="width: 70%; text-align: left; padding: 10px;">
                    {{observaciones}}
                </td>
            </tr>
        </tbody>

    </table>
</div>

<!-- ---------------------------------- -->

<style>
    .titulo{
    color:#5D4293;
    }
    .text-secondary{
    color: #E39800;
    }
    .text-seleccionada {
        background-color: #2D62A9;
        color: #FFF;
        padding: 0.2em 0.5em;
        border-radius: 0.2em;
        font-size: 1.2em;
    }
</style>

<h3><span class="text-secondary">{{num}}</span> &middot; {{nombre_corto}}</h3>
<h4 class="titulo">{{entidad_corto}} &middot; <span class="text-secondary">{{tema}}</span></h4>
<p>Seleccionada: <b class="text-seleccionada">{{seleccionada}}</b></p>
<p>{{descripción}}</p>
<p>
    <b>Respuesta:</b>
    {{respuesta_a_solicitante}}
</p>