<div id="pefilApp" class="center_box_750 my-4">
    <div class="">
        <div class="mb-3">
            <a href="<?= RCI_URL_APP ?>invitados/directorio" class="btn btn-light btn-sm">
                <i class="fas fa-arrow-left"></i> Directorio
            </a>
        </div>
        <div class="row">
            <div class="col-md-3">
                <img v-bind:src="`<?= URL_CONTENT ?>redcultural/images/profiles/` + user['username'] + `.jpg`"
                    class="rounded-circle" v-bind:alt="`Imagen de ` + user.display_name" width="100%"
                    v-bind:onerror="`this.src='<?= URL_CONTENT ?>redcultural/images/default/user.png'`">
            </div>
            <div class="col-md-9">
                <h2 class="card-title color-text-0">
                    {{ user.display_name }}
                    <span class="ms-2">
                        <img v-bind:src="`https://flagcdn.com/w20/` + user.text_1.toLowerCase() + `.png`"
                                        :alt="user.text_1" width="" height="auto" :title="user.text_1">
                    </span>
                </h2>
                <p class="mb-1">{{ user.job_role }}</p>
                <p><small class="">{{ user.team_1 }}</small></p>
                <p class="fst-italic">"{{ user.text_2 }}"</p>

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

        <div class="mt-4 text-center">
            <!-- <h5>Pregunta para el evento</h5> -->
            <blockquote class="blockquote" title="Pregunta que propone para el encuentro">
                <p class="mb-0 color-text-9"><strong>{{ meta('pregunta') }}</strong></p>
            </blockquote>
        </div>

        <p class="mt-3">{{ user.about }}</p>

        <div class="mb-3 text-center">
            <strong>Temas de interés:</strong> {{ user.text_3 }}
        </div>

        

        <div class="row">
            <div class="col-md-4">
                <h5>Redes sociales</h5>
                <div class="d-flex">
                    <a v-for="meta in metadata.filter(m => m.type.startsWith('url_'))" :href="meta.text_1"
                        target="_blank" class="link-primary text-decoration-none" v-show="meta.text_1.length">
                        <img :src="`<?= URL_IMG ?>social_icons/${meta.type.replace('url_', '')}.png`"
                            :alt="meta.type" width="30" height="30" class="me-2">
                    </a>

                </div>
            </div>

            <div class="col-md-8">
                <h5>Mis recomendados</h5>
                <ul class="list-group_">
                    <li class="list-group-item" v-if="meta('libro_autor')">
                        <i class="fas fa-arrow-right color-text-1"></i> Libro: {{ meta('libro_autor') }}
                    </li>
                    <li class="list-group-item" v-if="meta('cancion')">
                        <i class="fas fa-arrow-right color-text-1"></i> Canción: {{ meta('cancion') }}
                    </li>
                    <li class="list-group-item" v-if="meta('pelicula')">
                        <i class="fas fa-arrow-right color-text-1"></i> Película: {{ meta('pelicula') }}
                    </li>
                    <li class="list-group-item" v-if="meta('obra_artistica')">
                        <i class="fas fa-arrow-right color-text-1"></i> Obra artística: {{ meta('obra_artistica') }}
                    </li>
                    <li class="list-group-item" v-if="meta('recomendado')">
                        <i class="fas fa-arrow-right color-text-1"></i> Seguir a: {{ meta('recomendado') }}
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<script>
var pefilApp = createApp({
    data() {
        return {
            appUid: APP_UID,
            loading: false,
            fields: {},
            user: <?= json_encode($row) ?>,
            metadata: <?= json_encode($metadata->result()) ?>,
            followingStatus: <?= json_encode($following_status) ?>,
        }
    },
    methods: {
        meta(tipo) {
            const entry = this.metadata.find(m => m.type === tipo);
            return entry ? entry.text_1 : null;
        },
        altFollow: function(){
            this.loading = true;
            axios.get(URL_API + 'users/alt_follow/' + this.user.id)
            .then(response => {
                //this.loading = false;
                this.followingStatus = response.data.status;
            })
            .catch(function(error) { console.log(error) })
        },
    },
}).mount('#pefilApp')
</script>