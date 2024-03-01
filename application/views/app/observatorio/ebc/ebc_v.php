<?php $this->load->view('app/observatorio/ebc/style_v') ?>
<script src="<?= URL_CONTENT ?>observatorio/ebc/modulos.js"></script>
<script src="<?= URL_CONTENT ?>observatorio/ebc/textos.js"></script>


<h1 class="titulo-principal">Encuesta Bienal de Culturas 2022-2023</h1>
<div id="ebcApp">
    <div class="container">
        <div class="">
            <ul class="nav nav-tabs mb-2 justify-content-center">
                <li class="nav-item">
                    <a class="nav-link" v-on:click="section_1 = 'informacion'" v-bind:class="{'active': section_1 == 'informacion' }" href="#">Información</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" v-on:click="section_1 = 'resultados'" v-bind:class="{'active': section_1 == 'resultados' }" href="#">Resultados</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" v-on:click="section_1 = 'preguntas'" v-bind:class="{'active': section_1 == 'preguntas' }" href="#">Preguntas</a>
                </li>
            </ul>

            <div class="center_box_750" v-show="section_1 == 'informacion'">
                <p>
                    La Encuesta Bienal de Culturas - EBC, se constituye en una de las principales herramientas de
                    política
                    pública para la toma de decisión informada. La EBC fue pensada para recopilar información de interés
                    prioritaria en temas sociales y culturales, que permitan la comprensión de las relaciones sociales
                    de
                    quienes habitan la ciudad, al tiempo de caracterizar la participación y vivencias de las personas
                    con
                    las
                    apuestas artísticas, culturales, recreativas y deportivas de Bogotá.
                </p>
                <h2><i class="fas fa-caret-right text-main"></i> ¿Cómo se hizo la encuesta?</h2>
                <p>
                    La Encuesta Bienal de Culturas - EBC se hizo a un total de 000000 personas en la ciudad de Bogotá,
                    contemplando todas las localidades.
                    Etiam commodo neque quis elementum eleifend. Ut at tortor nibh. Mauris euismod nibh non felis
                    vulputate
                    vulputate. Nam placerat in lorem at feugiat. Donec eget nulla efficitur elit iaculis accumsan. In
                    consequat
                    leo at erat suscipit blandit non sed mi. Aliquam dictum justo nec mattis tincidunt. Curabitur ut
                    consequat
                    turpis. Sed fermentum ipsum auctor ante laoreet, quis iaculis mauris congue.
                </p>
                <h2><i class="fas fa-caret-right text-main"></i> ¿Cómo usar esta encuesta?</h2>
                <p>
                    Contenido pedagógico para la ciudadanía que les permita usar los datos tiam commodo neque quis
                    elementum
                    eleifend. Ut at tortor nibh. Mauris euismod nibh non felis vulputate vulputate. Nam placerat in
                    lorem at
                    feugiat. Donec eget nulla efficitur elit iaculis accumsan. In consequat leo at erat suscipit blandit
                    non
                    sed
                    mi. Aliquam dictum justo nec mattis tincidunt. Curabitur ut consequat turpis. Sed fermentum ipsum
                    auctor
                    ante laoreet, quis iaculis mauris congue.
                </p>
            </div>

            <div v-show="section_1 == 'resultados'">
                <?php $this->load->view('app/observatorio/ebc/resultados_v') ?>
            </div>
            <div v-show="section_1 == 'preguntas'">
                <?php $this->load->view('app/observatorio/ebc/preguntas_v') ?>
            </div>

        </div>
    </div>
    <footer class="fixed-bottom p-1">
        <img class="logo-dogcc float-end" src="<?= URL_IMG ?>ebc/logo-dogcc-yellow.png" alt="Logo Observatorio">
    </footer>
</div>


<?php $this->load->view('app/observatorio/ebc/vue_v') ?>