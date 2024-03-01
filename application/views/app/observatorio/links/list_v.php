<div class="alert alert-info" role="alert" v-show="qtyResults==0">
    <i class="fa fa-info-circle"></i>
    No hay resultados para la búsqueda realizada
</div>

<div class="pb-2 mb-3 border-bottom" v-for="(row, key) in list" v-bind:id="`row_` + row.id"
    v-show="viewFormat == `list`">
    <div class="d-flex">
        <div>
            <div class="d-flex justify-content-between">
                <h4 class="mt-0">
                    <a v-bind:href="row.link" class="" target="_blank">
                        {{ row.nombre }}
                    </a>
                </h4>
            </div>
            <p>{{ row.descripcion }}</p>
            <p><small class="text-muted">{{ row.link }}</small></p>
        </div>
    </div>

</div>