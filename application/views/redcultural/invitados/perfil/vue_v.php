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