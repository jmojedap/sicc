<?php
//Evitar errores de definición de variables e índices de arrays, 2013-12-07
    ini_set('display_errors', 1);
    ini_set('error_reporting', E_ERROR);
?>
<!doctype html>
<html lang="es">
    <head>
        <?php $this->load->view('templates/redcultural/main/head') ?>
        <?php if ( ENV == 'production' ) : ?>
            <?php $this->load->view('common/google_analytics_v.php') ?>
        <?php endif; ?>
    </head>
    <body class="">
        <?php $this->load->view('templates/redcultural/main/navbar') ?>
        <div class="container-">
            <div id="page_title">
                <?php if ( isset($page_title) ) : ?>
                    <h2 class="text-center"><?= $page_title ?></h2>
                <?php endif; ?>
            </div>
            <div id="nav_2">
                <?php if ( isset($nav_2) ) $this->load->view($nav_2); ?>
            </div>

            <div id="nav_3">
                <?php if ( isset($nav_3) ) $this->load->view($nav_3); ?>
            </div>

            <div class="text-center my-3" id="loading_indicator" style="display: none;">
                <div class="spinner-border text-secondary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>

            <?php if ( isset($breadcrumb) ) : ?>
                <?php $this->load->view('templates/redcultural/main/breadcrumb') ?>
            <?php endif; ?>

            <div id="view_a">
                <?php $this->load->view($view_a); ?>
            </div>

            <div id="view_b">
                <?php if ( isset($view_b) ) $this->load->view($view_b); ?>
            </div>
        </div>

        <?php $this->load->view('templates/redcultural/main/script') ?>
    </body>
</html>