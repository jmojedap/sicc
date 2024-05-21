<?php
//Evitar errores de definición de variables e índices de arrays, 2013-12-07
    ini_set('display_errors', 1);
    ini_set('error_reporting', E_ERROR);
?>
<!doctype html>
<html lang="es">
    <head>
        <?php $this->load->view('templates/easypml/main/head') ?>
        <style>
            body{
                padding-top: 50px;
            }

            .main-navbar{
                height: 40px;
                background-color: #f5f5f5;
                padding: 0em 0.5em;
                margin-bottom: 1em;
                border-bottom: 1px solid #FAFAFA;
            }

            .main-navbar .navbar-button{
                display: block;
                background-color: #f5f5f5;
                border: 0px;
                padding: 0px 0.5em;
                text-align: center;
            }

            .main-navbar .navbar-button:hover{
                border: 0px;
                color: var(--color-main-app);
            }

            h1.page-title {
                text-align: start;
                color: var(--color-main-app);
                font-size: 1.1em;
                margin-bottom: 0px;
            }
        </style>
    </head>
    <body>
        <div class="main-navbar fixed-top d-flex justify-content-between align-items-center">
            <div class="d-flex">
                <button class="navbar-button" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasScrolling" aria-controls="offcanvasScrolling">        
                    <i class="fas fa-bars"></i>
                </button>
                <a href="<?= URL_APP ?>observatorio/inicio" class="navbar-button">
                    <i class="fas fa-home"></i>
                </a>
            </div>
            <?php if ( isset($page_title) ) : ?>
                <h1 class="page-title"><?= $page_title ?></h1>
            <?php else: ?>
                <h1 class="page-title"><?= $head_title ?></h1>
            <?php endif; ?>
            <div>
                Ingresar
            </div>
        </div>

        <?php $this->load->view('templates/easypml/minimal/sidebar') ?>

        <div class="container-fluid">
    
            <div class="text-center my-3" id="loading_indicator" style="display: none;">
                <div class="spinner-border text-secondary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>
    
            <?php if ( isset($breadcrumb) ) : ?>
                <?php $this->load->view('templates/easypml/main/breadcrumb') ?>
            <?php endif; ?>
    
            <div id="view_a">
                <?php $this->load->view($view_a); ?>
            </div>
            <?php $this->load->view('templates/easypml/main/script') ?>
        </div>
    </body>
</html>