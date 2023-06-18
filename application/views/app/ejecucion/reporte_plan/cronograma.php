
    <h2 class="text-center">Cronograma</h2>
    <p>A continuación se el cronograma para las actividades definidas en el plan de acción</p>
    <table class="table table-bordered bg-white">
        <thead>
            <th class="text-center">No.</th>
            <th class="text-center">Actividad</th>
            <th class="text-center">Ejecutado</th>
            <th class="text-center" v-for="mes in meses">{{ mes.title }}</th>
        </thead>
        <tbody>
            <tr v-for="(actividad, key) in actividades">
                <td class="text-center">{{ key + 1 }}</td>
                <td width="50%">
                    {{ actividad.titulo }}
                </td>
                <td class="text-center">{{ actividad.pct_ejecutado }} %</td>
                <td class="text-center" v-for="mes in meses" v-bind:class="{'table-warning': actividad.periodos_array.includes(mes.id) }">
                    <span class="text-muted" v-show="actividad.periodos_array.includes(mes.id)">{{ mes.title }}</span>
                </td>
            </tr>
        </tbody>
    </table>