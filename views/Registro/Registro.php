<!DOCTYPE html>

<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Registrarse</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/style.css" />
</head>

<body>
    <a href="Login.php" class="back-link">&lt; Regresar</a>

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <h1>Registrarse</h1>

                <form id="form-registro">
                    <div class="row mb-4">
                        <div class="col-md-4 mb-3">
                            <label for="cedula" class="form-label fw-bold">Cédula *</label>
                            <input type="text" id="cedula" name="cedula" class="form-control" required />
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="nombre" class="form-label fw-bold">Nombre *</label>
                            <input type="text" id="nombre" name="nombre" class="form-control" required />
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="apellidos" class="form-label fw-bold">Apellidos *</label>
                            <input type="text" id="apellidos" name="apellidos" class="form-control" required />
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-4 mb-3">
                            <label for="email" class="form-label fw-bold">Correo electrónico *</label>
                            <input type="email" id="email" name="email" class="form-control" required />
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
                        <div class="col-md-8 mb-3">
                            <label for="direccion" class="form-label fw-bold">Dirección *</label>
                            <textarea id="direccion" name="direccion" class="form-control" rows="4" required></textarea>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="genero" class="form-label fw-bold">Género</label>
                            <select id="genero" name="genero" class="form-select">
                                <option value="">-- Selecciona --</option>
                                <option value="masculino">Masculino</option>
                                <option value="femenino">Femenino</option>
                                <option value="otro">Otro</option>
                            </select>

                            <label for="estado-civil" class="form-label fw-bold mt-3">Estado Civil</label>
                            <select id="estado-civil" name="estado_civil" class="form-select">
                                <option value="">-- Selecciona --</option>
                                <option value="soltero">Soltero(a)</option>
                                <option value="casado">Casado(a)</option>
                                <option value="viudo">Viudo(a)</option>
                                <option value="divorciado">Divorciado(a)</option>
                            </select>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6 mb-3">
                            <label for="password" class="form-label fw-bold">Contraseña *</label>
                            <input type="password" id="password" name="password" class="form-control" required />
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="confirm-password" class="form-label fw-bold">Verificar Contraseña *</label>
                            <input type="password" id="confirm-password" name="confirm_password" class="form-control"
                                required />
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12 text-center">
                            <button type="submit" class="btn btn-register">Registrarse</button>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-12 text-center">
                            <small class="text-muted">Los campos marcados con * son obligatorios</small>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="../js/scripts.js" defer></script>
</body>

</html>
