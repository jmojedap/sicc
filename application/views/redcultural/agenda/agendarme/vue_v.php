<?php
    $email = $this->session->userdata('email') ?? '';
    $display_name = $this->session->userdata('display_name') ?? '';
?>

<script>
var agendarmeApp = createApp({
    data(){
        return{
            section: 'form',
            step: 'group',
            loading: false,
            mesasViernesTarde: mesasViernesTarde,
            mesasSabado: mesasSabado,
            recorridosDomingo: recorridosDomingo,
            gruposOrigen: [
                {name: 'internacional', title: 'Invitada(o) Internacional'},
                {name: 'colombia', title: 'Invitada(o) Nacional (Colombia)'},
                {name: 'bogota', title: 'Invitada(o) de Bogotá'},
            ],
            gruposEdad: [
                {name: 'no-jovenes', title: 'Tengo 30 años o más'},
                {name: 'jovenes', title: 'Tengo menos de 30 años'},
            ],
            fields: {
                email: '<?= $email ?>',
                display_name: '<?= $display_name ?>',
                grupo_origen: '',
                grupo_edad: '',
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
                if ( response.data.recaptcha_valid == false ) {
                    toastr['warning']('Ocurrió un error al enviar, se recargará el formulario')
                    setTimeout(() => {
                        // Se recarga la página
                        window.location.reload();
                    }, 4000);
                }
                this.loading = false
            })
            .catch( function(error) {console.log(error)} )
        },
        setGrupos: function(){
            if ( this.fields.grupo_edad == 'jovenes' ) {
                this.fields.sabado_manana_opcion_1 = 'Encuentro de jóvenes, ciudades y culturas Universidad'
                this.fields.sabado_manana_opcion_2 = 'Encuentro de jóvenes, ciudades y culturas Universidad'
            } else {
                this.fields.sabado_manana_opcion_1 = ''
                this.fields.sabado_manana_opcion_2 = ''
            }
            if ( this.fields.grupo_origen == 'bogota' ) {
                this.fields.recorrido_domingo = '03 Ninguno'
            } else {
                this.fields.recorrido_domingo = ''
            }
            this.step = 'selections'
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
    },
    computed: {
        recorridoDescripcion: function(){
            //A partir según el valor de this.fields.recorrido domingo devolver this.recorridosDomingo.description
            let recorrido = this.recorridosDomingo.find( recorrido => recorrido.title === this.fields.recorrido_domingo )
            if ( recorrido ) {
                return recorrido.description
            }
            return ''
        },
    }
}).mount('#agendarmeApp')
</script>