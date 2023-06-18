<script>
var sectionId = '<?= $this->uri->segment(2) . '_' . $this->uri->segment(3) ?>'
var nav2RowId = '<?= $row->id ?>'
var sections = [
    {
        id: 'repositorio_info',
        text: 'Información',
        cf: 'repositorio/info/' + nav2RowId,
        roles: [1,2,3]
    },
    {
        id: 'repositorio_read',
        text: 'Ver',
        cf: 'repositorio/read/' + nav2RowId,
        roles: [1,2,3]
    },
    {
        id: 'repositorio_images',
        text: 'Imagen',
        cf: 'repositorio/images/' + nav2RowId,
        roles: [1,2,3],
        anchor: true
    },
    {
        id: 'repositorio_edit',
        text: 'Editar',
        cf: 'repositorio/edit/' + nav2RowId,
        roles: [1,2,3],
        anchor: true
    },
    {
        id: 'repositorio_details',
        text: 'Detalles',
        cf: 'repositorio/details/' + nav2RowId,
        roles: [1,2,3]
    },
    {
        id: 'repositorio_edit_details',
        text: 'Editar detalles',
        cf: 'repositorio/edit_details/' + nav2RowId,
        roles: [1,2,3],
        anchor: true
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
$this->load->view('common/nav_2_v');