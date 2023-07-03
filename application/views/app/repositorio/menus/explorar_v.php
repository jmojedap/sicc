<script>
    var sectionId = '<?= $this->uri->segment(2) . '_' . $this->uri->segment(3); ?>'
    var sections = [
        {
            text: 'Explorar',
            id: 'repositorio_explorar',
            cf: 'repositorio/explorar',
            roles: [1,2,3,8,99]
        },
        {
            text: '+ Nuevo',
            id: 'repositorio_add',
            cf: 'repositorio/add',
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
$this->load->view('common/bs5/nav_2_v');