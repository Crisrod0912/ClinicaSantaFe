<!DOCTYPE html>

<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Ver Expediente - Paciente</title>
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
                <a class="nav-link active" href="ConsultarExpediente.php">Expediente</a>
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
            <div class="col-md-11">
                <div class="d-flex align-items-center mb-4">
                    <button onclick="window.location.href='ConsultarExpediente.php'"
                        class="btn btn-outline-secondary me-3">
                        <i class="fas fa-arrow-left"></i> Volver
                    </button>
                    <h1>Cargando...</h1>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="personal-info-section">
                            <h3 class="section-title">Información Personal</h3>

                            <div class="mb-3">
                                <label class="form-label">Cédula</label>
                                <input type="text" class="form-control" id="cedula" readonly>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Correo electrónico</label>
                                <input type="email" class="form-control" id="correo" readonly>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Número Teléfono</label>
                                <input type="tel" class="form-control" id="telefono" readonly>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Estado Civil</label>
                                <input type="text" class="form-control" id="estado_civil" readonly>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Fecha Nacimiento</label>
                                <input type="text" class="form-control" id="fecha_nacimiento" readonly>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Género</label>
                                <input type="text" class="form-control" id="genero" readonly>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Dirección</label>
                                <input type="text" class="form-control" id="direccion" readonly>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-8">
                        <div class="medical-info-section">
                            <h3 class="section-title">Información Médica</h3>

                            <div class="row medical-row">
                                <div class="col-md-4">
                                    <label class="form-label">Peso (kg)</label>
                                    <input type="text" class="form-control" id="peso" readonly>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Altura (cm)</label>
                                    <input type="text" class="form-control" id="altura" readonly>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Tipo Sangre</label>
                                    <input type="text" class="form-control" id="tipo_sangre" readonly>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label">Enfermedades</label>
                                <textarea class="form-control" id="enfermedades" rows="3" readonly></textarea>
                            </div>

                            <div class="mb-4">
                                <label class="form-label">Alergias</label>
                                <textarea class="form-control" id="alergias" rows="3" readonly></textarea>
                            </div>

                            <div class="mb-4">
                                <label class="form-label">Cirugías</label>
                                <textarea class="form-control" id="cirugias" rows="3" readonly></textarea>
                            </div>

                            <div class="text-center">
                                <button type="button" class="btn btn-update" onclick="editarExpediente()">
                                    <i class="fas fa-edit"></i> Editar Expediente
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="loadingOverlay" class="position-fixed top-0 start-0 w-100 h-100 d-none"
        style="background: rgba(0,0,0,0.5); z-index: 9999;">
        <div class="d-flex justify-content-center align-items-center h-100">
            <div class="spinner-border text-light" role="status">
                <span class="visually-hidden">Cargando...</span>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/js/all.min.js"></script>
    <script src="../js/jquery-3.7.1.min.js"></script>
    <script>
        let idUsuarioActual = null;

        document.addEventListener('DOMContentLoaded', function () {
            const urlParams = new URLSearchParams(window.location.search);
            const idUsuario = urlParams.get('id_usuario');

            if (idUsuario) {
                idUsuarioActual = idUsuario;
                cargarDatosExpediente(idUsuario);
            } else {
                alert('ID de usuario no proporcionado');
                window.location.href = 'ConsultarExpediente.php';
            }
        });

        function mostrarCargando(mostrar) {
            const overlay = document.getElementById('loadingOverlay');
            if (overlay) {
                overlay.classList.toggle('d-none', !mostrar);
            }
        }

        function cargarDatosExpediente(idUsuario) {
            mostrarCargando(true);

            fetch(`../router.php?action=showExpedienteByUser&id_usuario=${idUsuario}`)
                .then(response => response.json())
                .then(data => {
                    mostrarCargando(false);
                    if (data.status === 'success') {
                        mostrarDatos(data.data);
                    } else {
                        alert('Error al cargar expediente: ' + data.message);
                    }
                })
                .catch(error => {
                    mostrarCargando(false);
                    console.error('Error:', error);
                    alert('Error de conexión al cargar expediente');
                });
        }

        function mostrarDatos(datos) {
            const titulo = document.querySelector('h1');
            if (titulo && datos.nombre && datos.apellidos) {
                titulo.textContent = `${datos.nombre} ${datos.apellidos}`;
            }

            document.getElementById('cedula').value = datos.cedula_usuario || '';
            document.getElementById('correo').value = datos.correo || '';
            document.getElementById('telefono').value = datos.telefono || '';
            document.getElementById('estado_civil').value = datos.estado_civil ?
                datos.estado_civil.charAt(0).toUpperCase() + datos.estado_civil.slice(1) : '';
            document.getElementById('fecha_nacimiento').value = datos.fecha_nacimiento ?
                new Date(datos.fecha_nacimiento).toLocaleDateString('es-ES') : '';
            document.getElementById('genero').value = datos.genero ?
                datos.genero.charAt(0).toUpperCase() + datos.genero.slice(1) : '';
            document.getElementById('direccion').value = datos.direccion || '';

            document.getElementById('peso').value = datos.peso || '';
            document.getElementById('altura').value = datos.altura || '';
            document.getElementById('tipo_sangre').value = datos.tipo_sangre || '';
            document.getElementById('enfermedades').value = datos.enfermedades || '';
            document.getElementById('alergias').value = datos.alergias || '';
            document.getElementById('cirugias').value = datos.cirugias || '';
        }

        function editarExpediente() {
            if (idUsuarioActual) {
                window.location.href = `ActualizarExpediente.html?id_usuario=${idUsuarioActual}`;
            }
        }
    </script>
</body>

</html>
