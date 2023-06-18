
<script>
Vue.filter('date_format', function (date) {
    if (!date) return ''
    return moment(date).format('dddd, MMMM D [de] YYYY')
});

Vue.filter('ago', function (date) {
    if (!date) return ''
    return moment(date, 'YYYY-MM-DD HH:mm:ss').fromNow()
});

// App
//-----------------------------------------------------------------------------

var app_explore = new Vue({
    el: '#app_explore',
    created: function(){
        this.calculate_active_filters()
    },
    data: {
        cf: '<?= $cf ?>',
        controller: '<?= $controller ?>',
        search_num_rows: <?= $search_num_rows ?>,
        num_page: <?= $num_page ?>,
        max_page: <?= $max_page ?>,
        list: <?= json_encode($list) ?>,
        element: [],
        selected: [],
        all_selected: false,
        filters: <?= json_encode($filters) ?>,
        str_filters: '<?= $str_filters ?>',
        display_filters: false,
        loading: false,
        arrType: <?= json_encode($arr_type) ?>,
        active_filters: false
    },
    methods: {
        get_list: function(e, num_page = 1){
            this.loading = true
            axios.post(URL_APP + this.controller + '/get/' + num_page, $('#search_form').serialize())
            .then(response => {
                this.num_page = num_page
                this.list = response.data.list
                this.max_page = response.data.max_page
                this.search_num_rows = response.data.search_num_rows
                this.str_filters = response.data.str_filters
                history.pushState(null, null, URL_APP + this.cf + this.num_page +'/?' + response.data.str_filters)
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
        sum_page: function(sum){
            var new_num_page = Pcrn.limit_between(this.num_page + sum, 1, this.max_page)
            this.get_list(null, new_num_page)
        },
        delete_selected: function(){
            var params = new FormData()
            params.append('selected', this.selected)
            
            axios.post(URL_APP + this.controller + '/delete_selected', params)
            .then(response => {
                this.hide_deleted()
                this.selected = []
                if ( response.data.qty_deleted > 0 )
                {
                    toastr['info']('Registros eliminados: ' + response.data.qty_deleted)
                }
            })
            .catch(function (error) { console.log(error) })
        },
        hide_deleted: function(){
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
        remove_filters: function(){
            this.filters.q = ''
            this.filters.type = ''
            this.display_filters = false
            //$('#adv_filters').hide()
            setTimeout(() => { this.get_list() }, 100)
        },
        calculate_active_filters: function(){
            var calculated_active_filters = false
            if ( this.filters.q ) calculated_active_filters = true
            if ( this.filters.type ) calculated_active_filters = true            

            this.active_filters = calculated_active_filters
        },
        // Funciones especiales para places
        //-----------------------------------------------------------------------------
        toggle_business_days: function(key){
            var period_id = this.list[key].id
            axios.get(URL_API + 'periods/toggle_business_days/' + period_id)
            .then(response => {
                if ( response.data.saved_id > 0 ) {
                    this.list[key].qty_business_days = response.data.qty_business_days
                }
            })
            .catch( function(error) {console.log(error)} )
        },
        placeTypeLabel: function(value, field){
            if (!value) return 'ND'
            var placeType = this.arrDocumentType.find(item => item.cod == value)
            if ( placeType === undefined ) {
                return 'ND'
            } else {
                return placeType[field]
            }
        },
    }
})
</script>