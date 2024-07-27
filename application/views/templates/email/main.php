<body style="<?= $styles['body'] ?>">
    <?php $this->load->view($view_a) ?>
    <p></p>
    <p style="<?= $styles['text_center'] ?>">
        <a href="<?= URL_APP ?>accounts/login" target="_blank" title="Ir a <?= APP_NAME ?>">
            <img src="<?= base_url() ?>resources/static/images/app/logo.png" alt="<?= APP_NAME ?>" style="width: 80px; margin-bottom: 1em;">
        </a>
        <br>
        <span><?= APP_NAME ?></span>
    </p>
    <footer style="<?= $styles['footer'] ?>">2024 &middot; Prototipos-Sicc.com &middot; Colombia</footer>
</body>