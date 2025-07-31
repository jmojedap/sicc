<div id="followingApp">
    <div class="center_box_750 mb-3">
        <ul class="nav nav-pills justify-content-center">
            <li class="nav-item pointer" v-for="sectionItem in sections" :key="sectionItem.name" v-on:click="setSection(sectionItem.name)">
                <a class="nav-link" v-bind:class="{'active': sectionItem.active == true }">{{ sectionItem.title }}</a>
            </li>
        </ul>
    </div>
    <div class="center_box_750" v-show="sections[0].active">
        <h4 class="text-center">Tengo interés cultural por <span class="badge bg-main">{{ following.length }}</span></h4>
        <div class="d-flex mb-3 justify-content-between" v-for="user in following" :key="user.id">
            <div class="d-flex">
                <div>
                    <a v-bind:href="`<?= RCI_URL_APP ?>invitados/perfil/` + user.id + `/` + user.username">
                        <img v-bind:src="`<?= URL_CONTENT ?>redcultural/images/profiles/` + user['username'] + `.jpg`"
                            alt="Foto de perfil" class="rounded-circle me-3"
                            style="width: 64px; height: 64px; object-fit: cover"
                            onerror="this.src='<?= URL_IMG ?>users/user.png'">
                    </a>
                </div>
                <div class="d-flex">
                    <div class="flex-grow-1">
                        <a :href="`<?= RCI_URL_APP ?>invitados/perfil/` + user.id + `/` + user.username">
                            <h5 class="mb-0">{{ user.display_name }}</h5>
                        </a>
                        <div v-if="user.pais_origen" class="mt-1">
                            <img :src="paisFlag(user.pais_origen)" :alt="user.pais_origen" width="20" class="me-1"
                                :title="user.pais_origen">
                            <span class="text-muted_ small">{{ paisTo(user.pais_origen) }}</span>
                        </div>
                    </div>
                </div>
            </div>
            <div>

                <button class="btn btn-light btn-sm" v-on:click="altFollow(user)">
                    Quitar
                </button>
            </div>
        </div>
    </div>

    <div class="center_box_750" v-show="sections[1].active">
        <h4 class="text-center">Soy de interés cultural para <span class="badge bg-main">{{ followers.length }}</span></h4>
        <div class="d-flex mb-3 justify-content-between" v-for="user in followers" :key="user.id">
            <div class="d-flex">
                <div>
                    <a v-bind:href="`<?= RCI_URL_APP ?>invitados/perfil/` + user.id + `/` + user.username">
                        <img v-bind:src="`<?= URL_CONTENT ?>redcultural/images/profiles/` + user['username'] + `.jpg`"
                            alt="Foto de perfil" class="rounded-circle me-3"
                            style="width: 64px; height: 64px; object-fit: cover"
                            onerror="this.src='<?= URL_IMG ?>users/user.png'">
                    </a>
                </div>
                <div class="d-flex">
                    <div class="flex-grow-1">
                        <a :href="`<?= RCI_URL_APP ?>invitados/perfil/` + user.id + `/` + user.username">
                            <h5 class="mb-0">{{ user.display_name }}</h5>
                        </a>
                        <div v-if="user.pais_origen" class="mt-1">
                            <img :src="paisFlag(user.pais_origen)" :alt="user.pais_origen" width="20" class="me-1"
                                :title="user.pais_origen">
                            <span class="text-muted_ small">{{ paisTo(user.pais_origen) }}</span>
                        </div>
                    </div>
                </div>
            </div>
            <div>

            </div>
        </div>
    </div>

    <div class="center_box_750" v-show="sections[2].active">
        <h4 class="text-center">Hay interés cultural mutuo con <span class="badge bg-main">{{ matches.length }}</span></h4>
        <div class="d-flex mb-3 justify-content-between" v-for="user in matches" :key="user.id">
            <div class="d-flex">
                <div>
                    <a v-bind:href="`<?= RCI_URL_APP ?>invitados/perfil/` + user.id + `/` + user.username">
                        <img v-bind:src="`<?= URL_CONTENT ?>redcultural/images/profiles/` + user['username'] + `.jpg`"
                            alt="Foto de perfil" class="rounded-circle me-3"
                            style="width: 64px; height: 64px; object-fit: cover"
                            onerror="this.src='<?= URL_IMG ?>users/user.png'">
                    </a>
                </div>
                <div class="d-flex">
                    <div class="flex-grow-1">
                        <a :href="`<?= RCI_URL_APP ?>invitados/perfil/` + user.id + `/` + user.username">
                            <h5 class="mb-0">{{ user.display_name }}</h5>
                        </a>
                        <div v-if="user.pais_origen" class="mt-1">
                            <img :src="paisFlag(user.pais_origen)" :alt="user.pais_origen" width="20" class="me-1"
                                :title="user.pais_origen">
                            <span class="text-muted_ small">{{ paisTo(user.pais_origen) }}</span>
                        </div>
                    </div>
                </div>
            </div>
            <div>

            </div>
        </div>
    </div>
</div>


<?php $this->load->view('redcultural/invitados/me_interesa/vue_v') ?>