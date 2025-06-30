<!-- Modal -->
<div class="modal fade" id="detailsModal" tabindex="-1" role="dialog" aria-labelledby="detailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="detail_modal_label">{{ element.display_name }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <table class="table table-borderless table-sm">
                    <tr>
                        <td width="30%" class="text-end td-title">ID</td>
                        <td>{{ element.id }}</td>
                    </tr>
                    <tr>
                        <td class="text-end td-title">Nombre</td>
                        <td>
                            {{ element.display_name }}
                        </td>
                    </tr>
                    <tr>
                        <td class="text-end td-title">Correo electrónico</td>
                        <td>
                            {{ element.email }}
                        </td>
                    </tr>
                    
                    
                </table>
            </div>
            <div class="modal-footer">
                    <a class="btn btn-primary w100p" v-bind:href="`<?= URL_APP . 'users/profile/' ?>` + element.id">Abrir</a>
                    <button type="button" class="btn btn-light w100p" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>