<div class="row">
    <div class="col-md-5">
        <div class="">
            <span class="entidad me-2"
                v-bind:class="textToClass(currentInvestigacion['Entidad'], 'entidad')">{{ currentInvestigacion['Entidad'] }}
            </span>

            <span class="text-muted">{{ currentInvestigacion['Tema'] }}</span>
        </div>
        <h3 class="card-title color-text-1">
            {{ currentInvestigacion['Nombre clave'].substring(3) }}
        </h3>
        <h4 class="card-title mb-2 color-text-5">
            {{ currentInvestigacion['Título'] }}
        </h4>
        <div class="">
            <b>Descripción</b>
            <p>
                {{ currentInvestigacion['Descripción'] }}
            </p>
            <b>Línea de investigación</b>
            <p>
                {{ currentInvestigacion['Línea de investigación'] }}
            </p>
            <p>
                <b>Investigador(a) responsable:</b>
                {{ currentInvestigacion['Investigador responsable'] }}
            </p>
            <p>
                <b>Descripción del avance:</b>
                {{ currentInvestigacion['Descripción avance'] }}
            </p>
        </div>
    </div>
    <div class="col-md-5">
        <table class="table bg-white w-100">
            <thead>
                <th width="20%">Fecha</th>
                <th>Nota</th>
            </thead>
            <tbody>
                <tr v-for="(nota, key) in notas"
                    v-show="nota['ID Investigación'] == currentInvestigacion['ID'] && nota['Nota'].length > 0">
                    <td>
                        {{ dateFormat(nota['Fecha'],'D MMM') }}
                        <br>
                        <small class="color-text-1">
                            {{ ago(nota['Fecha']) }}
                        </small>
                    </td>
                    <td>{{ nota['Nota'] }}</td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="col-md-2">
        Planeación
        <div class="progress">
            <div class="progress-bar" role="progressbar" aria-label="Example with label"
                v-bind:class="avanceClass(currentInvestigacion['P'])"
                v-bind:style="`width: ` + currentInvestigacion['P'] + `;`" aria-valuenow="25" aria-valuemin="0"
                aria-valuemax="100">
                {{ currentInvestigacion['P'] }}
            </div>
        </div>
        Instrumento
        <div class="progress">
            <div class="progress-bar" role="progressbar" aria-label=""
                v-bind:class="avanceClass(currentInvestigacion['I'])"
                v-bind:style="`width: ` + currentInvestigacion['I'] + `;`" aria-valuenow="25" aria-valuemin="0"
                aria-valuemax="100">
                {{ currentInvestigacion['I'] }}
            </div>
        </div>
        Recolección
        <div class="progress">
            <div class="progress-bar" role="progressbar" aria-label=""
                v-bind:class="avanceClass(currentInvestigacion['R'])"
                v-bind:style="`width: ` + currentInvestigacion['R'] + `;`" aria-valuenow="25" aria-valuemin="0"
                aria-valuemax="100">
                {{ currentInvestigacion['R'] }}
            </div>
        </div>
        Documentación
        <div class="progress">
            <div class="progress-bar" role="progressbar" aria-label=""
                v-bind:class="avanceClass(currentInvestigacion['D'])"
                v-bind:style="`width: ` + currentInvestigacion['D'] + `;`" aria-valuenow="25" aria-valuemin="0"
                aria-valuemax="100">
                {{ currentInvestigacion['D'] }}
            </div>
        </div>
        Finalización
        <div class="progress">
            <div class="progress-bar" role="progressbar" aria-label=""
                v-bind:class="avanceClass(currentInvestigacion['F'])"
                v-bind:style="`width: ` + currentInvestigacion['F'] + `;`" aria-valuenow="25" aria-valuemin="0"
                aria-valuemax="100">
                {{ currentInvestigacion['F'] }}
            </div>
        </div>
    </div>
    <div class="col-md-4 d-none">
        <!-- PRODUCTOS DE LA INVESTIGACIÓN -->
        <h5 class="text-center color-text-1">Productos ({{ productosFiltrados.length }})</h5>
        <div class="row">
            <div class="col-md-12">
                <div v-for="producto in productosFiltrados" class="producto">
                    <a class="d-flex" v-bind:href="producto['Link para ficha']" target="_blank">
                        <div width="65px" class="text-center me-3">
                            <div class="icon-container">
                                <span>
                                    <i v-bind:class="getProductoClass(producto['Tipo producto'])"></i>
                                </span>
                            </div>
                        </div>
                        <div>
                            {{ tituloProducto(producto) }}
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>