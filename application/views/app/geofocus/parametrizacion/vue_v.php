<script>
var parametrizacionApp = createApp({
    data(){
        return{
            loading: false,
            priorizacion:{
                id: 1,
                slug: 'priorizacion-pruebas',
                nombre: 'Priorización Pruebas',
                descripcion: 'Priorización Pruebas'
            },
            display: {
                descripcion: false
            },
            currentVariable: {},
            variables: <?= json_encode($variables) ?>,
            territorios: [],
        }
    },
    methods: {
        validateSubmit: function(){
            if ( this.validateForm() ) {
                this.submitForm()
            } else {
                toastr['warning']('No se pudo proocesar la petición')
            }
        },
        submitForm: function(){
            this.loading = true
            var payload = {
                'priorizacion': this.priorizacion,
                'variables': this.variablesActivas
            }
            axios.post(URL_API + 'geofocus/calcular_priorizacion/', payload, {
                headers: { 'Content-Type': 'application/json'}
            })
            .then(response => {
                this.loading = false
                this.territorios = response.data.territorios
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
        validateForm: function(){
            if ( this.variablesActivas.length == 0 ) return false
            return true
        },
    },
    computed: {
        variablesActivas: function(){
            return this.variables.filter(variable => variable.active == true)
        },
    },
    mounted(){
        this.startVariables()
    }
}).mount('#parametrizacionApp')
</script>