<header class="main-header">
    <!-- Logo -->
    <a href="<?= URL_FRONT ?>info/inicio/" class="logo fixed-top">
        <img src="<?= URL_BRAND ?>logo-admin.png" alt="Logo app" style="height: 40px;">
    </a>
    <nav class="navbar fixed-top" role="navigation">
        <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <i class="fa fa-bars"></i><span class="sr-only">Toggle navigation</span>
        </a>
        <h1 id="head_title"><?= substr($head_title, 0, 50) ?></h1>

        <div class="ml-auto">
            <div class="dropdown">
                <a href="#" data-toggle="dropdown">
                    <img src="<?= $this->session->userdata('picture') ?>" class="navbar-user-image" alt="User Image" onerror="this.src='<?= URL_IMG ?>users/sm_user.png'">
                </a>
                <ul class="dropdown-menu dropdown-menu-right">
                    <a class="dropdown-item" href="<?= URL_APP . 'accounts/profile' ?>">Mi cuenta</a>
                    <a class="dropdown-item" href="<?= URL_APP . 'accounts/logout' ?>">Cerrar sesión</a>
                </ul>
            </div>
        </div>
    </nav>
</header>