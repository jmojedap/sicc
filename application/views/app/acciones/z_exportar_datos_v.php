<div id="exportarDatosApp">
    <div class="center_box_750">
        <table class="table bg-white">
            <tbody>
                <tr v-for="(recurso, key) in recursos">
                    
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
            recursos: [
                {   name: 'Usuarios',
                    description: 'Usuarios registrados en la plataforma y asociados a las acciones de las estrategias',
                    link: 'acciones/usuarios_exportar',
                },
                {   name: 'Asistencias',
                    description: 'Relación de las acciones con los usuarios asistentes, un mismo usuario puede asistir a varias acciones',
                    link: 'acciones/acciones_asistentes_exportar',
                },
                {   name: 'Itinerantes',
                    description: 'Exportar asistentes a acciones itinerantes',
                    link: 'acciones/acciones_asistentes_itinerantes_exportar',
                },
            ]
        }
    },
    methods: {
        
    },
    mounted(){
        //this.getList()
    }
}).mount('#exportarDatosApp')
</script>