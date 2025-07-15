<div id="loginApp" class="text-center center_box mw360p">
    <div class="alert alert-warning" v-show="activationKey.length > 0">
        <i class="fa fa-info-circle"></i><br>
        El enlace para ingresar ya no es válido. <br>Envía uno nuevo.
    </div>
    <form accept-charset="utf-8" method="POST" id="loginForm" @submit.prevent="handleSubmit">
        <input type="hidden" name="app_name" value="redcultural">
        <fieldset v-bind:disabled="loading">
            <p>
                Escribe tu correo electrónico y recibirás un link para acceder a
                <span class="text-primary"><?= RCI_APP_NAME ?></span>
            </p>
            <div class="mb-3">
                <input class="form-control form-control-lg" name="email" type="email" v-model="email"
                    placeholder="Correo electrónico" required title="Correo electrónico">
            </div>

            <div class="mb-3">
                <button type="submit" class="btn btn-main btn-lg w-100 mb-2"
                    title="Enviar link o enlace para acceder a la aplicación">
                    <i class="fas fa-spin fa-spinner" v-show="loading"></i>
                    Enviar link
                </button>
            </div>
        </fieldset>
    </form>

    <p><a v-bind:href="`<?= RCI_URL_APP  ?>` + link" v-show="link.length > 0">INGRESA AQUÍ</a></p>

    <div v-show="status >= 0">
        <div class="alert" v-bind:class="alertClass">
            {{ message }}
        </div>
    </div>
</div>

<script>
var loginApp = createApp({
    data() {
        return {
            loading: false,
            message: 'Probando mensaje de error',
            alertClass: 'alert-warning',
            status: -1,
            activationKey: '<?= $activation_key ?>',
            link: '',
            email: '',
        }
    },
    methods: {
        handleSubmit: function() {
            this.loading = true
            var formValues = new FormData(document.getElementById('loginForm'))
            axios.post(URL_API + 'accounts/get_login_link', formValues)
                .then(response => {
                    this.status = response.data.status
                    this.message = response.data.message;
                    this.link = response.data.link || ''
                    if (this.status == 1) {
                        this.alertClass = 'alert-info'
                        this.email = ''
                    }
                    this.loading = false
                })
                .catch(function(error) { console.log(error) })
        }
    },
}).mount('#loginApp')
</script>