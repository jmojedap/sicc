<script>
var apikey = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6InZ6YmRteXR6cXR2Z2lkcXBpaWRsIiwicm9sZSI6ImFub24iLCJpYXQiOjE2NTk4MDE0NDcsImV4cCI6MTk3NTM3NzQ0N30.wXQuMWCGVlOnHXQw6zpok8uh0e4Ov_BQxVmlbvxELis';
var tableName = 'obligaciones'
var sbProjectAPI = 'https://vzbdmytzqtvgidqpiidl.supabase.co/rest/v1/'
var requestHeaders = {
        'Content-Type':'application/json',
        'apikey': apikey,
        'Authorization': 'Bearer ' + apikey
    }

// VueApp
//-----------------------------------------------------------------------------
var supabaseApp = createApp({
    data() {
        return {
            section: 'list',
            loading: false,
            loadingPosts: true,
            obligaciones: [],
            currentKey: -1,
            updateSegment: '',
            fields: {
                id: 0,
                nombre_obligacion: ''
            },
        }
    },
    methods: {
        getPosts: function() {
            axios.get(sbProjectAPI + tableName + '?select=*', {
                    headers: {
                        'apikey': apikey,
                        'Authorization': 'Bearer ' + apikey
                    },
                })
                .then(response => {
                    console.log(response.data)
                    this.obligaciones = response.data
                    this.loadingPosts = false
                })
                .catch(function(error) {
                    console.log(error)
                })
        },
        setCurrent: function(key) {
            console.log(key)
            this.currentKey = key
            this.fields = this.obligaciones[key]
            this.section = 'form'
            this.updateSegment = '?id=eq.' + this.fields.id
        },
        handleSubmit: function(){
            this.loading = true
            var formValues = new FormData(document.getElementById('obligacionForm'))
            if ( this.currentKey >= 0 ) {
                this.updateRow()
            } else {
                this.insertRow()
            }
        },
        insertRow: function(){
            axios.post(sbProjectAPI + tableName, this.fields, {
                headers: requestHeaders,
            })
            .then(response => {
                console.log(response.status)
                if ( response.status == 201 ) {
                    toastr['success']('Registro creado')
                }
                this.loading = false
            })
        },
        updateRow: function(){
            axios.patch(sbProjectAPI + tableName + '?id=eq.' + this.fields.id, this.fields,{
                headers: requestHeaders
            })
            .then(response => {
                if ( response.status == 204 ) {
                    toastr['success']('Registro actualizado')
                }
                this.loading = false
            })
        },
        setAddPost: function(){
            this.currentKey = -1,
            this.fields = {
                num_obligacion: 0,
                nombre_obligacion: '',
                descripcion: '',
            }
            this.setSection('form')
            this.updateSegment = ''
        },
        setSection: function(newSection){
            this.section = newSection
        },
    },
    mounted() {
        this.getPosts()
    }
}).mount('#supabaseApp')
</script>