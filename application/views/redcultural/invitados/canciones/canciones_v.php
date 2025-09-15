<script src="<?= URL_RESOURCES ?>js/pml_searcher.js"></script>

<?php $this->load->view('redcultural/invitados/style_v') ?>

<div id="cancionesApp">
    <div class="text-center" v-show="loading">
        <div class="spinner-border text-secondary" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>

    <div class="center_box_920" v-show="!loading">
        <div class="search-container">
            <input class="search-input mb-2" type="text" v-model="q" placeholder="Buscar" autofocus>
            <button class="search-button" v-show="this.q.length > 0" v-on:click="clearSearch()">
                <i class="fas fa-times"></i>
            </button>
        </div>

    </div>
    <!-- LISTA DE INVITADOS -->
    <div v-show="section == 'listado'">
        <div v-show="typeView == 'table'" class="center_box_920">
            <table class="table bg-white">
                <thead>
                    <th class="text-white" width="50px">i</th>
                    <th></th>
                    <th>
                        Canción recomendada
                    </th>
                </thead>
                <tbody>
                    <tr v-for="(elemento, key) in elementosFiltrados" v-show="directorioValue(elemento['username'], 'cancion')">
                        
                        <td>
                            <a v-bind:href="`<?= RCI_URL_APP ?>invitados/perfil/` + elemento['id'] + `/` + elemento['username']"
                                class="pointer">
                                <img v-bind:src="`<?= URL_CONTENT ?>redcultural/images/profiles/` + elemento['username'] + `.jpg`"
                                    class="w50p rounded rounded-circle shadow mb-2" v-bind:alt="`Imagen de ` + elemento['nombre_completo']" loading="lazy"
                                    v-bind:onerror="`this.src='<?= URL_IMG ?>redcultural/user.png'`">
                            </a>
                        </td>
                        <td>
                            <a v-bind:href="`<?= RCI_URL_APP ?>invitados/perfil/` + elemento['id'] + `/` + elemento['username']"
                                class="pointer">
                                    {{ elemento['nombre_completo'] }}
                            </a>
                            <br>
                            <small>{{ elemento['rol_actividad'] }}</small>
                        </td>
                        <td>
                            {{ directorioValue(elemento['username'], 'cancion') }}
                            <br>
                            <a class="badge bg-youtube me-2" v-bind:href="youTubeLink(directorioValue(elemento['username'], 'cancion'))" target="_blank">
                                YouTube
                            </a>
                            <a class="badge bg-spotify" v-bind:href="spotifyLink(directorioValue(elemento['username'], 'cancion'))" target="_blank">
                                Spotify
                            </a>
                        </td>
                        
                        
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

</div>
<?php $this->load->view('redcultural/invitados/canciones/vue_v') ?>