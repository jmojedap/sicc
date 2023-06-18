<script>
var sectionId = '<?= $this->uri->segment(2) . '_' . $this->uri->segment(3); ?>'
var element_id = '<?= $row->id ?>';
var sections = [
    {
        text: 'Información',
        id: 'mediciones_info',
        cf: 'mediciones/info/' + element_id,
        roles: [1,2]
    },
    {
        text: 'Editar',
        id: 'mediciones_edit',
        cf: 'mediciones/edit/' + element_id,
        roles: [1,2],
        anchor: true
    },
    {
        text: 'Detalles',
        id: 'mediciones_details',
        cf: 'mediciones/details/' + element_id,
        roles: [1,2]
    },
    {
        text: 'Editar detalles',
        id: 'mediciones_edit_details',
        cf: 'mediciones/edit_details/' + element_id,
        roles: [1,2]
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
$this->load->view('common/nav_2_v');