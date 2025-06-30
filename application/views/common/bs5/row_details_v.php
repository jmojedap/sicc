<div class="center_box_920">
    <table class="table bg-white">
        <tbody>
            <?php foreach ( $row as $field_name => $field_value ) : ?>
                <tr>
                    <td class="td-title"><?= str_replace('_', ' ', $field_name) ?></td>
                    <td class="text-break"><?= $field_value ?></td>
                </tr>
            <?php endforeach ?>
        </tbody>
    </table>
</div>