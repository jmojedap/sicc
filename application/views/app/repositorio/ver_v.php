<style>
    .embedFrame {
        height: calc(100vh - 130px);
        width: 100%
    }
</style>

<div class="container">
    <?php if ( $row->tipo_archivo == 10 ) : ?>
        <iframe src="<?= $row->url_contenido ?>" frameborder="0" class="embedFrame"></iframe>
    <?php elseif ($row->tipo_archivo == 20) : ?>
        <video width="640" height="360" controls>
            <source src="<?= $row->url_contenido ?>" type="video/mp4">
        </video>
    <?php elseif ($row->tipo_archivo == 30) : ?>
        <iframe src="<?= $row->url_contenido ?>" frameborder="0" class="embedFrame"></iframe>
    <?php elseif ($row->tipo_archivo == 50) : ?>
        <div class="card">
            <div class="card-body">
                <h3><?= $row->titulo ?></h3>
                <p><?= $row->descripcion ?></p>
                <a class="btn btn-light" href="<?= $row->url_contenido ?>" download>
                    <i class="fas fa-download"></i>
                    <?= $row->slug ?>.<?= $row->extension_archivo ?>
                </a>
            </div>
        </div>
    <?php else: ?>
        <div class="center_box_450">
            <div class="alert alert-info">
                Contenido no disponible
            </div>
        </div>
    <?php endif; ?>
</div>

