<?php if ($metadata && $metadata->num_rows() > 0): ?>
    <div class="table-responsive">
        <table class="table table-bordered table-striped table-hover align-middle">
            <thead class="table-dark">
                <tr>
                    <?php foreach ((array) $metadata->row() as $campo => $valor): ?>
                        <th><?= htmlspecialchars($campo) ?></th>
                    <?php endforeach; ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($metadata->result() as $row): ?>
                    <tr>
                        <?php foreach ($row as $valor): ?>
                            <td><?= htmlspecialchars($valor) ?></td>
                        <?php endforeach; ?>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php else: ?>
    <div class="alert alert-warning">No se encontraron registros.</div>
<?php endif; ?>
</div>
