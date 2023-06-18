<?php
//Evitar errores de definición de variables e índices de arrays, 2013-12-07
    ini_set('display_errors', 1);
    ini_set('error_reporting', E_ERROR);
?>

<!doctype html>
<html lang="es">

<head>
    <?php $this->load->view('templates/easypml/main/head') ?>
</head>

<body style="padding-top: 0.5em;">
    <?php //$this->load->view('templates/easypml/noticias/navbar') ?>
    <div class="container">
        <div id="view_a">
            <?php $this->load->view($view_a); ?>
        </div>
    </div>

    <?php $this->load->view('templates/easypml/main/script') ?>
</body>

</html>