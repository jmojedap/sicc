<script>
var sectionId = '<?= $this->uri->segment(2) . '_' . $this->uri->segment(3); ?>'
var element_id = '<?= $row->id ?>';
var sections = [
    {
        text: 'Información',
        id: 'events_info',
        cf: 'events/info/' + element_id,
        roles: [1,2]
    },
    {
        text: 'Editar',
        id: 'events_edit',
        cf: 'events/edit/' + element_id,
        roles: [1,2],
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
$this->load->view('common/nav_2_v');