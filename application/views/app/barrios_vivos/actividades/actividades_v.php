
    <?php $this->load->view('app/barrios_vivos/style_v') ?>

    <div id="actividadesApp">
        <div class="">
            <div class="mb-2 d-flex" v-if="appUid > 0">
                <div class="me-3">
                    <button class="btn btn-light me-1" v-on:click="clearForm" type="button" data-bs-toggle="modal"
                        data-bs-target="#formModal">
                        <i class="fa fa-plus"></i> Nueva
                    </button>
                </div>
                <div>
                    <button class="btn me-1" title="Mostrar como tabla" v-on:click="displayFormat = 'table'" v-bind:class="{'btn-light': displayFormat == 'table'}">
                        <i class="fas fa-th-list"></i>
                    </button>
                    <button class="btn" title="Mostrar como tarjetas" v-on:click="displayFormat = 'cards'" v-bind:class="{'btn-light': displayFormat == 'cards'}">
                        <i class="fas fa-newspaper"></i>
                    </button>
                </div>
            </div>

            <!-- VISTA TARJETAS -->
            <div class="center_box_750" v-show="displayFormat == 'cards'">
                <div v-for="(detalle, key) in detalles" class="card mb-2">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <h3>
                                {{ detalle.titulo_3 }}) {{ detalle.nombre }}
                            </h3>
                            
                            <div v-if="appRid <= 8">
                                <button class="btn-circle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fa-solid fa-ellipsis-v"></i>
                                </button>
                                <ul class="dropdown-menu">
                                    <li>
                                        <button class="dropdown-item" data-bs-toggle="modal" v-on:click="setDetalle(detalle.id)" data-bs-toggle="modal"
                                            data-bs-target="#formModal">
                                            Editar
                                        </button>
                                    </li>
                                    <li>
                                        <button class="dropdown-item" data-bs-toggle="modal" type="button" v-on:click="setDetalle(detalle.id)" data-bs-toggle="modal"
                                            data-bs-target="#deleteModal" data-bs-toggle="tooltip" title="Eliminar">
                                            Eliminar
                                        </button>
                                    </li>
                                </ul>

                            </div>

                        </div>

                        <p>
                            <span class="fase" v-bind:class="`fase-` + textToClass(detalle.categoria_1)">
                                {{ detalle.categoria_1 }}
                            </span>
                            &middot;
                            <span class="">{{ detalle.titulo_2 }}</span>
                        </p>
                        <p>
                            {{ detalle.descripcion }}
                        </p>
                        <p>
                            <i class="color-text-6 far fa-calendar"></i>
                            {{ dateFormat(detalle.fecha_1, 'dddd, D [de] MMMM') }} &middot;
                            {{ timeFormat(detalle.hora_1) }} - {{ timeFormat(detalle.hora_2) }}
                            &middot;
                            <span class="badge bg-secondary">
                                {{ ago(detalle.fecha_1) }}
                            </span>
                        </p>
                        <p>
                            <i class="color-text-6 fas fa-map-marker-alt"></i>
                            {{ detalle.texto_2 }} &middot;
                            <a :href="'https://www.google.com/maps/search/?api=1&query=' + encodeURIComponent(detalle.texto_2 + ' ' + detalle.texto_3)" target="_blank">
                                {{ detalle.texto_3 }}
                            </a>
                        </p>

                        <div class="d-flex">
                            <p>
                                <span class="text-muted">
                                    Radicado Orfeo
                                </span>
                                {{ detalle.num_radicacion }}
                            </p>
                        </div>
                        <div class="d-flex">
                            <p>
                                <a class="btn btn-light btn-sm me-1" v-bind:href="detalle.url_1" target="_blank" v-if="detalle.url_1">
                                    <i class="fas fa-check text-muted"></i>
                                    Evidencias
                                </a>
                            </p>
                            <p>
                                <a class="btn btn-light btn-sm me-1" v-bind:href="detalle.url_2" target="_blank" v-if="detalle.url_2">
                                    <i class="fas fa-laptop text-muted"></i>
                                    Presentación
                                </a>
                            </p>
                            <p>
                                <a class="btn btn-light btn-sm me-1" v-bind:href="detalle.url_3" target="_blank" v-if="detalle.url_3">
                                    <i class="fas fa-users text-muted"></i>
                                    Asistentes
                                </a>
                            </p>
                            <p>
                                <a class="btn btn-light btn-sm me-1" v-bind:href="detalle.url_4" target="_blank" v-if="detalle.url_4">
                                    <i class="fa fa-folder text-muted"></i>
                                    Carpeta
                                </a>
                            </p>

                        </div>
                        <p>
                            <small class="text-muted">
                                {{ detalle.notas }}
                            </small>
                        </p>


                    </div>
                </div>
            </div>

            <!-- VISTA TABLA -->
            <table class="table bg-white table-sm" style="font-size: 0.9rem;" v-show="displayFormat == 'table'">
                <thead>
                    <th width="10px">Núm</th>
                    <th>Actividad</th>
                    <th>Fase</th>
                    <th>Fecha</th>
                    <th>Lugar</th>
                    <th>Participantes</th>
                    <th v-if="appUid > 0">Actualizado por</th>
                    <th width="20px" v-if="appRid <= 8"></th>
                </thead>
                <tbody>
                    <tr v-for="(detalle, key) in detalles" v-bind:class="{'table-info': detalle.id == detalleId}">
                        <td class="text-center">
                            <span class="badge-light rounded-pill">
                                {{ detalle.titulo_3 }}
                            </span>
                        </td>
                        <td>{{ detalle.nombre }}</td>
                        <td>
                            <span class="fase" v-bind:class="`fase-` + textToClass(detalle.categoria_1)">
                                {{ detalle.categoria_1 }}
                            </span>
                            <br>
                            <small class="text-muted">{{ detalle.titulo_2 }}</small>
                        </td>
                        <td>
                            {{ dateFormat(detalle.fecha_1) }}
                            <br>
                            <small>
                                {{ timeFormat(detalle.hora_1) }} - {{ timeFormat(detalle.hora_2) }}
                            </small>
                        </td>
                        <td>
                            {{ detalle.texto_2 }}
                            <br>
                            <small class="text-muted">{{ detalle.texto_3 }}</small>
                        </td>
                        <td>
                            {{ Number(detalle.entero_1 || 0) + Number(detalle.entero_2 || 0) + Number(detalle.entero_3 || 0) }}
                            <br>
                            <small class="text-muted" title="Hombres, Mujeres y No disponible">
                                {{ detalle.entero_1 }}H &middot;
                                {{ detalle.entero_2 }}M &middot;
                                {{ detalle.entero_3 }}ND
                            </small>
                        </td>
                        <td v-if="appUid > 0">
                            <span v-bind:title="detalle.updater_display_name">
                                {{ detalle.updater_username }}
                            </span>
                            <br>
                            <small class="text-muted">{{ dateFormat(detalle.updated_at, 'DD/MMMM') }} &middot; {{ ago(detalle.updated_at) }}</small>
                        </td>
                        
                        <td v-if="appRid <= 8">
                            <button class="btn-circle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fa-solid fa-ellipsis-v"></i>
                            </button>
                            <ul class="dropdown-menu">
                                <li>
                                    <button class="dropdown-item" data-bs-toggle="modal" v-on:click="setDetalle(detalle.id)" data-bs-toggle="modal"
                                        data-bs-target="#formModal">
                                        Editar
                                    </button>
                                </li>
                                <li>
                                    <button class="dropdown-item" data-bs-toggle="modal" type="button" v-on:click="setDetalle(detalle.id)" data-bs-toggle="modal"
                                        data-bs-target="#deleteModal" data-bs-toggle="tooltip" title="Eliminar">
                                        Eliminar
                                    </button>
                                </li>
                            </ul>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="formModal" tabindex="-1" aria-labelledby="formModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <form accept-charset="utf-8" method="POST" id="actividadesForm" @submit.prevent="handleSubmit">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="exampleModalLabel">Actividad</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <?php $this->load->view('app/barrios_vivos/actividades/form_v') ?>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary w120p">Guardar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <?php $this->load->view('common/bs5/modal_single_delete_v') ?>

    </div>

    <?php $this->load->view('app/barrios_vivos/actividades/vue_v') ?>