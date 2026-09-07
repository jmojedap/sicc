<div v-bind:class="{'hidden-map': section != 'mapa' }">
    
</div>

<div class="d-flex geofocus-priority-map geofocus-base-content" v-bind:class="{'hidden-map': section != 'mapa' }">
    <div id="map-container"></div>
    <div id="map-info">
        <div class="geofocus-priority-map-filters">
        <div>
            <select v-model="currentTema" class="form-select" v-on:change="setTema">
                <option v-for="optionTemas in arrTemas" v-bind:value="optionTemas.name">{{ optionTemas.name }}</option>
            </select>
        </div>
        <div>
            <select v-model="currentVariableId" class="form-select" v-on:change="updateVariable">
                <option v-for="optionVariable in variables"
                    v-show="optionVariable.tema == currentTema && optionVariable.estado == 1" v-bind:value="optionVariable.id">{{ optionVariable.nombre }}
                </option>
            </select>
        </div>
        </div>

        <div class="geofocus-priority-map-copy" v-show="tipoInformacion == 'priorizacion'">
            <h2>{{ priorizacion.nombre }}</h2>
            <p>
                {{ priorizacion.descripcion }}
            </p>
            <p>
                {{ priorizacion.descripcion_generada }}
            </p>

        </div>

        <div class="geofocus-priority-map-copy" v-show="tipoInformacion == 'variable'">
            <h2>{{ currentVariable.nombre }}</h2>
            <div class="tema mb-2" v-bind:class="textToClass(currentVariable.tema,'tema')">
                {{ currentVariable.tema }}
            </div>
            <p>
                {{ currentVariable.descripcion }}
            </p>
            <table class="table table-sm table-borderless geofocus-priority-map-details">
                <tr>
                    <td class="td-title">Año datos</td>
                    <td>{{ currentVariable.anio_valores }}</td>
                </tr>
                <tr>
                    <td class="td-title">Unidad de medida</td>
                    <td>{{ currentVariable.unidad_medida }}</td>
                </tr>
    
                <tr>
                    <td class="td-title">Rango valores</td>
                    <td>
                        {{ formatNumber(currentVariable.minimo) }} a {{ formatNumber(currentVariable.maximo) }}
                    </td>
                </tr>
                <tr>
                    <td class="td-title">Media</td>
                    <td>
                        {{ formatNumber(currentVariable.media) }}
                    </td>
                </tr>
                <tr>
                    <td class="td-title">Desviación estándar</td>
                    <td>
                        {{ formatNumber(currentVariable.desviacion_estandar) }}
                    </td>
                </tr>
                <tr>
                    <td class="td-title">Datos origen</td>
                    <td>{{ currentVariable.datos_origen }}</td>
                </tr>
            </table>

            <p class="geofocus-priority-map-notes">
                <span class="text-primary">Cálculo:</span> {{ currentVariable.descripcion_calculo }} &middot;
                <span class="text-primary">Origen de los datos:</span> {{ currentVariable.datos_origen }} &middot;
                <span class="text-primary">Entidad:</span> {{ currentVariable.entidad }}
            </p>
        </div>
    </div>

    
</div>
