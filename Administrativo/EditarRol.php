<!DOCTYPE html>

<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Actualizar Rol</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
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
                <h1 class="text-center mb-5">Actualizar Rol</h1>

                <form id="form-registro">
                    <div class="row mb-4">
                        <div class="col-md-4 mb-3">
                            <label for="nombre" class="form-label fw-bold">Nombre</label>
                            <input type="text" id="nombre" name="nombre" class="form-control" />

                            <label for="estado" class="form-label fw-bold mt-3">Estado</label>
                            <select id="estado" name="estado" class="form-select">
                                <option value="">-- Selecciona --</option>
                            </select>
                        </div>

                        <div class="col-md-8 mb-3">
                            <label for="descripcion" class="form-label fw-bold">Descripción</label>
                            <textarea id="descripcion" name="descripcion" class="form-control" rows="6"></textarea>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12 text-center">
                            <button type="submit" class="btn btn-register">Actualizar Rol</button>
                            <a href="Roles.php" class="btn btn-secondary ms-2">Cancelar</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="loadingOverlay"
        style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 9999;">
        <div class="d-flex justify-content-center align-items-center h-100">
            <div class="text-white text-center">
                <i class="fas fa-spinner fa-spin fa-3x mb-3"></i>
                <div>Cargando datos...</div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="../js/jquery-3.7.1.min.js"></script>
    <script src="../js/roles.js"></script>
</body>

</html>
