<script>
var sectionId = '<?= $this->uri->segment(3) ?>';
var nav3RowId = '<?= $row->id ?>'
var sections = [
    {
        id: 'edit',
        text: 'General',
        cf: 'repositorio/edit/' + nav3RowId,
        roles: [1,2,3],
    },
    {
        id: 'edit_details',
        text: 'Detalles',
        cf: 'repositorio/edit_details/' + nav3RowId,
        roles: [1,2,3],
    },
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