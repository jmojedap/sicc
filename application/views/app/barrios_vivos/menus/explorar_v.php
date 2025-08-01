<script>
    var sectionId = '<?= $this->uri->segment(2) . '_' . $this->uri->segment(3); ?>'
    var sections = [
        {
            text: 'Explorar',
            id: 'barrios_vivos_explorar',
            cf: 'barrios_vivos/explorar',
            roles: [1,2,3,6,8,99],
            anchor: true
        },
        {
            text: '+ Nuevo',
            id: 'barrios_vivos_add',
            cf: 'barrios_vivos/add',
            roles: [1,2,3,6,8],
            anchor: true
        },
        {
            text: '📅 Calendario',
            id: 'barrios_vivos_calendario',
            cf: 'barrios_vivos/calendario',
            roles: [1,2,3,6,8,99],
            anchor: true
        },
        {
            text: 'Tutoriales',
            id: 'barrios_vivos_tutoriales',
            cf: 'barrios_vivos/tutoriales',
            roles: [1,2,3,6,8,99],
            anchor: true
        }
    ];
    
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