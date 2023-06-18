<div class="container center_box">
    <div class="row">
        <div class="col-md-8">
            <div class="card mb-2">
                <div class="card-body">
                    <div class="mb-2">
                        <a href="<?= URL_APP . "repositorio/explorar/?repo_tema={$row->tema_cod}" ?>">
                            <?= $this->Item_model->name(415, $row->tema_cod); ?>
                        </a>
                        <i class="fa fa-chevron-right"></i>
                        <a href="<?= URL_APP . "repositorio/explorar/?repo_subtema={$row->subtema_1}" ?>">
                            <?= $this->Item_model->name(416, $row->subtema_1); ?>
                        </a>
                    </div>
                    <div class="d-flex">
                        <div class="w240p me-2">
                            <a href="<?= URL_APP . "repositorio/ver/{$row->id}/{$row->slug}" ?>">
                                <img
                                    src="<?= $row->url_thumbnail ?>"
                                    class="rounded w100pc"
                                    alt="portada contenido"
                                    onerror="this.src='<?= URL_IMG ?>app/repo_contenido_nd.png'"
                                >
                            </a>
                        </div>    
                        <div>
                            <h2><?= $row->titulo ?></h2>
                            <h3 class="text-primary"><?= $row->anio_publicacion ?></h3>
                            <p>
                                <?= $row->descripcion ?>
                            </p>
                        </div>
                    </div>

                    <hr>
                    <p class="text-center">
                        <span class="text-muted">Palabras clave: </span>
                        <?= $row->palabras_clave ?>
                    </p>
                    <hr>
                    <div>
                        
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
        </div>
        <div class="col-md-4">
            <table class="table bg-white">
                <tbody>
                    <tr>
                        <td>ID</td>
                        <td><?= $row->id ?></td>
                    </tr>
                    <tr>
                        <td>Tipo</td>
                        <td><?= $row->tipo_contenido ?></td>
                    </tr>
                    <tr>
                        <td>Categoría</td>
                        <td><?= $row->categoria_contenido ?></td>
                    </tr>
                    <tr>
                        <td>Formato</td>
                        <td><?= $this->Item_model->name(410,$row->formato_cod); ?></td>
                    </tr>
                    <tr>
                        <td>URL al contenido</td>
                        <td>
                            <?php if ( strlen($row->url_contenido) > 0 ) : ?>
                            <a href="<?= $row->url_contenido ?>" target="_blank">
                                <?= substr($row->url_contenido,0,50) ?>...
                            </a>
                            <?php else: ?>
                            <span class="text-muted">No disponible</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                </tbody>
            </table>

            <table class="table bg-white">
                <tbody>

                    <tr>
                        <td>Actualizado por</td>
                        <td><?= $row->updater_id ?> &middot; <?= $this->App_model->name_user($row->updater_id, 'u') ?>
                        </td>
                    </tr>
                    <tr>
                        <td>Actualizado</td>
                        <td><?= $row->updated_at ?> &middot; <?= $this->pml->ago($row->updated_at) ?></td>
                    </tr>
                    <tr>
                        <td>Creador</td>
                        <td><?= $row->creator_id ?> &middot; <?= $this->App_model->name_user($row->creator_id, 'u') ?>
                        </td>
                    </tr>
                    <tr>
                        <td>Creado</td>
                        <td><?= $row->created_at ?> &middot; <?= $this->pml->ago($row->created_at) ?></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>