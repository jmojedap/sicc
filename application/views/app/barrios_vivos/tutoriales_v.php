<div id="tutorialesApp">
    <div class="center_box_750">
        <table class="table bg-white">
            <thead>
                <th>Tutorial</th>
                <th></th>
            </thead>
            <tbody>
                <tr v-for="(tutorial, key) in tutoriales">
                    <td>
                        <strong class="">
                            {{ tutorial.title }}
                        </strong>
                        &middot;
                        <small>
                            {{ tutorial.duracion }}
                        </small>
                        <br>
                        <span class="text-muted">
                            {{ tutorial.description }}
                        </span>
                    </td>
                    <td width="100px" class="text-center">
                        <a class="btn btn-light btn-sm w75p" v-bind:href="tutorial.url" target="_blank">
                            Ver <i class="fas fa-external-link-alt"></i>
                        </a>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<script>
var tutorialesApp = createApp({
    data(){
        return{
            tutoriales: [
                {
                    id: 1,
                    title: 'Iniciar sesión',
                    description: 'Iniciar sesión en la plataforma de Prototipos SICC',
                    url: 'https://drive.google.com/file/d/1kS-Bvb_ewe8HeumscRgfLlgR-brSENgq/view?usp=drive_link',
                    duracion: '1:03 min',
                },
                {
                    id: 2,
                    title: 'Crear un laboratorio',
                    description: 'Crear un laboratorio de Barrios Vivos, caracterización, ubicación, duplas, fechas, etc.',
                    url: 'https://drive.google.com/file/d/1SK1r638o3N696Krr9ZDgCPVmvQovEvTC/view?usp=drive_link',
                    duracion: '2:43 min',
                },
                {
                    id: 3,
                    title: 'Registrar actividad de un laboratorio',
                    description: 'Crear o registra una actividad de un laboratorio, fecha, hora, lugar, descripción, etapas, links de evidencias',
                    url: 'https://drive.google.com/file/d/1FE2KyYY6_O1urv28oHX3_oCmxYIGeoXQ/view?usp=drive_link',
                    duracion: '3:00 min',
                },
            ],
        }
    },
    methods: {
        
    },
    mounted(){
        //this.getList()
    }
}).mount('#tutorialesApp')
</script>