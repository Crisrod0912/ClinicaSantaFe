<!DOCTYPE html>

<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Registrar Cita</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/style.css" />
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid">
            <a href="inicioPaciente.html" class="navbar-brand d-flex align-items-center">
                <div class="logo"></div>
            </a>
            <div class="navbar-nav me-auto">
                <a class="nav-link" href="HistorialCitas.php">Citas</a>
                <a class="nav-link" href="Expediente.html">Expediente</a>
                <a class="nav-link" href="Medicamentos.php">Medicamentos</a>
                <a class="nav-link" href="Vacunas.php">Vacunas</a>
            </div>
            <div class="d-flex align-items-center">
                <a href="../router.php?action=logout" class="text-black me-3 text-decoration-none" onclick="return confirm('Esta seguro que desea cerrar sesion?')">Cerrar sesion</a>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <h1 class="text-center mb-5">Registrar Cita</h1>

                <div class="row mb-4">
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-bold">Servicio</label>
                        <select class="form-select" id="servicio">
                            <option selected></option>
                        </select>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-bold">Fecha</label>
                        <input type="text" id="fecha" name="fecha" class="form-control date-input" readonly />
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-bold">Hora</label>
                        <select class="form-select" id="hora">
                            <option selected></option>
                            <option value="08:00:00">08:00</option>
                            <option value="09:00:00">09:00</option>
                            <option value="10:00:00">10:00</option>
                            <option value="11:00:00">11:00</option>
                            <option value="14:00:00">14:00</option>
                            <option value="15:00:00">15:00</option>
                            <option value="16:00:00">16:00</option>
                        </select>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-bold">Especialidad</label>
                        <select class="form-select" id="especialidad">
                            <option selected></option>
                        </select>
                    </div>

                    <div class="col-md-4 mb-4">
                        <label class="form-label fw-bold">Calendario</label>
                        <div class="calendar-simple">
                            <div id="calendario-simple"></div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12 text-center">
                        <button type="button" class="btn btn-register">Registrar Cita</button>
                        <button type="button" id="cancelEdit" class="btn btn-secondary"
                            style="display:none;">Cancelar</button>
                    </div>
                </div>

                <div id="citaResult" class="mt-3"></div>
            </div>
        </div>
    </div>

    <script src="../js/jquery-3.7.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="../js/citas.js"></script>
    <script src="../js/calendario.js"></script>
</body>

</html>
