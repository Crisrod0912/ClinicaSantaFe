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
                <h1 class="text-center mb-5">Registrar Cita</h1>

                <form id="form-registro">
                    <div class="row mb-4">
                        <div class="col-md-4 mb-3">
                            <label for="cedula-paciente" class="form-label fw-bold">Cédula Paciente</label>
                            <input type="text" id="cedula-paciente" name="cedula_paciente" class="form-control" />

                            <label for="nombre-paciente" class="form-label fw-bold mt-3">Nombre Completo</label>
                            <input type="text" id="nombre-paciente" name="nombre_paciente" class="form-control" />
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="servicio" class="form-label fw-bold">Servicio</label>
                            <select id="servicio" name="servicio" class="form-select">
                                <option value="">-- Selecciona --</option>
                                <option value="consulta">Consulta</option>
                                <option value="seguimiento">Seguimiento</option>
                                <option value="urgencias">Urgencias</option>
                            </select>

                            <label for="especialidad" class="form-label fw-bold mt-3">Especialidad</label>
                            <select id="especialidad" name="especialidad" class="form-select">
                                <option value="">-- Selecciona --</option>
                                <option value="medicina-general">Medicina General</option>
                                <option value="pediatria">Pediatría</option>
                                <option value="ginecologia">Ginecología</option>
                            </select>

                            <label for="hora" class="form-label fw-bold mt-3">Hora</label>
                            <select id="hora" name="hora" class="form-select">
                                <option value="">-- Selecciona --</option>
                                <option value="08:00">08:00</option>
                                <option value="09:00">09:00</option>
                                <option value="10:00">10:00</option>
                                <option value="11:00">11:00</option>
                                <option value="14:00">14:00</option>
                                <option value="15:00">15:00</option>
                                <option value="16:00">16:00</option>
                            </select>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="fecha" class="form-label fw-bold">Fecha</label>
                            <input type="text" id="fecha" name="fecha" class="form-control date-input" readonly />

                            <label class="form-label fw-bold mt-3">Calendario</label>
                            <div id="calendario-simple"></div>
                        </div>
                    </div>
            </div>

            <div class="row">
                <div class="col-12 text-center">
                    <button type="submit" class="btn btn-register">Registrar Cita</button>
                </div>
            </div>
            </form>
        </div>
    </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="../js/jquery-3.7.1.min.js"></script>
    <script src="../js/admin-citas.js"></script>
    <script src="../js/calendario.js"></script>
</body>

</html>
