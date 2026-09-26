<!DOCTYPE html>

<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Registrar Usuario</title>
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
                <h1 class="text-center mb-5">Registrar Usuario</h1>

                <form id="form-registro">
                    <div class="row mb-4">
                        <div class="col-md-4 mb-3">
                            <label for="cedula" class="form-label fw-bold">Cédula</label>
                            <input type="text" id="cedula" name="cedula" class="form-control" />
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="nombre" class="form-label fw-bold">Nombre</label>
                            <input type="text" id="nombre" name="nombre" class="form-control" />
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="apellidos" class="form-label fw-bold">Apellidos</label>
                            <input type="text" id="apellidos" name="apellidos" class="form-control" />
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-4 mb-3">
                            <label for="email" class="form-label fw-bold">Correo electrónico</label>
                            <input type="email" id="email" name="email" class="form-control" />
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="telefono" class="form-label fw-bold">Teléfono</label>
                            <input type="tel" id="telefono" name="telefono" class="form-control" />
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="fecha-nacimiento" class="form-label fw-bold">Fecha Nacimiento</label>
                            <input type="date" id="fecha-nacimiento" name="fecha_nacimiento" class="form-control" />
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-4 mb-3">
                            <label for="password" class="form-label fw-bold">Contraseña</label>
                            <input type="password" id="password" name="password" class="form-control" />
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="genero" class="form-label fw-bold">Género</label>
                            <select id="genero" name="genero" class="form-select">
                                <option value="">-- Selecciona --</option>
                            </select>

                            <label for="estado-civil" class="form-label fw-bold mt-3">Estado Civil</label>
                            <select id="estado-civil" name="estado_civil" class="form-select">
                                <option value="">-- Selecciona --</option>
                            </select>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="rol" class="form-label fw-bold">Rol</label>
                            <select id="rol" name="rol" class="form-select">
                                <option value="">-- Selecciona --</option>
                            </select>

                            <label for="estado" class="form-label fw-bold mt-3">Estado</label>
                            <select id="estado" name="estado" class="form-select">
                                <option value="">-- Selecciona --</option>
                            </select>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-12 mb-3">
                            <label for="direccion" class="form-label fw-bold">Dirección</label>
                            <textarea id="direccion" name="direccion" class="form-control" rows="4"></textarea>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12 text-center">
                            <button type="submit" class="btn btn-register">Registrar Usuario</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="../js/jquery-3.7.1.min.js"></script>
    <script src="../js/usuarios.js"></script>
</body>

</html>