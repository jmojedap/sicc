<script>
const baseUrlApp = '<?= RCI_URL_APP ?>invitados/me_interesa/';
var activeSection = '<?= $this->uri->segment(4) ?>';
if (!activeSection) {
    activeSection = 'me-interesan';
}

var followingApp = createApp({
    data() {
        return {
            sections: [{
                    name: 'me-interesan',
                    title: 'Me interesan',
                    active: true
                },
                {
                    name: 'le-intereso-a',
                    title: 'Le intereso a',
                    active: false
                },
                {
                    name: 'coincidencias',
                    title: 'Coincidencias',
                    active: false
                },
            ],
            loading: false,
            fields: {},
            following: <?= json_encode($following->result()) ?>,
            followers: <?= json_encode($followers->result()) ?>,
        }
    },
    methods: {
        altFollow: function(user) {
            axios.get(URL_API + 'users/alt_follow/' + user.id)
                .then(response => {
                    if (response.data.status != 1) {
                        this.following = this.following.filter(u => u.id !== user.id);
                        toastr['info']('Se retiró a ' + user.display_name + ' de tus intereses');
                    }
                })
                .catch(function(error) {
                    console.log(error)
                })
        },
        setSection: function(activeSection){
            this.sections.forEach(section => {
                section.active = section.name === activeSection;
            });
            window.history.pushState({}, '', baseUrlApp + activeSection);
        },
        paisTo: function(countryCode, field = 'name') {
            return RciPaises.codeTo(countryCode, field);
        },
        paisFlag: function(countryCode) {
            return RciPaises.flagIconUrl(countryCode);
        },
    },
    computed: {
        //Devolver lista con los usuarios que tienen interés cultural mutuo
        matches() {
            return this.following.filter(user => this.followers.some(follower => follower.id === user.id));
        }
    },
    mounted() {
        this.setSection(activeSection);
    }
}).mount('#followingApp')
</script>