<div id="fileApp" class="center_box_450">
    <div class="card">
        <div class="card-body">
            <div v-show="contenido.contenido_disponible == 0">
                <?php $this->load->view('common/bs5/upload_file_form_v') ?>
            </div>
            <div v-show="contenido.contenido_disponible == 1">
                <a v-bind:href="contenido.url_contenido" class="btn btn-link" target="_blank">
                    {{ contenido.id }}-{{ contenido.slug }}
                </a>
                <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        </div>
    </div>
    <?php $this->load->view('common/bs5/modal_single_delete_v') ?>
</div>

<script>
// VueApp
//-----------------------------------------------------------------------------
var fileApp = createApp({
    data(){
        return{
            loading: false,
            file: null,
            contenido: {
                id: <?= $row->id ?>,
                slug: '<?= $row->slug ?>',
                extension_archivo: '<?= $row->extension_archivo ?>',
                url_contenido: '<?= $row->url_contenido ?>',
                contenido_disponible: <?= $row->contenido_disponible ?>,
            },
        }
    },
    methods: {
        handleSubmit: function(){
            this.loading = true
            let form_data = new FormData();
            form_data.append('file_field', this.file);

            axios.post(URL_API + 'repositorio/upload_file/' + this.contenido.id, form_data, {headers: {'Content-Type': 'multipart/form-data'}})
            .then(response => {
                //Cargar imagen
                if ( response.data.status == 1 )
                { 
                    this.contenido.contenido_disponible = 1;
                }
                //Mostrar respuesta html, si existe
                if ( response.data.html ) { 
                    $('#upload_response').html(response.data.html)
                }
                document.getElementById('field-file').value = null
            }).catch(function (error) { console.log(error) })
        },
        handleFileUpload(){
            this.file = this.$refs.file_field.files[0]
        },
        deleteElement: function(){
            this.loading = true
            axios.get(URL_API + 'repositorio/delete_file/' + this.contenido.id + '/' + this.contenido.slug)
            .then(response => {
                if ( response.data.saved_id > 0 ) {
                    this.contenido.contenido_disponible = 0;
                    toastr['info']('Archivo eliminado');
                }
                this.loading = false
            }).catch(function (error) { console.log(error)})
        },
    }
}).mount('#fileApp')
</script>