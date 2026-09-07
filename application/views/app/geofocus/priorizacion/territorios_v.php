<div class="geofocus-priority-section geofocus-priority-territories geofocus-base-content" v-show="section == 'territorios'">
    <header class="geofocus-base-content-header geofocus-priority-section-header">
        <div>
            <h1>Territorios priorizados</h1>
            <p>Resultados ordenados según la configuración de variables seleccionada.</p>
        </div>
        <div class="geofocus-priority-actions">
        <!-- v-bind:disabled="!descripcion.active" -->
        <button class="btn btn-light me-2" v-on:click="getDescripcionPriorizacion"
            title="Generar una descripción de la configuración realizada a la priorización"
            v-bind:disabled="!isEditable()">
            <img src="<?= URL_IMG ?>icons/ia-generate.png" alt="" class="geofocus-priority-ai-icon"> Generar
            descripción
        </button>
        <button class="btn btn-light me-2" v-on:click="actualizarMapa('mapa')">
            Ver en el mapa
        </button>
        <a class="btn btn-success" href="<?= URL_APP . "geofocus/export/{$row->id}" ?>">
            <i class="fas fa-download"></i> Exportar
        </a>
        </div>
    </header>

    <div class="geofocus-priority-section-body">
    <p class="d-none">
        <strong>textoParametrizacion:</strong> {{ textoParametrizacion }}
    </p>
    <div class="geofocus-priority-description" v-show="descripcion.texto.length > 0">
        <strong>Descripción automática de la parametrización:</strong><br>
        <p id="typing-respuesta"><?= $row->descripcion_generada ?></p>
    </div>

    <div class="geofocus-priority-loading" role="status" v-show="loading">
        <div class="spinner-grow text-warning" aria-hidden="true"></div>
            <span>Calculando priorización…</span>
        </div>

    <div class="table-responsive geofocus-priority-results-table">
    <table class="table table-hover align-middle mb-0">
        <thead>
            <tr>
                <th scope="col">Orden</th>
                <th scope="col">Barrio</th>
                <th scope="col">UPZ</th>
                <th scope="col">Localidad</th>
                <th scope="col">Sector</th>
                <th scope="col">Puntaje <i class="fas fa-info-circle text-muted"
                        title="Puntaje calculado ponderado por las variables seleccionadas"></i></th>
            </tr>
        </thead>
        <tbody v-show="!loading">
            <tr v-for="(territorio, key) in territorios">
                <td class="text-center text-muted">{{ territorio.orden }}</td>
                <td>
                    {{ territorio.nombre }}
                </td>
                <td>{{ territorio.upz }}</td>
                <td>{{ territorio.localidad }}</td>
                <td>
                    <span class="sector" v-bind:class="classSector(territorio.cod_localidad)">
                        {{ localidadValor(territorio.cod_localidad, 'sector') }}
                    </span>
                </td>
                <td class="text-center">{{ formatNumber(territorio.valor) }}</td>
            </tr>
        </tbody>
    </table>
    </div>
    </div>
</div>
