<div class="row">
    <div class="col-md-4">
        <p>Descubre y pregunta aquí más información sobre las personas invitadas al Encuentro</p>
        <p>Explora contenidos generados con preguntas de otros usuarios <i class="fas fa-arrow-right"></i></p>
        <table class="bg-white table table-sm">
            <tbody>
                <tr v-for="contenido in contenidos" :key="contenido.id" v-bind:class="{'table-warning': contenido.id == currentContenido.id}">
                    <td>
                        <a v-bind:href="`<?= RCI_URL_APP . "invitados/abrir_perfil/" ?>` + contenido.creator_id">
                            <img v-bind:src="`<?= URL_CONTENT ?>redcultural/images/profiles/` + contenido.username + `.jpg`"
                            v-bind:title="contenido.user_display_name"
                            class="rounded-circle w50p" v-bind:alt="`Imagen de ` + contenido.user_display_name" width="100%"
                            v-bind:onerror="`this.src='<?= URL_CONTENT ?>redcultural/images/default/user.png'`">
                        </a>
                    </td>
                    <td>
                        <a class="pointer" v-on:click="setCurrentContenido(contenido.id)">
                            {{ contenido.solicitud.slice(0, 100) }}
                            <span v-if="contenido.solicitud.length > 100">...</span>
                        </a>
                    </td>
                    <td width="10px" class="text-center align-middle">
                        <button class="btn" v-show="contenido.creator_id == <?= $this->session->userdata('user_id') ?>"
                            v-on:click="setCurrentContenido(contenido.id)"
                            data-bs-toggle="modal" data-bs-target="#deleteModal"
                        >
                            <i class="fas fa-trash text-muted"></i>
                        </button>
                    </td>
                </tr>    
            </tbody>
        </table>
    </div>
    <div class="col-md-8">
        <div class="generated-content">
            <p class="lead color-text-1">
                {{ currentContenido.solicitud }}
            </p>
            <p v-html="markdownToHtml(currentContenido.contenido)"></p>

        </div>
    </div>
</div>