<script>
var sectionId = '<?= $this->uri->segment(2) . '_' . $this->uri->segment(3) ?>'
var nav2RowId = '<?= $row->id ?>'
var sections = [
    {
        id: 'cuidado_details',
        text: 'Detalles',
        cf: 'cuidado/details/' + nav2RowId,
        roles: [1,2,3]
    },
    {
        id: 'cuidado_actividad_asistentes',
        text: 'Asistentes',
        cf: 'cuidado/actividad_asistentes/' + nav2RowId,
        roles: [1,2,3]
    },
    {
        id: 'cuidado_actividad_sesiones',
        text: 'Sesiones',
        cf: 'cuidado/actividad_sesiones/' + nav2RowId,
        roles: [1,2,3]
    },
    {
        id: 'cuidado_location',
        text: 'Geolocalización',
        cf: 'cuidado/location/' + nav2RowId,
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
var nav_2 = sections.filter(section => section.roles.includes(parseInt(APP_RID)));

//Set active class
nav_2.forEach((section,i) => {
    nav_2[i].class = ''
    if ( section.id == sectionId ) nav_2[i].class = 'active'
})
</script>

<?php
$this->load->view('common/nav_2_v');