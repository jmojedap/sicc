<!-- Modal -->
<div class="modal fade" id="modalCreated" tabindex="-1" aria-labelledby="exampleModalCreatedLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Usuario creado</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <i class="fa fa-check text-success"></i> Usuario creado correctamente
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" v-on:click="goToCreated">Abrir usuario</button>
                <button type="button" class="btn btn-secondary" v-on:click="clearForm" data-bs-dismiss="modal">
                    <i class="fa fa-plus"></i> Crear otro
                </button>
            </div>
        </div>
    </div>
</div>