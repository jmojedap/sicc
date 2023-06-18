<script>
var sectionId = '<?= $this->uri->segment(4) ?>';
var sections = [
    {
        id: 'general',
        text: 'General',
        cf: 'users/add/general',
        roles: [1,2,3],
        anchor: true
    },
    {
        id: 'student',
        text: 'Estudiante',
        cf: 'users/add/student',
        roles: [1,2,3],
        anchor: true
    },
];

//Filter role sections
var nav_3 = sections.filter(section => section.roles.includes(parseInt(APP_RID)));

//Set active class
nav_3.forEach((section,i) => {
    nav_3[i].class = ''
    if ( section.id == sectionId ) nav_3[i].class = 'active'
})
if ( sectionId == '' ) nav_3[0].class = 'active'
</script>

<?php
$this->load->view('common/nav_3_v');