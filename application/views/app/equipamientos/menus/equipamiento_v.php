<script>
var sectionId = '<?= $this->uri->segment(2) . '_' . $this->uri->segment(3); ?>'
var nav2RowId = '<?= $row->id ?>';
var sections = [
    {
        id: 'equipamientos_info',
        text: 'Información',
        cf: 'equipamientos/info/' + nav2RowId,
        roles: [1,2,3,6,8,99],
        anchor: true
    },
    /* {
        id: 'equipamientos_edit',
        text: 'Editar',
        cf: 'equipamientos/edit/' + nav2RowId + '/basic',
        roles: [1,2,3,6,8],
        anchor: true
    }, */
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