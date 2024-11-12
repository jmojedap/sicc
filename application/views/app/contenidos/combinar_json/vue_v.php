<script>
var combinarJsonApp = createApp({
    data() {
        return {
            elementos: <?= json_encode($elementos) ?>,
            loading: false,
            q:'',
        }
    },
    methods: {
        ago: function(date){
            if (!date) return ''
            return moment(date, 'YYYY-MM-DD HH:mm:ss').fromNow()            
        },
        clearSearch: function(){
            this.q = ''
        },
        // Método para formatear el texto reemplazando `\n` por `<br>`
        formatText(text) {
            return text.replace(/\n/g, '<br>');
        }
    },
    computed: {
        elementosFiltrados: function() {
            if (this.q.length > 0) {
                var fieldsToSearch = ['Pregunta']
                return PmlSearcher.getFilteredResults(this.q, this.elementos, fieldsToSearch)
            }
            return this.elementos
        },
    },
}).mount('#combinarJsonApp')
</script>