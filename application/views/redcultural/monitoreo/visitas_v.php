<div id="visitasApp">
    <div class="center_box_750">
        <table class="table bg-white">
            <thead>
                <th></th>
                <th>Invitado</th>
                <th>Visitas</th>
            </thead>
            <tbody>
                <tr v-for="(visita, key) in visitas">
                    <td class="w50p">
                        <a v-bind:href="`<?= RCI_URL_APP . "invitados/abrir_perfil/" ?>` + visita.user_id">
                            <img 
                                v-bind:src="`<?= URL_CONTENT ?>redcultural/images/profiles/` + visita.username + `.jpg`"
                                class="w50p rounded-circle" v-bind:onerror="`this.src='<?= URL_CONTENT ?>redcultural/images/default/user.png'`"
                                alt="Imagen de invitado">
                        </a>
                    </td>
                    <td>
                        <a v-bind:href="`<?= RCI_URL_APP . "invitados/abrir_perfil/" ?>` + visita.user_id">
                            {{ visita.display_name }}
                        </a>
                        <br>
                        <span class="text-muted">{{ visita.username }}</span>
                    </td>
                    <td class="text-center">
                        {{ visita.qty_events }}
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<script>
var visitasApp = createApp({
    data(){
        return{
            loading: false,
            visitas: <?= json_encode($visitas->result()) ?>,
        }
    },
    methods: {
        ago: function(date){
            if (!date) return ''
            return moment(date, 'YYYY-MM-DD HH:mm:ss').fromNow()            
        },
        dateFormat: function(date){
            if (!date) return ''
            return moment(date).format('D MMM YYYY')
        },
    },
    mounted(){
        //this.getList()
    }
}).mount('#visitasApp')
</script>