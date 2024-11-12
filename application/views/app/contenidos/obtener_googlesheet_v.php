<div id="getGoogleSheetDataApp">
    <div class="center_box_750">
        <form accept-charset="utf-8" method="POST" id="getDataForm" @submit.prevent="handleSubmit">
            <fieldset v-bind:disabled="loading">
                <div class="mb-3 row">
                    <label for="fileId" class="col-md-4 col-form-label text-end text-right">File ID</label>
                    <div class="col-md-8">
                        <input
                            name="fileId" type="text" class="form-control"
                            required
                            title="File ID" placeholder="File ID"
                            v-model="fields.fileId"
                        >
                    </div>
                </div>

                <div class="mb-3 row">
                    <label for="gid" class="col-md-4 col-form-label text-end text-right">GID</label>
                    <div class="col-md-8">
                        <input
                            name="gid" type="text" class="form-control"
                            required
                            title="GID" placeholder="GID"
                            v-model="fields.gid"
                        >
                    </div>
                </div>

                <div class="mb-3 row">
                    <label for="folder" class="col-md-4 col-form-label text-end text-right">Folder</label>
                    <div class="col-md-8">
                        <input
                            name="folder" type="text" class="form-control"
                            required
                            title="Folder" placeholder="Folder"
                            v-model="fields.folder"
                        >
                    </div>
                </div>

                <div class="mb-3 row">
                    <label for="fileName" class="col-md-4 col-form-label text-end text-right">File name</label>
                    <div class="col-md-8">
                        <input
                            name="fileName" type="text" class="form-control"
                            required
                            title="File name" placeholder="File name"
                            v-model="fields.fileName"
                        >
                    </div>
                </div>
                
                
                <div class="mb-3 row">
                    <div class="col-md-8 offset-md-4">
                        <button class="btn btn-primary w120p" type="submit">Guardar</button>
                    </div>
                </div>
            <fieldset>
        </form>

        <hr>

        <pre class="border bg-black text-white p-2"><code>{{ arrayResponse }}</code></pre>

    </div>
</div>

<script>
var getGoogleSheetDataApp = createApp({
    data(){
        return{
            loading: false,
            fields: {
                fileId: '1YfrRfLgVf4VPN7IfPxXMAKAWiAxctJtxiYNVpAfzNwI',
                gid:'0',
                folder:'barrios_vivos',
                fileName:'preguntas'
            },
            arrayResponse: [
                {
                    id:'1',name:'Ejemplo','Pregunta':'Hola'
                }
            ],
        }
    },
    methods: {
        handleSubmit: function(){
            var urlSubmit = URL_API + 'tools/googlesheet_array/' + this.fields.fileId + '/' + this.fields.gid
            /*+ '/' + this.fields.folder
            + '/' + this.fields.fileName*/
            console.log(urlSubmit)
            axios.get(urlSubmit)
            .then(response => {
                this.arrayResponse = response.data
            })
            .catch(function(error) { console.log(error) })
        },  
    },
    mounted(){
        //this.getList()
    }
}).mount('#getGoogleSheetDataApp')
</script>