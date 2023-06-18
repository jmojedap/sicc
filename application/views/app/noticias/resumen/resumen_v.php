<div id="resultadosApp" class="center_box_750">
    <h3 class="text-primary">Clasificación</h3>
    <table class="table bg-white">
        <thead>
            <th width="150px">Clasificación</th>
            <th width="150px" class="text-center">Cantidad</th>
            <th></th>
        </thead>
        <tbody>
            <tr v-for="(row, key) in resultadosClasificacion">
                <td>{{ clasificacionName(row.clasificacion) }}</td>
                <td class="text-center">{{ row.qty_noticias }}</td>
                <td>
                    <div class="progress">
                        <div class="progress-bar" v-bind:class="clasificacionName(row.clasificacion,'infoClass')"
                            role="progressbar"
                            v-bind:style="`width: ` + intPercent(row.qty_noticias,resultadosClasificacionSummary.sum) + `%;`"
                            aria-valuenow="" aria-valuemin="0" aria-valuemax="100">{{ intPercent(row.qty_noticias,resultadosClasificacionSummary.sum) }}%</div>
                    </div>
                </td>
            </tr>
        </tbody>
        <tfoot>
            <th>Total</th>
            <th class="text-center">{{ resultadosClasificacionSummary.sum }}</th>
            <th></th>
        </tfoot>
    </table>
    <h3 class="text-primary">Clasificadores</h3>
    <table class="table bg-white">
        <thead>
            <th>Participante</th>
            <th class="text-center">Cantidad</th>
        </thead>
        <tbody>
            <tr v-for="(row, key) in resultadosClasificador">
                <td>{{ row.actualizado_por }}</td>
                <td class="text-center">{{ row.qty_noticias }}</td>
            </tr>
        </tbody>
        <tfoot>
            <th>Total</th>
            <th class="text-center">{{ resultadosClasificadorSummary.sum }}</th>
        </tfoot>
    </table>

    <h3 class="text-primary">Por año</h3>
    <table class="table bg-white">
        <thead>
            <th width="150px">Año</th>
            <th width="150px" class="text-center">Cantidad</th>
        </thead>
        <tbody>
            <tr v-for="(row, key) in resultadosAnio">
                <td>{{ row.anio_publicacion }}</td>
                <td class="text-center">{{ row.qty_noticias }}</td>
            </tr>
        </tbody>
        <tfoot>
            <th>Total</th>
            <th class="text-center">{{ resultadosAnioSummary.sum }}</th>
        </tfoot>
    </table>
    
</div>

<?php $this->load->view('app/noticias/resumen/vue_v') ?>