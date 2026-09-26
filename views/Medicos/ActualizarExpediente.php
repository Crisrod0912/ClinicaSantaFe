<!DOCTYPE html>

<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Editar Expediente - Paciente</title>
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

                <form id="formExpediente">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="personal-info-section">
                                <h3 class="section-title">Información Personal</h3>

                                <div class="mb-3">
                                    <label class="form-label">Cédula</label>
                                    <input type="text" class="form-control" name="cedula" placeholder="Número" readonly>
                                    <small class="text-muted">Campo de solo lectura</small>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Correo electrónico *</label>
                                    <input type="email" class="form-control" name="correo" placeholder="Correo"
                                        required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Número Teléfono</label>
                                    <input type="tel" class="form-control" name="telefono" placeholder="Número">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Estado Civil</label>
                                    <select class="form-select" name="estado_civil">
                                        <option value="">Seleccione estado civil</option>
                                        <option value="soltero">Soltero/a</option>
                                        <option value="casado">Casado/a</option>
                                        <option value="divorciado">Divorciado/a</option>
                                        <option value="viudo">Viudo/a</option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Fecha Nacimiento</label>
                                    <input type="date" class="form-control date-input" name="fecha_nacimiento">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Género</label>
                                    <select class="form-select" name="genero">
                                        <option value="">Seleccione género</option>
                                        <option value="masculino">Masculino</option>
                                        <option value="femenino">Femenino</option>
                                        <option value="otro">Otro</option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Dirección</label>
                                    <input type="text" class="form-control" name="direccion" placeholder="Dirección">
                                </div>
                            </div>
                        </div>

                        <div class="col-md-8">
                            <div class="medical-info-section">
                                <h3 class="section-title">Información Médica</h3>

                                <div class="row medical-row">
                                    <div class="col-md-4">
                                        <label class="form-label">Peso (kg)</label>
                                        <input type="text" class="form-control" name="peso" placeholder="Peso">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Altura (cm)</label>
                                        <input type="text" class="form-control" name="altura" placeholder="Altura">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Tipo Sangre</label>
                                        <input type="text" class="form-control" name="tipo_sangre"
                                            placeholder="Ej: O+, A-, AB+">
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <label class="form-label">Enfermedades</label>
                                    <textarea class="form-control" name="enfermedades" rows="3"
                                        placeholder="Enfermedades crónicas o relevantes"></textarea>
                                </div>

                                <div class="mb-4">
                                    <label class="form-label">Alergias</label>
                                    <textarea class="form-control" name="alergias" rows="3"
                                        placeholder="Alergias conocidas a medicamentos, alimentos, etc."></textarea>
                                </div>

                                <div class="mb-4">
                                    <label class="form-label">Cirugías</label>
                                    <textarea class="form-control" name="cirugias" rows="3"
                                        placeholder="Cirugías previas y fechas aproximadas"></textarea>
                                </div>

                                <div class="text-center">
                                    <button type="button" class="btn btn-update">
                                        <i class="fas fa-save"></i> Guardar Cambios
                                    </button>
                                    <button type="button" id="btnCancelar" class="btn btn-secondary ms-2">
                                        <i class="fas fa-times"></i> Cancelar
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
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
        document.addEventListener('DOMContentLoaded', function () {
            const urlParams = new URLSearchParams(window.location.search);
            const idUsuario = urlParams.get('id_usuario');

            if (idUsuario) {
                cargarDatosExpediente(idUsuario);
                configurarFormulario(idUsuario);
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
                        llenarFormulario(data.data);
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

        function llenarFormulario(datos) {
            const titulo = document.querySelector('h1');
            if (titulo && datos.nombre && datos.apellidos) {
                titulo.textContent = `${datos.nombre} ${datos.apellidos}`;
            }

            const form = document.getElementById('formExpediente');

            form.querySelector('input[name="cedula"]').value = datos.cedula_usuario || '';
            form.querySelector('input[name="correo"]').value = datos.correo || '';
            form.querySelector('input[name="telefono"]').value = datos.telefono || '';
            form.querySelector('select[name="estado_civil"]').value = datos.estado_civil || '';
            form.querySelector('input[name="fecha_nacimiento"]').value = datos.fecha_nacimiento || '';
            form.querySelector('select[name="genero"]').value = datos.genero || '';
            form.querySelector('input[name="direccion"]').value = datos.direccion || '';

            form.querySelector('input[name="peso"]').value = datos.peso || '';
            form.querySelector('input[name="altura"]').value = datos.altura || '';
            form.querySelector('input[name="tipo_sangre"]').value = datos.tipo_sangre || '';
            form.querySelector('textarea[name="enfermedades"]').value = datos.enfermedades || '';
            form.querySelector('textarea[name="alergias"]').value = datos.alergias || '';
            form.querySelector('textarea[name="cirugias"]').value = datos.cirugias || '';
        }

        function configurarFormulario(idUsuario) {
            const btnGuardar = document.querySelector('.btn-update');
            const btnCancelar = document.querySelector('#btnCancelar');

            if (btnGuardar) {
                btnGuardar.addEventListener('click', function () {
                    if (validarFormulario()) {
                        guardarExpediente(idUsuario);
                    }
                });
            }

            if (btnCancelar) {
                btnCancelar.addEventListener('click', function () {
                    if (confirm('¿Está seguro que desea cancelar los cambios?')) {
                        window.location.href = 'ConsultarExpediente.php';
                    }
                });
            }
        }

        function guardarExpediente(idUsuario) {
            mostrarCargando(true);

            const form = document.getElementById('formExpediente');
            const formData = new FormData();

            formData.append('id_usuario', idUsuario);
            formData.append('correo', form.querySelector('input[name="correo"]').value);
            formData.append('telefono', form.querySelector('input[name="telefono"]').value);
            formData.append('estado_civil', form.querySelector('select[name="estado_civil"]').value);
            formData.append('fecha_nacimiento', form.querySelector('input[name="fecha_nacimiento"]').value);
            formData.append('genero', form.querySelector('select[name="genero"]').value);
            formData.append('direccion', form.querySelector('input[name="direccion"]').value);
            formData.append('peso', form.querySelector('input[name="peso"]').value);
            formData.append('altura', form.querySelector('input[name="altura"]').value);
            formData.append('tipo_sangre', form.querySelector('input[name="tipo_sangre"]').value);
            formData.append('enfermedades', form.querySelector('textarea[name="enfermedades"]').value);
            formData.append('alergias', form.querySelector('textarea[name="alergias"]').value);
            formData.append('cirugias', form.querySelector('textarea[name="cirugias"]').value);

            fetch('../router.php?action=updateExpedienteAdmin', {
                method: 'POST',
                body: formData
            })
                .then(response => response.json())
                .then(data => {
                    mostrarCargando(false);
                    if (data.status === 'success') {
                        alert('✅ Expediente actualizado exitosamente');
                        window.location.href = 'ConsultarExpediente.php';
                    } else {
                        alert('❌ Error al actualizar expediente: ' + data.message);
                    }
                })
                .catch(error => {
                    mostrarCargando(false);
                    console.error('Error:', error);
                    alert('❌ Error de conexión al actualizar expediente');
                });
        }

        function validarFormulario() {
            const form = document.getElementById('formExpediente');
            const correo = form.querySelector('input[name="correo"]').value;
            const telefono = form.querySelector('input[name="telefono"]').value;

            if (!correo) {
                alert('El correo electrónico es obligatorio');
                return false;
            }

            if (correo && !validarEmail(correo)) {
                alert('Por favor ingrese un correo electrónico válido');
                return false;
            }

            if (telefono && !/^[0-9-\s]+$/.test(telefono)) {
                alert('Por favor ingrese un número de teléfono válido');
                return false;
            }

            return true;
        }

        function validarEmail(email) {
            const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return re.test(email);
        }
    </script>
</body>

</html>
