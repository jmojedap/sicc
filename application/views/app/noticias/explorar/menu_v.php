<script>
var sectionId = '<?= $this->uri->segment(2) . '_' . $this->uri->segment(3) ?>';
var sections = [
    {
        id: 'noticias_explorar',
        text: 'Explorar',
        cf: 'noticias/explorar',
        roles: [1,2,3,31,99],
        anchor: true
    },
    {
        id: 'noticias_resumen',
        text: 'Resumen',
        cf: 'noticias/resumen',
        roles: [1,2,3,31],
    },
    {
        id: 'noticias_resultados_linea',
        text: 'Resultados',
        cf: 'noticias/resultados_linea',
        roles: [1,2,3,31,99],
        anchor: true,
    },
    {
        id: 'noticias_resultados_categoria',
        text: 'Por categoría',
        cf: 'noticias/resultados_categoria',
        roles: [1,2,3,31,99],
        anchor: true,
    },
]

//Filter role sections
var nav_2 = sections.filter(section => section.roles.includes(parseInt(APP_RID)));

//Set active class
nav_2.forEach((section,i) => {
    nav_2[i].class = ''
    if ( section.id == sectionId ) nav_2[i].class = 'active'
})
</script>

<?php
$this->load->view('common/bs5/nav_2_v');