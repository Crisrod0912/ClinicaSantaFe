<!DOCTYPE html>

<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Inicio del Administrador</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" />
    <link rel="stylesheet" href="../css/style.css" />
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid">
            <a href="inicioAdmin.html" class="navbar-brand d-flex align-items-center">
                <div class="logo"></div>
            </a>
            <div class="navbar-nav me-auto">
                <a class="nav-link" href="Asociacion.php">Médico-Especialidad</a>
                <a class="nav-link" href="Usuarios.php">Usuarios</a>
                <a class="nav-link" href="Roles.php">Roles</a>
                <a class="nav-link" href="Citas.php">Citas</a>
                <a class="nav-link" href="Medicamentos.php">Medicamentos</a>
                <a class="nav-link" href="Vacunas.php">Vacunas</a>
            </div>
            <div class="d-flex align-items-center">
                <a href="../router.php?action=logout" class="text-black me-3 text-decoration-none"onclick="return confirm('Esta seguro que desea cerrar sesion?')">Cerrar sesion</a>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <h1 class="mb-4">Panel del Administrador</h1>
        <div class="row row-cols-1 row-cols-md-2 g-4">
            <div class="col">
                <div class="card text-center border-0 shadow-sm h-100 custom-card">
                    <div class="card-body">
                        <i class="bi bi-person-heart custom-icon fs-1 mb-3"></i>
                        <h5 class="card-title">Médico-Especialidad</h5>
                        <p class="card-text">Gestiona las asignaciones entre médicos y sus especialidades médicas.</p>
                        <a href="Asociacion.php" class="btn btn-nuevo btn-card w-100">Gestionar Asignaciones</a>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="card text-center border-0 shadow-sm h-100 custom-card">
                    <div class="card-body">
                        <i class="bi bi-people custom-icon fs-1 mb-3"></i>
                        <h5 class="card-title">Usuarios</h5>
                        <p class="card-text">Gestiona el registro, modificación y control de usuarios del sistema.</p>
                        <a href="Usuarios.php" class="btn btn-nuevo btn-card w-100">Gestionar Usuarios</a>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="card text-center border-0 shadow-sm h-100 custom-card">
                    <div class="card-body">
                        <i class="bi bi-person-badge custom-icon fs-1 mb-3"></i>
                        <h5 class="card-title">Roles</h5>
                        <p class="card-text">Administra los roles de acceso del sistema.</p>
                        <a href="Roles.php" class="btn btn-nuevo btn-card w-100">Administrar Roles</a>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="card text-center border-0 shadow-sm h-100 custom-card">
                    <div class="card-body">
                        <i class="bi bi-calendar-check custom-icon fs-1 mb-3"></i>
                        <h5 class="card-title">Citas</h5>
                        <p class="card-text">Registra y gestiona citas médicas para pacientes.</p>
                        <a href="Citas.php" class="btn btn-nuevo btn-card w-100">Gestionar Citas</a>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="card text-center border-0 shadow-sm h-100 custom-card">
                    <div class="card-body">
                        <i class="bi bi-capsule custom-icon fs-1 mb-3"></i>
                        <h5 class="card-title">Medicamentos</h5>
                        <p class="card-text">Registra y mantiene actualizado el catálogo de medicamentos.</p>
                        <a href="Medicamentos.php" class="btn btn-nuevo btn-card w-100">Ver Medicamentos</a>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="card text-center border-0 shadow-sm h-100 custom-card">
                    <div class="card-body">
                        <i class="bi bi-shield-plus custom-icon fs-1 mb-3"></i>
                        <h5 class="card-title">Vacunas</h5>
                        <p class="card-text">Administra la base de datos de vacunas del sistema.</p>
                        <a href="Vacunas.php" class="btn btn-nuevo btn-card w-100">Ver Vacunas</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>

</html>
