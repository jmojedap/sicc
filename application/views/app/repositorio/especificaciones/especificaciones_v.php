<style>
.docs h1 {
    color: #5e4296;
    text-align: center;
}

.docs p{
    text-align: justify;
}

.docs h2 {
    color: #0256d5;
    padding-top: 0.5em;
    margin-bottom: 0.5em;
    border-bottom: 1px solid #CCC;
}

.docs img{
    width: 100%;
    border: 1px solid #DDD;
    border-radius: 0.2em;
}
</style>

<div>
    <div class="row">
        <div class="col-md-3" id="especificacionesApp">
            <div class="mb-2">
                <a href="<?= URL_APP ?>repositorio/especificaciones_print" class="btn btn-light w100pc" target="_blank">
                    <i class="fa-solid fa-file-pdf text-danger"></i> Para impresión
                </a>
            </div>
            <div class="list-group">
                <button type="button" class="list-group-item list-group-item-action"
                    v-for="(item,index) in indice" v-on:click="setDoc(index)"  aria-current="" v-bind:class="{'active': index == currentIndex }">
                    {{ item.title }}
                </button>
            </div>
            <div class="mt-2 pt-2">
                <p class="text-muted text-center">
                    {{ currentDoc.title }} <br>
                    {{ dateFormat(currentDoc.updated_at) }} &middot;
                    {{ ago(currentDoc.updated_at) }}
                </p>
            </div>
            <div class="text-center mt-2" v-show="loading">
                <div class="spinner-border text-secondary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>                  
        </div>
        <div class="col-md-9">
            <div class="center_box_750">
                <div class="docs" id="docs-content"></div>
            </div>
        </div>
    </div>
</div>

<script>
var especificacionesApp = createApp({
    data() {
        return {
            loading: false,
            pageSlug: '<?= $page_slug ?>',
            indice: <?= $indice ?>,
            content: '',
            currentDoc: {},
            currentIndex: 0,
        }
    },
    methods: {
        setDoc: function(index){
            this.currentIndex = index
            this.currentDoc = this.indice[index]
            document.title = 'Repositorio - ' + this.currentDoc.title
            this.getDocContent()
        },
        getDocContent: function(){
            this.loading = true
            var formValues = new FormData()
            formValues.append('type',this.currentDoc.type)
            formValues.append('file_path', 'docs/componentes/repositorio/' + this.currentDoc.pageName)
            formValues.append('view_path', this.currentDoc.pageName)
            axios.post(URL_APP + 'app/get_doc/', formValues)
            .then(response => {
                $("#docs-content").html(response.data.content)
                this.loading = false
                history.pushState(null, null, URL_APP + 'repositorio/especificaciones/' + this.currentDoc.slug)
            })
            .catch( function(error) {console.log(error)} )
        },
        startIndex: function(){
            var defaultIndex = this.indice.findIndex(doc => doc.slug == this.pageSlug)
            if (defaultIndex >= 0) {
                this.currentIndex = defaultIndex
            } else {
                this.currentIndex = 0
            }
        },
        ago: function(date){
            if (!date) return ''
            return moment(date, 'YYYY-MM-DD HH:mm:ss').fromNow()
        },
        dateFormat: function(date){
            if (!date) return ''
            return moment(date).format('D MMM YYYY')
        },
    },
    mounted() {
        this.startIndex()
        this.setDoc(this.currentIndex)
    }
}).mount('#especificacionesApp')
</script>