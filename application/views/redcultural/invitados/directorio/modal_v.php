<!-- Modal -->
<div class="modal fade" id="profileModal" tabindex="-1" aria-labelledby="profileModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content" v-if="currentElement">
            <div class="perfil">
                <div class="perfil-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="img-container">
                                <img :src="`<?= URL_CONTENT ?>redcultural/images/profiles/` + currentElement['username'] + `.jpg`"
                                    class="card-img-top object-fit-cover" :alt="currentElement.display_name"
                                    v-bind:onerror="`this.src='<?= URL_IMG ?>redcultural/user.png'`">
                                <div class="overlay-text">
                                    {{ (currentProfile['pregunta_retos'] || '').slice(0, 200) }}
                                    <span v-show="(currentProfile['pregunta_retos'] || '').length > 200">...</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex flex-column">
                                <div class="d-flex justify-content-between mb-3">
                                    <div>
                                        <a v-bind:href="`<?= RCI_URL_APP ?>invitados/abrir_perfil/` + currentElement['id'] + `/` + currentElement['username']"
                                            class="btn btn-main text-white me-2">
                                            Ver perfil completo
                                        </a>
                                        
                                        <?php if ( $this->session->userdata('logged') ) : ?>
                                            <button class="btn btn-warning w150p" v-show="!inFollowed && !loadingFollowing" v-on:click="altFollow">
                                                <i class="far fa-circle"></i>
                                                Me interesa
                                            </button>
                                            <button class="btn btn-primary w150p" v-show="inFollowed && !loadingFollowing" v-on:click="altFollow">
                                                <i class="fas fa-check-circle"></i>
                                                Te interesa
                                            </button>
                                            <button class="btn btn-light w150p" v-show="loadingFollowing">
                                                <i class="fas fa-spin fa-spinner"></i>
                                            </button>
                                        <?php else: ?>
                                            <a class="btn btn-warning w150p" href="<?= RCI_URL_APP ?>accounts/login_link">
                                                <i class="far fa-circle"></i>
                                                Me interesa
                                            </a>
                                        <?php endif; ?>

                                    </div>
                                    <button v-on:click="nextRandomProfile" title="Descubre un perfil aleatorio"
                                        class="btn btn-light w120p">
                                        Aleatorio <i class="fas fa-arrow-right"></i>
                                    </button>
                                </div>
                                <div class="">
                                    <h3 class="title">{{ currentElement['nombre_completo'] }}</h3>
                                    <p>
                                        {{ currentElement['rol_actividad'] }}
                                    </p>
                                    <p class="small">
                                        <img v-bind:src="paisFlag(currentElement['pais_origen'])"
                                            :alt="currentElement['pais_origen']" width="" height="auto">
                                            {{ currentElement['ciudad'] }} &middot;
                                        {{ paisTo(currentElement['pais_origen']) }}
                                    </p>
                                    <p>
                                        <span v-html="(currentElement['perfil'] || '').slice(0, 480)"></span>
                                        <span class="color-text-1" v-if="(currentElement['perfil'] || '').length > 480">...</span>
                                    </p>
                                    <span class="resaltar-2">Intereses</span>
                                    <br>
                                    <p>
                                        <span v-html="currentElement['intereses']"></span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
