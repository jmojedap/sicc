<div class="center_box_750">
    <div class="text-center">
        <img src="<?= URL_CONTENT . 'redcultural/images/profiles/' . $row->username . '.jpg' ?>"
            alt="Imagen de perfil del usuario" class="w120p rounded rounded-circle border mb-2"
            onerror="this.src='<?= URL_IMG ?>users/user.png'">
        <!-- <a href="<?= URL_APP . "accounts/edit/image" ?>">
        </a> -->
    </div>
    <table class="table bg-white">
        <tbody>
            <tr>
                <td class="text-end" width="33%"><span class="text-muted">Nombre</span></td>
                <td><?= $row->display_name ?></td>
            </tr>

            <tr>
                <td class="text-end"><span class="text-muted">Nombre de usuario</span></td>
                <td><?= $row->username ?></td>
            </tr>

            <tr>
                <td class="text-end"><span class="text-muted">Correo electrónico</span></td>
                <td><?= $row->email ?></td>
            </tr>
            <tr>
                <td class="text-end"><span class="text-muted">Teléfono</span></td>
                <td><?= $row->phone_number ?></td>
            </tr>

            <tr>
                <td class="text-end"><span class="text-muted">País</span></td>
                <td><?= $row->text_1 ?></td>
            </tr>
            <tr>
                <td class="text-end"><span class="text-muted">Institución o Red cultural</span></td>
                <td><?= $row->team_1 ?></td>
            </tr>
            <tr>
                <td class="text-end"><span class="text-muted">Actividad</span></td>
                <td><?= $row->job_role ?></td>
            </tr>
            <tr>
                <td class="text-end"><span class="text-muted">Perfil</span></td>
                <td><?= $row->about ?></td>
            </tr>

            <tr>
                <td class="text-end"><span class="text-muted">Lema</span></td>
                <td><?= $row->text_2 ?></td>
            </tr>
            <tr>
                <td class="text-end"><span class="text-muted">Temas de interés</span></td>
                <td><?= $row->text_3 ?></td>
            </tr>
        </tbody>
    </table>
</div>