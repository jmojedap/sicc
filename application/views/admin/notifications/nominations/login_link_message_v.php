<h3 style="<? $styles['h3'] ?>"><b><?= $user->display_name ?></b></h3>
<p>Haz clic en el siguiente link para ingresar</p>
<br>
<a style="<?= $styles['btn'] ?>" href="<?= $user->login_link ?>" target="_blank">
    INGRESAR
</a>
<p style="<?= $styles['text_center'] ?>">
    También puedes ingresar con este código QR desde tu celular
</p>
<p style="<?= $styles['text_center'] ?>">
    <img src="<?= $user->url_access_qr_image ?>" alt="QR de accceso a la aplicación">
</p>