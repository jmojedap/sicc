<style>
    .easy-sidebar{
        padding: 0.5em;
    }

    .easy-sidebar ul {
        list-style-type: none;
        font-size: 1em;
        padding-left: 0rem;
    }

    .easy-sidebar ul a.menu-link {
        margin-top: 0.5em;
        border-top: 1px solid #f0f4f9;
        padding: 0.5em 1em;
        color: #666;
        display: block;
        font-size: 0.7em;
        text-transform: uppercase;
        font-weight: bold;
    }

    .easy-sidebar ul a.submenu-link {
        padding: 0.2em 0em 0.2em 1em;
        background-color: #FEFEFE;
        color: #666;
        display: block;
        border-radius: 12px;
        font-size: 0.9em;
    }

    .easy-sidebar ul a.submenu-link:hover {
        background-color: #f0f4f9;
    }

    .easy-sidebar ul a.submenu-link.active {
        background-color: #d3e3fd;
    }


    .easy-sidebar .menu-element{

    }
</style>

<div id="sidebarApp">
    <div class="offcanvas offcanvas-start" data-bs-scroll="true" data-bs-backdrop="false" tabindex="-1"
        id="offcanvasScrolling" aria-labelledby="offcanvasScrollingLabel">
        <div class="offcanvas-header">
            <div class="d-flex">
                <img src="<?= URL_RESOURCES ?>brands/sicc/logo-navbar.png" class="w30p me-2" alt="Logo Prototipos SICC">
                <h5 class="offcanvas-title" id="offcanvasScrollingLabel">Prototipos SICC</h5>
            </div>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="easy-sidebar offcanvas-body">
            <ul class="sidebar-menu">
                <li class="" v-for="(element, i) in elements" v-bind:class="{'dropdown': element.subelements.length }">
                    <a v-if="element.subelements.length == 0" class="menu-link" href="#"
                            v-bind:class="{'active': element.active }"
                            v-on:click="navClick(i)">
                        {{ element.text }}
                    </a>
                    <a v-else v-on:click="navClick(i)" class="menu-link" href="#" role="button"
                        v-bind:class="{'active': element.active }"
                        data-bs-toggle="dropdown" aria-expanded="false"
                        >
                        {{ element.text }}
                    </a>
                    <ul class=""  v-if="element.subelements.length > 0">
                        <li v-for="(subelement, j) in element.subelements">
                            <a class="submenu-link" href="#" 
                                v-bind:class="{ 'active': subelement.active }"
                                v-on:click="navClickSub(i,j)">
                                {{ subelement.text }}
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
            <ul class="sidebar-menu">
                <?php if ( $this->session->userdata('user_id') ) : ?>
                    <a class="menu-link" href="#" role="button">
                        <?= $this->session->userdata('username') ?>
                    </a>
                    <ul>
                        <li><a class="submenu-link" href="<?= URL_APP . 'accounts/profile' ?>">Mi cuenta</a></li>
                        <?php if ( in_array($this->session->userdata('role'), array(1,2,3)) ) { ?>
                            <li><a class="submenu-link" href="<?= URL_ADMIN . 'users/explore' ?>">Administración</a></li>
                        <?php } ?>
                        <li><a class="submenu-link" href="<?= URL_APP . 'accounts/logout' ?>">Cerrar sesión</a></li>
                    </ul>
                <?php else: ?>
                    <a class="menu-link">
                        Usuario
                    </a>
                    <li>
                        <a class="submenu-link" href="<?= URL_APP ?>accounts/login_code" role="button">
                            Ingresar
                        </a>
                    </li>                     
                <?php endif; ?>
            </ul>
        </div>
    </div>    
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
const sidebarApp = createApp({
    data(){
        return{
            elements: nav_1_elements
        }
    },
    methods: {
        navClick: function(i){
            if ( this.elements[i].subelements.length == 0 )
            {
                this.elements.forEach(element => { element.active = false; });
                this.elements[i].active = true;
                if ( this.elements[i].anchor ) {
                    window.location = URL_APP + this.elements[i].cf;
                } else {
                    app_cf = this.elements[i].cf;
                    loadSections('nav_1');
                }
            }
        },
        navClickSub: function(i,j){
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
                loadsections('nav_1');
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
    }
}).mount('#sidebarApp')
</script>

