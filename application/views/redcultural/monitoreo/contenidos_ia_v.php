<?php $this->load->view('redcultural/invitados/descubre/style_v') ?>

<script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/dompurify@3.0.5/dist/purify.min.js"></script>

<div id="contenidosApp">
    <div class="container">
        <table class="table bg-white" v-show="section == 'list'">
            <thead>
                <th></th>
                <th>Invitado</th>
                <th width="50%">Solicitud</th>
                <th>
                    Tokens
                </th>
                <th>
                    Costo (COP)
                </th>
                <th></th>
            </thead>
            <tbody>
                <tr class="table-info">
                    <td></td>
                    <td></td>
                    <td>Total</td>
                    <td class="text-end">
                        <strong>{{ totalTokens() }}</strong>
                    </td>
                    <td class="text-end">
                        <small class="text-muted">$ </small>
                        <strong>
                            {{ totalCost() }}
                        </strong>
                    </td>
                    <td></td>
                </tr>
                <tr v-for="(contenido, key) in contenidos">
                    <td class="w50p">
                        <a v-bind:href="`<?= RCI_URL_APP . "invitados/abrir_perfil/" ?>` + contenido.user_id">
                            <img 
                                v-bind:src="`<?= URL_CONTENT ?>redcultural/images/profiles/` + contenido.username + `.jpg`"
                                class="w50p rounded-circle" v-bind:onerror="`this.src='<?= URL_CONTENT ?>redcultural/images/default/user.png'`"
                                alt="Imagen de invitado">
                        </a>
                    </td>
                    <td>
                        <a v-bind:href="`<?= RCI_URL_APP . "invitados/abrir_perfil/" ?>` + contenido.user_id">
                            {{ contenido.display_name }}
                        </a>
                        <br>
                        <span class="text-muted">{{ contenido.username }}</span>
                    </td>
                    <td>
                        {{ contenido.excerpt }}
                    </td>
                    <td class="text-center">
                        {{ contenido.integer_3 }}
                    </td>
                    <td class="text-end">
                        <small class="text-muted">$</small>
                        {{ tokensToMoney(contenido.integer_3) }}
                    </td>
                    <td>
                        <button class="btn btn-light" v-on:click="setCurrent(key)">
                            Ver
                        </button>
                    </td>
                </tr>
            </tbody>
        </table>

        <div v-show="section == 'contenido'">
            <div class="mb-2">
                <button class="btn btn-light" v-on:click="section = 'list'">
                    <i class="fas fa-arrow-left"></i> Listado
                </button>
            </div>
            <p class="lead">
                {{ currentContenido.excerpt }}
            </p>
            <div class="generated-content" v-html="markdownToHtml(currentContenido.content)"></div>
        </div>
    </div>



</div>

<script>
var contenidos_ia = <?= json_encode($contenidos_ia->result()) ?>;

// VueApp
//-----------------------------------------------------------------------------
var contenidosApp = createApp({
    data(){
        return{
            section: 'list',
            loading: false,
            contenidos: contenidos_ia,
            currentContenido: contenidos_ia[0],
        }
    },
    methods: {
        tokensToMoney: function(qtyTokens){
            var money = qtyTokens * (0.3/1000000) * 4050
            money = money.toFixed(0)
            money = money
            return money
        },
        totalTokens: function(){
            var total = 0
            this.contenidos.forEach(contenido => {
                total += parseInt(contenido.integer_3)
            });
            return total
        },
        totalCost: function(){
            var total = 0
            this.contenidos.forEach(contenido => {
                total += parseInt(contenido.integer_3)
            });
            return this.tokensToMoney(total)
        },
        setCurrent: function(index){
            this.section = 'contenido'
            this.currentContenido = this.contenidos[index]
        },
        // Convertir respuesta de Markdown a HTML
        markdownToHtml: function(markdownText) {
            // Convertir markdown a HTML
            const rawHtml = marked.parse(markdownText); //

            // Sanitizar si DOMPurify está disponible
            var responseHtml = window.DOMPurify
                ? DOMPurify.sanitize(rawHtml)
                : rawHtml;

            return responseHtml;
        },
    },
    mounted(){
        //this.getList()
    }
}).mount('#contenidosApp')
</script>