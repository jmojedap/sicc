<?php $this->load->view('assets/highcharts') ?>

<div id="vizApp" class="">
    <ul class="nav nav-pills mb-2 justify-content-center">
        <li class="nav-item pointer" v-for="optionYear in years">
            <a class="nav-link" aria-current="page" v-on:click="setYear(optionYear.value)" v-bind:class="{'active': optionYear.value == year }">
                {{ optionYear.name }}
            </a>
        </li>
    </ul>
</div>
<figure class="highcharts-figure">
    <div id="chart" style="min-height: calc(100vh - 230px);" class="border"></div>
</figure>

<?php $this->load->view('app/noticias/resultados/categoria/script_v') ?>