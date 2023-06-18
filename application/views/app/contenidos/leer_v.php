<div id="leerApp">
    <div class="center_box_750">
        <div class="d-flex">
            <div class="w40p me-2">
                <img
                    v-bind:src="author.url_thumbnail"
                    class="rounded rounded-circle w100pc"
                    v-bind:alt="author.username"
                    onerror="this.src='<?= URL_IMG ?>users/sm_user.png'"
                >
            </div>
            <div>
                <span class="text-secondary">{{ author.display_name }}</span>
                <p>{{ dateFormat(post.published_at) }} &middot; <small>{{ ago(post.published_at) }}</small></p>
            </div>
        </div>
        <?php if ( in_array($this->session->userdata('role'), [1,2] )) : ?>
            <div class="mb-3">
                <a v-bind:href="`<?= URL_ADMIN . "posts/edit/" ?>` + post.id" class="btn btn-light btn-sm w100p">
                    Editar
                </a>
            </div>
        <?php endif; ?>
        <h1 id="page-title">{{ post.post_name }}</h1>
        <p class="lead">{{ post.excerpt }}</p>
        <div class="w-100" v-html="post.content_embed" v-if="post.content_embed.length > 5"></div>
        <div class="mb-2" v-html="post.content"></div>
    </div>
</div>

<script>
var leerApp = createApp({
    data(){
        return{
            loading: false,
            fields: {},
            post: <?= json_encode($row) ?>,
            author: <?= json_encode($author) ?>,
            ratiosClass:{
                10:'ratio-1x1',
                20:'ratio-16x9',
                30:'ratio-16x9',
                50:'ratio-16x9',
            }
        }
    },
    methods: {
        // Formato y valores
        //-----------------------------------------------------------------------------
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
}).mount('#leerApp')
</script>