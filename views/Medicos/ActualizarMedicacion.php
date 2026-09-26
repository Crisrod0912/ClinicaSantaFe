<!DOCTYPE html>

<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Editar Medicación</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/style.css" />
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid">
            <a href="inicioMedico.html" class="navbar-brand d-flex align-items-center">
                <div class="logo"></div>
            </a>
            <div class="navbar-nav me-auto">
                <a class="nav-link" href="CitasProgramadas.php">Citas</a>
                <a class="nav-link" href="ConsultarExpediente.php">Expediente</a>
                <a class="nav-link" href="Medicamentos.php">Medicamentos</a>
                <a class="nav-link" href="Vacunas.php">Vacunas</a>
                <a class="nav-link" href="Especialidades.php">Especialidades</a>
            </div>
            <div class="d-flex align-items-center">
                <a href="../router.php?action=logout" class="text-black me-3 text-decoration-none" onclick="return confirm('Esta seguro que desea cerrar sesion?')">Cerrar sesion</a>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <h1 class="text-center mb-5">Editar Medicación</h1>

                <form id="form-registro">
                    <div class="row mb-4">
                        <div class="col-md-8">
                            <div class="row mb-3">
                                <div class="col-md-6 mb-3">
                                    <label for="cedula" class="form-label fw-bold">Cédula Paciente</label>
                                    <input type="text" id="cedula" name="cedula" class="form-control"
                                        placeholder="Ingrese cédula" readonly />
                                    <small class="text-muted">No se puede modificar el paciente</small>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="nombre" class="form-label fw-bold">Nombre Completo</label>
                                    <input type="text" id="nombre" name="nombre" class="form-control"
                                        placeholder="Nombre del paciente" readonly />
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6 mb-3">
                                    <label for="medicamento" class="form-label fw-bold">Medicamento</label>
                                    <select id="medicamento" name="medicamento" class="form-select">
                                        <option value="">-- Selecciona --</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="tiempo" class="form-label fw-bold">Tiempo Tratamiento</label>
                                    <input type="text" id="tiempo" name="tiempo" class="form-control"
                                        placeholder="Ej: 7 días" />
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12">
                                    <label for="indicaciones" class="form-label fw-bold">Indicaciones</label>
                                    <textarea id="indicaciones" name="indicaciones" class="form-control" rows="4"
                                        placeholder="Indicaciones del medicamento"></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="fecha" class="form-label fw-bold">Fecha Prescripción</label>
                                <input type="date" id="fecha" name="fecha" class="form-control date-input" />
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Calendario</label>
                                <div id="calendario-simple" class="border rounded p-3 bg-light text-center"
                                    style="min-height: 250px;">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12 text-center">
                            <button type="submit" class="btn btn-register">Actualizar Medicación</button>
                            <a href="Medicamentos.php" class="btn btn-secondary ms-2">Cancelar</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="../js/jquery-3.7.1.min.js"></script>
    <script src="../js/calendario.js"></script>
    <script src="../js/medicacion.js"></script>
</body>

</html>
