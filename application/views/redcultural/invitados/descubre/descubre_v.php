<?php $this->load->view('redcultural/invitados/descubre/style_v') ?>

<script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/dompurify@3.0.5/dist/purify.min.js"></script>

<div id="chatApp">
    <div class="container pt-2">
        <ul class="nav nav-pills mb-3 justify-content-center">
            <li class="nav-item pointer" v-for="sectionOption in sections">
                <a class="nav-link" aria-current="page" v-bind:class="{'active': sectionOption.name == section }"
                    v-on:click="section = sectionOption.name">
                    {{ sectionOption.title }}
                </a>
            </li>
        </ul>

        <div v-show="section == 'contents'">
            <?php $this->load->view('redcultural/invitados/descubre/anteriores_v') ?>
        </div>

        <div v-show="section == 'help'">
            <?php $this->load->view('redcultural/invitados/descubre/help_v') ?>
        </div>


        <div class="row" v-show="section == 'generation'">
            <div class="col-md-4">
                <pre class="d-none">{{ tokens }}</pre>
                <!-- Example single danger button -->
                <strong>Ejemplos</strong>
                <div class="dropdown-center mb-3 mt-1">
                    <button type="button" class="btn btn-light dropdown-toggle w-100" data-bs-toggle="dropdown"
                        aria-expanded="false">
                        {{ currentFuncion.titulo_corto }}
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item disabled">Personaliza tus preguntas:</a></li>
                        <li v-for="funcion in funciones">
                            <a class="dropdown-item" href="#" v-bind:class="{'active': currentFuncion == funcion }"
                                v-on:click="setFuncion(funcion)">{{ funcion.titulo }}</a>
                        </li>
                    </ul>
                </div>

                <form accept-charset="utf-8" method="POST" id="ia-chat-form" @submit.prevent="handleSubmit">
                    <fieldset v-bind:disabled="loading || percentUsageTokens >= 100">
                        <div class="chat-input mb-2">
                            <textarea name="user_input" id="user-input" v-model="user_input" rows="5" ref="userInput"
                                @input="autoExpand($event)" @keydown.enter="handleKeyDown" required maxlength="300"
                                placeholder="Haz una petición a la Red Cultural"></textarea>
                        </div>
                        <div class="text-end">
                            <button class="btn btn-submit btn-lg" type="submit">
                                Descubrir
                                <img src="<?= URL_IMG ?>icons/ia-generate.png" alt="Generar" style="width: 20px;">
                            </button>
                        </div>
                        <fieldset>
                </form>

            </div>
            <div class="col-md-8">
                <div class="center_box_750">
                    <div class="tools-bar my-2 d-flex justify-content-end">
                        <button class="btn btn-sm btn-warning" title="Agregar a los contenidos públicos"
                            v-on:click="updateContentStatus(lastMessage.id, 1)" v-bind:disabled="messages.length == 0"
                            target="_blank">
                            <i class="fas fa-save"></i> Guardar
                        </button>
                    </div>
                    
                    <div class="progress mb-2" style="height: 15px;"
                        v-bind:title="`Uso de tokens: ${percentUsageTokens}%`">
                        <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar"
                            v-bind:style="`width: ${percentUsageTokens}%`">
                            {{ percentUsageTokens }}% usado
                        </div>
                    </div>
                    <div v-show="loading" class="text-center p-4">
                        <div class="spinner-border text-secondary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>

                    <div v-html="responseHtml" class="generated-content" id="generated-content" v-show="!loading"></div>
                </div>
            </div>
        </div>
    </div>
    <?php $this->load->view('common/bs5/modal_delete_set_v') ?>

    <!-- Modal para iniciar sesión -->
    <div class="modal fade" id="sessionModal" tabindex="-1" aria-labelledby="sessionModal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Inicia sesión</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Es necesario iniciar sesión para generar respuestas con esta herramienta.
                </div>
                <div class="modal-footer">
                    <a type="button" class="btn btn-primary" href="<?= RCI_URL_APP . 'accounts/login_link' ?>">Ingresar</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $this->load->view('redcultural/invitados/descubre/vue_v') ?>