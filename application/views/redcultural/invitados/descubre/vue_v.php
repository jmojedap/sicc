<script>
// Variables y datos
//-----------------------------------------------------------------------------

const funciones = [
    {   funcion_id: 10,
        nombre: 'intereses-comuntes',
        titulo_corto: 'Intereses',
        titulo: 'Intereses comunes',
        descripcion: 'Menciona 5 invitados, con username y nombre completo, y una breve explicación de porqué habría compatibilidad, que serían más compatibles conmigo',
        active: true,
    },
    {   funcion_id: 20,
        nombre: 'preguntas-relevantes',
        titulo_corto: 'Preguntas',
        titulo: 'Preguntas relevantes',
        descripcion: 'Consolida en 5 grupos o categorías, las preguntas propuestas por los invitados sobre los retos de la cultura en Iberoamérica, muéstralas en una lista con título y descripción.',
        active: true,
    },
    {   funcion_id: 25,
        nombre: 'mis-pares',
        titulo_corto: 'Mis pares',
        titulo: 'Mis pares',
        descripcion: '¿Quiénes son las 5 personas invitadas que más tiene afinidad cultural y temática conmigo? Haz una lista y explica brevemente por qué?',
        active: true,
    },
    {   funcion_id: 30,
        nombre: 'invitados-argentina',
        titulo_corto: 'Invitados por país',
        titulo: 'Invitados por país',
        descripcion: 'Genera una tabla con las personas invitadas de <?= $user_country ?>, incluyendo nombre, username y una breve descripción de su relevancia cultural.',
        active: true,
    },
    {   funcion_id: 40,
        nombre: 'poblacion-infantil',
        titulo_corto: 'Población infantil',
        titulo: 'Población infantil',
        descripcion: '¿Cuáles de las personas invitadas tienen más interés o tienen más relación con temáticas asociadas a la niñez y adolescencia?',
        active: true,
    },
    {   funcion_id: 40,
        nombre: 'redes-culturales',
        titulo_corto: 'Redes culturales',
        titulo: 'Redes culturales',
        descripcion: '¿Cuáles son las Redes Culturales más frecuentes o recurrentes a las que pertenecen las personas invitadas al Encuentro?',
        active: true,
    },
];

const maxTokens = <?= $max_tokens ?>;

// VueApp
//-----------------------------------------------------------------------------
var chatApp = createApp({
    data(){
        return{
            section: 'contents',
            //section: 'generation',
            sections: [
                { name: 'contents', title: 'Contenidos' },
                { name: 'generation', title: 'Generar' },
                { name: 'help', title: '¿Cómo funciona?' },
            ],
            loading: false,
            messages: [],
            userId: <?= $session_user_id ?>,
            currentContenido: {
                id: 0,
                solicitud: '',
                contenido: ''
            },
            contenidos: <?= json_encode($contenidos['list']) ?>,
            lastMessage: {
                id: 0, 
            },
            user_input: funciones[2].descripcion,
            responseText:'',
            responseHtml: '',
            tokens: <?= json_encode($tokens) ?>,
            funciones: funciones,
            functionId: 0,
            currentFuncion: funciones[2], // Función activa por defecto
            deleteConfirmationTexts : {
                title: 'Eliminar contenido',
                text: '¿Confirma la eliminación del contenido generado?',
                buttonText: 'Eliminar'
            },
        }
    },
    methods: {
        handleSubmit: function(){
            this.loading = true
            var newMessage = {
                role:'user',
                text: this.user_input,
            }
            this.getResponse()
        },
        getResponse: function() {
            this.loading = true;
            if (!this.user_input) {
                console.warn("El input está vacío");
                this.loading = false;
                return;
            }

            if ( this.userId == 0 ) {
                //toastr['info']('Es necesario iniciar sesión para generar contenidos con esta herramienta')
                const modalEl = document.getElementById('sessionModal');
                const modal = new bootstrap.Modal(modalEl, {
                    backdrop: 'static', // opcional: evita cerrar al hacer click fuera
                    keyboard: false     // opcional: evita cerrar con ESC
                });
                modal.show();
                this.loading = false;
                return;
            }

            const formValues = new FormData();
            formValues.append('user_input', this.user_input.trim());
            formValues.append('system_instruction_key', 'invitados-ecci');
            
            this.user_input = ''; // Limpiar el input del usuario antes de enviar

            axios.post(URL_API + 'red_cultural/get_answer/', formValues)
            .then(response => {
                this.responseText = response.data.response_text ?? 'Ocurrió un error al obtener la respuesta.';
                this.tokens.usage += response.data.token_count ?? 0;

                var newMessage = {
                    id: response.data.post_id,
                    role:'model',
                    text: this.responseText
                }
                this.addNewMessage(newMessage)
                this.user_input = '';
                this.loading = false;
            })
            .catch(error => {
                console.error(error);
                this.responseText = 'Error al obtener la respuesta del Modelo.';
            });
        },
        // Convertir respuesta de Markdown a HTML
        markdownToHtml: function(markdownText) {
            // Convertir markdown a HTML
            const rawHtml = marked.parse(markdownText); //

            // Sanitizar si DOMPurify está disponible
            var responseHtml = window.DOMPurify
                ? DOMPurify.sanitize(rawHtml)
                : rawHtml;

            return responseHtml;
        },
        addNewMessage(newMessage) {
            this.messages.push(newMessage);
            this.setResponseContent();
        },
        // Establecer el contenido HTML de la respuesta a partir de los mensajes, se toma el último mensaje que envió el modelo
        setResponseContent: function(){
            if ( this.messages.length > 0 ) {
                this.lastMessage = this.messages.slice().reverse().find(msg => msg.role === 'model');
                this.responseHtml = this.lastMessage ? this.markdownToHtml(this.lastMessage.text) : '';
                this.$nextTick(() => {
                    this.aplicarFadeInGeneratedContent();
                    document.getElementById('user-input').focus();
                });
            }
        },
        aplicarFadeInGeneratedContent() {
            const contentElement = document.getElementById('generated-content');
            
            if (contentElement) {
                contentElement.classList.remove('fade-enter'); // por si quedó de antes
                void contentElement.offsetWidth; // Forzar reflow
                
                contentElement.classList.add('fade-enter');

                setTimeout(() => {
                    contentElement.classList.remove('fade-enter');
                }, 1000); // Tiempo suficiente para la animación
            }  
        },
        handleKeyDown(event) {
            if (!event.shiftKey) {
                event.preventDefault();
                if (this.user_input.trim() !== '') {
                    this.handleSubmit();
                }
            }
        },
        autoExpand(event) {
            const el = event?.target || this.$refs.userInput;
            if (!el) return;
            el.style.height = 'auto';
            el.style.height = el.scrollHeight + 'px';
        },
        // Establecer tipo de funciones de generación de la herramienta
        setFuncion: function(funcion) {
            this.funciones.forEach(f => f.active = false); // Desactivar todas las funciones
            funcion.active = true; // Activar la función seleccionada
            this.currentFuncion = funcion; // Actualizar la función actual
            this.setBasePrompt(); // Establecer el prompt base para la función seleccionada
        },
        setBasePrompt() {
            this.user_input = this.currentFuncion.descripcion;

            this.$nextTick(() => {
                const el = this.$refs.userInput;
                if (!el) return;
                el.focus();
                this.autoExpand({ target: el });
            });
        },
        setCurrentContenido: function(newCurrentContenidoId){
            // Si existe newCurrentContenidoId, si no existe, se elige el primero
            this.currentContenido = this.contenidos.find(c => c.id == newCurrentContenidoId) || this.contenidos[0];
        },
        deleteElements: function(){
            this.updateContentStatus(this.currentContenido.id, 2);
        },
        updateContentStatus: function(postId, newStatus){
            this.loading = true
            var formValues = new FormData()
            formValues.append('id', postId)
            formValues.append('status', newStatus)
            axios.post(URL_API + 'red_cultural/update_status_ai_content/', formValues)
            .then(response => {
                if ( response.data.saved_id > 0 ) {
                    if (newStatus === 2) {
                        toastr['info']('Contenido eliminado')
                    } else {
                        toastr['success']('Contenido guardado')
                    }
                    this.getContenidos(postId)
                }
                this.loading = false
            })
            .catch( function(error) {console.log(error)} )
        },
        getContenidos: function(newContentId){
            this.loading = true
            //var formValues = new FormData(document.getElementById('jkd'))
            axios.post(URL_API + 'red_cultural/get_ai_contents/')
            .then(response => {
                this.contenidos = response.data.list
                this.section = 'contents'
                this.loading = false
                if ( newContentId ) {
                    this.setCurrentContenido(newContentId);
                }
            })
            .catch( function(error) {console.log(error)} )
        },
    },
    mounted(){
        this.$nextTick(() => {
            document.getElementById('user-input').focus();
        });
        this.setResponseContent();
    },
    computed: {
        //Porcentaje de uso de tokens número entero
        percentUsageTokens: function() {
            var percent = Pcrn.intPercent(this.tokens.usage, this.tokens.max)
            if ( percent > 100 ) percent = 100
            return percent
        }
    },
}).mount('#chatApp');
</script>