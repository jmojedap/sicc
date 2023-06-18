<style>
    .cat-grid {
        display: grid;
        /*grid-template-rows: 1fr 1fr 1fr;*/
        grid-template-columns: 1fr;
        gap: 0.3em;
    }

    .cat {
        border: 1px solid #DDD;
        background-color: #FFF;
        border-radius: 0.2em;
        height: 2.5em;
    }

    .cat:hover{
        border: 1px solid #CCC;
        background-color: #FAFAFA;
    }

    .cat.active{
        background-color: var(--color-main-app);
        border-color: var(--color-main-app);
        color: #FFF;
    }

    .compartible-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 0.3em;
    }

    /* Pantallas pequeñas */
    @media (max-width: 767px) {
        .cat-grid { 
            grid-template-columns: 1fr 1fr;
            gap: 3px;
         }
    }

    .section {
        text-align: center;
        /*color: #398ceb;*/
        font-size: 1.2em;
    }
</style>

<div id="clasificarApp">
    <div class="center_box">
        <div class="d-flex justify-content-between mb-2">
            <div>
                <button class="btn btn-light me-2 btn-sm w75p" v-on:click="setSection('clasificar')" v-show="section == 'ayuda'">
                    <i class="fa fa-arrow-left"></i>
                </button>
                <button class="btn btn-light me-2 btn-sm" v-on:click="setSection('ayuda')" v-show="section == 'clasificar'">
                    ¿Cómo clasificar?
                </button>
            </div>
            <a class="btn btn-light btn-sm w75p" href="<?= URL_APP ?>noticias/salir">
                Salir
            </a>
        </div>
        <div class="progress mb-2">
            <div class="progress-bar bg-main" role="progressbar" v-bind:style="`width: ` + checkedPercent + `%`" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100">
                {{ qtyUserChecked }} / {{ checkGoal }}
            </div>
        </div>
        <div v-show="section == 'clasificar'">
            <?php $this->load->view('app/noticias/clasificar_afro/formulario_v') ?>
        </div>
        <div v-show="section == 'ayuda'">
            <?php $this->load->view('app/noticias/clasificar_afro/ayuda_v') ?>
        </div>
        

    </div>
</div>

<?php $this->load->view('app/noticias/clasificar_afro/vue_v') ?>