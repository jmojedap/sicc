<?php
    $contador_actividad = 0;

    $bitacora_actividad = array();
    foreach ($actividades->result() as $actividad) {
        $bitacora_actividad[$actividad->code] = array();
    }
    
    foreach ($bitacora->result() as $bactividad) {
        $bitacora_actividad[$bactividad->no_actividad][] = $bactividad;
    }
?>

<h2 class="text-center">Actividades plan de acción (<?= $actividades->num_rows() ?>)</h2>
<p>
    A continuación se presenta una descripción de cada actividad del plan de acción,
    el avance realizado, 
    el tipo de actividad, las subactividades realizadas y sus productos o evidencias.
</p>
<?php foreach ( $actividades->result() as $actividad ) : ?>
    <?php
        $contador_actividad++;
        $class_ejecutado = 'bg-warning';
        $pct_ejecutado = $actividad->pct_ejecutado;
        if ( $actividad->pct_ejecutado < 5 ) { $pct_ejecutado = 5; }
        if ( $actividad->pct_ejecutado > 25  ) $class_ejecutado = 'bg-warning';
        if ( $actividad->pct_ejecutado > 45  ) $class_ejecutado = 'bg-primary';
        if ( $actividad->pct_ejecutado > 80  ) $class_ejecutado = 'bg-success';
    ?>
    <div class="mb-3">
        <h3>
            <?= $contador_actividad ?>)
            <?= $actividad->titulo ?>
        </h3>
        <p><?= $actividad->detalle ?></p>
        <div class="progress my-2">
            <div class="progress-bar <?= $class_ejecutado ?>"
                role="progressbar"
                style="width: <?= $pct_ejecutado ?>%;"
                aria-valuenow="<?= $actividad->pct_ejecutado ?>"
                aria-valuemin="0" aria-valuemax="100">
                <?= $actividad->pct_ejecutado ?> %
            </div>
        </div>
        <span class="text-muted">Tipo: </span><?= $actividad->tipo_actividad ?>
        <p>
            Desde: <?= $this->pml->date_format($actividad->desde, 'Y-M-d') ?> &middot; 
            Hasta: <?= $this->pml->date_format($actividad->hasta, 'Y-M-d') ?>
        </p>
    
        <?php if ( count($bitacora_actividad[$actividad->code]) > 0 ) : ?>
            <h4>Subactividades y productos</h4>
            <?php $contador_bitacora = 0 ?>
            <?php foreach ( $bitacora_actividad[$actividad->code] as $bactividad ) : ?>
                <?php
                    $contador_bitacora++;
                ?>
                <div class="ps-3">
                    <p class="text-justify">
                        <strong>
                            <?= $contador_actividad ?>.<?= $contador_bitacora ?>.
                            <?= $bactividad->titulo ?>:
                        </strong>
                        <?= $bactividad->descripcion ?>
                        (<?= $this->pml->date_format($bactividad->fecha, 'Y-M-d'); ?>)
                    </p>
                    <?php if ( strlen($bactividad->link) > 0 ) : ?>
                        <p class="text-left">Link >> <a href="<?= $bactividad->link ?>"><?= $bactividad->link ?></a></p>
                    <?php endif; ?>
                    <?php if ( strlen($bactividad->radicado) > 0 ) : ?>
                        <p>Radicado: >> <?= $bactividad->radicado ?></p>
                    <?php endif; ?>
                </div>
            <?php endforeach ?>
        <?php else: ?>
            <p>Todavía no se han realizado actividades en este punto.</p>
        <?php endif; ?>
        <hr>
    </div>
<?php endforeach ?>