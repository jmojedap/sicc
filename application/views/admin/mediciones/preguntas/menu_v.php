<script>
var sectionId = '<?= $this->uri->segment(2) . '_' . $this->uri->segment(3); ?>'
var element_id = '<?= $row->id ?>';
var sections = [
    {  
        text: 'Información',
        id: 'preguntas_info',
        cf: 'preguntas/info/' + element_id,
        roles: [1,2]
    },
    {  
        text: 'Editar',
        id: 'preguntas_edit',
        cf: 'preguntas/edit/' + element_id,
        roles: [1,2],
        anchor: true
    },
    {
        text: 'Detalles',
        id: 'preguntas_details',
        cf: 'preguntas/details/' + element_id,
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
$this->load->view('common/nav_2_v');