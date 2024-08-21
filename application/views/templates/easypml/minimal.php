<?php
//Evitar errores de definición de variables e índices de arrays, 2013-12-07
    ini_set('display_errors', 1);
    ini_set('error_reporting', E_ERROR);
?>
<!doctype html>
<html lang="es">
    <head>
        <?php $this->load->view('templates/easypml/main/head') ?>
        <?php $this->load->view('templates/easypml/minimal/style') ?>
        <?php if ( ENV == 'production' ) : ?>
            <?php $this->load->view('common/google_analytics_v.php') ?>
        <?php endif; ?>
    </head>
    <body>
        

        <?php $this->load->view('templates/easypml/minimal/navbar') ?>
        <?php $this->load->view('templates/easypml/minimal/sidebar') ?>

        <div id="nav_2">
            <?php if ( isset($nav_2) ) $this->load->view($nav_2); ?>
        </div>

        <div id="nav_3">
            <?php if ( isset($nav_3) ) $this->load->view($nav_3); ?>
        </div>

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