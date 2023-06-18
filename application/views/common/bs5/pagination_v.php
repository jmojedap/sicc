<div class="d-flex justify-content-end w-100">
    <a class="btn text-muted">
        {{ (numPage - 1) * perPage + 1 }} a {{ numPage * perPage }} de
        <span class="text-primary">
                {{ qtyResults }}
        </span>
    </a>
    <button class="btn" v-on:click="sumPage(-1)" title="Página anterior" v-bind:disabled="numPage==1">
        <i class="fa fa-chevron-left"></i>
    </button>
    <button class="btn" v-on:click="sumPage(1)" title="Página siguiente" v-bind:disabled="numPage>=maxPage">
        <i class="fa fa-chevron-right"></i>
    </button>
</div>