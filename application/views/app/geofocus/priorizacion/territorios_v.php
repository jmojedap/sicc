    <div class="center_box_750" v-show="section == 'territorios'">
        <table class="table bg-white">
            <thead>
                <th width="10px">Orden</th>
                <th>Barrio</th>
                <th>Localidad</th>
                <th>Sector</th>
                <th>Puntaje <i class="fas fa-info-circle text-muted" title="Puntaje calculado ponderado por las variables seleccionadas"></i></th>
            </thead>
            <tbody>
                <tr v-for="(territorio, key) in territorios">
                    <td class="text-center text-muted">{{ territorio.orden }}</td>
                    <td>{{ territorio.nombre }}</td>
                    <td>{{ territorio.localidad }}</td>
                    <td>{{ localidadValor(territorio.cod_localidad, 'sector') }}</td>
                    <td class="text-center">{{ territorio.valor }}</td>
                </tr>
            </tbody>
        </table>
        <a class="btn btn-success" href="<?= URL_APP . "geofocus/export/{$row->id}" ?>">
            <i class="fas fa-download"></i> Exportar
        </a>
    </div>