<!-- Modal -->
<div class="modal fade" id="profileModal" tabindex="-1" aria-labelledby="profileModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="perfil">
                <div class="perfil-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="img-container">
                                <img :src="`<?= URL_CONTENT ?>redcultural/images/profiles/` + currentElement['username'] + `.jpg`"
                                    class="card-img-top object-fit-cover" :alt="currentElement.display_name"
                                    v-bind:onerror="`this.src='<?= URL_IMG ?>redcultural/user.png'`">
                                <div class="overlay-text">
                                    {{ currentProfile['pregunta_retos'].slice(0, 200) }}
                                    <span v-show="currentProfile['pregunta_retos'].length > 200">...</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex flex-column">
                                <div class="d-flex justify-content-between mb-3">
                                    <a v-bind:href="`<?= RCI_URL_APP ?>invitados/abrir_perfil/` + currentElement['id'] + `/` + currentElement['username']"
                                        class="btn btn-main w120p text-white me-2">
                                        Abrir
                                    </a>
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
                                        <span v-html="currentElement['perfil'].slice(0, 480)"></span>
                                        <span class="color-text-1" v-if="currentElement['perfil'].length > 480">...</span>
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