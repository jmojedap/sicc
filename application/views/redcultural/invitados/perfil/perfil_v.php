<?php $this->load->view('redcultural/invitados/style_v') ?>

<div id="pefilApp" class="container my-4">
    <div class="mb-3">
        <a href="<?= RCI_URL_APP ?>invitados/directorio" class="btn btn-light btn-sm">
            <i class="fas fa-arrow-left"></i> Directorio
        </a>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="row">
                <div class="col-md-4">
                    <img v-bind:src="`<?= URL_CONTENT ?>redcultural/images/profiles/` + user['username'] + `.jpg`"
                        class="rounded-circle" v-bind:alt="`Imagen de ` + user.display_name" width="100%"
                        v-bind:onerror="`this.src='<?= URL_CONTENT ?>redcultural/images/default/user.png'`">
                </div>
                <div class="col-md-8">
                    <h2 class="card-title color-text-0">
                        <span class="resaltar-2">{{ user.display_name }}</span>
                    </h2>
                    <p class="mb-1">{{ user.job_role }} <br>
                        <span class="me-2">
                            <img v-bind:src="`https://flagcdn.com/w20/` + user.text_1.toLowerCase() + `.png`"
                                :alt="user.text_1" width="" height="auto" :title="user.text_1">
                        </span>
                        {{ user.city_name }}
                    </p>
                    <p><small class="">{{ user.team_1 }}</small></p>
                    <p class="fst-italic" v-show="user.text_2.length > 2">"{{ user.text_2 }}"</p>

                    <?php if ( $this->session->userdata('logged') ) : ?>
                    <div v-if="user.id != appUid">
                        <button class="btn btn-light w150p" v-show="followingStatus != 1" v-on:click="altFollow">
                            <i class="far fa-circle"></i>
                            Me interesa
                        </button>
                        <button class="btn btn-warning w150p" v-show="followingStatus == 1" v-on:click="altFollow">
                            <i class="fas fa-check-circle"></i>
                            Te interesa
                        </button>
                    </div>
                    <?php else: ?>
                    <a class="btn btn-light w150p" href="<?= RCI_URL_APP ?>accounts/login_code">
                        <i class="far fa-circle"></i>
                        Me interesa
                    </a>
                    <?php endif; ?>
                </div>

            </div>

            <p class="mt-3">{{ user.about }}</p>

            <table class="table table-borderless">
                <tbody>
                    <tr>
                        <td class="color-text-1 text-end_">Temas de interés</td>
                        <td class="profile-info">{{ user.text_3 }}</td>
                    </tr>
                    <tr v-if="meta('obra_representativa')">
                        <td class="color-text-1 text-end_">Obra representativa</td>
                        <td class="profile-info">{{ meta('obra_representativa') }}</td>
                    </tr>
                    <tr v-if="meta('redes_culturales')">
                        <td class="color-text-1 text-end_">Redes culturales</td>
                        <td class="profile-info">{{ meta('redes_culturales') }}</td>
                    </tr>
                    <tr v-if="meta('proyecto_cultural_recomendado')">
                        <td class="color-text-1 text-end_">Proyecto cultural recomendado</td>
                        <td class="profile-info">{{ meta('proyecto_cultural_recomendado') }}</td>
                    </tr>
                    <tr v-if="meta('centro_cultural_recomendado')">
                        <td class="color-text-1 text-end_">Centro cultural recomendado</td>
                        <td class="profile-info">{{ meta('centro_cultural_recomendado') }}</td>
                    </tr>

                </tbody>
            </table>
        </div>
        <div class="col-md-6">
            <h4>
                <span class="resaltar-1">Redes sociales</span>
            </h4>
            <div class="col-md-5 my-3">
                <div class="d-flex mt-3">
                    <a v-for="meta in metadata.filter(m => m.type.startsWith('url_'))" :href="urlSocial(meta)"
                        target="_blank" class="link-primary text-decoration-none" v-show="meta.text_1.length">
                        <img :src="`<?= URL_RESOURCES ?>templates/redcultural/social/${meta.type.replace('url_', '')}.svg`"
                            :alt="meta.type" width="30" height="30" class="me-2">
                    </a>
                </div>
            </div>

            <h4>
                <span class="resaltar-1">Mis recomendados</span>
            </h4>
            <table class="table table-sm" style="background-color: white;">
                <tbody>
                    <tr v-if="meta('libro_autor')">
                        <td style="width: 40px;">
                            <i class="bi bi-book color-text-1 icon-recomendado" title="Libro"></i>
                        </td>
                        <td class="text-muted align-middle">Libro</td>
                        <td class="align-middle">
                            {{ meta('libro_autor') }}
                        </td>
                    </tr>
                    <tr v-if="meta('cancion')">
                        <td>
                            <i class="bi bi-music-note-beamed color-text-1 icon-recomendado" title="Canción"></i>
                        </td>
                        <td class="text-muted align-middle">Canción</td>
                        <td class="align-middle">{{ meta('cancion') }}</td>
                    </tr>
                    <tr v-if="meta('pelicula')">
                        <td>
                            <i class="bi bi-film color-text-1 icon-recomendado" title="Película"></i>
                        </td>
                        <td class="text-muted align-middle">Película</td>
                        <td class="align-middle">{{ meta('pelicula') }}</td>
                    </tr>
                    <tr v-if="meta('obra_artistica')">
                        <td><i class="bi bi-palette color-text-1 icon-recomendado" title="Obra artística"></i></td>
                        <td class="text-muted align-middle">Obra artística</td>
                        <td class="align-middle">{{ meta('obra_artistica') }}</td>
                    </tr>
                    <tr v-if="meta('recomendado')">
                        <td><i class="bi bi-person-heart color-text-1 icon-recomendado" title="Obra artística"></i></td>
                        <td class="text-muted align-middle">A quién seguir</td>
                        <td class="align-middle">{{ meta('recomendado') }}</td>
                    </tr>
                </tbody>

            </table>

            <h4>
                <span class="resaltar-1">Mis preguntas</span>
            </h4>

            <div class="mt-4">
                <h5>
                    <span class="resaltar-2" style="font-size: 0.8em;">Pregunta frente a los retos de la cultura en Iberoamérica</span>
                </h5>
                <p class="text-center text-italic" title="Pregunta que propone para el encuentro">
                    <p class="mb-0 color-text-9">{{ meta('pregunta_retos') }}</p>
                </p>
            </div>
            <div class="mt-4">
                <h5>
                    <span style="font-size: 0.8em;" class="resaltar-2">
                        Pregunta propuesta para guiar las conversaciones del Encuentro
                    </span>
                </h5>
                <p class="" title="Pregunta que propone para el encuentro">
                    <p class="mb-0 color-text-9">{{ meta('pregunta_conversaciones') }}</p>
                </p>
            </div>
            
        </div>
    </div>
    <div class="px-3">





    </div>
</div>

<?php $this->load->view('redcultural/invitados/perfil/vue_v') ?>