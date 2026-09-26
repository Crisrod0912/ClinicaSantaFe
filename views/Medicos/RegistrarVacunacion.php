<!DOCTYPE html>

<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Registrar Vacunación</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
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
        <div class="row justify-content-center">
            <div class="col-md-10">
                <h1 class="text-center mb-5">Registrar Vacunación</h1>

                <form id="form-vacunacion">
                    <div class="row mb-4">
                        <div class="col-md-8">
                            <div class="row mb-3">
                                <div class="col-md-6 mb-3">
                                    <label for="cedula" class="form-label fw-bold">Cédula Paciente</label>
                                    <input type="text" id="cedula" name="cedula" class="form-control"
                                        placeholder="Ingrese cédula" />
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="nombre_completo" class="form-label fw-bold">Nombre Completo</label>
                                    <input type="text" id="nombre_completo" name="nombre_completo" class="form-control"
                                        placeholder="Nombre del paciente" readonly />
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6 mb-3">
                                    <label for="id_vacuna" class="form-label fw-bold">Vacuna</label>
                                    <select id="id_vacuna" name="id_vacuna" class="form-select">
                                        <option value="">-- Selecciona --</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="dosis" class="form-label fw-bold">Dosis</label>
                                    <input type="text" id="dosis" name="dosis" class="form-control"
                                        placeholder="Ej: 1ra dosis" />
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-12 mb-3">
                                    <label for="tiempo_tratamiento" class="form-label fw-bold">Tiempo de
                                        Tratamiento</label>
                                    <input type="text" id="tiempo_tratamiento" name="tiempo_tratamiento"
                                        class="form-control" placeholder="Ej: Dosis única, cada 6 meses" />
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12">
                                    <label for="descripcion" class="form-label fw-bold">Descripción</label>
                                    <textarea id="descripcion" name="descripcion" class="form-control" rows="4"
                                        placeholder="Observaciones de la vacunación"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="fecha_vacunacion" class="form-label fw-bold">Fecha Vacunación</label>
                                <input type="date" id="fecha_vacunacion" name="fecha_vacunacion"
                                    class="form-control date-input" />
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Calendario</label>
                                <div id="calendario-simple" class="border rounded p-3 bg-light text-center"
                                    style="min-height: 250px;">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12 text-center">
                            <button type="submit" class="btn btn-register">Registrar Vacunación</button>
                            <a href="Vacunas.php" class="btn btn-secondary ms-2">Cancelar</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="../js/jquery-3.7.1.min.js"></script>
    <script src="../js/calendario.js"></script>
    <script src="../js/vacunacion.js"></script>
</body>

</html>
