<body style="<?= $styles['body'] ?>">
    <?php $this->load->view($view_a) ?>
    <p></p>
    <p style="<?= $styles['text_center'] ?>">
        <a href="<?= RCI_URL_APP ?>accounts/login_link" target="_blank" title="Ir a <?= RCI_APP_NAME ?>">
            <img src="<?= base_url() ?>resources/static/images/app/rci.png" alt="<?= RCI_APP_NAME ?>" style="width: 80px; margin-bottom: 1em;">
        </a>
        <br>
        <span><?= RCI_APP_NAME ?></span>
    </p>
    <footer style="<?= $styles['footer'] ?>">2025 &middot; Powered By Prototipos-Sicc.com &middot; Colombia</footer>
</body>