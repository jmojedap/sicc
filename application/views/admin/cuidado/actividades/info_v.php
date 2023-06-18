<div class="row">
    <div class="col-md-4">
        <table class="table bg-white">
            <tbody>
                <tr>
                    <td>ID</td>
                    <td><?= $row->id ?></td>
                </tr>
                <tr>
                    <td>Nombre actividad</td>
                    <td><?= $row->nombre_actividad ?></td>
                </tr>
                <tr>
                    <td>Fecha</td>
                    <td><?= $this->pml->date_format($row->inicio, 'd-M-Y') ?></td>
                </tr>
                <tr>
                    <td>Horario</td>
                    <td>
                        <?= $this->pml->date_format($row->inicio, 'd-M-Y') ?>
                    </td>
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
        <div class="card">
            <div class="card-body">
                <h2><?= $row->nombre_actividad ?></h2>
                <div>
                    <h4 class="text-muted">excerpt</h4>
                    <?= $row->excerpt ?>
                </div>
                <hr>
                <div>
                    <h4 class="text-muted">content</h4>
                    <?= $row->content ?>
                </div>
                <hr>
                <div>
                    <h4 class="text-muted">content json</h4>
                    <?= $row->content_json ?>
                </div>
                <hr>
                <div>
                    <h4 class="text-muted">keywords:</h4>
                    <?= $row->keywords ?>
                </div>
            </div>
        </div>
    </div>
</div>