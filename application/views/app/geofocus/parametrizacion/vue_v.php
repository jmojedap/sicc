<script>
var parametrizacionApp = createApp({
    data(){
        return{
            loading: false,
            parametrizacion:{ id:1 },
            display: {
                descripcion: false
            },
            currentVariable: {},
            variables: <?= json_encode($variables) ?>,
        }
    },
    methods: {
        handleSubmitAnt: function(){
            this.loading = true
            var formValues = new FormData(document.getElementById('parametrizacionForm'))
            axios.post(URL_API + 'geofocus/calcular_priorizacion/' + this.parametrizacion.id, formValues)
            .then(response => {
                
                this.loading = false
            })
            .catch( function(error) {console.log(error)} )
        },
        handleSubmit: function(){
            this.loading = true
            axios.post(URL_API + 'geofocus/calcular_priorizacion/' + this.parametrizacion.id, this.variables, {
                headers: {
                    'Content-Type': 'application/json'
                }
            })
            .then(response => {
                this.loading = false
            })
            .catch( function(error) {console.log(error)} )
        },
        setCurrent: function(variable){
            this.currentVariable = variable
        },
        startVariables: function(){
            this.variables.forEach(variable => {
                variable.active = false;
            });
        },
    },
    mounted(){
        //this.getList()
        this.startVariables()
    }
}).mount('#parametrizacionApp')
</script>