<?php
    $quan_not_imported = count($results) - $qty_imported;

    $status_icons = array(
        0 => 'fa fa-exclamation-triangle',
        1 => 'fa fa-check-circle'
    );

    $status_cl = array(
        0 => 'warning',
        1 => 'success'
    );

    $status_text = array(
        0 => 'No',
        1 => 'Sí'
    );
?>

<a href="<?= URL_ADMIN . $back_destination ?>" class="btn btn-secondary">
    <i class="fa fa-arrow-circle-left"></i>
    Volver
</a>

<h4>Resultado importación</h4>

<table class="table bg-white">
    <tbody>
        <tr>
            <td>Nombre hoja cálculo</td>
            <td width="50px"></td>
            <td><?= $sheet_name ?></td>
        </tr>
        <tr>
            <td>Filas encontradas</td>
            <td><i class="fa fa-info-circle text-info"></i></td>
            <td><?= count($results) ?></td>
        </tr>
        <tr>
            <td>Filas importadas</td>
            <td><i class="fa fa-check-circle text-success"></i></td>
            <td><?= $qty_imported ?></td>
        </tr>
        <tr class="<?= $class_not_imported ?>">
            <td>Filas no importadas</td>
            <td>
                <?php if ( $quan_not_imported > 0 ) { ?>
                    <i class="fa fa-exclamation-triangle text-warning"></i>
                <?php } ?>
            </td>
            <td>
                <?= $quan_not_imported ?>
            </td>
        </tr>
    </tbody>
</table>

<h5>Detalle por fila</h5>

<table class="table bg-white mt-2" id="table_results">
    <thead>
        <th width="50px">Fila</th>
        <th width="50px"></th>
        <th width="50px">Importada</th>
        <th>Descripción</th>
    </thead>
    <tbody>
        <?php foreach ( $results as $row_number => $result ) { ?>
            <tr>
                <td><?= $row_number ?></td>
                <td class="table-">
                    <i class="text-<?= $status_cl[$result['status']] ?> <?= $status_icons[$result['status']] ?>"></i>
                </td>
                <td>
                    <?= $status_text[$result['status']] ?>
                </td>
                <td><?= $result['text'] ?></td>
            </tr>
        <?php } ?>
    </tbody>
</table>