
<script>
// Variables
//-----------------------------------------------------------------------------
const arrCat1 = <?= json_encode($arrCat1); ?>;
const arrCat2 = <?= json_encode($arrCat2); ?>;
const arrDocumentType = <?= json_encode($arrDocumentType) ?>;

// VueApp
//-----------------------------------------------------------------------------
var appExplore = createApp({
    data(){
        return{
            cf: '<?= $cf ?>',
            controller: '<?= $controller ?>',
            qtyResults: <?= $qtyResults ?>,
            numPage: <?= $numPage ?>,
            maxPage: <?= $maxPage ?>,
            list: <?= json_encode($list) ?>,
            filters: <?= json_encode($filters) ?>,
            strFilters: '<?= $strFilters ?>',
            perPage: 10,
            element: [],
            selected: [],
            allSelected: false,
            showFilters: false,
            loading: false,
            activeFilters: false,
            arrCat1: arrCat1,
            arrCat2: arrCat2,
            arrDocumentType: arrDocumentType,
        }
    },
    methods: {
        getList: function(e, numPage = 1){
            this.loading = true
            var formValues = new FormData(document.getElementById('searchForm'))
            axios.post(URL_APP + this.controller + '/get/' + numPage + '/' + this.perPage, formValues)
            .then(response => {
                this.numPage = numPage
                this.list = response.data.list
                this.maxPage = response.data.maxPage
                this.qtyResults = response.data.qtyResults
                this.strFilters = response.data.strFilters
                history.pushState(null, null, URL_APP + this.cf + this.numPage + '/?' + response.data.strFilters)
                this.allSelected = false
                this.selected = []
                this.loading = false
            })
            .catch(function (error) { console.log(error) })
        },
        selectAll: function() {
            if ( this.allSelected )
            { this.selected = this.list.map(function(element){ return element.id }) }
            else
            { this.selected = [] }
        },
        sumPage: function(sum){
            var newNumPage = Pcrn.limit_between(this.numPage + sum, 1, this.maxPage)
            this.getList(null, newNumPage)
        },
        deleteSelected: function(){
            this.loading = true
            var formValues = new FormData()
            formValues.append('selected', this.selected)
            axios.post(URL_APP + this.controller + '/delete_selected', formValues)
            .then(response => {
                this.hideDeleted()
                this.selected = []
                if ( response.data.qty_deleted > 0 )
                {
                    toastr['info']('Registros eliminados: ' + response.data.qty_deleted)
                }
                this.loading = false
            })
            .catch(function (error) { console.log(error) })
        },
        hideDeleted: function(){
            this.selected.forEach(rowId => {
                $('#row_' + rowId).addClass('table-danger')
                $('#row_' + rowId).hide('slow')
            })
        },
        setCurrent: function(key){
            this.element = this.list[key]
        },
        toggleFilters: function(){
            this.showFilters = !this.showFilters
        },
        clearFilters: function(){
            Object.keys(this.filters).forEach(key => {
                this.filters[key] = ''
            })
            this.showFilters = false
            setTimeout(() => { this.getList() }, 100)
        },
        calculateShowFilters: function(){
            if ( this.strFilters.length > 0 ) this.showFilters = true
        },
        clearCat2: function(){
            this.filters.cat_2 = ''
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
        cat1Name: function(value = '', field = 'name'){
            var cat1Name = ''
            var item = arrCat1.find(row => row.cod == value)
            if ( item != undefined ) cat1Name = item[field]
            return cat1Name
        },
        cat2Name: function(value = '', field = 'name'){
            var cat2Name = ''
            var item = arrCat2.find(row => row.cod == value)
            if ( item != undefined ) cat2Name = item[field]
            return cat2Name
        },
        documentTypeName: function(value = '', field = 'name'){
            var documentTypeName = ''
            var item = arrDocumentType.find(row => row.cod == value)
            if ( item != undefined ) documentTypeName = item[field]
            return documentTypeName
        },
    },
    computed: {
        arrCat2Filtered(){
            cat1Value = this.filters.cat_1 || ''
            if ( cat1Value.length == 0 ) {
                return this.arrCat2
            } else {
                return this.arrCat2.filter(item =>
                    item.parent_id == parseInt(this.filters.cat_1)
                )
            }
        },
    },
    mounted(){
        this.calculateShowFilters()
    },
}).mount('#appExplore')
</script>