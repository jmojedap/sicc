
<script>
// Variables
//-----------------------------------------------------------------------------
//var arrType = <?php //echo json_encode($arrType); ?>;
var arrEstadoPublicacion = <?= json_encode($arrEstadoPublicacion) ?>;
var arrFormato = <?= json_encode($arrFormato) ?>;
var arrTipo = <?= json_encode($arrTipo) ?>;
var arrTema = <?= json_encode($arrTema) ?>;
var arrSubtema = <?= json_encode($arrSubtema) ?>;
var arrEntidad = <?= json_encode($arrEntidad) ?>;
var arrSiNoNa = <?= json_encode($arrSiNoNa) ?>;

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
            perPage: <?= $perPage ?>,
            element: [],
            selected: [],
            allSelected: false,
            showFilters: true,
            loading: false,
            activeFilters: false,
            arrEstadoPublicacion: arrEstadoPublicacion,
            arrFormato: arrFormato,
            arrTipo: arrTipo,
            arrTema: arrTema,
            arrSubtema: arrSubtema,
            arrEntidad: arrEntidad,
            arrSiNoNa: arrSiNoNa,
            viewFormat: 'list',
            showDetails: false,
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
            //this.showFilters = false
            setTimeout(() => { this.getList() }, 100)
        },
        calculateShowFilters: function(){
            if ( this.strFilters.length > 0 ) this.showFilters = true
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
        tipoName: function(value = '', field = 'name'){
            var tipoName = 'ND'
            var item = this.arrTipo.find(row => row.cod == value)
            if ( item != undefined ) tipoName = item[field]
            return tipoName
        },
        estadoPublicacionName: function(value = '', field = 'name'){
            var estadoPublicacionName = ''
            var item = arrEstadoPublicacion.find(row => row.cod == value)
            if ( item != undefined ) estadoPublicacionName = item[field]
            return estadoPublicacionName
        },
        subtemaName: function(value = '', field = 'name'){
            var subtemaName = ''
            var item = this.arrSubtema.find(row => row.cod == value)
            if ( item != undefined ) subtemaName = item[field]
            return subtemaName
        },
        entidadName: function(value = '', field = 'name'){
            var entidadName = ''
            var item = this.arrEntidad.find(row => row.cod == value)
            if ( item != undefined ) entidadName = item[field]
            return entidadName
        },

    },
    mounted(){
        this.calculateShowFilters()
    }
}).mount('#appExplore')
</script>