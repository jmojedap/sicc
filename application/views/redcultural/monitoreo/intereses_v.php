<div id="interesesApp">
    <div class="center_box_750">
        <table class="table bg-white">
            <thead>
                <th></th>
                <th>Invitado</th>
                <th></th>
                <th>Seguido por</th>
                <th>Hace</th>
            </thead>
            <tbody>
                <tr v-for="(interes, key) in intereses">
                    <td class="w50p">
                        <a v-bind:href="`<?= RCI_URL_APP . "invitados/abrir_perfil/" ?>` + interes.user_id">
                            <img 
                                v-bind:src="`<?= URL_CONTENT ?>redcultural/images/profiles/` + interes.username + `.jpg`"
                                class="w50p rounded-circle" v-bind:onerror="`this.src='<?= URL_CONTENT ?>redcultural/images/default/user.png'`"
                                alt="Imagen de invitado">
                        </a>
                    </td>
                    <td>
                        <a v-bind:href="`<?= RCI_URL_APP . "invitados/abrir_perfil/" ?>` + interes.user_id">
                            {{ interes.display_name }}
                        </a>
                        <br>
                        <span class="text-muted">{{ interes.username }}</span>
                    </td>
                    <td>
                        <a v-bind:href="`<?= RCI_URL_APP . "invitados/perfil/" ?>` + interes.seguidor_id">
                            <img 
                                v-bind:src="`<?= URL_CONTENT ?>redcultural/images/profiles/` + interes.seguidor_username + `.jpg`"
                                class="w50p rounded-circle" v-bind:onerror="`this.src='<?= URL_CONTENT ?>redcultural/images/default/user.png'`"
                                alt="Imagen de seguidor">
                        </a>
                    </td>
                    <td class="">
                        {{ interes.seguidor }}
                    </td>
                    <td>
                        {{ dateFormat(interes.created_at) }} <br>
                        {{ ago(interes.created_at) }}
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<script>
var interesesApp = createApp({
    data(){
        return{
            loading: false,
            intereses: <?= json_encode($intereses->result()) ?>,
        }
    },
    methods: {
        ago: function(date){
            if (!date) return ''
            return moment(date, 'YYYY-MM-DD HH:mm:ss').fromNow()            
        },
        dateFormat: function(date){
            if (!date) return ''
            return moment(date).format('D MMM')
        },
    },
    mounted(){
        //this.getList()
    }
}).mount('#interesesApp')
</script>