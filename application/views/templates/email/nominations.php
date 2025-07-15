<?php
    $app_info = $this->App_model->app_info('nominations');
?>

<body style="<?= $styles['body'] ?>">
    <?php $this->load->view($view_a) ?>
    <p style="<?= $styles['text_center'] ?>">
        <span><?= $app_info['title'] ?></span>
    </p>
    <footer style="<?= $styles['footer'] ?>">2025 &middot; Realizado por Pacarina Media Lab &middot; Colombia</footer>
</body>