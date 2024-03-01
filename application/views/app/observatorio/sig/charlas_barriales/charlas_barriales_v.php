<style>
    .poligono-localidad:hover{
        opacity: 0.7;
        cursor: pointer;
    }

    .img-mapa{
        width: 100%;
        margin: -25%;
        position: relative;
    }

    .titulo-seccion{
        color: #5E4296;
        text-align: center;
    }

    .fila-activa{
        background-color: #f5deee;
    }
</style>

<div class="container">
    <!-- <img src="<?= URL_CONTENT ?>observatorio/mapas/localidades.svg" alt="Localidades de Bogotá"> -->
    <div id="charlasBarrialesApp">
        

        <div class="row mt-2">
            <div class="col-md-5">
                <h3 class="titulo-seccion">{{ currentLocalidad.nombre }}</h3>
                <div class="mb-2" style="width: 100%; height: 250px; overflow: hidden; border: 1px solid #D9D2E9; background-color: #FFFFFF;">
                    <?php $this->load->view('app/observatorio/sig/charlas_barriales/mapa_v') ?>
                </div>
                <table class="table bg-white table-sm">
                    <thead>
                        <th class="text-center">Localidad</th>
                        <th class="text-center">Compromisos</th>
                        <th class="text-center" width="20px"></th>
                    </thead>
                    <tbody>
                        <tr v-for="(localidad, k) in localidades" v-bind:class="{'fila-activa': localidad.localidad_cod == localidadCod }">
                            <td>{{ localidad.nombre }}</td>
                            <td class="text-center">
                                <span class="badge bg-primary" v-show="localidad.cantidad_compromisos > 0">
                                    {{ localidad.cantidad_compromisos }}
                                </span>
                                <span v-show="localidad.cantidad_compromisos == 0" class="text-muted">
                                    {{ localidad.cantidad_compromisos }}
                                </span>
                            </td>
                            <td>
                                <button class="btn btn-sm btn-light" v-on:click="setCurrent(localidad.localidad_cod)">
                                    <i class="fas fa-arrow-right"></i>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="col-md-7">
                <h3 class="titulo-seccion">Compromisos <span class="badge bg-primary">{{ currentLocalidad.cantidad_compromisos }}</span></h3>
        
                <table class="table bg-white">
                    <thead>
                        <th>Necesidad</th>
                        <th>Compromiso</th>
                        <th width="200px">Avance</th>
                    </thead>
                    <tbody>
                        <tr v-for="(compromiso, key) in compromisos" v-show="localidadCod == compromiso.localidad_cod">
                            <td>{{ compromiso.necesidad }}</td>
                            <td>
                                <p>
                                    {{ compromiso.descripcion_compromiso }}
                                </p>
                                <button class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#compromisoModal" v-on:click="setCompromiso(key)">
                                    <i class="fas fa-plus" title="Detalles sobre el compromiso"></i> Detalles
                                </button>
                            </td>
                            <td>
                                <div class="progress">
                                    <div class="progress-bar" role="progressbar" v-bind:style="`width: ` + compromiso.avance + `%;`" v-bind:aria-valuenow="compromiso.avance" aria-valuemin="0" aria-valuemax="100">{{ compromiso.avance }}%</div>
                                </div>
                                
                            </td>
                        </tr>
                    </tbody>
                </table>
                <p class="text-center">
                    <small class="text-muted">Dirección Observatorio y Gestión del Conocimiento Cultural</small>
                </p>
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="compromisoModal" tabindex="-1" aria-labelledby="compromisoModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="compromisoModalLabel">{{ currentCompromiso.tema }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <table class="table-sm table table-borderless">
                    <tbody>
                        <tr>
                            <td class="text-main">Necesidad</td>
                            <td style="max-width:450px;">{{ currentCompromiso.necesidad }}</td>
                        </tr>
                        <tr>
                            <td class="text-main">Nombres</td>
                            <td>{{ currentCompromiso.nombres }}</td>
                        </tr>
                        <tr>
                            <td class="text-main">Tema</td>
                            <td>{{ currentCompromiso.tema }}</td>
                        </tr>
                        <tr>
                            <td class="text-main">Estado</td>
                            <td>{{ currentCompromiso.estado }}</td>
                        </tr>
                        <tr>
                            <td class="text-main">Descripción</td>
                            <td>{{ currentCompromiso.descripcion_compromiso }}</td>
                        </tr>
                        <tr>
                            <td class="text-main">Compromiso comunicación</td>
                            <td>{{ currentCompromiso.compromiso_comunicacion }}</td>
                        </tr>
                        <tr>
                            <td class="text-main">Responsable</td>
                            <td>{{ currentCompromiso.responsable }}</td>
                        </tr>
                        <tr>
                            <td class="text-main">Archivos</td>
                            <td>
                                <p class="text-wrap" style="max-width: 350px;">
                                    {{ currentCompromiso.archivos }}
                                </p>
                            </td>
                        </tr>
                        
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cerrar</button>
            </div>
            </div>
        </div>
        </div>
        
    </div>

    <?php $this->load->view('app/observatorio/sig/charlas_barriales/data_v') ?>
    <?php $this->load->view('app/observatorio/sig/charlas_barriales/vue_v') ?>
    
</div>