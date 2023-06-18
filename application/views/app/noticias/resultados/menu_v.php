<script>
var sectionId = '<?= $this->uri->segment(2) . '_' . $this->uri->segment(3) ?>';
var sections = [
    {
        id: 'noticias_resultados_linea',
        text: 'Línea de tiempo',    
        cf: 'noticias/resultados_linea/',
        roles: [1,2,4,21,31,99],
        anchor: true,
    },
    {
        id: 'noticias_resultados_categoria',
        text: 'Categorías',    
        cf: 'noticias/resultados_categoria/',
        roles: [1,2,4,21,31,99],
        anchor: true,
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