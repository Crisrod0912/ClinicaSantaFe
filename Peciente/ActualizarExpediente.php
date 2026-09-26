<!DOCTYPE html>

<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Actualizar Expediente</title>
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

                <form id="expedienteForm">
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
                                    <input type="email" id="correo" name="correo" class="form-control"
                                        placeholder="Correo" required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Número Teléfono</label>
                                    <input type="tel" id="telefono" name="telefono" class="form-control"
                                        placeholder="Número">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Estado Civil</label>
                                    <select id="estadoCivil" name="estado_civil" class="form-select">
                                        <option value="">Estado civil</option>
                                        <option value="soltero">Soltero/a</option>
                                        <option value="casado">Casado/a</option>
                                        <option value="divorciado">Divorciado/a</option>
                                        <option value="viudo">Viudo/a</option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Fecha Nacimiento</label>
                                    <input type="date" id="fechaNacimiento" name="fecha_nacimiento"
                                        class="form-control date-input">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Género</label>
                                    <select id="genero" name="genero" class="form-select">
                                        <option value="">Genero</option>
                                        <option value="masculino">Masculino</option>
                                        <option value="femenino">Femenino</option>
                                        <option value="otro">Otro</option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Dirección</label>
                                    <input type="text" id="direccion" name="direccion" class="form-control"
                                        placeholder="Dirección">
                                </div>
                            </div>
                        </div>

                        <div class="col-md-8">
                            <div class="medical-info-section">
                                <h3 class="section-title">Información Médica</h3>

                                <div class="row medical-row">
                                    <div class="col-md-4">
                                        <label class="form-label">Peso</label>
                                        <input type="text" id="peso" name="peso" class="form-control"
                                            placeholder="Peso (kg)">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Altura</label>
                                        <input type="text" id="altura" name="altura" class="form-control"
                                            placeholder="Altura (cm)">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Tipo Sangre</label>
                                        <input type="text" id="tipoSangre" name="tipo_sangre" class="form-control"
                                            placeholder="Tipo Sangre">
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <label class="form-label">Enfermedades</label>
                                    <input type="text" id="enfermedades" name="enfermedades" class="form-control"
                                        placeholder="Enfermedades">
                                </div>

                                <div class="mb-4">
                                    <label class="form-label">Alergias</label>
                                    <input type="text" id="alergias" name="alergias" class="form-control"
                                        placeholder="Alergias">
                                </div>

                                <div class="mb-4">
                                    <label class="form-label">Cirugías</label>
                                    <input type="text" id="cirugias" name="cirugias" class="form-control"
                                        placeholder="Cirugías">
                                </div>

                                <div class="text-center">
                                    <button type="submit" class="btn btn-register">Guardar Información</button>
                                    <button type="button" class="btn btn-secondary"
                                        onclick="window.location.href='Expediente.html'">Cancelar</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="../js/expediente.js"></script>
</body>

</html>
