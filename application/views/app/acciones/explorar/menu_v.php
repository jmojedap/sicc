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
            text: 'Exportar',
            id: 'acciones_exportar_datos',
            cf: 'acciones/exportar_datos',
            roles: [1,2,3,6,8]
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