<!DOCTYPE html>

<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Medicamentos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/style.css" />
</head>

<body>
    <?php include("../components/MenuMedico.php") ?>

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <h1 class="text-center mb-5">Medicaciones de Pacientes</h1>

                <div class="row mb-4">
                    <div class="col-md-12">
                        <input type="text" id="buscarMedicamento" class="form-control" placeholder="Buscar medicamento">
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-4">
                        <a type="button" class="btn btn-nuevo" href="RegistrarMedicacion.html">+ Registrar
                            Medicamento</a>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="custom-table table">
                        <thead>
                            <tr>
                                <th>Paciente</th>
                                <th>Medicamento</th>
                                <th>Grupo Terapéutico</th>
                                <th>Fecha Prescripción</th>
                                <th>Tiempo Tratamiento</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="medicamentosTableBody">
                            <tr>
                                <td colspan="7" class="text-center">
                                    <div class="spinner-border text-primary" role="status">
                                        <span class="visually-hidden">Cargando...</span>
                                    </div>
                                    <p class="mt-2">Cargando medicaciones...</p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="medicacionModal" tabindex="-1" aria-labelledby="medicacionModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="medicacionModalLabel">
                        <i class="fas fa-pills me-2"></i>Detalles de Medicación
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="medicacionDetalles">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalConfirmacion" tabindex="-1" aria-labelledby="modalConfirmacionLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalConfirmacionLabel">
                        <i class="fas fa-exclamation-triangle me-2 text-warning"></i>Confirmar Acción
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>¿Está seguro que desea deshabilitar esta medicación?</p>
                    <p class="text-muted small">Esta acción marcará la medicación como inactiva.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-danger" onclick="confirmarDeshabilitar()">
                        <i class="fas fa-ban me-2"></i>Deshabilitar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="../js/medicacion.js"></script>
</body>

</html>
