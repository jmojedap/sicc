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
        roles: [1,2,3,6,8,99],
        anchor: true
    },
    {
        id: 'geofocus_variables',
        text: 'Variables',
        cf: 'geofocus/variables/',
        roles: [1,2,3,6,8,99],
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

if ( sectionId == 'geofocus_variables_importar_valores' ) nav_2[2].class = 'active';
if ( sectionId == 'geofocus_variables_importar_valores_e' ) nav_2[2].class = 'active';
if ( sectionId == 'geofocus_priorizacion' ) nav_2[1].class = 'active';
</script>

<?php
$this->load->view('common/bs5/nav_2_v');