<div class="row">
    <div class="col-md-3">
        <div class="mini-titulo">MÓDULOS</div>
        <div class="list-group">
            <a class="link-modulo" v-for="(modulo, km) in modulos" 
                type="button" style="min-height: 66px;"
                v-on:click="currentModulo = modulo" v-bind:class="linkModuloClass(modulo)"
            >
            <div class="d-flex">
                <div class="numero-modulo">{{ modulo.modulo_id }}</div>
                <div>{{ modulo.nombre }}</div>
            </div>
            </a>
        </div>
    </div>
    <div class="col-md-9">
        <div class="mw750p ebc-texto">
            <h3 class="text-center modulo-titulo" v-bind:class="`modulo-titulo-` + currentModulo.modulo_id">
                {{ currentModulo.nombre }}
            </h3>
            
            <div class="contenido-textos">
                <p class="border-bottom pb-2 lead">
                    {{ currentModulo.descripcion }}
                </p>
                <div class="alert alert-light text-center" v-show="currentModulo.status != 1">
                    Este módulo de la encuesta todavía se encuentra en proceso de recolección, procesamiento y análisis.
                </div>
                <div v-for="(texto, kt) in textos" v-show="texto.modulo_id == currentModulo.modulo_id" class="mt-3">
                    <h4 class="titulo">{{ texto.titulo }}</h4>
                    <h5 class="subtitulo">{{ texto.subtitulo }}</h5>
                    <p class="text-end"><small class="text-muted">{{ texto.politica_programa }}</small></p>
                    <div class="d-flex">
                        <div class="me-3" v-show="texto.icono.length > 0">
                            <div v-html="texto.icono" class="icono" v-bind:class="`icono-` + currentModulo.modulo_id"></div>
                        </div>
                        <p style="text-align: justify;">{{ texto.parrafo }}</p>
                    </div>
                    <img v-if="texto.imagen_drive.length > 0" v-bind:src="`https://drive.usercontent.google.com/download?id=` + texto.imagen_drive + `&export=view&authuser=0`"
                        alt="Imagen sobre párrafo" class="w-100 mb-2 rounded shadow" loading="lazy">
                        <img src="https://drive.google.com/uc?export=view&id=1LHsGVJHWkodQIt4itou9iTlkKSRNrkfX" alt="Imagen desde Google Drive" style="max-width: 100%; height: auto;">
                </div>
            </div>
        </div>
    </div>
</div>