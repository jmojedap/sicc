<script>
// Variables
//-----------------------------------------------------------------------------
var appStates = {
    add: { buttonText: 'Agregar', buttonClass: 'btn-success'},
    edit: { buttonText: 'Actualizar', buttonClass: 'btn-primary'},
    saved: { buttonText: 'Guardado', buttonClass: 'btn-success' },
    inserted: { buttonText: 'Guardado', buttonClass: 'btn-success'},
    updated: { buttonText: 'Actualizado', buttonClass: 'btn-success'}
};

var baseUrl = '<?= base_url() ?>';
var categoryCod = <?= $category_cod ?>;
var categories = <?= json_encode($categories->result()) ?>;
var startCategory = categories.find(category => category.cod == categoryCod);
var scope = '<?= $scope ?>';

// VueApp
//-----------------------------------------------------------------------------
var itemsValuesApp = createApp({
    data(){
        return{
            loading: false,
            filters: {q:'',fe2:scope},
            categories: categories,
            categoryKey: 0,
            currCategory: startCategory,
            rowKey: 0,
            rowId: 0,
            list: [],
            item: {},
            marginValue: '50',
            fields: {
                id: 0,
                item_name: '',
                cod: '',
                abbreviation: '',
                filter: '',
                parent_id: '',
                slug: '',
                description: '',
                long_name: '',
                short_name: '',
                item_group: '',
                level: '',
                ancestry: '',
            },
            formConfig: {
                title: 'Nuevo elemento',
                buttonText: 'Agregar',
                buttonClass: 'btn-primary'
            },
            appState: appStates.add,
            scopes: itemsScopes, //items-scopes.js
            appRid: APP_RID,
            displayFormat: 'table',
            deleteConfirmationTexts : {
                title: 'Eliminar ítem',
                text: '¿Confirma la eliminación de este ítem?',
                buttonText: 'Eliminar'
            }
        }
    },
    methods: {
        getCategories: function(){
            this.loading = true
            var formValues = new FormData()
            formValues.append('q', this.filters.q)
            formValues.append('cat_1', 0)
            formValues.append('fe2', this.filters.fe2)
            axios.post(URL_API + 'items/get/', formValues)
            .then(response => {
                this.categories = response.data.list
                this.setFirstCategory()
                this.loading = false
            })
            .catch( function(error) {console.log(error)} )
        },
        setFirstCategory: function(){
            if ( this.categories.length > 0 ) {
                this.currCategory = this.categories[0]
                this.getList()
                this.setMarginValue(0)
            }
        },
        clearFilters: function(){
            this.filters.q = ''
            this.getCategories()
        },
        getList: function (){
            axios.get(URL_API + 'items/get_list/' + this.currCategory.cod)
            .then(response => {
                this.list = response.data
                history.pushState(null, null, URL_APP + 'items/values/' + this.currCategory.cod + '/' + this.filters.fe2);
            })
            .catch(function (error) { console.log(error) })
        },
        //Cargar el formulario con datos de un elemento (key) de la list
        loadFormValues: function (key){
            this.appState = appStates.edit
            this.rowId = this.list[key].id
            for ( field in this.fields ) { this.fields[field] = this.list[key][field] }
            this.fields.parent_id = '0' + this.list[key].parent_id
            this.formConfig.title = 'Editar: ' + this.list[key].item_name
        },
        //Establece un elemento como el actual
        setCurrent: function(key) {
            this.rowId = this.list[key].id
            this.rowKey = key
            this.item = this.list[key]
            console.log(this.rowId)
        },
        setCategory: function(categoryCod){
            //var categoryKey = 0
            var categoryKey = this.categories.findIndex(row => row.cod == categoryCod)
            if ( categoryKey < 0 ) categoryKey = 0

            console.log('soy: ', categoryKey)
            this.categoryKey = categoryKey
            this.currCategory = this.categories[categoryKey]
            this.setMarginValue()
            
            this.getList()
        },
        setMarginValue: function(category_key){
            this.marginValue = 0 + category_key * 42
        },
        handleSubmit: function(){
            this.loading = true
            var formValues = new FormData(document.getElementById('itemForm'))
            axios.post(URL_API + 'items/save/' + this.rowId, formValues)
            .then(response => {
                if ( response.data.status == 1 ) 
                {
                    appState = appStates.saved
                    toastr['success']('Registro guardado')
                    
                    if ( this.rowId > 0 ) {
                        this.appState = appStates.updated
                    } else {
                        this.appState = appStates.inserted
                        for ( key in this.fields ) this.fields[key] = ''
                    }
                    
                    this.getList()
                    this.rowId = response.data.saved_id
                }
                this.loading = false
                
            })
            .catch(function (error) { console.log(error) })
        },
        autocomplete: function(){
            this.setNames()
            if ( this.fields.abbreviation.length == 0 ) { this.setAbbreviation() }
            if ( this.fields.slug.length == 0 ) { this.setSlug() }
        },
        deleteElements: function(){
            axios.get(URL_API + 'items/delete/' + this.rowId + '/' + this.currCategory.cod)
            .then(response => {
                if ( response.data.status == 1 )
                {
                    this.getList()
                    toastr['info']('Elemento eliminado')
                }
            })
            .catch(function (error) { console.log(error) })
        },
        clearForm: function() {
            this.rowId = 0;
            this.rowKey = 0;
            for ( key in this.fields ) { this.fields[key] = ''; }
            this.formConfig.title = 'Nuevo elemento';
            this.appState = appStates.add;
        },
        setNames: function(){
            if ( this.fields.description.length == 0 ) { 
                this.fields.description = this.currCategory.item_name + ', ' + this.fields.item_name
            }
            if ( this.fields.short_name.length == 0 ) { this.fields.short_name = this.fields.item_name }
        },
        setAbbreviation: function() {
            var abbreviation = '';
            abbreviation = this.fields.item_name.substr(0,3);
            abbreviation = abbreviation.toLowerCase();
            this.fields.abbreviation = abbreviation;
        },
        //Establecer item.slug
        setSlug: function() {
            const formValues = new FormData();
            formValues.append('text', this.fields.item_name);
            formValues.append('table', 'items');
            formValues.append('field', 'slug');
            
            axios.post(baseUrl + 'tools/unique_slug/', formValues)
            .then(response => {
                console.log(response.data)
                this.fields.slug = response.data
            })
            .catch(function (error) { console.log(error) })
        },
    },
    mounted(){
        //this.getCategories()
        this.setCategory(categoryCod)
    }
}).mount('#itemsValuesApp')
</script>