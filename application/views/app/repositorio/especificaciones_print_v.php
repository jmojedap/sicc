<style>
.docs h1 {
    color: #5e4296;
    text-align: center;
}

.docs p{
    text-align: justify;
}

.docs h2 {
    color: #0256d5;
    padding-top: 0.5em;
    margin-bottom: 0.5em;
    border-bottom: 1px solid #CCC;
}

.docs img{
    width: 100%;
    border: 1px solid #DDD;
    border-radius: 0.2em;
}
</style>

<p class="text-center text-muted">
    <i class="fa fa-info-circle text-warning"></i>
    <br>
    Versión: <?= $this->pml->date_format(date('Y-m-d H:i:s')) ?>
    <br>
    Este documento contiene las especificaciones de requerimientos del componente
    Repositorio de Contenidos como herramienta del sistema de información Cultured_Bogotá.
    Esta versión no es final pero es una avance entregable para el inicio de actividades
    de desarrollo e implementación.
</p>

<?php foreach ( $indice as $index => $item ) : ?>
    <p class="text-muted text-center">
        <?= $this->pml->date_format($item->updated_at) ?>
    </p>
    <div class="docs">
        <?= $contenidos[$index] ?>
    </div>
<?php endforeach ?>
