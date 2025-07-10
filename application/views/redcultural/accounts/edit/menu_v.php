<script>
var sectionId = '<?= $this->uri->segment(4) ?>';
var sections = [
    {
        id: 'basic',
        text: 'General',
        cf: 'accounts/edit/basic',
        roles: [1,2,3,6,8,21,31],
    },
    {
        id: 'image',
        text: 'Imagen',
        cf: 'accounts/edit/image',
        roles: [1,2],
    },
    {
        id: 'password',
        text: 'Contraseña',
        cf: 'accounts/edit/password',
        roles: [1,2,3,6,8],
    }
];

//Filter role sections
var nav_3 = sections.filter(section => section.roles.includes(parseInt(APP_RID)));

//Set active class
nav_3.forEach((section,i) => {
    nav_3[i].class = ''
    if ( section.id == sectionId ) nav_3[i].class = 'active'
})
</script>

<?php
$this->load->view('common/bs5/nav_3_v');