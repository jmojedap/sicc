<script>
var sectionId = '<?= $this->uri->segment(2) . '_' . $this->uri->segment(3); ?>'
var sections = [
    {
        text: 'Inicio',
        id: 'geofocus_panel',
        cf: 'geofocus/panel/',
        roles: [1,2,3,6,8,99]
    },
    {
        id: 'geofocus_priorizaciones',
        text: 'Priorizaciones',
        cf: 'geofocus/priorizaciones/',
        roles: [1,2,3,6,8],
        anchor: true
    },
    {
        id: 'geofocus_variables',
        text: 'Variables',
        cf: 'geofocus/variables/',
        roles: [1,2,3,6,8],
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