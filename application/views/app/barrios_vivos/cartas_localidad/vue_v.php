<script>


var cartasApp = createApp({
    data(){
        return{
            loading: false,
            laboratorios: <?= json_encode($laboratorios) ?>,
            localidades: <?= json_encode($localidades) ?>,
            localidadCod: <?= $localidadCod ?>,
            currLocalidad: {}
        }
    },
    methods: {
        setLocalidad: function(){
            this.currLocalidad = this.localidades.find(localidad => localidad.localidad_cod == this.localidadCod)
        },
    },
    mounted(){
        this.setLocalidad()
    }
}).mount('#cartasApp')
</script>