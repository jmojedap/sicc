<style>
@import url('https://fonts.googleapis.com/css2?family=Luckiest+Guy&family=Quicksand:wght@400;700&display=swap');

:root {
    --bg-color: #f5f3ff;
    --main-purple: #8b5cf6;
    --dark-purple: #6d28d9;
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
    font-family: 'Luckiest Guy', cursive;
    letter-spacing: 1px;
}

.card-game {
    background: white;
    border: 4px solid var(--main-purple);
    border-radius: 1.5rem;
    box-shadow: 0 8px 0 var(--dark-purple);
    padding: 1.5rem;
    margin-bottom: 20px;
}

.btn-option {
    font-family: 'Luckiest Guy', cursive;
    font-size: clamp(1.5rem, 6vw, 2.5rem);
    border-radius: 1rem;
    padding: 0.8rem 0.5rem;
    box-shadow: 0 5px 0 #4c1d95;
    transition: all 0.1s;
    width: 100%;
}

.btn-option:active:not(:disabled) {
    transform: translateY(3px);
    box-shadow: 0 2px 0 #4c1d95;
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

.number-pop {
    display: inline-block;
    animation: bounceIn 0.5s;
}

@keyframes bounceIn {
    0% { transform: scale(0.3); opacity: 0; }
    50% { transform: scale(1.1); }
    70% { transform: scale(0.9); }
    100% { transform: scale(1); opacity: 1; }
}

/* Specific for multiplication: Grid of dots or items */
.multiplication-visual {
    display: grid;
    gap: 4px;
    justify-content: center;
    margin-bottom: 1rem;
    padding: 10px;
    background: #fdf2f8;
    border-radius: 1rem;
    border: 2px dashed #f472b6;
}

.dot {
    width: 20px;
    height: 20px;
    background-color: #f472b6;
    border-radius: 50%;
    box-shadow: 0 2px 0 #db2777;
}
</style>
