<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user']['id'])) {
    header("Location: ../index.php");
    exit();
}

error_log("Usuario en sesión: " . print_r($_SESSION['user'], true));
?>
<!DOCTYPE html>

<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Historial de Citas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/style.css" />
</head>

<body>
    <?php include("../components/MenuPaciente.php") ?>

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <h1 class="text-center mb-5">Historial de Citas</h1>

                <div class="row mb-4">
                    <div class="col-md-12">
                        <input type="text" class="form-control" placeholder="Buscar en citas...">
                    </div>
                </div>

                <div id="citasResult" class="mb-3"></div>

                <div class="row mb-4">
                    <div class="col-4">
                        <a type="button" class="btn btn-nuevo" href="RegistrarCita.html">+ Registrar Cita</a>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table custom-table" id="citasTable">
                        <thead>
                            <tr>
                                <th>Fecha</th>
                                <th>Hora</th>
                                <th>Servicio</th>
                                <th>Especialidad</th>
                                <th>Médico</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="7" class="text-center">Cargando citas...</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalConfirmacion" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirmar Cancelación</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    ¿Está seguro de que desea cancelar esta cita?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
                    <button type="button" class="btn btn-danger" id="confirmarCancelacion">Sí, Cancelar</button>
                </div>
            </div>
        </div>
    </div>

    <div id="loadingOverlay"
        style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 9999;">
        <div
            style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); color: white; text-align: center;">
            <i class="fas fa-spinner fa-spin fa-3x"></i>
            <p class="mt-3">Cargando...</p>
        </div>
    </div>

    <script src="../js/jquery-3.7.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="../js/citas.js"></script>

    <script>
        $(document).ready(function () {
            console.log('Documento listo, iniciando carga de citas...');
            setTimeout(function () {
                if (typeof loadPatientAppointments === 'function') {
                    loadPatientAppointments();
                } else {
                    console.error('Función loadPatientAppointments no encontrada');
                }
            }, 1000);
        });
    </script>
</body>

</html>
