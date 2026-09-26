<!DOCTYPE html>

<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Panel del Médico</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" />
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
        <h1 class="mb-4">Panel del Médico</h1>
        <div class="row row-cols-1 row-cols-md-2 g-4">
            <div class="col">
                <div class="card text-center border-0 shadow-sm h-100 custom-card">
                    <div class="card-body">
                        <i class="bi bi-calendar-event custom-icon fs-1 mb-3"></i>
                        <h5 class="card-title">Citas</h5>
                        <p class="card-text">Gestiona y revisa las citas asignadas.</p>
                        <a href="CitasProgramadas.php" class="btn btn-nuevo btn-card w-100">Ver Citas</a>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="card text-center border-0 shadow-sm h-100 custom-card">
                    <div class="card-body">
                        <i class="bi bi-folder2-open custom-icon fs-1 mb-3"></i>
                        <h5 class="card-title">Expediente</h5>
                        <p class="card-text">Accede a los expedientes clínicos de tus pacientes.</p>
                        <a href="ConsultarExpediente.php" class="btn btn-nuevo btn-card w-100">Ver Expedientes</a>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="card text-center border-0 shadow-sm h-100 custom-card">
                    <div class="card-body">
                        <i class="bi bi-capsule custom-icon fs-1 mb-3"></i>
                        <h5 class="card-title">Medicamentos</h5>
                        <p class="card-text">Consulta y administra los tratamientos recetados.</p>
                        <a href="Medicamentos.php" class="btn btn-nuevo btn-card w-100">Ver Medicamentos</a>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="card text-center border-0 shadow-sm h-100 custom-card">
                    <div class="card-body">
                        <i class="bi bi-shield-check custom-icon fs-1 mb-3"></i>
                        <h5 class="card-title">Vacunas</h5>
                        <p class="card-text">Revisa y actualiza el historial de vacunación de tus pacientes.</p>
                        <a href="Vacunas.php" class="btn btn-nuevo btn-card w-100">Ver Vacunas</a>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="card text-center border-0 shadow-sm h-100 custom-card">
                    <div class="card-body">
                        <i class="bi bi-person-heart custom-icon fs-1 mb-3"></i>
                        <h5 class="card-title">Mis Especialidades</h5>
                        <p class="card-text">Consulta las especialidades médicas que tienes asignadas.</p>
                        <a href="Especialidades.php" class="btn btn-nuevo btn-card w-100">Ver Especialidades</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="../js/scripts.js" defer></script>
</body>

</html>
