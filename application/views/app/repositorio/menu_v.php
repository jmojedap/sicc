<script>
var sectionId = '<?= $this->uri->segment(2) . '_' . $this->uri->segment(3) ?>'
var nav2RowId = '<?= $row->id ?>'
var sections = [
    {
        id: 'repositorio_informacion',
        text: 'Información',
        cf: 'repositorio/informacion/' + nav2RowId,
        roles: [1,2,3,99]
    },
    {
        id: 'repositorio_ver',
        text: 'Ver',
        cf: 'repositorio/ver/' + nav2RowId,
        roles: [1,2,3,99]
    },
    {
        id: 'repositorio_detalles',
        text: 'Detalles',
        cf: 'repositorio/detalles/' + nav2RowId,
        roles: [1,2,3,99]
    },
]
    
//Filter role sections
var nav_2 = sections.filter(section => section.roles.includes(parseInt(APP_RID)));

//Set active class
nav_2.forEach((section,i) => {
    nav_2[i].class = ''
    if ( section.id == sectionId ) nav_2[i].class = 'active'
})
</script>

<?php
$this->load->view('common/bs5/nav_2_v');