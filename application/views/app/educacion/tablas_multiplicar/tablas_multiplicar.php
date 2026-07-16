<?php $this->load->view('app/educacion/tablas_multiplicar/style_v') ?>

<div id="tablasApp" class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-12 col-md-10 col-lg-8">

            <!-- Pantalla de Victoria Final -->
            <div v-if="gameFinished" class="card-game text-center animate__animated animate__zoomIn">
                <h1 class="font-kids text-warning display-4 mb-3">¡ERES PRO! 🚀</h1>
                <p class="h4 text-secondary mb-4">¡20 multiplicaciones correctas! ¡Eres una superestrella!</p>
                <div class="display-1 mb-4">🏮🎆🌟</div>
                <button @click="resetFullGame" class="btn btn-primary btn-lg font-kids w-100 py-3"
                    style="border-radius: 1rem; font-size: 1.8rem; box-shadow: 0 5px 0 #0a58ca;">
                    ¡JUGAR DE NUEVO!
                </button>
            </div>

            <!-- Pantalla Principal del Juego -->
            <div v-else class="card-game animate__animated animate__fadeIn">

                <!-- Encabezado -->
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h1 class="font-kids text-primary h3 m-0">TABLAS DIVERTIDAS ✖️</h1>
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
                    <div class="font-kids text-primary operation-text number-pop" :key="'a'+numA">{{ numA }}</div>
                    <div class="font-kids text-danger operator-sign">×</div>
                    <div class="font-kids text-primary operation-text number-pop" :key="'b'+numB">{{ numB }}</div>
                    <div class="font-kids text-muted operator-sign">=</div>
                    <div class="font-kids text-warning operation-text">?</div>
                </div>

                <!-- Ayuda Visual: Cuadrícula de puntos -->
                <div v-if="numA * numB <= 100" class="multiplication-visual" 
                     :style="{ gridTemplateColumns: 'repeat(' + numB + ', 1fr)' }">
                    <div v-for="n in (numA * numB)" :key="n" class="dot animate__animated animate__pulse animate__infinite"></div>
                </div>

                <p class="text-center text-muted mb-3 small fw-bold">
                    {{ numA }} veces el número {{ numB }} es...
                    <span class="text-primary d-block h5 mt-1">¿Cuál es el resultado?</span>
                </p>

                <!-- Opciones de respuesta -->
                <div class="row g-2">
                    <div v-for="opcion in opciones" :key="opcion" class="col-6">
                        <button @click="checkAnswer(opcion)" :disabled="feedback && isCorrect"
                            class="btn btn-info btn-option text-white">
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
                        REINICIAR JUEGO
                    </button>
                </div>

            </div>
        </div>
    </div>
</div>

<script>
const {
    ref,
    onMounted,
    computed
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
        const timerInterval = ref(null);

        const displayTime = computed(() => {
            const m = Math.floor(timerSeconds.value / 60).toString().padStart(2, '0');
            const s = (timerSeconds.value % 60).toString().padStart(2, '0');
            return `${m}:${s}`;
        });

        const startTimer = () => {
            if (!timerActive.value) {
                timerActive.value = true;
                timerInterval.value = setInterval(() => {
                    timerSeconds.value++;
                }, 1000);
            }
        };

        const stopTimer = () => {
            timerActive.value = false;
            if (timerInterval.value) clearInterval(timerInterval.value);
        };

        const speak = (text) => {
            if ('speechSynthesis' in window) {
                const utterance = new SpeechSynthesisUtterance(text);
                utterance.lang = 'es-ES';
                window.speechSynthesis.speak(utterance);
            }
        };

        const generateGame = () => {
            feedback.value = false;

            // Generamos numA y numB entre 1 y 10 (las tablas clásicas)
            numA.value = Math.floor(Math.random() * 9) + 2; // De 2 a 10
            numB.value = Math.floor(Math.random() * 9) + 2; // De 2 a 10

            const correct = numA.value * numB.value;
            let opts = new Set();
            opts.add(correct);

            while (opts.size < 4) {
                // Distractores razonables
                let variety = Math.floor(Math.random() * 3);
                let fake;
                if (variety === 0) {
                    fake = (numA.value + 1) * numB.value;
                } else if (variety === 1) {
                    fake = numA.value * (numB.value - 1);
                } else {
                    fake = Math.floor(Math.random() * 90) + 1;
                }
                
                if (fake > 0 && fake !== correct) {
                    opts.add(fake);
                }
            }
            opciones.value = Array.from(opts).sort(() => Math.random() - 0.5);
        };

        const checkAnswer = (val) => {
            if (!timerActive.value && score.value < 20 && !gameFinished.value) {
                startTimer();
            }

            const correct = numA.value * numB.value;
            feedback.value = true;

            if (val === correct) {
                isCorrect.value = true;
                score.value++;
                feedbackMessage.value = "¡EXCELENTE! ✨";
                speak("¡Excelente!");

                if (score.value >= 20) {
                    stopTimer();
                    setTimeout(() => {
                        gameFinished.value = true;
                        speak("¡Felicidades! Eres un maestro de las tablas.");
                    }, 1000);
                } else {
                    setTimeout(generateGame, 1500);
                }
            } else {
                isCorrect.value = false;
                feedbackMessage.value = "¡OH NO! INTÉNTALO DE NUEVO 🔁";
                speak("Vuelve a intentarlo.");
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
}).mount('#tablasApp');
</script>