
<script>
// Variables
//-----------------------------------------------------------------------------
var status_icons = {
    "0":'<i class="far fa-circle text-danger" title="Inactivo"></i>',
    "1":'<i class="fa fa-check-circle text-success" title="Activo"></i>'
};

// VueApp
//-----------------------------------------------------------------------------
var appExploree = createApp({
    data(){
        return{
            cf: '<?= $cf ?>',
            controller: '<?= $controller ?>',
            qtyResults: <?= $qtyResults ?>,
            numPage: <?= $numPage ?>,
            maxPage: <?= $maxPage ?>,
            perPage: <?= $perPage ?>,
            list: <?= json_encode($list) ?>,
            element: [],
            selected: [],
            all_selected: false,
            filters: <?= json_encode($filters) ?>,
            str_filters: '<?= $str_filters ?>',
            display_filters: false,
            loading: false,
            active_filters: false,
            arrRole: <?= json_encode($arrRole) ?>,
            today: '<?= date('Y-m-d') ?>',
        }
    },
    methods: {
        get_list: function(e, numPage = 1){
            this.loading = true
            axios.post(URL_API + this.controller + '/get/' + numPage, $('#search_form').serialize())
            .then(response => {
                this.numPage = numPage
                this.list = response.data.list
                this.maxPage = response.data.maxPage
                this.qtyResults = response.data.qtyResults
                this.str_filters = response.data.str_filters
                history.pushState(null, null, URL_APP + this.cf + this.numPage +'/?' + response.data.str_filters)
                this.all_selected = false
                this.selected = []
                this.loading = false

                this.calculate_active_filters()
            })
            .catch(function (error) { console.log(error) })
        },
        select_all: function() {
            if ( this.all_selected )
            { this.selected = this.list.map(function(element){ return element.id }) }
            else
            { this.selected = [] }
        },
        sumPage: function(sum){
            var new_numPage = Pcrn.limit_between(this.numPage + sum, 1, this.maxPage)
            this.get_list(null, new_numPage)
        },
        deleteSelected: function(){
            var params = new FormData()
            params.append('selected', this.selected)
            
            axios.post(URL_APP + this.controller + '/delete_selected', params)
            .then(response => {
                this.hideDeleted()
                this.selected = []
                if ( response.data.qty_deleted > 0 )
                {
                    toastr['info']('Registros eliminados: ' + response.data.qty_deleted)
                }
            })
            .catch(function (error) { console.log(error) })
        },
        hideDeleted: function(){
            for ( let index = 0; index < this.selected.length; index++ )
            {
                const element = this.selected[index]
                console.log('ocultando: row_' + element)
                $('#row_' + element).addClass('table-danger')
                $('#row_' + element).hide('slow')
            }
        },
        set_current: function(key){
            this.element = this.list[key]
        },
        toggle_filters: function(){
            this.display_filters = !this.display_filters
            $('#adv_filters').toggle('fast')
        },
        clearFilters: function(){
            Object.keys(this.filters).forEach(key => {
                this.filters[key] = ''
            })
            //this.showFilters = false
            setTimeout(() => { this.getList() }, 100)
        },
        calculate_active_filters: function(){
            var calculated_active_filters = false
            if ( this.filters.q ) calculated_active_filters = true
            if ( this.filters.role ) calculated_active_filters = true
            if ( this.filters.fe1 ) calculated_active_filters = true
            if ( this.filters.fe2 ) calculated_active_filters = true
            if ( this.filters.d1 ) calculated_active_filters = true
            if ( this.filters.d2 ) calculated_active_filters = true

            this.active_filters = calculated_active_filters
        },
        // Especiales users
        //-----------------------------------------------------------------------------
        roleName: function(value = '', field = 'name'){
            var roleName = ''
            var item = this.arrRole.find(row => row.cod == value)
            if ( item != undefined ) roleName = item[field]
            return roleName
        },
        ago: function(date){
            if (!date) return ''
            return moment(date, "YYYY-MM-DD HH:mm:ss").fromNow()
        },
        status_icon: function(value){
            if (!value) return ''
            value = status_icons[value]
            return value
        },
        age: function(date){
            if (!date) return ''
            return moment().diff(date, 'years',false)
        },
    },
    mounted(){
        this.calculate_active_filters()
    }
}).mount('#appExplore');
</script>