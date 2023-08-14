<script>
    var sectionId = '<?= $this->uri->segment(2) . '_' . $this->uri->segment(3); ?>'
    var sections = [
        {
            text: 'Explorar',
            id: 'acciones_explorar',
            cf: 'acciones/explorar',
            roles: [1,2,3,6,8,99],
            anchor: true
        },
        {
            text: '+ Nueva',
            id: 'acciones_add',
            cf: 'acciones/add',
            roles: [1,2,3,6,8]
        },
        {
            text: 'Balance',
            id: 'acciones_balance',
            cf: 'acciones/balance',
            roles: [1,2,3,6,8,99]
        },
        {
            text: 'Usuarios',
            id: 'acciones_usuarios',
            cf: 'acciones/usuarios',
            roles: [1,2,3,6,8]
        },
        {
            text: 'Detalle asistentes',
            id: 'acciones_acciones_asistentes',
            cf: 'acciones/acciones_asistentes',
            roles: [1,2,3,6,8],
            anchor: true,
        },
        {
            text: 'Detalle itinerantes',
            id: 'acciones_acciones_asistentes_itinerantes',
            cf: 'acciones/acciones_asistentes_itinerantes',
            roles: [1,2,3,6,8],
            anchor: true,
        },
        {
            text: 'Procesos',
            id: 'acciones_processes',
            cf: 'acciones/processes',
            roles: [1,2,3,6],
            anchor: true,
        },
    ]
    
//Filter role sections
var nav_2 = sections.filter(section => section.roles.includes(parseInt(APP_RID)))

//Set active class
nav_2.forEach((section,i) => {
    nav_2[i].class = ''
    if ( section.id == sectionId ) nav_2[i].class = 'active'
})
</script>

<?php
$this->load->view('common/bs5/nav_2_v');