<div id="userImageApp" class="center_box_450">
    <div class="card">
        <img
            v-bind:src="row.url_image" class="card-img-top" alt="post image"
            onerror="this.src='<?= URL_IMG ?>users/user.png'"
        >
        <div class="card-body">
            <div v-show="row.image_id == 0">
                <?php $this->load->view('common/upload_file_form_v') ?>
            </div>
            <div v-show="row.image_id > 0">
                <a class="btn btn-light me-2" id="btn_crop" href="<?= URL_ADMIN . "users/edit/{$row->id}/cropping" ?>">
                    <i class="fas fa-crop"></i> Recortar
                </a>
                <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#deleteModal">
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
var userImageApp = createApp({
    data(){
        return{
            file: null,
            loading: false,
            row: {
                id: <?= $row->id ?>,
                image_id: <?= $row->image_id ?>,
                url_image: '<?= $row->url_image ?>'
            },
            default_image: '<?= URL_IMG ?>users/user.png'
        }
    },
    methods: {
        submitFileForm: function(){
            let form_data = new FormData();
            form_data.append('file_field', this.file);
            form_data.append('table_id', '1000');
            form_data.append('related_1', this.row.id);

            axios.post(URL_API + 'users/set_image/' + this.row.id, form_data, {headers: {'Content-Type': 'multipart/form-data'}})
            .then(response => {
                //Cargar imagen
                if ( response.data.status == 1 )
                { 
                    this.row.image_id = response.data.image_id;
                    this.row.url_image = response.data.url_image;
                    //window.location = URL_APP + 'users/edit/'+ this.row.id + '/cropping';
                }
                //Mostrar respuesta html, si existe
                if ( response.data.html ) { $('#upload_response').html(response.data.html); }
                //Limpiar formulario
                $('#field-file').val('')
            }).catch(function (error) { console.log(error) })
        },
        handleFileUpload(){
            this.file = this.$refs.file_field.files[0]
        },
        deleteElement: function(){
            axios.get(URL_API + 'users/remove_image/' + this.row.id)
            .then(response => {
                if ( response.data.status == 1 ) {
                    this.row.image_id = 0;
                    this.row.url_image = this.default_image;
                    toastr['info']('Imagen eliminada');
                }
            }).catch(function (error) { console.log(error)})
        },
    },
    mounted(){
        //this.getList()
    }
}).mount('#userImageApp')
</script>