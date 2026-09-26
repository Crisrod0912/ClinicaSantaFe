<!DOCTYPE html>

<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Expedientes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/style.css" />
</head>

<body>
    <?php include("../components/MenuMedico.php") ?>

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <h1 class="text-center mb-5">Expedientes de Pacientes</h1>

                <div class="mb-4">
                    <input type="text" class="search-input w-100"
                        placeholder="Buscar por cédula o nombre del paciente...">
                </div>

                <table class="custom-table table">
                    <thead>
                        <tr>
                            <th>Cédula</th>
                            <th>Nombre Paciente</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="3" class="text-center">Cargando expedientes...</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/js/all.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            cargarExpedientes();

            // Configurar búsqueda
            const searchInput = document.querySelector('.search-input');
            if (searchInput) {
                searchInput.addEventListener('input', function () {
                    filtrarExpedientes(this.value);
                });
            }
        });

        function cargarExpedientes() {
            fetch('../router.php?action=listExpedientes')
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        mostrarExpedientes(data.data);
                    } else {
                        console.error('Error:', data.message);
                        mostrarError('Error al cargar expedientes: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error de conexión:', error);
                    mostrarError('Error de conexión al cargar expedientes');
                });
        }

        function mostrarExpedientes(expedientes) {
            const tbody = document.querySelector('.custom-table tbody');
            if (!tbody) return;

            if (expedientes.length === 0) {
                tbody.innerHTML = `
                <tr>
                    <td colspan="3" class="text-center">No hay expedientes disponibles</td>
                </tr>
            `;
                return;
            }

            tbody.innerHTML = expedientes.map(exp => `
            <tr data-cedula="${exp.cedula_usuario}" data-nombre="${exp.nombre_completo.toLowerCase()}">
                <td>${exp.cedula_usuario}</td>
                <td>${exp.nombre_completo}</td>
                <td>
                    <button type="button" class="btn-action btn-ver me-2" 
                            onclick="verExpediente(${exp.id_usuario})" 
                            title="Ver Expediente">
                        <i class="fas fa-file-medical"></i> Ver
                    </button>
                    <button type="button" class="btn-action btn-editar" 
                            onclick="editarExpediente(${exp.id_usuario})" 
                            title="Editar Expediente">
                        <i class="fas fa-edit"></i> Editar
                    </button>
                </td>
            </tr>
        `).join('');
        }

        function filtrarExpedientes(termino) {
            const rows = document.querySelectorAll('.custom-table tbody tr');
            const terminoLower = termino.toLowerCase();

            rows.forEach(row => {
                const cedula = row.dataset.cedula || '';
                const nombre = row.dataset.nombre || '';

                const coincide = cedula.includes(terminoLower) || nombre.includes(terminoLower);
                row.style.display = coincide ? '' : 'none';
            });
        }

        function verExpediente(idUsuario) {
            window.location.href = `Expediente.html?id_usuario=${idUsuario}`;
        }

        function editarExpediente(idUsuario) {
            window.location.href = `ActualizarExpediente.html?id_usuario=${idUsuario}`;
        }

        function mostrarError(mensaje) {
            alert('❌ ' + mensaje);
        }
    </script>
</body>

</html>
