    <div class="center_box_750" v-show="section == 'territorios'">
        <div class="mb-2 text-end">
        <!-- v-bind:disabled="!descripcion.active" -->
            <button class="btn btn-light me-2" v-on:click="getDescripcionPriorizacion"
                title="Generar una descripción de la configuración realizada a la priorización"
                
                >
                <img src="<?= URL_IMG ?>icons/ia-generate.png" alt="Icono generar contenido" style="width: 20px;"> Generar descripción
            </button>
            <button class="btn btn-light me-2" v-on:click="actualizarMapa('mapa')">
                Ver en el mapa
            </button>
            <a class="btn btn-success" href="<?= URL_APP . "geofocus/export/{$row->id}" ?>">
                <i class="fas fa-download"></i> Exportar
            </a>
        </div>

        <p class="d-none">
            <strong>textoParametrizacion:</strong> {{ textoParametrizacion }}
        </p>
        <div class="center_box_750 mb-3" v-show="descripcion.texto.length > 0">
            <strong>Descripción automática de la parametrización:</strong><br>
            <p id="typing-respuesta"><?= $row->descripcion_generada ?></p>
        </div>

        <div class="d-flex justify-content-center mb-2">
            <div class="spinner-grow text-warning" role="status" v-show="loading">
                <span class="visually-hidden">Calculando...</span>
            </div>
        </div>

        <table class="table bg-white">
            <thead>
                <th width="10px">Orden</th>
                <th>Barrio</th>
                <th>Localidad</th>
                <th>Sector</th>
                <th>Puntaje <i class="fas fa-info-circle text-muted" title="Puntaje calculado ponderado por las variables seleccionadas"></i></th>
            </thead>
            <tbody v-show="!loading">
                <tr v-for="(territorio, key) in territorios">
                    <td class="text-center text-muted">{{ territorio.orden }}</td>
                    <td>{{ territorio.nombre }}</td>
                    <td>{{ territorio.localidad }}</td>
                    <td>{{ localidadValor(territorio.cod_localidad, 'sector') }}</td>
                    <td class="text-center">{{ territorio.valor }}</td>
                </tr>
            </tbody>
        </table>
    </div>