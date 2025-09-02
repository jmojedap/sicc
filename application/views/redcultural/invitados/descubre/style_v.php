<style>
    .chat-container {
        display: flex;
        flex-direction: column;
        margin: 0 auto;
        height: calc(100vh - 250px);
        font-family: "Lexend", sans-serif;
        font-optical-sizing: auto;
        font-weight: <weight>;
        font-style: normal;
        font-variation-settings:
            "wdth" 100;
    }
    .chat-messages {
        flex: 1;
        padding: 10px;
        overflow-y: auto;
        border-bottom: 1px solid #FFF;
        margin-bottom: 1em;
        scroll-behavior: smooth;
        display: flex;
        flex-direction: column;
        align-items: center; /* Centra horizontalmente los mensajes */
    }

    .chat-mensaje {
        width: 100%;
        max-width: 720px;
        transition: opacity 2s ease-in-out;
        margin-bottom: 1em;
    }

    .chat-mensaje.fade-enter {
        opacity: 0;
    }

    .chat-pregunta {
        padding: 0.7em 0.7em 0em 0.7em;
        background-color: #7d5dc7;
        color: #FFF;
        border-radius: 0.6em 0em 0.6em 0.6em;
        width: calc(100% - 2em);
        text-align: left;
    }

    .chat-respuesta {
        padding: 0.7em 0.7em 0em 0.7em;
        background-color: #e7f1ff;
        border-radius: 0em 0.6em 0.6em 0.6em;
        width: calc(100% - 2em);
        text-align: left;
    }

    .chat-input {
        display: flex;
        border-radius: 1em;
        background-color: #FFF;
    }

    .chat-input textarea {
        flex: 1;
        padding: 10px 10px 10px 20px;
        border: 1px solid #DDD;
        background-color: #FFF;
        border-radius: 0em;
        resize: none; /* evita que el usuario arrastre la esquina */
        overflow: hidden;
        outline: none; /* elimina borde azul al hacer focus */
        box-shadow: none;
    }

    .btn-submit {
        border: none;
        background-color: #966EF0;
        color: white;
        min-width: 120px;
    }

    .btn-submit:hover {
        background-color: #845ce4ff;
        color: #FFF;
    }

    .btn-round {
        border-radius: 23px;
    }

    .chat-input input:focus {
        outline: none;
        box-shadow: none;
        border: none;
    }
    
    .typing-effect {
        display: inline-block;
        white-space: pre;
        border-right: 2px solid rgba(0,0,0,0.75);
        animation: blink 0.7s steps(44) infinite;
    }
    @keyframes blink {
        0%, 100% { border-color: transparent; }
        50% { border-color: rgba(0,0,0,0.75); }
    }

    .chat-pregunta-ejemplo {
        background-color: #FFF;
        padding: 0.5em;
        font-size: 0.9em;
        border-radius: 0.3em;
        border: 1px solid #CCC;
        cursor: pointer;
    }

    .chat-pregunta-ejemplo:hover {
        background-color: #e7f1ff;
        color: #0C63E4;
        border-color: #FFF;
    }

    .generated-content {
        border-left: 1px solid #966EF099;
        width: 100%;
        background-color: #FFF;
        padding: 2.5em;
        opacity: 1;
        transition: opacity 1s ease;
        font-family: "Lexend", sans-serif;
        font-size: 1.1rem;
        font-optical-sizing: auto;
        font-style: normal;
        font-variation-settings:
            "wdth" 100;
    }

    .generated-content.fade-enter {
        opacity: 0; /* Aplica antes del reflow, luego vuelve a 1 */
    }

    .generated-content strong {
        color: var(--color-text-1);
    }

    .generated-content table {
        width: 100%;
        border-collapse: collapse;
        margin: 16px 0;
        font-size: 13px;
        background-color: #ffffff;
        overflow: hidden; /* Para aplicar bordes redondeados */
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    }

    .generated-content th, 
    .generated-content td {
        border: 1px solid #ddd;
        padding: 6px 8px;
        text-align: left;
    }

    .generated-content th {
        background-color: #966EF0;
        font-weight: 600;
        color: #FFF;
        text-transform: capitalize;
    }

    .generated-content tr:nth-child(even) {
        background-color: #f9fafb;
    }

    .generated-content tr:hover {
        background-color: #eef3f7;
        transition: background 0.3s ease;
    }

    .generated-content caption {
        caption-side: top;
        font-size: 18px;
        font-weight: bold;
        padding: 8px;
        color: #444;
    }

</style>