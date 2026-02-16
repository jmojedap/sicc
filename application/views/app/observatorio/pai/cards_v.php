<div v-show="seccion == 'listado'">
    <div class="row mb-4" v-for="investigacion in investigacionesFiltrados">
        <div class="col-md-3">
            <img v-bind:src="`<?= URL_CONTENT ?>observatorio/investigaciones/` + investigacion['ID'] + `.jpg`"
                class="rounded w-100 pointer" alt="Miniatura investigación"
                onerror="this.src='<?= URL_CONTENT ?>observatorio/investigaciones/nd.jpg'"
                v-on:click="setCurrent(investigacion)">

        </div>
        <div class="col-md-9">
            <span class="grupo-investigacion pointer text-main" v-on:click="setCurrent(investigacion)"
                style="font-size: 1.5em;">
                {{ investigacion['Nombre clave'].substring(3) }}
            </span>

            <br>
            <strong class="text-muted pointer" v-on:click="setCurrent(investigacion)">
                {{ investigacion['Título'] }}
            </strong>
            <br>

            <br>
            <p>
                {{ investigacion['Descripción'] }}
            </p>

            <span class="color-text-2">
                {{ investigacion['grupo_1'] }}
            </span>
            &middot;
            <span class="label-linea-investigacion me-2"
                v-bind:class="`linea-` + textToClass(investigacion['Línea de investigación'])">
                {{ investigacion['Línea de investigación'] }}
            </span>
        </div>
    </div>
</div>