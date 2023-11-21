<div class="row">
    <div class="col-md-3">
        <div class="list-group">
            <a class="link-modulo" v-for="(modulo, km) in modulos" 
                type="button" style="min-height: 66px;"
                v-on:click="currentModulo = modulo" v-bind:class="linkModuloClass(modulo)"
            >
                {{ modulo.nombre }}
            </a>
        </div>
    </div>
    <div class="col-md-9">
        <div class="mw750p">
            <h3 class="text-center modulo-titulo" v-bind:class="`modulo-titulo-` + currentModulo.modulo_id">
                {{ currentModulo.nombre }}
            </h3>
            <div class="contenido-textos">
                <div class="border-bottom mb-3 py-3 text-muted text-center">
                    Realizada entre el 12 de agosto y 25 de septiembre de 2022
                </div>
                <div v-for="(texto, kt) in textos" v-show="texto.modulo_id == currentModulo.modulo_id" class="mt-3">
                    <h4 class="color-text-1">{{ texto.politica }}</h4>
                    <h5 class="text-main">{{ texto.subtitulo }}</h5>
                    <div class="d-flex">
                        <div class="me-3" v-show="texto.icono.length > 0">
                            <div v-html="texto.icono" class="icono" v-bind:class="`icono-` + currentModulo.modulo_id"></div>
                        </div>
                        <p style="text-align: justify;">{{ texto.parrafo }}</p>
                    </div>
                    <img v-if="texto.imagen_drive.length > 0" v-bind:src="`https://drive.google.com/uc?id=` + texto.imagen_drive"
                        alt="Imagen sobre párrafo" class="w-100 mb-2 rounded shadow" loading="lazy">
                </div>
            </div>
        </div>
    </div>
</div>