<body style="<?= $styles['body'] ?>">
    <?php $this->load->view($view_a) ?>
    <p style="<?= $styles['text_center'] ?>">
        <a href="<?= RCI_URL_APP ?>" target="_blank" title="Ir a <?= RCI_APP_NAME ?>">
            <img src="<?= base_url() ?>resources/static/images/app/rci.png" alt="<?= RCI_APP_NAME ?>" style="width: 300px; margin-bottom: 1em;">
        </a>
    </p>    
    <footer style="<?= $styles['footer'] ?>">
        2025 &middot; Creado por Prototipos-Sicc.com &middot; Colombia para
        <br> 
        Encuentro Ciudades y Culturas en Iberoamérica ::
        Conversaciones desde Bogotá
    </footer>
</body>