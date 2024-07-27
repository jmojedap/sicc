<?php
    $texts['link'] = URL_APP . "accounts/validate_login_link/{$user->activation_key}";
?>
<?= $user->display_name ?>: 
Copia el siguiente link y pégalo en el navegador: <?= $texts['link'] ?>