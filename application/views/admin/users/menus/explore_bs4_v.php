<script>
var sectionId = '<?= $this->uri->segment(2) . '_' . $this->uri->segment(3) ?>'
var sections = [
    {
        id: 'users_explore',
        text: 'Explorar',
        cf: 'users/explore',
        anchor: true,
        roles: [1,2,3],
    },
    {
        id: 'users_import',
        text: 'Importar',
        cf: 'users/import',
        anchor: true,
        roles: [1],
    },
    {
        id: 'users_add',
        text: 'Nuevo',
        cf: 'users/add',
        anchor: true,
        roles: [1,2,3],
    }
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
$this->load->view('common/nav_2_v');