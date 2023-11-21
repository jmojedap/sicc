<script>
var solicitudesApp = createApp({
    data(){
        return{
            loading: false,
            fields: {},
            arrEntidades: <?= json_encode($arrEntidades) ?>,
        }
    },
    methods: {
        handleSubmit: function(){
            toastr['success']('Solicitud registrada')
        },
    },
    mounted(){
        //this.getList()
    }
}).mount('#solicitudesApp')
</script>