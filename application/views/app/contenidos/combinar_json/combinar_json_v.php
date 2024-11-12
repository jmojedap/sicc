<script src="<?= URL_RESOURCES ?>js/pml_searcher.js"></script>

<div id="combinarJsonApp">
    <div class="text-center" v-show="loading">
        <div class="spinner-border text-secondary" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>

    <div>

    </div>
    <div class="center_box_750" v-show="!loading">
        <div class="search-container">
            <input class="search-input mb-2" type="text" v-model="q"
                placeholder="Buscar" autofocus>
            <button class="search-button" v-show="this.q.length > 0" v-on:click="clearSearch()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <p class="text-center">{{ elementosFiltrados.length }} resultados</p>

        <table class="table bg-white">
            <thead>
                <th>Num</th>
                <th>Pregunta</th>
            </thead>
            <tbody>
                <tr v-for="(elemento, key) in elementosFiltrados">
                    <td>
                        <strong>
                            {{ elemento['num'] }}
                        </strong>
                    </td>
                    <td>
                        {{ elemento['Pregunta'] }}
                        
                        <p class="ps-3" v-html="formatText(elemento['Opciones de respuesta'])"></p>
                        <hr>
                        <p>
                            Tipo laboratorio: {{ elemento['Tipo laboratorio'] }} &middot;
                            {{ elemento['Tema'] }} &middot;
                            {{ elemento['Explicación'] }} &middot;
                        </p>
                    </td>
                </tr>
            </tbody>
        </table>


        <!-- <div class="row mb-3" v-for="elemento in elementosFiltrados">
            <div class="col-md-12">
                {{ elemento['Pregunta'] }}
            </div>
        </div> -->
    </div>
</div>

<?php $this->load->view('app/contenidos/combinar_json/vue_v') ?>