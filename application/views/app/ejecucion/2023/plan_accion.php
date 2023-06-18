<h2 class="text-center">Introducción</h2>
<p class="text-center">
    Este documento consiste en la definición de acciones orientadas al cumplimiento de las obligaciones del contrato 170 de 2023.
</p>
<p class="text-muted text-center">Febrero 27 de 2023</p>

<h2 class="text-center">Acciones</h2>
<?php foreach ( $arrAcciones['arr_sheet'] as $rowAccion ) : ?>
    <b class="text-primary">
        Acción <?= $rowAccion[1] ?>: <?= $rowAccion[3] ?>
    </b>
    <br>
    <p>
        <?= $rowAccion[4] ?>
    </p>
    <p>
        <span class="text-muted">Tipo: </span>
        <b><?= $rowAccion[2] ?></b>
        &middot;
        <span class="text-muted">Producto: </span>
        <b><?= $rowAccion[5] ?> (<?= $rowAccion[6] ?>)</b>
        &middot;
        <span class="text-muted">Obligación relacionada: </span>
        <b><?= $rowAccion[10] ?></b>
    </p>
    <hr>
<?php endforeach ?>

<p class="text-muted text-center">FIN DEL DOCUMENTO</p>