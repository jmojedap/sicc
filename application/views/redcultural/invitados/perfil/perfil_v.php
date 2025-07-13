<div id="pefilApp" class="center_box_750 my-4">
    <div class="card shadow">
        <div class="card-body">
            <div class="row">
                <div class="col-md-3">
                    <img
                        v-bind:src="`<?= URL_CONTENT ?>redcultural/images/profiles/` + user['username'] + `.jpg`"
                        class="rounded"
                        v-bind:alt="`Imagen de ` + user.display_name"
                        width="100%"
                        v-bind:onerror="`this.src='<?= URL_CONTENT ?>redcultural/images/default/user.png'`"
                    >
                </div>
                <div class="col-md-9">
                    <h2 class="card-title">
                        {{ user.display_name }}
                        <span class="ms-2">{{ banderaEmoji(user.text_1) }}</span>
                    </h2>
                    <p class="text-muted mb-1">{{ user.job_role }}</p>
                    <p><small class="text-muted ">{{ user.team_1 }}</small></p>
                    <p class="fst-italic">"{{ user.text_2 }}"</p>
                    
                </div>
                
            </div>
            <p class="mt-3">{{ user.about }}</p>

            <div class="mb-3">
                <strong>Temas de interés:</strong> {{ user.text_3 }}
            </div>

            <hr>

            <div class="row">
                <div class="col-md-6">
                    <h5>Redes sociales</h5>
                    <ul class="list-unstyled">
                        <li v-for="meta in metadata.filter(m => m.type.startsWith('url_'))" :key="meta.id">
                            <a :href="meta.text_1" target="_blank" class="link-primary">
                                {{ meta.type.replace('url_', '').toUpperCase() }}
                            </a>
                        </li>
                        <li v-if="metadata.find(m => m.type === 'url_web')">
                            <a :href="metadata.find(m => m.type === 'url_web').text_1" target="_blank">Sitio web personal</a>
                        </li>
                    </ul>
                </div>

                <div class="col-md-6">
                    <h5>Recomendaciones</h5>
                    <ul class="list-group">
                        <li class="list-group-item" v-if="meta('libro_autor')">📘 {{ meta('libro_autor') }}</li>
                        <li class="list-group-item" v-if="meta('cancion')">🎵 {{ meta('cancion') }}</li>
                        <li class="list-group-item" v-if="meta('pelicula')">🎬 {{ meta('pelicula') }}</li>
                        <li class="list-group-item" v-if="meta('obra_artistica')">🖼️ {{ meta('obra_artistica') }}</li>
                        <li class="list-group-item" v-if="meta('recomendado')">👤 Recomendado: {{ meta('recomendado') }}</li>
                    </ul>
                </div>
            </div>

            <div class="mt-4">
                <h5>Pregunta para el evento</h5>
                <blockquote class="blockquote">
                    <p class="mb-0">{{ meta('pregunta') }}</p>
                </blockquote>
            </div>
        </div>
    </div>
</div>

<script>
var pefilApp = createApp({
    data(){
        return{
            loading: false,
            fields: {},
            user: <?= json_encode($row) ?>,
            metadata: <?= json_encode($metadata->result()) ?>,
        }
    },
    methods: {
        meta(tipo) {
            const entry = this.metadata.find(m => m.type === tipo);
            return entry ? entry.text_1 : null;
        },
        banderaEmoji(codigoISO) {
            if (!codigoISO || codigoISO.length !== 2) return '';
            const codePoints = [...codigoISO.toUpperCase()].map(
                c => 127397 + c.charCodeAt()
            );
            return String.fromCodePoint(...codePoints);
        }
    },
    mounted(){
        //this.getList()
    }
}).mount('#pefilApp')
</script>