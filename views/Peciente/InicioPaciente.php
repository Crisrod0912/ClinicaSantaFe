<!DOCTYPE html>

<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Historical Citas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" />
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
        <h1>Bienvenido</h1>
        <div class="row row-cols-1 row-cols-md-2 g-4">
            <div class="col">
                <div class="card text-center border-0 shadow-sm h-100 custom-card">
                    <div class="card-body">
                        <i class="bi bi-calendar-check custom-icon"></i>
                        <h5 class="card-title">Citas</h5>
                        <p class="card-text">Consulta tu historial de citas médicas.</p>
                        <a href="HistorialCitas.php" class="btn btn-nuevo btn-card w-100">Ir</a>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="card text-center border-0 shadow-sm h-100 custom-card">
                    <div class="card-body">
                        <i class="bi bi-folder2-open custom-icon"></i>
                        <h5 class="card-title">Expediente</h5>
                        <p class="card-text">Consulta tu expediente médico completo.</p>
                        <a href="Expediente.html" class="btn btn-nuevo btn-card w-100">Ir</a>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="card text-center border-0 shadow-sm h-100 custom-card">
                    <div class="card-body">
                        <i class="bi bi-capsule-pill custom-icon"></i>
                        <h5 class="card-title">Medicamentos</h5>
                        <p class="card-text">Revisa los medicamentos que te han recetado.</p>
                        <a href="Medicamentos.php" class="btn btn-nuevo btn-card w-100">Ir</a>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="card text-center border-0 shadow-sm h-100 custom-card">
                    <div class="card-body">
                        <i class="bi bi-shield-plus custom-icon"></i>
                        <h5 class="card-title">Vacunas</h5>
                        <p class="card-text">Consulta el registro de tus vacunas.</p>
                        <a href="Vacunas.php" class="btn btn-nuevo btn-card w-100">Ir</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>

</html>
