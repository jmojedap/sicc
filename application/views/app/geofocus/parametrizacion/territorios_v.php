    <div class="center_box_750" v-show="section == 'territorios'">
        <table class="table bg-white">
            <thead>
                <th width="10px">Orden</th>
                <th>Barrio</th>
                <th>Localidad</th>
                <th>Índice calculado</th>
            </thead>
            <tbody>
                <tr v-for="(territorio, key) in territorios">
                    <td class="text-center text-muted">{{ territorio.orden }}</td>
                    <td>{{ territorio.nombre }}</td>
                    <td>{{ territorio.localidad }}</td>
                    <td class="text-center">{{ territorio.valor }}</td>
                </tr>
            </tbody>
        </table>
    </div>