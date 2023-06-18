<?php
    $general_colors = array(
        array('name' => 'info','background' => '#00c0ef', 'font_color' => '#FFFFFF'),
        array('name' => 'info hover','background' => '#0ab6e0', 'font_color' => '#FFFFFF'),
        array('name' => 'primary','background' => '#3E8EF7', 'font_color' => '#FFFFFF'),
        array('name' => 'primary hover','background' => '#589FFC', 'font_color' => '#FFFFFF'),
        array('name' => 'success','background' => '#11c26d', 'font_color' => '#FFFFFF'),
        array('name' => 'success hover','background' => '#28d17c', 'font_color' => '#FFFFFF'),
        array('name' => 'warning','background' => '#fdd835', 'font_color' => '#FFFFFF'),
        array('name' => 'warning hover','background' => '#f1cd2d', 'font_color' => '#FFFFFF'),
        array('name' => 'danger','background' => '#FF4C52', 'font_color' => '#FFFFFF'),
        array('name' => 'danger hover','background' => '#FF666B', 'font_color' => '#FFFFFF')
    );

    $app_colors = array(
        array('name' => 'main','background' => '#5e4296', 'font_color' => '#FFFFFF'),
        array('name' => 'light','background' => '#ab98d1', 'font_color' => '#000'),
        array('name' => 'dark','background' => '#4f387e', 'font_color' => '#FFF'),
        array('name' => 'darker','background' => '#422f68', 'font_color' => '#FFF'),
        array('name' => 'secondary','background' => '#e6a000', 'font_color' => '#FFFFFF'),
        array('name' => 'color-1','background' => '#5e4296', 'font_color' => '#FFFFFF'),
        array('name' => 'color-2','background' => '#2D62A9', 'font_color' => '#FFFFFF'),
        array('name' => 'color-3','background' => '#0097a7', 'font_color' => '#FFFFFF'),
        array('name' => 'color-4','background' => '#30a338', 'font_color' => '#FFFFFF'),
        array('name' => 'color-5','background' => '#e6a000', 'font_color' => '#FFFFFF'),
        array('name' => 'color-6','background' => '#ec6c38', 'font_color' => '#FFFFFF'),
        array('name' => 'color-7','background' => '#ee3248', 'font_color' => '#FFFFFF'),
        array('name' => 'color-8','background' => '#ea3471', 'font_color' => '#FFFFFF'),
    );

    $arr_classes = array(
        'light',
        'info',
        'primary',
        'success',
        'warning',
        'danger',
        'secondary',
    );
?>

<script>
    $(document).ready(function(){
        $('.btn').click(function(){
            console.log('mostrando')
            toastr['success']('El mensaje se guardó correctamenbe');
            toastr['error']('Ocurrió un error');
            toastr['info']('Estamos informando algo');
            toastr['warning']('Estamos informando algo');
        })
    })
</script>

<div class="row">
    <div class="col-md-4">
        <table class="table bg-white">
            <thead>
                <th>Color</th>
                <th></th>
            </thead>
            <?php foreach ( $general_colors as $color ) { ?>
                <tr>
                    <td><?= $color['name'] ?></td>
                    <td style="background-color: <?= $color['background'] ?>; color: <?= $color['font_color'] ?>"><?= $color['background'] ?></td>
                </tr>
            <?php } ?>
        </table>
    </div>
    <div class="col-md-4">
        <h3>Colores aplicación</h3>
        <table class="table bg-white">
            <thead>
                <th>Color</th>
                <th></th>
            </thead>
            <?php foreach ( $app_colors as $color ) { ?>
                <tr>
                    <td><?= $color['name'] ?></td>
                    <td style="background-color: <?= $color['background'] ?>; color: <?= $color['font_color'] ?>"><?= $color['background'] ?></td>
                </tr>
            <?php } ?>
        </table>
        <p class="bg-white border p-3">
        [
        <?php foreach ( $app_colors as $color ) : ?>
            '<?php echo $color['background'] ?>',
        <?php endforeach ?>
        ]
        </p>
    </div>
    <div class="col-md-4">
        <?php foreach ( $arr_classes as $class ) { ?>
            <button class="btn btn-<?= $class ?> btn-block"><?= $class ?></button>
        <?php } ?>
    </div>
</div>