<script>
var sectionId = '<?= $this->uri->segment(5) ?>';
var nav3RowId = '<?= $row->id ?>';
var sections = [
    {
        id: 'basic',
        text: 'General',
        cf: 'barrios_vivos/edit/' + nav3RowId + '/basic',
        roles: [1,2,4,8],
        anchor: true
    },
    {
        id: 'details',
        text: 'Detalles',
        cf: 'barrios_vivos/edit/' + nav3RowId + '/details',
        roles: [1,2,4,8],
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
//if ( sectionId == '' ) nav_3[0].class = 'active'
</script>

<?php
$this->load->view('common/bs5/nav_3_v');