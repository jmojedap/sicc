<div v-bind:class="{'hidden-map': section != 'mapa' }">
    <div id="map-container"></div>
</div>

<div class="d-flex d-none" v-bind:class="{'hidden-map': section != 'mapa' }">
    <div class="row">
        <div class="col-md-6">
        <p>
            {{ currentVariable.descripcion }}
        </p>
        <table class="table table-borderless">
            <tr>
                <td class="td-title">Tema</td>
                <td>{{ currentVariable.tema }}</td>
            </tr>
            <tr>
                <td class="td-title">Año datos</td>
                <td>{{ currentVariable.anio_valores }}</td>
            </tr>
            <tr>
                <td class="td-title">Cálculo</td>
                <td>{{ currentVariable.descripcion_calculo }}</td>
            </tr>
            <tr>
                <td class="td-title">Entidad</td>
                <td>{{ currentVariable.entidad }}</td>
            </tr>
            <tr>
                <td class="td-title">Unidad de medida</td>
                <td>{{ currentVariable.unidad_medida }}</td>
            </tr>

            <tr>
                <td class="td-title">Min</td>
                <td>
                    {{ currentVariable.minimo }}
                </td>
            </tr>
            <tr>
                <td class="td-title">Media</td>
                <td>
                    {{ currentVariable.media }}
                </td>
            </tr>
            <tr>
                <td class="td-title">Desviación estándar</td>
                <td>
                    {{ currentVariable.desviacion_estandar }}
                </td>
            </tr>
            <tr>
                <td class="td-title">Máx</td>
                <td>
                    {{ currentVariable.maximo }}
                </td>
            </tr>
            <tr>
                <td class="td-title">Datos origen</td>
                <td>{{ currentVariable.datos_origen }}</td>
            </tr>
            <tr>
                <td class="td-title">clave</td>
                <td>{{ currentVariable.clave }}</td>
            </tr>
        </table>
        </div>
        <div class="col-md-6">
            
        </div>
    </div>
</div>