<?php $this->load->view('assets/recaptcha') ?>

<div id="loginApp" class="text-center center_box mw360p">
    <img class="w240p mb-3" src="<?= URL_BRAND ?>dogcc-1.png" alt="Logo aplicación">
    <p class="lead text-main">
        Estamos clasificando noticias
    </p>
    <p>¿Quieres ayudarnos?</p>
    
    <form accept-charset="utf-8" method="POST" id="loginForm" @submit.prevent="validateLogin">
        <fieldset v-bind:disabled="loading">
            <!-- Campo para validación Google ReCaptcha V3 -->
            <input type="hidden" id="g-recaptcha-response" name="g-recaptcha-response">

            <div class="mb-3 text-start">
                <label class="form-label" for="email">Correo electrónico</label>
                <input type="email" name="email" class="form-control" placeholder="nombre.apellido@scrd.gov.co" required>
            </div>

            <div class="mb-3 text-start">
                <label class="form-label" for="destination">Grupo noticias</label>
                <select v-model="destination" class="form-select">
                    <option value="noticias">Noticias sobre Bogotá</option>
                    <option value="noticias_afro">Noticias población Afro</option>
                </select>
            </div>
            
            <div class="mb-3">
                <button type="submit" class="btn btn-main w150p btn-lg btn-block">INICIAR</button>
            </div>
            <div class="text-center" v-show="loading">
                <div class="spinner-border text-secondary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>
        <fieldset>
    </form>

    <br/>
    
    <div id="messages" v-if="!status">
        <div class="alert alert-warning" v-for="message in messages">
            {{ message }}
        </div>
    </div>
</div>

<script>
var loginApp = createApp({
    data(){
        return{
            loading: false,
            messages: [],
            status: 1,
            destination: 'noticias',
        }
    },
    methods: {
        validateLogin: function(){
            this.loading = true
            var payload = new FormData(document.getElementById('loginForm'))
            axios.post(URL_APP + 'noticias/crear_sesion', payload)
            .then(response => {
                if ( response.data.status == 1 )
                {
                    window.location = URL_APP + this.destination + '/siguiente';
                } else {
                    this.messages = response.data.messages;
                    this.status = response.data.status;
                }
                if ( response.data.recaptcha != 1 ) {
                    this.reloadPage()
                }
            })
            .catch(function (error) { console.log(error) })
        },
        reloadPage: function(){
            toastr['info']('Se reiniciará el formulario...', 'ReCaptcha falló')
            setTimeout(() => {
                window.location = URL_APP + 'noticias/inicio'
            }, 3000);
        },
    },
}).mount('#loginApp')
</script>
