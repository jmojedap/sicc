<?php
    $texts['link'] = RCI_URL_APP . "accounts/validate_login_link/{$user->activation_key}";
?>
<h3 style="<?= $styles['h3'] ?>"><b><?= $user->display_name ?></b></h3>
<p><span style="color: #FFFFFF; background-color: #966EF0;">Copia y pega</span> el siguiente código en el formulario de ingreso</p>
<h4 style="<?= $styles['text_center'] ?> font-size: 2em; color: #000000;"><?= $user->activation_key ?></h4>