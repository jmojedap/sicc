<style>
    p {
        text-align: justify;
        text-justify: inter-word;
    }

    .bg-success {
        background-color: #98ca3f !important;
    }
</style>

<div id="reporte_plan_app">
    <h1 class="text-center">Reporte de avance plan de acción</h1>
    <table class="table table-bordered">
        <tbody>
            <tr>
                <td width="30%">Fecha</td>
                <td>2022/06/2022</td>
            </tr>
            <tr>
                <td>Entidad</td>
                <td>Secretaría Distrital de Cultura, Recreación y Deporte</td>
            </tr>
            <tr>
                <td>Contrato No.</td>
                <td>313 de 2022</td>
            </tr>
            <tr>
                <td>Nombre completo del contratista</td>
                <td>Javier Mauricio Ojeda Pepinosa</td>
            </tr>
            <tr>
                <td>Radicado plan de acción</td>
                <td>20229100201613</td>
            </tr>
        </tbody>
    </table>
    
    <?php $this->load->view('app/ejecucion/reporte_plan/cronograma') ?>
    <?php $this->load->view('app/ejecucion/reporte_plan/actividades') ?>

    <!-- CIERRE Y FIRMA -->
    <div class="card">
        <div class="card-body text-center">
            <div class="border-top" style="width: 25%; margin: 50px auto;">
                <strong>JAVIER MAURICIO OJEDA PEPINOSA</strong>
                <br>
            </div>
        </div>
    </div>
    
</div>

<?php $this->load->view('app/ejecucion/reporte_plan/vue') ?>