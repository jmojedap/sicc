<style>
    .selector-tipo{
        font-size: 1.2em;
    }

    .text-off{
        color: #ADB3B8;
    }

    .range {
        width: 100%;
        height: 15px;
        -webkit-appearance: none;
        background: #FAFAFA;
        outline: none;
        border-radius: 15px;
        overflow: hidden;
        /*box-shadow: inset 0 0 2px rgba(0, 0, 0, 1);*/
        border: 1px solid #AAA;
    }
    .range::-webkit-slider-thumb {
        -webkit-appearance: none;
        width: 13px;
        height: 13px;
        border-radius: 50%;
        background: #5E4296;
        cursor: pointer;
        /*border: 2px solid #20124D;*/
        box-shadow: -407px 0 0 400px #D9D2E9;
    }

    
    .loader {
        --c:no-repeat linear-gradient(orange 0 0);
        background: 
            var(--c),var(--c),var(--c),
            var(--c),var(--c),var(--c),
            var(--c),var(--c),var(--c);
        background-size: 16px 16px;
        animation: 
            l32-1 1s infinite,
            l32-2 1s infinite;
        }
        @keyframes l32-1 {
        0%,100% {width:45px;height: 45px}
        35%,65% {width:65px;height: 65px}
        }
        @keyframes l32-2 {
        0%,40%  {background-position: 0 0,0 50%, 0 100%,50% 100%,100% 100%,100% 50%,100% 0,50% 0,  50% 50% }
        60%,100%{background-position: 0 50%, 0 100%,50% 100%,100% 100%,100% 50%,100% 0,50% 0,0 0,  50% 50% }
        }

</style>