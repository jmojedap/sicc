<div id="supabaseApp">
    <div class="text-center" v-show="loadingPosts">
        <div class="spinner-border text-secondary" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>
    <div v-show="!loadingPosts">
        <!-- LISTADO DE POSTS -->
        <table class="table bg-white" v-show="section == 'list'">
            <thead>
                <th>ID</th>
                <th>Obligación</th>
                <th>
                    <button class="a4" title="Agregar post" v-on:click="setAddPost">
                        <i class="fa fa-plus"></i>
                    </button>
                </th>
            </thead>
            <tbody>
                <tr v-for="(post, key) in obligaciones">
                    <td>{{ post.id }}</td>
                    <td>
                        <h3>
                            {{ post.nombre_obligacion }}
                        </h3>
                        <div>
                            {{ post.descripcion }}
                        </div>
                    </td>
                    <td>
                        <button class="a4" v-on:click="setCurrent(key)">
                            <i class="fa fa-pencil-alt"></i>
                        </button>
                    </td>
                </tr>
            </tbody>
        </table>

        <div v-show="section == 'form'">
            <?php $this->load->view('admin/tests/supabase/edit_form_v') ?>
        </div>
    </div>
</div>

<?php $this->load->view('admin/tests/supabase/vue_v') ?>