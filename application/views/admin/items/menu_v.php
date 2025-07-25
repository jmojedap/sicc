<script>
var sectionId = '<?= $this->uri->segment(2) . '_' . $this->uri->segment(3) ?>'
var sections = [
    {
        id: 'items_values',
        text: 'Lista',
        cf: 'items/values',
        roles: [1,2]
    },
    {
        id: 'items_import',
        text: 'Importar',
        cf: 'items/import',
        roles: [1,2]
    },
]
//Filter role sections
var nav_2 = sections.filter(section => section.roles.includes(parseInt(APP_RID)))

//Set active class
nav_2.forEach((section,i) => {
    nav_2[i].class = ''
    if ( section.id == sectionId ) nav_2[i].class = 'active'
});

if ( sectionId == 'items_import_e' ) { nav_2[1].class = 'active' }
</script>

<?php
$this->load->view('common/bs5/nav_2_v');