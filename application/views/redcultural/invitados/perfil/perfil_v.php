<style>
.resaltar-1 {
    background-color: #966DF2;
    color: #FFF;
    padding: 0rem 0.5rem;
    font-weight: bold;
}

.resaltar-2 {
    background-color: #FFDE46;
    color: #000;
    text-transform: uppercase;
    padding: 0rem 0.5rem;
    font-weight: bold;
}
</style>

<div id="pefilApp" class="center_box_750 my-4">
    <div class="px-3">
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
                    <span class="resaltar-1">{{ user.display_name }}</span>


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

        <div class="mt-4 text-center">
            <!-- <h5>Pregunta para el evento</h5> -->
            <blockquote class="blockquote" title="Pregunta que propone para el encuentro">
                <p class="mb-0 color-text-9"><strong>{{ meta('pregunta') }}</strong></p>
            </blockquote>
        </div>

        <p class="mt-3">{{ user.about }}</p>

        <table class="table table-borderless">
            <tbody>
                <tr>
                    <td class="color-text-1 text-end">Temas de interés</td>
                    <td>{{ user.text_3 }}</td>
                </tr>
                <tr v-if="meta('obra_representativa')">
                    <td class="color-text-1 text-end">Obra representativa</td>
                    <td>{{ meta('obra_representativa') }}</td>
                </tr>

            </tbody>
        </table>



        <div class="row">
            <div class="col-md-4 my-3">
                <h4 class="resaltar-2">
                    Redes
                </h4>
                <div class="d-flex mt-3">
                    <a v-for="meta in metadata.filter(m => m.type.startsWith('url_'))" :href="urlSocial(meta)"
                        target="_blank" class="link-primary text-decoration-none" v-show="meta.text_1.length">
                        <img :src="`<?= URL_RESOURCES ?>templates/redcultural/social/${meta.type.replace('url_', '')}.svg`"
                            :alt="meta.type" width="30" height="30" class="me-2">
                    </a>
                </div>
            </div>

            <div class="col-md-8 my-3">
                <h4>
                    <span class="resaltar-2">Mis recomendados</span>
                </h4>
                <table class="table table-sm">
                    <tbody>
                        <tr v-if="meta('libro_autor')">
                            <td><i class="fas fa-arrow-right color-text-1"></i></td>
                            <td class="color-text-1"><b>Libro</b></td>
                            <td>{{ meta('libro_autor') }}</td>
                        </tr>
                        <tr v-if="meta('cancion')">
                            <td><i class="fas fa-arrow-right color-text-1"></i></td>
                            <td class="color-text-1"><b>Canción</b></td>
                            <td>{{ meta('cancion') }}</td>
                        </tr>
                        <tr v-if="meta('pelicula')">
                            <td><i class="fas fa-arrow-right color-text-1"></i></td>
                            <td class="color-text-1"><b>Película</b></td>
                            <td>{{ meta('pelicula') }}</td>
                        </tr>
                        <tr v-if="meta('obra_artistica')">
                            <td><i class="fas fa-arrow-right color-text-1"></i></td>
                            <td class="color-text-1"><b>Obra artística</b></td>
                            <td>{{ meta('obra_artistica') }}</td>
                        </tr>
                        <tr v-if="meta('recomendado')">
                            <td><i class="fas fa-arrow-right color-text-1"></i></td>
                            <td class="color-text-1"><b>Seguir a</b></td>
                            <td>{{ meta('recomendado') }}</td>
                        </tr>
                    </tbody>

                </table>
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
        altFollow: function() {
            this.loading = true;
            axios.get(URL_API + 'users/alt_follow/' + this.user.id)
                .then(response => {
                    //this.loading = false;
                    this.followingStatus = response.data.status;
                })
                .catch(function(error) {
                    console.log(error)
                })
        },
        urlSocial: function(meta) {
            if (meta.type == 'url_instagram') {
                return 'https://www.instagram.com/' + meta.text_1
            }
            if (meta.type == 'url_x') {
                return 'https://www.x.com/' + meta.text_1
            }
            if (meta.type == 'url_facebook') {
                return 'https://www.facebook.com/search/top?q=' + this.slugText(this.user.display_name)
            }
            if (meta.type == 'url_youtube') {
                return 'https://www.youtube.com/user/' + meta.text_1
            }
            if (meta.type == 'url_linkedin') {
                return meta.text_1
            }
            if (meta.type == 'url_web') {
                return meta.text_1
            }

            return 'https://www.google.com/search?q=' + this.slugText(this.user.display_name)
        },
        slugText: function(texto) {
            // 1. Convertir a minúsculas
            let resultado = texto.toLowerCase();

            // 2. Separar palabras
            const palabras = resultado.trim().split(/\s+/); // separa por uno o más espacios

            // 3. Aplicar la transformación: primera palabra con +, las siguientes con -
            if (palabras.length === 0) return "";

            resultado = palabras[0]; // primera palabra (sin prefijo)
            for (let i = 1; i < palabras.length; i++) {
                if (i === 1) {
                    resultado += "+" + palabras[i];
                } else {
                    resultado += "-" + palabras[i];
                }
            }

            return resultado;
        },
    },
}).mount('#pefilApp')
</script>