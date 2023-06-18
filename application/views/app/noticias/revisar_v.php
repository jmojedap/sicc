<style>
    .cat-grid {
        display: grid;
        grid-template-rows: 1fr 1fr 1fr;
        grid-template-columns: 1fr 1fr 1fr 1fr 1fr;
        gap: 10px;
    }
</style>

<div id="clasificarApp">
    <div class="center_box_750">
        <div class="progress mb-2">
            <div class="progress-bar" role="progressbar" v-bind:style="`width: ` + checkedPercent + `%`" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100">
                {{ qtyUserChecked }} / {{ checkGoal }}
            </div>
        </div>
        <div class="mb-2 d-flex justify-content-between">
            <button class="btn btn-light w150p" href="<?= URL_APP . "noticias/anterior/{$row->aleatorio}" ?>" disabled>
                <i class="fa fa-arrow-left"></i>
            </button>
            <a class="btn w150p" href="<?= URL_APP . "noticias/siguiente/{$row->aleatorio}" ?>"
                v-bind:class="{'btn-light': savedId == 0, 'btn-primary': savedId > 0 }"
                >
                Siguiente  <i class="fa fa-arrow-right"></i>
            </a>
        </div>
        <div class="d-flex border-bottom">
            <div>
                <img
                    v-bind:src="noticia.url_thumbnail"
                    class="rounded me-2"
                    alt="imagen noticia"
                    onerror="this.src='<?= URL_IMG ?>app/nd.png'"
                >
            </div>
            <div>
                <h3>{{ noticia.titular }}</h3>
                <p>{{ noticia.epigrafe }}</p>
                <p class="text-muted">
                    {{ dateFormat(noticia.fecha_publicacion) }}
                    &middot;
                    {{ ago(noticia.fecha_publicacion) }}
                </p>
                <p>
                    <a v-bind:href="noticia.url_publicacion" class="btn btn-secondary btn-sm w100p" target="_blank">Abrir</a>
                </p>
            </div>
        </div>
        <div class="d-flex justify-content-evenly w100pc py-3 border-bottom">
            <button class="btn w120p" v-for="optionClasificacion in optionsClasificacion"
                v-bind:class="btnClasificacionClass(optionClasificacion.value)"
                v-on:click="setClasificacion(optionClasificacion.value)"
                >
                {{ optionClasificacion.title }}
            </button>
        </div>
        <div class="cat-grid py-3 border-bottom">
            <button class="btn" v-for="optionCat1 in optionsCat"
                v-bind:class="{'btn-outline-secondary': optionCat1.id != formValues.cat_1, 'btn-primary': optionCat1.id == formValues.cat_1 }"
                v-on:click="setCat1(optionCat1.id)"
                >
                {{ optionCat1.title }}
            </button>
        </div>
        <form accept-charset="utf-8" method="POST" id="noticiaForm" @submit.prevent="sendForm">
            <fieldset v-bind:disabled="loading">
                <input name="actualizado_por" type="hidden" v-model="formValues.actualizado_por">
                <input name="cat_1" type="hidden" v-model="formValues.cat_1">
                <input name="clasificacion" type="hidden" v-model="formValues.clasificacion">
                <div class="text-center py-3">
                    <button class="btn btn-lg w150p"
                        type="submit" v-bind:disabled="submitDisabled"
                        v-bind:class="{'btn-success': !submitDisabled, 'btn-outline-success': submitDisabled }"
                        >
                        Guardar
                    </button>
                    <div class="my-2">
                        <p v-show="noticia.actualizado_por.length > 0">
                            <i class="fa fa-check"></i> Clasificada por <a v-bind:href="`<?= URL_APP . 'noticias/explorar/?fe2=' ?>` + noticia.actualizado_por">{{ noticia.actualizado_por }}</a>
                            &middot;
                            {{ ago(noticia.updated_at) }} 
                        </p>
                    </div>
                </div>
            <fieldset>
        </form>
    </div>
</div>

<script>
var clasificarApp = createApp({
    data(){
        return{
            noticia: <?= json_encode($row) ?>,
            loading: false,
            formValues: {
                cat_1: <?= $row->cat_1 ?>,
                cat_2: <?= $row->cat_2 ?>,
                clasificacion: <?= $row->clasificacion ?>,
            },
            optionsClasificacion: <?= json_encode($options_clasificacion) ?>,
            optionsCat: <?= json_encode($options_cat_1) ?>,
            savedId: 0,
            checkGoal:60,
            qtyUserChecked: <?= $qty_user_checked ?>,
        }
    },
    methods: {
        setClasificacion: function(value){
            this.formValues.clasificacion = value
        },
        setCat1: function(value){
            this.formValues.cat_1 = value
        },
        setCat2: function(value){
            this.formValues.cat_2 = value
        },
        sendForm: function(){
            this.loading = true
            var formValues = new FormData(document.getElementById('noticiaForm'))

            axios.post(URL_APP + 'noticias/actualizar/' + this.noticia.id, formValues)
            .then(response => {
                if ( response.data.saved_id > 0 ) {
                    this.savedId = response.data.saved_id
                    toastr['success']('Guardado')
                    this.qtyUserChecked++
                }
                this.loading = false
            })
            .catch( function(error) {console.log(error)} )
        },
        btnClasificacionClass: function(value){
            currentOption = this.optionsClasificacion.find(item => item.value == value)
            if ( this.formValues.clasificacion == value) {
                return currentOption.class;
            } else {
                return currentOption.emptyClass;
            }
        },
        // Formatos y nombres
        //-----------------------------------------------------------------------------
        ago: function(date){
            if (!date) return ''
            return moment(date, 'YYYY-MM-DD HH:mm:ss').fromNow()
        },
        dateFormat: function(date){
            if (!date) return ''
            return moment(date).format('D MMM YYYY')
        },
    },
    computed:{
        submitDisabled: function(){
            if ( this.formValues.cat_1 == 0 ) return true
            if ( this.formValues.clasificacion == 0 ) return true
            return false
        },
        checkedPercent: function() {
            return Pcrn.intPercent(this.qtyUserChecked, this.checkGoal)
        }
    },
    mounted(){
        //this.getList()
    },
}).mount('#clasificarApp')
</script>