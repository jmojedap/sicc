<?php
    $opciones = [];
    if ( strlen($row->opciones_json) ) {
        $opciones = json_decode($row->opciones_json,TRUE);
    }
?>

<div class="row">
    <div class="col-md-4">
        <table class="table bg-white">
            <tbody>
                <tr>
                    <td></td>
                    <td><a href="<?= URL_ADMIN . "variables/ver/{$row->id}/" ?>" class="btn btn-sm btn-light w120p" target="_blank">Abrir</a></td>
                </tr>
                <tr>
                    <td>ID</td>
                    <td><?= $row->id ?></td>
                </tr>
                <tr>
                    <td>ID Medición</td>
                    <td><?= $row->medicion_id ?></td>
                </tr>
                <tr>
                    <td>Código pregunta</td>
                    <td><strong class="text-primary"><?= $row->codigo ?></strong></td>
                </tr>
                <tr>
                    <td>Nombre variable</td>
                    <td><?= $row->nombre ?></td>
                </tr>
                <tr>
                    <td>Temática</td>
                    <td><?= $row->tematica_id ?></td>
                </tr>
                <tr>
                    <td>Subtemática</td>
                    <td><?= $row->subtematica_id ?></td>
                </tr>
                <tr>
                    <td>Rol pregunta</td>
                    <td><?= $row->rol ?> &middot; <?= $this->Item_model->name(155, $row->rol) ?> </td>
                </tr>
            </tbody>
        </table>

        <table class="table bg-white">
            <tbody>
                <tr>
                    <td>Actualizado por</td>
                    <td><?= $row->updater_id ?> &middot; <?= $this->App_model->name_user($row->updater_id, 'u') ?></td>
                </tr>
                <tr>
                    <td>Actualizado</td>
                    <td><?= $row->updated_at ?> &middot; <?= $this->pml->ago($row->updated_at) ?></td>
                </tr>
                <tr>
                    <td>Creador</td>
                    <td><?= $row->creator_id ?> &middot; <?= $this->App_model->name_user($row->creator_id, 'u') ?></td>
                </tr>
                <tr>
                    <td>Creado</td>
                    <td><?= $row->created_at ?> &middot; <?= $this->pml->ago($row->created_at) ?></td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="col-md-8">
        <div class="card mw750p">
            <div class="card-body">
                <div class="text-center">
                    <h2><span class="badge badge-primary mr-1"><?= $row->etiqueta_1 ?></span></h2>
                    <h3><?= $row->enunciado_1 ?></h3>
                    <?php if ( strlen($row->url_imagen) > 0 ) : ?>
                        <div class="border">
                            <img src="<?= $row->url_imagen ?>" alt="Imagen pregunta" class="w360p">
                        </div>
                    <?php endif; ?>
                    <?php if ( count($opciones) ) : ?>
                        <table class="table">
                            <tbody>
                                <?php foreach ( $opciones as $cod_opcion => $etiqueta_opcion ) : ?>
                                    <tr>
                                        <td width="10px"><?= $cod_opcion ?></td>
                                        <td class="text-left"><?= $etiqueta_opcion ?></td>
                                    </tr>
                                <?php endforeach ?>
                            </tbody>
                        </table>
                    <?php endif; ?>
                    <h5 class="text-muted"><?= $row->instruccion ?></h5>
                </div>
                <hr>
                <div>
                    <h4 class="text-muted">Descripción</h4>
                    <?= $row->descripcion ?>
                </div>                
                <div>
                    <h4 class="text-muted">Palabras clave</h4>
                    <?= $row->palabras_clave ?>
                </div>
            </div>
        </div>
    </div>
</div>