<style>
.image-container {
    position: relative;
    width: 802px;
    height: auto;
}

.label {
    position: absolute;
    background-color: rgba(200, 240, 255, 0.8);
    font-size: 0.8em;
    padding: 2px 5px;
    border-radius: 5px;
    box-shadow: 0 0 5px rgba(0, 0, 0, 0.5);
    text-align: center;
}
</style>

<div id="filbo2023App">

    <ul class="nav nav-tabs mb-2 justify-content-center">
        <li class="nav-item">
            <a class="nav-link" aria-current="page" href="#" v-on:click="setSection('heatMap')" v-bind:class="{'active': section == 'heatMap' }">
                <i class="fa-solid fa-fire"></i> Mapa de calor
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#" v-on:click="setSection('powerbi')" v-bind:class="{'active': section == 'powerbi' }">
                <i class="fa-solid fa-chart-simple"></i> Gráficas
            </a>
        </li>
    </ul>

    <div v-show="section == 'heatMap'">
        <nav class="nav nav-pills nav-fill mb-2">
            <a class="nav-link" href="#" v-for="(dia,key) in dias" v-bind:class="{'active': key == currentDia}"
                v-on:click="setDia(key)">
                {{ dia[2] }}
            </a>
        </nav>
        <nav class="nav nav-pills nav-fill mb-2">
            <a class="nav-link" href="#" v-for="(hora,keyHora) in horas"
                v-bind:class="{'active': keyHora == currentHora}" v-on:click="setHora(keyHora)">
                {{ hora[1] }}
            </a>
        </nav>
        <div>
            <div class="mb-2 text-center">
                <div class="btn-group me-2">
                    <button class="btn btn-light w50p" v-on:click="goToConteo(-1)" v-bind:disabled="playing">
                        <i class="fa-solid fa-backward-step"></i>
                    </button>
                    <button class="btn btn-light w50p" v-on:click="playConteos" v-show="!playing">
                        <i class="fa fa-play"></i>
                    </button>
                    <button class="btn btn-light w50p" v-on:click="pauseConteos" v-show="playing">
                        <i class="fa fa-pause"></i>
                    </button>
                    <button class="btn btn-light w50p" v-on:click="goToConteo(1)" v-bind:disabled="playing">
                        <i class="fa-solid fa-forward-step"></i>
                    </button>
                </div>
                <div class="btn-group">
                    <button class="btn w50p" v-for="speed in speeds" v-on:click="setSpeed(speed.miliseconds)"
                        v-bind:disabled="playing">
                        <span v-bind:class="{'text-primary': speed.miliseconds == playingSpeed }">
                            {{ speed.label }}
                        </span>
                    </button>
                </div>
            </div>
            <div class="d-flex">
                <div class="image-container me-2">
                    <img v-bind:src="`<?= URL_CONTENT ?>data_science/filbo2023/images/` + currentImg" alt="Imagen"
                        style="width: 800px;"
                        onerror="this.src='<?= URL_CONTENT ?>data_science/filbo2023/images/nd.jpg'" class="border">
                    <div class="label" v-for="zona in zonas" v-bind:style="labelPosition(zona)" v-show="displayLabels">
                        {{ zona[1] }}
                    </div>
                    <div class="mt-2">
                        <input type="checkbox" v-model="displayLabels"> Nombres zonas &middot;
                    </div>
                </div>
                <div class="border p-2 w-100 bg-white">
                    <table class="table table-borderless">
                        <thead>
                            <th class="text-center"></th>
                            <th class="text-center">Zona</th>
                            <th class="text-center">Asistentes</th>
                        </thead>
                        <tbody>
                            <tr v-for="(conteo, keyConteo) in conteos" v-show="currentMomento == conteo[6]">
                                <td class="text-center">
                                    <i class="fa fa-circle" v-bind:class="classAsistentes(conteo)"></i>
                                </td>
                                <td>
                                    {{ conteo[3] }}
                                    <br>
                                    <div class="progress" style="height: 6px;">
                                        <div class="progress-bar" role="progressbar"
                                            v-bind:style="`width: `+ asistentesPercent(conteo[5]) +`%;`"
                                            aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </td>
                                <td class="text-center">{{ conteo[5] }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>


    <div v-show="section == `powerbi`">
        <iframe title="Report Section" width="100%" height="850"
            src="https://app.powerbi.com/view?r=eyJrIjoiZWQzMjY1Y2ItMTUwYS00OTJkLWE2Y2UtZGE2YWRiZDRkNmNmIiwidCI6IjRmNzkzOWM3LWFhNjAtNDliZC05YjdiLTZmODFjMzdkMWIzNyJ9"
            frameborder="0" allowFullScreen="true"></iframe>
    </div>
</div>

<script>
var maxAsistentes = 130;
var widthBase = 800;
var heightBase = 500;

// VueApp
//-----------------------------------------------------------------------------
var filbo2023App = createApp({
    data() {
        return {
            section: '<?= $section ?>',
            dias: <?= json_encode($dias['arr_sheet']) ?>,
            currentDia: 0,
            horas: <?= json_encode($horas['arr_sheet']) ?>,
            currentHora: 0,
            conteos: <?= json_encode($conteos['arr_sheet']) ?>,
            zonas: <?= json_encode($zonas['arr_sheet']) ?>,
            displayLabels: true,
            playing: false,
            intervalAnimation: null,
            speeds: [{
                    label: '0.5',
                    miliseconds: 2000
                },
                {
                    label: '0.75',
                    miliseconds: 1500
                },
                {
                    label: '1',
                    miliseconds: 1000
                },
                {
                    label: '1.25',
                    miliseconds: 750
                },
            ],
            playingSpeed: 1000,
        }
    },
    methods: {
        setDia: function(keyDia) {
            this.currentDia = keyDia
        },
        setHora: function(keyHora) {
            this.currentHora = keyHora
        },
        asistentesPercent: function(asistentes) {
            return Pcrn.intPercent(asistentes, maxAsistentes);
        },
        labelPosition: function(zona) {
            return 'top: ' + zona[5] + 'px; left: ' + zona[6] + 'px;';
        },
        classAsistentes: function(conteo) {
            zona = this.zonas.find(item => item[0] == conteo[4])
            factor = conteo[5] / zona[7]
            if (factor < 0.1) return 'text-muted'
            if (factor < 0.5) return 'text-info'
            if (factor < 1) return 'text-primary'
            if (factor < 1.5) return 'text-warning'
            if (factor >= 1.5) return 'text-danger'
            return 'text-light'
        },
        playConteos: function() {
            this.playing = true
            this.intervalAnimation = setInterval(() => {
                this.goToConteo(1)
            }, this.playingSpeed);
        },
        goToConteo: function(sumHora) {
            this.currentHora += sumHora
            if (this.currentHora > 8) {
                this.currentHora = 0
                this.currentDia++
            }
            if (this.currentHora < 0) {
                console.log('pre', this.currentDia)
                this.currentHora = this.horas.length
                this.currentDia--
                console.log('post', this.currentDia)
            }
            if (this.currentDia >= this.dias.length) {
                this.currentDia = 0
                this.currentHora = 0
            }
            if (this.currentDia < 0) {
                this.currentDia = 0
                this.currentHora = 0
            }
        },
        pauseConteos: function() {
            this.playing = false
            clearInterval(this.intervalAnimation)
        },
        setSpeed: function(miliseconds) {
            this.playingSpeed = miliseconds
        },
        setSection: function(newSection){
            this.section = newSection
            history.pushState(null, null, URL_APP + 'data_science/filbo2023/' + this.section)
        },
    },
    computed: {
        currentImg() {
            return this.dias[this.currentDia][0] + '_' + this.horas[this.currentHora][0] + '.jpg'
        },
        currentMomento() {
            return this.dias[this.currentDia][0] + '_' + this.horas[this.currentHora][0]
        },
    },
    mounted() {
        //this.getList()
    }
}).mount('#filbo2023App')
</script>