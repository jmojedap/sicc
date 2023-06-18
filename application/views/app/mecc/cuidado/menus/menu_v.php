<script>
var sectionId = '<?= $this->uri->segment(2) . '_' . $this->uri->segment(3); ?>'
var nav2RowId = '<?= $row->id ?>';
var sections = [
    {
        text: 'Información',
        id: 'cuidado_detalles',
        cf: 'cuidado/detalles/' + nav2RowId,
        roles: [1,2,3,21,31,99]
    },
    {
        id: 'cuidado_asistentes',
        text: 'Asistentes',
        cf: 'cuidado/asistentes/' + nav2RowId,
        roles: [1,2,3],
        anchor: true
    },
    {
        id: 'cuidado_edit',
        text: 'Editar',
        cf: 'cuidado/edit/' + nav2RowId,
        roles: [1,2,3],
        anchor: true
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