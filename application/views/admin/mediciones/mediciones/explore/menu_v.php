<script>
    var sectionId = '<?= $this->uri->segment(2) . '_' . $this->uri->segment(3); ?>'
    var sections = [
        {
            text: 'Explorar',
            id: 'mediciones_explore',
            cf: 'mediciones/explore',
            roles: [1,2,3]
        },
        {
            text: 'Nuevo',
            id: 'mediciones_add',
            cf: 'mediciones/add',
            roles: [1,2,3]
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