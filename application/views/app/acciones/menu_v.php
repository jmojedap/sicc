<script>
var sectionId = '<?= $this->uri->segment(2) . '_' . $this->uri->segment(3); ?>'
var nav2RowId = '<?= $row->id ?>';
var sections = [
    {
        text: 'Información',
        id: 'acciones_info',
        cf: 'acciones/info/' + nav2RowId,
        roles: [1,2,3,8,99]
    },
    {
        id: 'acciones_localizacion',
        text: 'Localización',
        cf: 'acciones/localizacion/' + nav2RowId,
        roles: [1,2,3,8],
        anchor: true
    },
    {
        id: 'acciones_edit',
        text: 'Editar',
        cf: 'acciones/edit/' + nav2RowId + '/basic',
        roles: [1,2,3,8],
        anchor: true
    },
    {
        text: 'Asistentes',
        id: 'acciones_asistentes',
        cf: 'acciones/asistentes/' + nav2RowId,
        roles: [1,2,3,8]
    },
    {
        text: 'Itinerantes',
        id: 'acciones_asistentes_itinerantes',
        cf: 'acciones/asistentes_itinerantes/' + nav2RowId,
        roles: [1,2,3,8]
    },
    {
        text: 'Beneficiarios',
        id: 'acciones_poblacion_beneficiaria',
        cf: 'acciones/poblacion_beneficiaria/' + nav2RowId,
        roles: [1,2,3,8]
    },
    {
        text: 'Entidades',
        id: 'acciones_entidades_participantes',
        cf: 'acciones/entidades_participantes/' + nav2RowId,
        roles: [1,2,3,8]
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