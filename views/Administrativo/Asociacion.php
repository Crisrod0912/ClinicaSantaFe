<!DOCTYPE html>

<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Médico-Especialidad</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/style.css" />
</head>

<body>
    <?php include("../components/MenuAdmin.php") ?>

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <h1 class="text-center mb-5">Médico-Especialidad</h1>

                <div class="row mb-4">
                    <div class="col-md-12">
                        <input type="text" class="form-control" placeholder="Buscar médico o especialidad">
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-4">
                        <a type="button" class="btn btn-nuevo" href="RegistrarAsociacion.html">+ Asignar Especialidad a
                            Médico</a>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="custom-table table" id="medicoEspecialidadTable">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Médico</th>
                                <th>Especialidad</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalConfirmacion" tabindex="-1" aria-labelledby="modalConfirmacionLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalConfirmacionLabel">Confirmar acción</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="text-center">
                        <i class="fas fa-exclamation-triangle text-warning mb-3" style="font-size: 3rem;"></i>
                        <h5 class="mb-3">¿Está seguro que desea deshabilitar esta asignación?</h5>
                        <p class="text-muted">Esta acción cambiará el estado de la asignación médico-especialidad a
                            "Inactivo". Podrá habilitarla nuevamente si es necesario.</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-danger"
                        onclick="deshabilitarMedicoEspecialidad()">Deshabilitar</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/js/all.min.js"></script>
    <script src="../js/jquery-3.7.1.min.js"></script>
    <script src="../js/asociacion.js"></script>
</body>

</html>
