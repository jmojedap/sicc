<style>
    .embedFrame {
        height: calc(100vh - 130px);
        width: 100%
    }
</style>

<div class="container">
    <?php if ( $row->tipo_archivo == 10 ) : ?>
        <iframe src="<?= $row->url_contenido ?>" frameborder="0" class="embedFrame"></iframe>
    <?php elseif ($row->tipo_archivo == 30) : ?>
        <iframe src="<?= $row->url_contenido ?>" frameborder="0" class="embedFrame"></iframe>
    <?php else: ?>
        <div class="center_box_450">
            <div class="alert alert-info">
                No existe ningún archivo de contenido asignado
            </div>
        </div>
    <?php endif; ?>
</div>

