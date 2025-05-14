
<script>
// Variables
//-----------------------------------------------------------------------------
var arrTipoBV = <?= json_encode($arrTipoBV); ?>;
var arrCategoriaBV = <?= json_encode($arrCategoriaBV); ?>;
var arrFaseBV = <?= json_encode($arrFaseBV); ?>;
var arrDependencia = <?= json_encode($arrDependencia); ?>;

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
            showFilters: true,
            loading: false,
            activeFilters: false,
            arrTipoBV: arrTipoBV,
            arrCategoriaBV: arrCategoriaBV,
            arrFaseBV: arrFaseBV,
            arrDependencia: arrDependencia,
            appRid: parseInt(APP_RID),
            viewFormat: 'table',
        }
    },
    methods: {
        getList: function(e, numPage = 1){
            this.loading = true
            var formValues = new FormData(document.getElementById('searchForm'))
            axios.post(URL_API + this.controller + 'get/' + numPage + '/' + this.perPage, formValues)
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
        deleteElement: function(){
            this.selected = [this.element.id]
            this.deleteSelected()
        },
        deleteSelected: function(){
            this.loading = true
            var formValues = new FormData()
            formValues.append('selected', this.selected)
            axios.post(URL_API + this.controller + 'delete_selected', formValues)
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
            //this.showFilters = false
            setTimeout(() => { this.getList() }, 100)
        },
        calculateShowFilters: function(){
            if ( this.strFilters.length > 0 ) this.showFilters = true
        },
        clearLineaEstrategica: function(){
            this.filters.linea_e = ''
        },
        // Formato y valores
        //-----------------------------------------------------------------------------
        ago: function(date){
            if (!date) return ''
            if (date == '0000-00-00') return ''
            return moment(date, 'YYYY-MM-DD HH:mm:ss').fromNow()
        },
        dateFormat: function(date, format='YYYY-MM-DD'){
            if (!date) return ''
            if (date == '0000-00-00') return 'ND'
            return moment(date).format(format)
        },
        tipoBVName: function(value = '', field = 'name'){
            var tipoBVName = ''
            var item = this.arrTipoBV.find(row => row.cod == value)
            if ( item != undefined ) tipoBVName = item[field]
            return tipoBVName
        },
        categoriaBVName: function(value = '', field = 'name'){
            var categoriaBVName = ''
            var item = this.arrCategoriaBV.find(row => row.cod == value)
            if ( item != undefined ) categoriaBVName = item[field]
            return categoriaBVName
        },
        faseBVName: function(value = '', field = 'name'){
            var faseBVName = ''
            var item = this.arrFaseBV.find(row => row.cod == value)
            if ( item != undefined ) faseBVName = item[field]
            return faseBVName
        },
        dependenciaName: function(value = '', field = 'name'){
            var dependenciaName = ''
            var item = this.arrDependencia.find(row => row.abbreviation == value)
            if ( item != undefined ) dependenciaName = item[field]
            return dependenciaName
        },
    },
    computed: {
        arrTipoBVFiltered(){
            tipoBVValue = this.filters.tipoBV || ''
            if ( tipoBVValue.length == 0 ) {
                return this.arrTipoBV
            } else {
                return this.arrLineaEstrategica.filter(item =>
                    item.parent_id == parseInt(this.filters.estrategia)
                )
            }
        },
        displayPrivate(){
            return this.appRid <= 8
        }
    },
    mounted(){
        this.calculateShowFilters()
    },
}).mount('#appExplore')
</script>