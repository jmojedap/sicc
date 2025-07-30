<div class="main-navbar fixed-top d-flex justify-content-between align-items-center">
    <div class="d-flex">
        <a href="<?= RCI_URL_APP ?>observatorio/inicio">
            <img src="<?= RCI_URL_BRAND ?>logo-navbar.png" alt="Logo App" title="Inicio" class="app-logo">
        </a>
        <button class="navbar-button" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasScrolling" aria-controls="offcanvasScrolling">        
            <i class="fas fa-bars"></i>
        </button>
    </div>
    <?php if ( isset($page_title) ) : ?>
        <h1 class="page-title"><?= $page_title ?></h1>
    <?php else: ?>
        <h1 class="page-title"><?= $head_title ?></h1>
    <?php endif; ?>
    <div>
        <div class="only-lg">
            <?php if ( $this->session->userdata('user_id') ) : ?>
                <div class="">
                    <a class="main-navbar-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <?= $this->session->userdata('username') ?>
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <li><a class="dropdown-item" href="<?= RCI_URL_APP . 'accounts/profile' ?>">Mi cuenta</a></li>
                        <?php if ( in_array($this->session->userdata('role'), array(1,2,3)) ) { ?>
                            <li><a class="dropdown-item" href="<?= URL_ADMIN . 'users/explore' ?>">Administración</a></li>
                        <?php } ?>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="<?= RCI_URL_APP . 'accounts/logout' ?>">Cerrar sesión</a></li>
                    </ul>
                </div>
            <?php else: ?>
                <div class="">
                    <a class="main-navbar-link" href="<?= RCI_URL_APP ?>accounts/login_code" role="button">
                        Ingresar
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>