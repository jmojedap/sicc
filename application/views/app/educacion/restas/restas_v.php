<?php $this->load->view('app/educacion/restas/style_v') ?>

<div id="restasApp" class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-12 col-md-10 col-lg-8">

            <!-- Pantalla de Victoria Final -->
            <div v-if="gameFinished" class="card-game text-center animate__animated animate__zoomIn">
                <h1 class="font-kids text-warning display-4 mb-3">¡LO LOGRASTE! 🏆</h1>
                <p class="h4 text-secondary mb-4">¡20 restas correctas! Eres un genio de las matemáticas.</p>
                <div class="display-1 mb-4">🎉🥳🎊</div>
                <button @click="resetFullGame" class="btn btn-primary btn-lg font-kids w-100 py-3"
                    style="border-radius: 1rem; font-size: 1.8rem; box-shadow: 0 5px 0 #0a58ca;">
                    OTRA VEZ
                </button>
            </div>

            <!-- Pantalla Principal del Juego -->
            <div v-else class="card-game animate__animated animate__fadeIn">

                <!-- Encabezado -->
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h1 class="font-kids text-success h3 m-0">RESTA Y GANA 🍎</h1>
                    <div class="d-flex gap-2">
                        <div class="timer-badge font-kids">
                            ⏱️ {{ displayTime }}
                        </div>
                        <div class="score-badge font-kids">
                            ⭐ {{ score }}/20
                        </div>
                    </div>
                </div>

                <!-- Operación Numérica -->
                <div class="d-flex justify-content-center align-items-center gap-2 mb-3">
                    <div class="font-kids text-success operation-text number-pop" :key="'a'+numA">{{ numA }}</div>
                    <div class="font-kids text-danger operator-sign">-</div>
                    <div class="font-kids text-danger operation-text number-pop" :key="'b'+numB">{{ numB }}</div>
                    <div class="font-kids text-muted operator-sign">=</div>
                    <div class="font-kids text-primary operation-text">?</div>
                </div>

                <!-- Área Visual de Barra -->
                <div class="bar-wrapper mb-3">
                    <div class="subtraction-bar">
                        <!-- Parte que se quita (Roja) -->
                        <div class="bar-segment bar-removed" :style="{ width: (numB / numA * 100) + '%' }">
                            {{ numB }}
                        </div>
                        <!-- Parte que queda (Azul) -->
                        <div class="bar-segment bar-remaining" :style="{ width: ((numA - numB) / numA * 100) + '%' }">
                            ?
                        </div>
                    </div>
                </div>

                <!-- Texto de Ayuda -->
                <p class="text-center text-muted mb-3 small fw-bold">
                    La barra mide {{ numA }}. Le quitamos el pedazo rojo de {{ numB }}.
                    <span class="text-success d-block h5 mt-1">¿Cuánto mide la parte azul?</span>
                </p>

                <!-- Opciones de respuesta -->
                <div class="row g-2">
                    <div v-for="opcion in opciones" :key="opcion" class="col-6">
                        <button @click="checkAnswer(opcion)" :disabled="feedback && isCorrect"
                            class="btn btn-warning btn-option text-white">
                            {{ opcion }}
                        </button>
                    </div>
                </div>

                <!-- Feedback Mensaje -->
                <div class="text-center mt-3" style="min-height: 40px;">
                    <div v-if="feedback" class="animate__animated animate__bounceIn">
                        <div :class="isCorrect ? 'text-success' : 'text-danger'" class="h4 font-kids">
                            {{ feedbackMessage }}
                        </div>
                    </div>
                </div>

                <!-- Botón de Reiniciar -->
                <div class="text-center mt-2">
                    <button @click="resetFullGame"
                        class="btn btn-link text-decoration-none text-muted btn-sm fw-bold p-0">
                        REINICIAR
                    </button>
                </div>

            </div>
        </div>
    </div>
</div>

<script>
const {
    ref,
    onMounted
} = Vue;

createApp({
    setup() {
        const numA = ref(0);
        const numB = ref(0);
        const opciones = ref([]);
        const score = ref(0);
        const isCorrect = ref(false);
        const feedback = ref(false);
        const feedbackMessage = ref("");
        const gameFinished = ref(false);

        // Timer logic
        const timerSeconds = ref(0);
        const timerActive = ref(false);
        const timerInterault = ref(null);

        const displayTime = Vue.computed(() => {
            const m = Math.floor(timerSeconds.value / 60).toString().padStart(2, '0');
            const s = (timerSeconds.value % 60).toString().padStart(2, '0');
            return `${m}:${s}`;
        });

        const startTimer = () => {
            if (!timerActive.value) {
                timerActive.value = true;
                timerInterault.value = setInterval(() => {
                    timerSeconds.value++;
                }, 1000);
            }
        };

        const stopTimer = () => {
            timerActive.value = false;
            if (timerInterault.value) clearInterval(timerInterault.value);
        };

        const speak = (text) => {
            const utterance = new SpeechSynthesisUtterance(text);
            utterance.lang = 'es-ES';
            window.speechSynthesis.speak(utterance);
        };

        const generateGame = () => {
            feedback.value = false;

            // Generamos numA entre 5 y 19
            numA.value = Math.floor(Math.random() * (19 - 5 + 1)) + 5;

            // Generamos numB entre 0 y 9, pero aseguramos que sea menor que numA
            // Con el rango de numA (5-19) y numB (0-9), casi siempre es así,
            // pero este bucle garantiza que nunca sean iguales ni que B sea mayor.
            let tempB = Math.floor(Math.random() * 10);
            while (tempB >= numA.value) {
                tempB = Math.floor(Math.random() * 10);
            }
            numB.value = tempB;

            const correct = numA.value - numB.value;
            let opts = new Set();
            opts.add(correct);

            while (opts.size < 4) {
                // Generamos distractores cercanos al resultado o en el rango lógico (0-19)
                let fake = Math.floor(Math.random() * 20);
                opts.add(fake);
            }
            opciones.value = Array.from(opts).sort(() => Math.random() - 0.5);
        };

        const checkAnswer = (val) => {
            // Iniciar timer si no ha iniciado
            if (!timerActive.value && score.value < 20 && !gameFinished.value) {
                // Check specific condition: "starts counting from the moment they answer the first subtraction"
                // If timerSeconds is 0, we assume it's the first interaction that should start the clock.
                // But strictly, we check if it is active.
                startTimer();
            }

            const correct = numA.value - numB.value;
            feedback.value = true;

            if (val === correct) {
                isCorrect.value = true;
                score.value++;
                feedbackMessage.value = "¡MUY BIEN! ⭐";
                speak("¡Excelente!");

                if (score.value >= 20) {
                    stopTimer(); // Stop timer immediately on victory
                    setTimeout(() => {
                        gameFinished.value = true;
                        speak("¡Felicidades! Ganaste el juego.");
                    }, 1000);
                } else {
                    setTimeout(generateGame, 2000);
                }
            } else {
                isCorrect.value = false;
                feedbackMessage.value = "¡CASI! VAMOS CON OTRA 🔄";
                speak("Casi. Probemos con otra resta.");
                setTimeout(generateGame, 1500);
            }
        };

        const resetFullGame = () => {
            score.value = 0;
            gameFinished.value = false;
            feedback.value = false;
            stopTimer();
            timerSeconds.value = 0;
            generateGame();
        };

        onMounted(generateGame);

        return {
            numA,
            numB,
            opciones,
            score,
            isCorrect,
            feedback,
            feedbackMessage,
            gameFinished,
            checkAnswer,
            resetFullGame,
            displayTime
        }
    }
}).mount('#restasApp');
</script>