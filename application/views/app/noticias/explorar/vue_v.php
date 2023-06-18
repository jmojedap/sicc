
<script>
// Variables
//-----------------------------------------------------------------------------
var categories = <?= json_encode($options_cat_1); ?>;
var arrClasificacion = <?= json_encode($options_clasificacion); ?>;

// VueApp
//-----------------------------------------------------------------------------
var appExplore = createApp({
    data(){
        return{
            cf: '<?= $cf ?>',
            controller: '<?= $controller ?>',
            qtyResults: <?= $search_num_rows ?>,
            numPage: <?= $num_page ?>,
            maxPage: <?= $max_page ?>,
            list: <?= json_encode($list) ?>,
            element: [],
            selected: [],
            allSelected: false,
            filters: <?= json_encode($filters) ?>,
            strFilters: <?= json_encode($str_filters) ?>,
            loading: false,
            options_cat_1: <?= json_encode($options_cat_1) ?>,
            options_clasificacion: <?= json_encode($options_clasificacion) ?>,
        }
    },
    methods: {
        getList: function(e, numPage = 1){
            this.loading = true
            var formValues = new FormData(document.getElementById('searchForm'))
            axios.post(URL_APP + this.controller + '/get/' + numPage, formValues)
            .then(response => {
                this.numPage = numPage
                this.list = response.data.list
                this.maxPage = response.data.max_page
                this.qtyResults = response.data.search_num_rows
                this.strFilters = response.data.str_filters
                history.pushState(null, null, URL_APP + this.cf + this.numPage + '/?' + this.strFilters)
                this.allSelected = false
                this.selected = []
                this.loading = false
            })
            .catch(function (error) { console.log(error) })
        },
        sumPage: function(sum){
            var newNumPage = Pcrn.limit_between(this.numPage + sum, 1, this.maxPage)
            this.getList(null, newNumPage)
        },
        setCurrent: function(key){
            this.element = this.list[key]
        },
        toggle_filters: function(){
            this.display_filters = !this.display_filters
            $('#adv_filters').toggle('fast')
        },
        removeFilters: function(){
            var obj = this.filters
            Object.keys(obj).forEach(key => {
                this.filters[key] = null
            });
            setTimeout(() => { this.getList() }, 100)
        },
        clasificacionClass: function(value){
            if (!value) return ''
            var clasificacion = arrClasificacion.find(item => item.value == value)
            if ( clasificacion === undefined ) {
                return ''
            } else {
                return clasificacion.infoClass
            }
        },
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
        clasificationName: function(value){
            if (!value) return 'ND'
            var clasificacion = arrClasificacion.find(item => item.value == value)
            if ( clasificacion === undefined ) {
                return 'ND'
            } else {
                return clasificacion.title
            }
        },
        catName: function(value){
            if (!value) return 'ND'
            var category = categories.find(item => item.id == value)
            if ( category === undefined ) {
                return 'ND'
            } else {
                return category.title
            }
        },
    }
}).mount('#appExplore')
</script>