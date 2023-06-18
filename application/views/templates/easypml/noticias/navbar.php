<script src="<?= URL_RESOURCES ?>config/easypml/menus/nav_1_elements_noticias.js"></script>

<div id="navbarApp">
    <nav class="navbar fixed-top navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="<?= URL_APP ?>noticias/inicio">
                <img class="d-block" src="<?= URL_BRAND ?>logo-navbar.png" alt="Logo App" style="height: 30px;">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item" v-for="(element, i) in elements" v-bind:class="{'dropdown': element.subelements.length }">
                        <a v-if="element.subelements.length == 0" class="nav-link" href="#"
                                v-bind:class="{'active': element.active }"
                                v-on:click="nav1Click(i)">
                            {{ element.text }}
                        </a>
                        <a v-else v-on:click="nav1Click(i)"
                            class="nav-link dropdown-toggle" href="#" role="button"
                            v-bind:class="{'active': element.active }"
                            data-bs-toggle="dropdown" aria-expanded="false"
                            >
                            {{ element.text }}
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdownNav" v-if="element.subelements.length > 0">
                            <li v-for="(subelement, j) in element.subelements">
                                <a class="dropdown-item" href="#" 
                                    v-bind:class="{ 'active': subelement.active }"
                                    v-on:click="nav1ClickSub(i,j)">
                                    {{ subelement.text }}
                                </a>
                            </li>
                        </ul>
                    </li>
                </ul>
                <ul class="navbar-nav -2 mb-lg-0">
                    <?php if ( $this->session->userdata('username') ) : ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <?= $this->session->userdata('username') ?>
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <li><a class="dropdown-item" href="<?= URL_APP . 'noticias/salir' ?>">Cerrar sesión</a></li>
                            </ul>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= URL_APP ?>accounts/login_link" role="button">
                                Ingresar
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>
</div>

<script>
//Activación inicial de elementos actuales
//-----------------------------------------------------------------------------
nav_1_elements.forEach(element => {
    //Activar elemento actual, si está en las secciones
    if ( element.sections.includes(app_cf) ) { element.active = true }
    //Activar subelemento actual, si está en las secciones
    if ( element.subelements )
    {
        element.subelements.forEach(subelement => {
            if ( subelement.sections.includes(app_cf) )
            {
                element.active = true
                subelement.active = true
            }
        })
    }
});

// VueApp
//-----------------------------------------------------------------------------
const navbarApp = createApp({
    data(){
        return{
            elements: nav_1_elements
        }
    },
    methods: {
        nav1Click: function(i){
            if ( this.elements[i].subelements.length == 0 )
            {
                this.elements.forEach(element => { element.active = false; });
                this.elements[i].active = true;
                if ( this.elements[i].anchor ) {
                    window.location = URL_APP + this.elements[i].cf;
                } else {
                    app_cf = this.elements[i].cf;
                    getSections('nav_1');
                }
            }
        },
        nav1ClickSub: function(i,j){
            //Activando elemento
            this.elements.forEach(element => { element.active = false; });
            this.elements[i].active = true;

            //Activando subelemento
            this.elements[i].subelements.forEach(subelement => { subelement.active = false; });
            this.elements[i].subelements[j].active = true;

            if ( this.elements[i].subelements[j].anchor ) {
                window.location = URL_APP + this.elements[i].subelements[j].cf;
            } else {
                //Cargando secciones
                app_cf = this.elements[i].subelements[j].cf;
                getSections('nav_1');
            }
        },
        logout: function(){
            axios.get(URL_API + 'accounts/logout/ajax')
            .then(response => {
                if ( response.data.status == 1 ) {
                    window.location = URL_APP + 'accounts/login'
                }
            })
            .catch(function(error) { console.log(error)} )
        },
    },
    mounted(){
        //this.getList()
    }
}).mount('#navbarApp')
</script>
