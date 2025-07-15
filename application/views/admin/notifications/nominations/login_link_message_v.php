<?php
    $base_url = 'https://www.ejemplo.com/accounts/validate_login_link/';
    $texts['link'] = "{$base_url}?login_key={$user->activation_key}";
?>
<h3 style="<? $styles['h3'] ?>"><b><?= $user->display_name ?></b></h3>
<p>Haz clic en el siguiente link para ingresar</p>
<br>
<a style="<?= $styles['btn'] ?>" href="<?= $texts['link'] ?>" target="_blank">
    INGRESAR
</a>