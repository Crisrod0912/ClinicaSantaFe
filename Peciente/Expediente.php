<!DOCTYPE html>

<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Expediente Médico</title>
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
            <div class="col-md-11">
                <h1 id="nombreCompleto">Cargando...</h1>

                <div class="row">
                    <div class="col-md-4">
                        <div class="personal-info-section">
                            <h3 class="section-title">Información Personal</h3>

                            <div class="mb-3">
                                <label class="form-label">Cédula</label>
                                <input type="text" id="cedula" class="form-control" readonly>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Correo electrónico</label>
                                <input type="email" id="correo" class="form-control" readonly>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Número Teléfono</label>
                                <input type="tel" id="telefono" class="form-control" readonly>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Estado Civil</label>
                                <select id="estadoCivil" class="form-select" disabled>
                                    <option value="">Estado civil</option>
                                    <option value="soltero">Soltero/a</option>
                                    <option value="casado">Casado/a</option>
                                    <option value="divorciado">Divorciado/a</option>
                                    <option value="viudo">Viudo/a</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Fecha Nacimiento</label>
                                <input type="date" id="fechaNacimiento" class="form-control date-input" readonly>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Género</label>
                                <select id="genero" class="form-select" disabled>
                                    <option value="">Genero</option>
                                    <option value="masculino">Masculino</option>
                                    <option value="femenino">Femenino</option>
                                    <option value="otro">Otro</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Dirección</label>
                                <input type="text" id="direccion" class="form-control" readonly>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-8">
                        <div class="medical-info-section">
                            <h3 class="section-title">Información Médica</h3>

                            <div class="row medical-row">
                                <div class="col-md-4">
                                    <label class="form-label">Peso</label>
                                    <input type="text" id="peso" class="form-control" readonly>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Altura</label>
                                    <input type="text" id="altura" class="form-control" readonly>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Tipo Sangre</label>
                                    <input type="text" id="tipoSangre" class="form-control" readonly>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label">Enfermedades</label>
                                <input type="text" id="enfermedades" class="form-control" readonly>
                            </div>

                            <div class="mb-4">
                                <label class="form-label">Alergias</label>
                                <input type="text" id="alergias" class="form-control" readonly>
                            </div>

                            <div class="mb-4">
                                <label class="form-label">Cirugías</label>
                                <input type="text" id="cirugias" class="form-control" readonly>
                            </div>

                            <div class="text-center">
                                <a type="button" class="btn btn-register" href="ActualizarExpediente.html">Actualizar
                                    Información</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="../js/expediente.js"></script>
</body>

</html>
