<script>
    var sectionId = '<?= $this->uri->segment(2) . '_' . $this->uri->segment(3); ?>'
    var sections = [
        {
            text: 'Me interesan',
            id: 'invitados_me_interesa',
            cf: 'invitados/me_interesa',
            roles: [1,2,3,11],
            anchor: true
        },
        {
            text: 'Le intereso a',
            id: 'invitados_me_interesa',
            cf: 'invitados/me_interesa',
            roles: [1,2,3,11],
            anchor: true
        },
    ];
    
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