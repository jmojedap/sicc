<?php
//Evitar errores de definición de variables e índices de arrays, 2013-12-07
    ini_set('display_errors', 1);
    ini_set('error_reporting', E_ERROR);
?>
<!doctype html>
<html lang="es">
    <head>
        <?php $this->load->view('templates/cultured_bogota/main/head'); ?>
        <link rel="stylesheet" href="<?= URL_RESOURCES . 'templates/cultured_bogota/start.css' ?>">
    </head>
    <body>
        <?php $this->load->view('templates/cultured_bogota/main/navbar') ?>
        <div id="start_content" class="container text-center">
            <a href="<?= URL_APP ?>"><img class="w240p mb-2" src="<?= URL_BRAND ?>logo-start.png" alt="Logo aplicación"></a>
            <div class="center_box_320">
                <?php $this->load->view($view_a); ?>
            </div>
        </div>
        <?php $this->load->view('templates/cultured_bogota/main/script') ?>
    </body>
</html>