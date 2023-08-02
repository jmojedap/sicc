<div id="ayudaMeccApp">
    <div class="row">
        <div class="col-md-4">
        <div class="list-group">
            <a href="#" class="list-group-item list-group-item-action" aria-current="true" v-for="(post,i) in posts"
                v-bind:class="{'active': post.id == currentPost.id }" v-on:click="setCurrentPost(i)"
            >
                {{ post.title }}
            </a>
            </div>
        </div>
        <div class="col-md-8">
            <iframe v-bind:src="`https://drive.google.com/file/d/` + currentPost.idFile + `/preview`" width="785" height="422"></iframe>
        </div>
    </div>
</div>

<script>
var ayudaMeccApp = createApp({
    data(){
        return{
            loading: true,
            posts: [
                {
                    id:1,
                    title:'Login en Herramienta Prototipo',
                    idFile:'1ljtWC3ipF1WVyxGKTXZwJ4bUrIxxcMVw',
                },
                {
                    id:2,
                    title:'Registrar una acción CC',
                    idFile:'1C7XJRM85ZWArMebL1jbCWf8lqqU3ap81',
                },
                {
                    id:3,
                    title:'Geolocalizar una acción CC',
                    idFile:'1ztzTDO-SnJAwwfFUO_5Cal7src1Jpv0d',
                },
            ],
            currentPost: {
                id:0,
                title:'Cargando...',
                idFile:''
            }
        }
    },
    methods: {
        setCurrentPost: function(key){
            this.currentPost = this.posts[key]
            this.loading = false
        },
    },
    mounted(){
        this.setCurrentPost(0)
    }
}).mount('#ayudaMeccApp')
</script>