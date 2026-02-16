<style>
@import url('https://fonts.googleapis.com/css2?family=Luckiest+Guy&family=Quicksand:wght@400;700&display=swap');

:root {
    --bg-color: #f0fdf4;
    --main-green: #4ade80;
    --dark-green: #22c55e;
}

body {
    font-family: 'Quicksand', sans-serif;
    background-color: var(--bg-color);
    min-height: 100vh;
    display: flex;
    align-items: center;
    overflow-x: hidden;
    margin: 0;
    padding: 10px;
}

.font-kids {
    font-family: 'Luckiest+Guy', cursive;
    letter-spacing: 1px;
}

.card-game {
    background: white;
    border: 4px solid var(--main-green);
    border-radius: 1.5rem;
    box-shadow: 0 8px 0 var(--dark-green);
    padding: 1.5rem;
    margin-bottom: 20px;
}

/* Contenedor de la barra */
.bar-wrapper {
    padding: 1rem 0;
    min-height: 80px;
}

.subtraction-bar {
    width: 100%;
    height: 60px;
    background-color: #ddd;
    border-radius: 1rem;
    display: flex;
    overflow: hidden;
    transition: all 0.5s ease;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    position: relative;
    border: 4px solid #198754;
}

.bar-segment {
    height: 100%;
    transition: width 0.8s cubic-bezier(0.34, 1.56, 0.64, 1);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: bold;
    font-family: 'Luckiest+Guy', cursive;
    font-size: 1.5rem;
    text-shadow: 0 2px 0 rgba(0,0,0,0.2);
}

.bar-remaining {
    background-color: #0d6efd;
    background-image: linear-gradient(45deg, rgba(255,255,255,0.15) 25%, transparent 25%, transparent 50%, rgba(255,255,255,0.15) 50%, rgba(255,255,255,0.15) 75%, transparent 75%, transparent);
    background-size: 1rem 1rem;
}

.bar-removed {
    background-color: #dc3545;
    background-image: repeating-linear-gradient(45deg, transparent, transparent 10px, rgba(0,0,0,0.1) 10px, rgba(0,0,0,0.1) 20px);
    opacity: 0.8;
}


.btn-option {
    font-family: 'Luckiest+Guy', cursive;
    font-size: clamp(1.5rem, 6vw, 2.5rem);
    border-radius: 1rem;
    padding: 0.8rem 0.5rem;
    box-shadow: 0 5px 0 #946b00;
    transition: all 0.1s;
    width: 100%;
}

.btn-option:active:not(:disabled) {
    transform: translateY(3px);
    box-shadow: 0 2px 0 #946b00;
}

.score-badge {
    background-color: #fff3cd;
    border: 2px solid #ffecb5;
    color: #856404;
    border-radius: 50rem;
    padding: 0.3rem 1rem;
    font-size: clamp(1rem, 4vw, 1.4rem);
}

.timer-badge {
    background-color: #cff4fc;
    border: 2px solid #b6effb;
    color: #055160;
    border-radius: 50rem;
    padding: 0.3rem 1rem;
    font-size: clamp(1rem, 4vw, 1.4rem);
}

.operation-text {
    font-size: clamp(3rem, 12vw, 5rem);
}

.operator-sign {
    font-size: clamp(2rem, 8vw, 4rem);
}

/* Animación de entrada para los números */
.number-pop {
    display: inline-block;
    animation: bounceIn 0.5s;
}

@keyframes bounceIn {
    0% {
        transform: scale(0.3);
        opacity: 0;
    }

    50% {
        transform: scale(1.1);
    }

    70% {
        transform: scale(0.9);
    }

    100% {
        transform: scale(1);
        opacity: 1;
    }
}
 </style>