<script>
var parametrizacionApp = createApp({
    data(){
        return{
            section: 'variables',
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
            allSelected: false,
            territorios: [],
        }
    },
    methods: {
        validateSubmit: function(){
            if ( this.validateForm() ) {
                this.submitForm()
            } else {
                toastr['warning']('No se pudo procesar la petición')
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
                this.section = 'territorios'
            })
            .catch( function(error) {console.log(error)} )
        },
        setCurrent: function(variable){
            this.currentVariable = variable
        },
        startVariables: function(){
            this.variables.forEach(variable => {
                variable.active = false;
                variable.tipo_priorizacion = 1;
            });
        },
        validateForm: function(){
            if ( this.variablesActivas.length == 0 ) return false
            return true
        },
        toggleSelectAll: function(){
            this.variables.forEach(variable => {
                variable.active = this.allSelected;
            });
        },
        toggleActivateVariable: function(index){
            this.variables[index].active = !this.variables[index].active
        },
        setTipoPriorizacion: function(index, newValue){
            console.log(newValue)
            this.variables[index].tipo_priorizacion = newValue
        },
        normalizarVariable: function(variable){
            axios.get(URL_API + 'geofocus/normalizar_variable/' + variable.id)
            .then(response => {
                console.log(response.data)
            })
            .catch(function(error) { console.log(error) })
        },
        setSection: function(newSection){
            this.section = newSection
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