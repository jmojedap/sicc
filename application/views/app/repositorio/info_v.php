<style>
.contenido-portada {
    border: 1px solid #EEE;
}

.contenido-portada:hover {
    border-color: #FFF;
}

.contenido-descriptor {
    width: 200px;
    height: 85px;
    border-right: 1px solid #D6d6d6;
    text-align: center;
}

.contenido-descriptor h5 {
    color: #636366;
    text-transform: uppercase;
    font-size: 0.9em;
    font-weight: bold;
}

.contenido-descriptor .contador {
    color: #636366;
    font-size: 1.5em;
    color: #1d1d1d;
    font-weight: bold;
}
</style>

<div class="center_box_920">
    <div class="p-1">
        <div class="d-flex">
            <div class="me-3 text-center">
                <a href="<?= URL_APP . "repositorio/ver/{$row->id}/{$row->slug}" ?>">
                    <img src="<?= $row->url_thumbnail ?>" class="rounded w320p mb-2 shadow-lg contenido-portada"
                        alt="portada contenido" onerror="this.src='<?= URL_IMG ?>app/repo_contenido_nd.png'">
                </a>
            </div>
            <div>
                <div class="mb-2">
                    <a href="<?= URL_APP . "repositorio/explorar/?repo_tema={$row->tema_cod}" ?>">
                        <?= $this->Item_model->name(415, $row->tema_cod); ?>
                    </a>
                    <i class="fa fa-chevron-right"></i>
                    <a href="<?= URL_APP . "repositorio/explorar/?repo_subtema={$row->subtema_1}" ?>">
                        <?= $this->Item_model->name(416, $row->subtema_1); ?>
                    </a>
                </div>
                <h2 class="h4"><?= $row->titulo ?></h2>
                <p>
                    <span class="badge bg-warning"><?= $row->anio_publicacion ?></span>
                </p>

                <p><?= $row->descripcion ?></p>
                <?php if ( strlen($row->url_contenido) == 0) : ?>
                    <?php if ( strlen($row->url_contenido_externo) > 0 ) : ?>
                        <a class="btn btn-light" href="<?= $row->url_contenido_externo ?>" target="_blank">
                            <i class="fa-solid fa-arrow-up-right-from-square"></i>
                            Disponible externo
                        </a>
                    <?php endif; ?>
                    <?php if ( strlen($row->url_carpeta_anexos) > 0 ) : ?>
                        <a class="btn btn-light" href="<?= $row->url_carpeta_anexos ?>" target="_blank">
                            <i class="fa-solid fa-arrow-up-right-from-square"></i> Anexos
                        </a>
                    <?php endif; ?>
                    
                <?php endif; ?>
            </div>
        </div>

        <hr>
        <h4>Palabras clave</h4>
        <p class="">
            <?= $row->palabras_clave ?>
        </p>

        <div class="d-flex justify-content-center">
            <div class="contenido-descriptor">
                <h5>Páginas</h5>
                <p class="contador"><?= $row->cantidad_paginas ?></p>
            </div>
            <div class="contenido-descriptor">
                <h5>Formato</h5>
                <p><?= $this->Item_model->name(410, $row->formato_cod);  ?></p>
            </div>
            <div class="contenido-descriptor">
                <h5>Categoría</h5>
                <p><?= $this->Item_model->name(413, $row->categoria_contenido);  ?></p>
            </div>
            <div class="contenido-descriptor">
                <h5>Metodología</h5>
                <p><?= $this->Item_model->name(414, $row->metodologia_cod);  ?></p>
            </div>
        </div>

        <hr>
        <p>
            <span class="text-muted">Investigadores:</span>
            <span class="text-primary">
                <?= $row->investigadores; ?>
            </span>
        </p>
    </div>
</div>