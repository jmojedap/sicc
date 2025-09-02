<!-- Modal -->
<div class="modal fade" id="profileModal" tabindex="-1" aria-labelledby="profileModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="perfil">
                <div class="perfil-body">
                    <div class="row">
                        <div class="col-md-6">
                            <img :src="`<?= URL_CONTENT ?>redcultural/images/profiles/` + currentElement['username'] + `.jpg`"
                                class="card-img-top object-fit-cover" :alt="currentElement.display_name"
                                v-bind:onerror="`this.src='<?= URL_IMG ?>redcultural/user.png'`">
                        </div>
                        <div class="col-md-6">
                            <h3 class="title">{{ currentElement['nombre_completo'] }}</h3>
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
                            <div>
                                <a v-bind:href="`<?= RCI_URL_APP ?>invitados/abrir_perfil/` + currentElement['id'] + `/` + currentElement['username']"
                                    class="btn btn-main w120p text-white">
                                    Ver más
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>