<div id="exportarDatosApp">
    <div class="center_box_750">
        <table class="table bg-white">
            <tbody>
                <tr v-for="(recurso, key) in recursos" v-show="recurso.group === group">
                    <td>
                        {{ recurso.name }}
                        <br>
                        <small>{{ recurso.description }}</small>
                    </td>
                    <td width="10px">
                        <a class="btn btn-success" v-bind:href="`<?= base_url() ?>app/` + recurso.link" target="_blank">
                            <i class="fa-solid fa-download"></i>
                        </a>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<script>
var exportarDatosApp = createApp({
    data(){
        return{
            recursos: <?= json_encode($recursos) ?>,
            group: '<?= $group ?>'
        }
    },
    methods: {
        
    },
    mounted(){
        //this.getList()
    }
}).mount('#exportarDatosApp')
</script>