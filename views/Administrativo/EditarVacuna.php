<!DOCTYPE html>

<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Actualizar Vacuna</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
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
                <a href="../router.php?action=logout" class="text-black me-3 text-decoration-none" onclick="return confirm('Esta seguro que desea cerrar sesion?')">Cerrar sesion</a>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <h1 class="text-center mb-5">Actualizar Vacuna</h1>

                <form id="form-registro">
                    <div class="row mb-4">
                        <div class="col-md-6 mb-3">
                            <label for="nombre" class="form-label fw-bold">Nombre</label>
                            <input type="text" id="nombre" name="nombre" class="form-control" required />
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="enfermedad" class="form-label fw-bold">Enfermedad</label>
                            <select id="enfermedad" name="enfermedad" class="form-select" required>
                                <option value="">-- Selecciona --</option>
                            </select>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6 mb-3">
                            <label for="esquema" class="form-label fw-bold">Esquema Vacunación</label>
                            <select id="esquema" name="esquema_vacunacion" class="form-select" required>
                                <option value="">-- Selecciona --</option>
                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="via-administracion" class="form-label fw-bold">Vía Administración</label>
                            <select id="via-administracion" name="via_administracion" class="form-select" required>
                                <option value="">-- Selecciona --</option>
                            </select>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6 mb-3">
                            <label for="estado" class="form-label fw-bold">Estado</label>
                            <select id="estado" name="estado" class="form-select" required>
                                <option value="">-- Selecciona --</option>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12 text-center">
                            <button type="submit" class="btn btn-register">Actualizar Vacuna</button>
                            <a href="Vacunas.php" class="btn btn-secondary ms-2">Cancelar</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/js/all.min.js"></script>
    <script src="../js/admin-vacunas.js"></script>
</body>

</html>
