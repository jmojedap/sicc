<div class="center_box_750">
    <div class="card">
        <div class="card-body">
            <h1 class="lead">Exportar datos de Escuela de Cuidado</h1>
            
        </div>
        <table class="table">
            <tbody>
                <tr>
                    <td>Manzanas de cuidado</td>
                    <td width="150px">
                        <a class="btn btn-success" href="<?= URL_ADMIN . "cuidado/export_manzanas/" ?>">
                            <i class="fa fa-download"></i> Descargar
                        </a>
                    </td>
                </tr>
                <tr>
                    <td>Actividades escuela</td>
                    <td width="150px">
                        <a class="btn btn-success" href="<?= URL_ADMIN . "cuidado/export/" ?>">
                            <i class="fa fa-download"></i> Descargar
                        </a>
                    </td>
                </tr>
                <tr>
                    <td>Detalles de Actividades: Módulos y sesiones</td>
                    <td width="150px">
                        <a class="btn btn-success" href="<?= URL_ADMIN . "cuidado/export_actividades_sesiones/" ?>">
                            <i class="fa fa-download"></i> Descargar
                        </a>
                    </td>
                </tr>
                <tr>
                    <td>Detalles de Actividades: Estudiantes asistentes</td>
                    <td width="150px">
                        <a class="btn btn-success" href="<?= URL_ADMIN . "cuidado/export_asistencia/" ?>">
                            <i class="fa fa-download"></i> Descargar
                        </a>
                    </td>
                </tr>
                <tr>
                    <td>Estudiantes</td>
                    <td width="150px">
                        <a class="btn btn-success" href="<?= URL_ADMIN . "cuidado/export_students" ?>">
                            <i class="fa fa-download"></i> Descargar
                        </a>
                    </td>
                </tr>
                <tr>
                    <td>Detalles Estudiantes: Personas con quienes convive</td>
                    <td width="150px">
                        <a class="btn btn-success" href="<?= URL_ADMIN . "users/export_meta/personas_hogar" ?>">
                            <i class="fa fa-download"></i> Descargar
                        </a>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

</div>