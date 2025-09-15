<?php
    $email = $this->session->userdata('email') ?? '';
    $display_name = $this->session->userdata('display_name') ?? '';
?>

<script>
var agendarmeApp = createApp({
    data(){
        return{
            section: 'form',
            loading: false,
            mesasViernesTarde: mesasViernesTarde,
            mesasSabado: mesasSabado,
            recorridosDomingo: recorridosDomingo,
            fields: {
                email: '<?= $email ?>',
                display_name: '<?= $display_name ?>',
                viernes_tarde: '',
                viernes_tarde_opcion_2: '',
                sabado_manana_opcion_1: '',
                sabado_manana_opcion_2: '',
                recorrido_domingo: '',
            },
            invitados: <?= json_encode($elementos) ?>,
        }
    },
    methods: {
        handleSubmit: function(){
            this.loading = true
            var formValues = new FormData(document.getElementById('agendarme-form'))
            axios.post(URL_API + 'red_cultural/guardar_agenda/', formValues)
            .then(response => {
                if ( response.data.qty_saved >= 2 ) {
                    this.section = 'success'
                }
                this.loading = false
            })
            .catch( function(error) {console.log(error)} )
        },
        setDisplayName: function(){
            console.log('setDisplayName')
            // Buscar en this.invitados por email
            let invitado = this.invitados.find( invitado => invitado.email.toLowerCase() === this.fields.email.toLowerCase() )
            if ( invitado ) {
                this.fields.display_name = invitado.nombre_completo
            } else {
                this.fields.display_name = this.fields.email.split('@')[0];
            }
        },
    },
    mounted(){
        //this.getList()
    }
}).mount('#agendarmeApp')
</script>