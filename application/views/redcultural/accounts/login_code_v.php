<div id="loginApp" class="text-center center_box mw360p">
    <a href="<?= RCI_URL_APP ?>"><img class="mw360p my-5" src="<?= RCI_URL_BRAND ?>logo-start.png" alt="Logo aplicación"></a>
    <form accept-charset="utf-8" method="POST" id="loginForm" @submit.prevent="handleSubmit" v-show="section == 'email'">
        <input type="hidden" name="app_name" value="redcultural">
        <fieldset v-bind:disabled="loading">
            <p>
                Escribe tu correo electrónico y recibirás un código de doce letras para acceder a
                <span class="text-primary"><?= RCI_APP_NAME ?></span>
            </p>
            <div class="mb-3">
                <input class="form-control form-control-lg" name="email" type="email" v-model="email"
                    placeholder="Correo electrónico" required title="Correo electrónico">
            </div>

            <div class="mb-3">
                <button type="submit" class="btn btn-primary btn-lg w-100 mb-2"
                    title="Enviar a tu correo electrónico un código para acceder a la aplicación">
                    <i class="fas fa-spin fa-spinner" v-show="loading"></i>
                    Solicitar código
                </button>
            </div>
        </fieldset>
    </form>
    <form accept-charset="utf-8" method="POST" id="accessCodeForm" @submit.prevent="submitAccessCode" v-show="section == 'accessCode'">
        <input type="hidden" name="app_name" value="redcultural">
        <input type="hidden" name="username" v-model="email">
        <fieldset v-bind:disabled="loading">
            <p>
                Pega o escribe aquí el código de 12 letras que recibiste en tu correo electrónico para acceder a
                <span class="text-primary"><?= RCI_APP_NAME ?></span>
            </p>
            <div class="mb-3">
                <input class="form-control form-control-lg text-center" name="access_code" type="text" v-model="accessCode"
                    placeholder="Código recibido" required title="Código de acceso">
            </div>

            <div class="mb-3">
                <button type="submit" class="btn btn-main btn-lg w-100 mb-2"
                    title="Validar código para acceder a la aplicación">
                    <i class="fas fa-spin fa-spinner" v-show="loading"></i>
                    INGRESAR
                </button>
            </div>
        </fieldset>
    </form>

    <p v-show="testAccessCode.length > 0">{{ testAccessCode }}</p>

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
            section: 'email',
            loading: false,
            message: '',
            alertClass: 'alert-warning',
            status: -1,
            accessCode: '',
            testAccessCode: '',
            email: '',
        }
    },
    methods: {
        handleSubmit: function() {
            this.loading = true
            var formValues = new FormData(document.getElementById('loginForm'))
            axios.post(URL_API + 'accounts/get_login_code', formValues)
                .then(response => {
                    this.status = response.data.status
                    this.message = response.data.message;
                    this.testAccessCode = response.data.access_code || ''
                    if (this.status == 1) {
                        this.alertClass = 'alert-info'
                        this.section = 'accessCode'
                    }
                    this.loading = false
                })
                .catch(function(error) { console.log(error) })
        },
        submitAccessCode: function(){
            this.loading = true
            var formValues = new FormData(document.getElementById('accessCodeForm'))
            axios.post(URL_API + 'accounts/validate_login_code/', formValues)
            .then(response => {
                if ( response.data.status == 1 )
                {
                    toastr['success'](response.data.message);
                    setTimeout(() => {
                        window.location = URL_APP + 'accounts/logged';
                    }, 2000);
                } else {
                    this.message = response.data.message;
                    this.status = response.data.status;
                    this.section = 'email'
                    this.loading = false
                }
            })
            .catch( function(error) {console.log(error)} )
        },
    },
}).mount('#loginApp')
</script>