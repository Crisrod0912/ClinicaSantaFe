let currentVacunacionId = null;

$(document).ready(function () {
    if (window.location.pathname.includes('Vacunas.php') || $('#vacunasTableBody').length > 0) {
        inicializarListado();
    }

    if (window.location.pathname.includes('RegistrarVacunacion') || $('#form-vacunacion').length > 0) {
        inicializarRegistro();
    }

    if (window.location.pathname.includes('ActualizarVacunacion') || $('#form-vacunacion-edit').length > 0) {
        inicializarActualizacion();
    }
});

function inicializarListado() {
    console.log('Inicializando listado de vacunaciones...');
    cargarVacunaciones();

    $('#buscarVacuna').on('keyup', function () {
        const searchTerm = $(this).val().toLowerCase();
        filtrarTabla(searchTerm);
    });
}

function cargarVacunaciones() {
    console.log('Cargando vacunaciones...');

    $('#vacunasTableBody').html('<tr><td colspan="6" class="text-center"><i class="fas fa-spinner fa-spin"></i> Cargando vacunaciones...</td></tr>');

    $.ajax({
        url: '../router.php?action=listVacunas',
        method: 'GET',
        dataType: 'json',
        timeout: 10000,
        success: function (response) {
            console.log('Respuesta del servidor:', response);

            if (response && response.status === 'success') {
                if (response.data && Array.isArray(response.data)) {
                    mostrarVacunaciones(response.data);
                } else {
                    console.warn('Datos no válidos recibidos:', response.data);
                    $('#vacunasTableBody').html('<tr><td colspan="6" class="text-center">No hay datos válidos para mostrar</td></tr>');
                }
            } else {
                console.error('Error en respuesta del servidor:', response);
                const mensaje = response && response.message ? response.message : 'Error desconocido del servidor';
                $('#vacunasTableBody').html(`<tr><td colspan="6" class="text-center text-danger">Error: ${mensaje}</td></tr>`);
            }
        },
        error: function (xhr, status, error) {
            console.error('Error AJAX completo:', {
                status: status,
                error: error,
                responseText: xhr.responseText,
                statusCode: xhr.status
            });

            let mensaje = 'Error de conexión';
            if (xhr.status === 404) {
                mensaje = 'Archivo router.php no encontrado';
            } else if (xhr.status === 500) {
                mensaje = 'Error interno del servidor';
            } else if (status === 'timeout') {
                mensaje = 'Tiempo de espera agotado';
            }

            $('#vacunasTableBody').html(`<tr><td colspan="6" class="text-center text-danger">${mensaje}</td></tr>`);
        }
    });
}

function mostrarVacunaciones(vacunaciones) {
    console.log('Mostrando', vacunaciones.length, 'vacunaciones');
    let html = '';

    if (vacunaciones.length === 0) {
        html = '<tr><td colspan="6" class="text-center text-muted">No hay vacunaciones registradas</td></tr>';
    } else {
        vacunaciones.forEach(function (vacunacion, index) {
            if (!vacunacion.id_vacuna_paciente) {
                console.warn(`Vacunación ${index} sin ID válido:`, vacunacion);
                return;
            }

            const id = vacunacion.id_vacuna_paciente;
            const nombrePaciente = vacunacion.nombre_paciente || vacunacion.nombre_completo || 'Sin nombre';
            const nombreVacuna = vacunacion.nombre_vacuna || 'Vacuna no especificada';
            const dosis = vacunacion.dosis || 'No especificada';
            const fecha = formatearFecha(vacunacion.fecha_vacunacion);
            const estadoBadge = getEstadoBadge(vacunacion);

            let actionsHtml = `
                <button class="btn btn-sm btn-info" onclick="verDetalles(${id})" title="Ver Detalles" data-id="${id}">
                    <i class="fas fa-eye"></i>
                </button>
                <button class="btn btn-sm btn-warning ms-1" onclick="editarVacunacion(${id})" title="Editar" data-id="${id}">
                    <i class="fas fa-edit"></i>
                </button>
            `;

            if (vacunacion.id_estado == 1) {
                actionsHtml += `
                    <button class="btn btn-sm btn-danger ms-1" onclick="deshabilitarVacuna(${id})" title="Deshabilitar" data-id="${id}">
                        <i class="fas fa-ban"></i>
                    </button>
                `;
            } else if (vacunacion.id_estado == 2) {
                actionsHtml += `
                    <button class="btn btn-sm btn-success ms-1" onclick="habilitarVacuna(${id})" title="Habilitar" data-id="${id}">
                        <i class="fas fa-check"></i>
                    </button>
                `;
            }

            html += `
                <tr data-vacunacion-id="${id}">
                    <td>${escapeHtml(nombrePaciente)}</td>
                    <td>${escapeHtml(nombreVacuna)}</td>
                    <td>${fecha}</td>
                    <td>${escapeHtml(dosis)}</td>
                    <td>${estadoBadge}</td>
                    <td>
                        <div class="btn-group" role="group">
                            ${actionsHtml}
                        </div>
                    </td>
                </tr>
            `;
        });
    }

    $('#vacunasTableBody').html(html);
}

function verDetalles(id) {
    if (!validarId(id, 'ver detalles')) return;

    console.log('Viendo detalles de vacunación ID:', id);

    $.ajax({
        url: `../router.php?action=showVacunaPaciente&id=${id}`,
        method: 'GET',
        dataType: 'json',
        success: function (response) {
            if (response && response.status === 'success' && response.data) {
                mostrarModalDetalles(response.data);
            } else {
                const mensaje = response && response.message ? response.message : 'No se encontraron los datos';
                alert('Error al cargar detalles: ' + mensaje);
            }
        },
        error: function (xhr, status, error) {
            console.error('Error al cargar detalles:', error);
            alert('Error de conexión al cargar los detalles');
        }
    });
}

function mostrarModalDetalles(vacuna) {
    const detallesHtml = `
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">Paciente:</label>
                <div class="p-2 border rounded bg-light">
                    ${escapeHtml(vacuna.nombre_completo || 'N/A')}
                </div>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">Cédula:</label>
                <div class="p-2 border rounded bg-light">
                    ${escapeHtml(vacuna.cedula_usuario || 'N/A')}
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">Vacuna:</label>
                <div class="p-2 border rounded bg-light">
                    ${escapeHtml(vacuna.nombre_vacuna || 'N/A')}
                </div>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">Dosis:</label>
                <div class="p-2 border rounded bg-light">
                    ${escapeHtml(vacuna.dosis || 'N/A')}
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">Fecha de Vacunación:</label>
                <div class="p-2 border rounded bg-light">
                    ${formatearFecha(vacuna.fecha_vacunacion)}
                </div>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">Tiempo de Tratamiento:</label>
                <div class="p-2 border rounded bg-light">
                    ${escapeHtml(vacuna.tiempo_tratamiento || 'N/A')}
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 mb-3">
                <label class="form-label fw-bold">Descripción:</label>
                <div class="p-2 border rounded bg-light" style="min-height: 60px;">
                    ${escapeHtml(vacuna.descripcion || 'Sin descripción')}
                </div>
            </div>
        </div>
    `;

    $('#vacunaDetalles').html(detallesHtml);
    $('#vacunaModal').modal('show');
}

function editarVacunacion(id) {
    if (!validarId(id, 'editar')) return;

    console.log('Editando vacunación ID:', id);

    const posiblesArchivos = [
        `ActualizarVacunacion.html?id=${id}`,
        `ActualizarVacunacion.html?id=${id}`,
        `EditarVacunacion.php?id=${id}`
    ];

    window.location.href = posiblesArchivos[0];
}

function confirmarEliminacion(id) {
    if (!validarId(id, 'eliminar')) return;

    console.log('Preparando eliminación de vacunación ID:', id);
    currentVacunacionId = id;
    $('#modalConfirmacion').modal('show');
}

function confirmarDeshabilitacion() {
    if (!currentVacunacionId) {
        alert('Error: No se especificó qué vacunación eliminar');
        return;
    }

    const id = currentVacunacionId;
    console.log('Eliminando vacunación ID:', id);

    const btnConfirmar = $('button[onclick="confirmarDeshabilitacion()"]');
    const textoOriginal = btnConfirmar.html();
    btnConfirmar.html('<i class="fas fa-spinner fa-spin"></i> Eliminando...').prop('disabled', true);

    $.ajax({
        url: '../router.php?action=deleteVacuna',
        method: 'POST',
        data: { id: id },
        dataType: 'json',
        success: function (response) {
            btnConfirmar.html(textoOriginal).prop('disabled', false);

            if (response && response.status === 'success') {
                alert('Vacunación eliminada exitosamente');
                $('#modalConfirmacion').modal('hide');
                currentVacunacionId = null;
                cargarVacunaciones();
            } else {
                const mensaje = response && response.message ? response.message : 'Error desconocido';
                alert('Error al eliminar: ' + mensaje);
            }
        },
        error: function (xhr, status, error) {
            btnConfirmar.html(textoOriginal).prop('disabled', false);
            console.error('Error al eliminar:', error);
            alert('Error de conexión al eliminar la vacunación');
        }
    });
}

function inicializarRegistro() {
    console.log('Inicializando registro de vacunación...');

    cargarVacunasCatalogo();

    $('#form-vacunacion').on('submit', function (e) {
        e.preventDefault();
        registrarVacunacion();
    });

    setupPatientSearchVacunacion();

    const today = new Date().toISOString().split('T')[0];
    $('#fecha_vacunacion').val(today);

    $('#fecha-actual').text(new Date().toLocaleDateString('es-ES', {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    }));
}

function setupPatientSearchVacunacion() {
    let searchTimeout;

    $('#cedula').on('input', function () {
        this.value = this.value.replace(/[^0-9]/g, '');

        const cedula = $(this).val().trim();

        clearTimeout(searchTimeout);

        if (!cedula) {
            $('#nombre_completo').val('');
            $('#cedula').removeData('paciente-id');
            return;
        }

        searchTimeout = setTimeout(() => {
            buscarPacientePorCedulaAuto(cedula);
        }, 500);
    });
}

function buscarPacientePorCedulaAuto(cedula) {
    if (cedula.length < 9) {
        $('#nombre_completo').val('');
        $('#cedula').removeData('paciente-id');
        return;
    }

    console.log('Buscando paciente con cédula:', cedula);

    $.ajax({
        url: '../router.php?action=searchPatientVacuna',
        method: 'GET',
        data: { cedula: cedula },
        dataType: 'json',
        success: function (response) {
            console.log('Respuesta búsqueda automática:', response);

            if (response && response.status === 'success' && response.data) {
                const paciente = response.data;
                $('#nombre_completo').val(paciente.nombre_completo);
                $('#cedula').data('paciente-id', paciente.id_usuario || paciente.id);

                $('#id_vacuna').focus();
            } else {
                $('#nombre_completo').val('');
                $('#cedula').removeData('paciente-id');

                if (cedula.length >= 9) {
                    console.warn('Paciente no encontrado con cédula:', cedula);
                }
            }
        },
        error: function (xhr, status, error) {
            console.error('Error al buscar paciente automáticamente:', error);
            $('#nombre_completo').val('');
            $('#cedula').removeData('paciente-id');
        }
    });
}

function cargarVacunasCatalogo() {
    $.ajax({
        url: '../router.php?action=getVacunasCatalogo',
        method: 'GET',
        dataType: 'json',
        success: function (response) {
            if (response && response.status === 'success') {
                llenarSelectVacunas(response.data);
            } else {
                console.error('Error al cargar catálogo:', response);
                alert('Error al cargar el catálogo de vacunas');
            }
        },
        error: function (xhr, status, error) {
            console.error('Error al cargar catálogo:', error);
            alert('Error de conexión al cargar las vacunas disponibles');
        }
    });
}

function llenarSelectVacunas(vacunas) {
    const select = $('#id_vacuna');
    select.empty().append('<option value="">-- Selecciona una vacuna --</option>');

    if (vacunas && Array.isArray(vacunas)) {
        vacunas.forEach(vacuna => {
            select.append(`<option value="${vacuna.id_vacuna}">${escapeHtml(vacuna.nombre)}</option>`);
        });
    }
}

function registrarVacunacion() {
    if (!validarFormularioVacunacion()) {
        return;
    }

    const formData = {
        nombre_completo: $('#nombre_completo').val().trim(),
        fecha_vacunacion: $('#fecha_vacunacion').val(),
        tiempo_tratamiento: $('#tiempo_tratamiento').val().trim(),
        dosis: $('#dosis').val().trim(),
        descripcion: $('#descripcion').val().trim(),
        id_vacuna: parseInt($('#id_vacuna').val())
    };

    console.log('Datos de vacunación a enviar:', formData);

    const btnSubmit = $('#form-vacunacion button[type="submit"]');
    const textoOriginal = btnSubmit.text();
    btnSubmit.prop('disabled', true).text('Registrando...');

    $.ajax({
        url: '../router.php?action=createVacunaPatient',
        method: 'POST',
        data: formData,
        dataType: 'json',
        success: function (response) {
            console.log('Respuesta del registro:', response);

            if (response && response.status === 'success') {
                limpiarFormulario();
                alert('✅ Vacunación registrada exitosamente');

                setTimeout(() => {
                    window.location.href = 'Vacunas.php';
                }, 1500);
            } else {
                const mensaje = response && response.message ? response.message : 'Error desconocido';
                alert('❌ Error al registrar vacunación: ' + mensaje);
            }
        },
        error: function (xhr, status, error) {
            console.error('Error al registrar vacunación:', error, xhr.responseText);
            alert('❌ Error de conexión al registrar la vacunación');
        },
        complete: function () {
            btnSubmit.prop('disabled', false).text(textoOriginal);
        }
    });
}

function validarFormularioVacunacion() {
    let isValid = true;
    let errors = [];

    const nombreCompleto = $('#nombre_completo').val().trim();
    if (!nombreCompleto) {
        errors.push('El nombre completo del paciente es requerido');
        isValid = false;
    }

    const idVacuna = $('#id_vacuna').val();
    if (!idVacuna || idVacuna === '') {
        errors.push('Debe seleccionar una vacuna');
        isValid = false;
    }

    const dosis = $('#dosis').val().trim();
    if (!dosis) {
        errors.push('La dosis es requerida');
        isValid = false;
    }

    const tiempoTratamiento = $('#tiempo_tratamiento').val().trim();
    if (!tiempoTratamiento) {
        errors.push('El tiempo de tratamiento es requerido');
        isValid = false;
    }

    const fechaVacunacion = $('#fecha_vacunacion').val();
    if (!fechaVacunacion) {
        errors.push('La fecha de vacunación es requerida');
        isValid = false;
    }

    const descripcion = $('#descripcion').val().trim();
    if (!descripcion) {
        errors.push('La descripción es requerida');
        isValid = false;
    }

    if (!isValid) {
        alert('Por favor corrija los siguientes errores:\n• ' + errors.join('\n• '));

        if (!nombreCompleto) $('#nombre_completo').focus();
        else if (!idVacuna) $('#id_vacuna').focus();
        else if (!dosis) $('#dosis').focus();
        else if (!tiempoTratamiento) $('#tiempo_tratamiento').focus();
        else if (!fechaVacunacion) $('#fecha_vacunacion').focus();
    }

    return isValid;
}

function buscarPaciente() {
    const cedula = $('#cedula').val().trim();

    if (!cedula) {
        alert('Por favor ingrese una cédula');
        $('#cedula').focus();
        return;
    }

    buscarPacientePorCedulaAuto(cedula);
}

let vacunacionId = null;
let datosOriginales = null;

function inicializarActualizacion() {
    console.log('Inicializando actualización de vacunación...');

    vacunacionId = obtenerIdDeUrl();

    if (!vacunacionId) {
        alert('Error: No se especificó qué vacunación editar');
        window.location.href = 'Vacunas.php';
        return;
    }

    console.log('ID de vacunación a editar:', vacunacionId);

    cargarVacunasCatalogoParaEdicion();

    $('#form-vacunacion-edit').on('submit', function (e) {
        e.preventDefault();
        actualizarVacunacion();
    });
}

function obtenerIdDeUrl() {
    const urlParams = new URLSearchParams(window.location.search);
    const id = urlParams.get('id');
    console.log('ID obtenido de URL:', id);
    return id;
}

function cargarVacunasCatalogoParaEdicion() {
    $.ajax({
        url: '../router.php?action=getVacunasCatalogo',
        method: 'GET',
        dataType: 'json',
        success: function (response) {
            if (response && response.status === 'success') {
                llenarSelectVacunas(response.data);
                cargarDatosVacunacion();
            } else {
                console.error('Error al cargar catálogo:', response);
                alert('Error al cargar el catálogo de vacunas');
            }
        },
        error: function (xhr, status, error) {
            console.error('Error al cargar catálogo:', error);
            alert('Error de conexión al cargar las vacunas disponibles');
        }
    });
}

function cargarDatosVacunacion() {
    $('#loading-overlay').show();

    $.ajax({
        url: `../router.php?action=showVacunaPaciente&id=${vacunacionId}`,
        method: 'GET',
        dataType: 'json',
        success: function (response) {
            console.log('Datos cargados:', response);

            if (response && response.status === 'success' && response.data) {
                datosOriginales = response.data;
                llenarFormularioConDatos(response.data);
            } else {
                const mensaje = response && response.message ? response.message : 'No se encontraron los datos';
                alert('Error al cargar datos de la vacunación: ' + mensaje);
                window.location.href = 'Vacunas.php';
            }
        },
        error: function (xhr, status, error) {
            console.error('Error al cargar vacunación:', error);
            alert('Error de conexión al cargar la vacunación');
            window.location.href = 'Vacunas.php';
        },
        complete: function () {
            $('#loading-overlay').hide();
        }
    });
}

function llenarFormularioConDatos(vacunacion) {
    console.log('Llenando formulario con datos:', vacunacion);

    $('#id_vacuna_paciente').val(vacunacion.id_vacuna_paciente || vacunacionId);
    $('#cedula').val(vacunacion.cedula_usuario || '');
    $('#nombre_completo').val(vacunacion.nombre_completo || '');
    $('#dosis').val(vacunacion.dosis || '');
    $('#tiempo_tratamiento').val(vacunacion.tiempo_tratamiento || '');
    $('#fecha_vacunacion').val(vacunacion.fecha_vacunacion || '');
    $('#descripcion').val(vacunacion.descripcion || '');

    if (vacunacion.id_vacuna) {
        const checkSelect = setInterval(() => {
            if ($('#id_vacuna option').length > 1) {
                $('#id_vacuna').val(vacunacion.id_vacuna);
                clearInterval(checkSelect);
                console.log('Vacuna seleccionada:', vacunacion.id_vacuna);
            }
        }, 100);

        setTimeout(() => {
            clearInterval(checkSelect);
            $('#id_vacuna').val(vacunacion.id_vacuna);
        }, 3000);
    }
}

function actualizarVacunacion() {
    if (!validarFormularioVacunacionEdit()) {
        return;
    }

    const idVacunacionPaciente = $('#id_vacuna_paciente').val();

    if (!idVacunacionPaciente) {
        alert('Error: No se puede identificar la vacunación a actualizar');
        return;
    }

    const formData = {
        id_vacuna_paciente: idVacunacionPaciente,
        nombre_completo: $('#nombre_completo').val().trim(),
        fecha_vacunacion: $('#fecha_vacunacion').val(),
        tiempo_tratamiento: $('#tiempo_tratamiento').val().trim(),
        dosis: $('#dosis').val().trim(),
        descripcion: $('#descripcion').val().trim(),
        id_vacuna: parseInt($('#id_vacuna').val())
    };

    console.log('Datos de actualización a enviar:', formData);

    const btnSubmit = $('#form-vacunacion-edit button[type="submit"]');
    const textoOriginal = btnSubmit.text();
    btnSubmit.prop('disabled', true).text('Actualizando...');

    $('#loading-overlay').show();

    $.ajax({
        url: '../router.php?action=updateVacuna',
        method: 'POST',
        data: formData,
        dataType: 'json',
        success: function (response) {
            console.log('Respuesta de actualización:', response);

            if (response && response.status === 'success') {
                alert('✅ Vacunación actualizada exitosamente');

                setTimeout(() => {
                    window.location.href = 'Vacunas.php';
                }, 1000);
            } else {
                const mensaje = response && response.message ? response.message : 'Error desconocido';
                alert('❌ Error al actualizar vacunación: ' + mensaje);
            }
        },
        error: function (xhr, status, error) {
            console.error('Error al actualizar vacunación:', error, xhr.responseText);
            alert('❌ Error de conexión al actualizar la vacunación');
        },
        complete: function () {
            btnSubmit.prop('disabled', false).text(textoOriginal);
            $('#loading-overlay').hide();
        }
    });
}

function validarFormularioVacunacionEdit() {
    let isValid = true;
    let errors = [];

    const nombreCompleto = $('#nombre_completo').val().trim();
    if (!nombreCompleto) {
        errors.push('El nombre completo del paciente es requerido');
        isValid = false;
    }

    const idVacuna = $('#id_vacuna').val();
    if (!idVacuna || idVacuna === '') {
        errors.push('Debe seleccionar una vacuna');
        isValid = false;
    }

    const dosis = $('#dosis').val().trim();
    if (!dosis) {
        errors.push('La dosis es requerida');
        isValid = false;
    }

    const tiempoTratamiento = $('#tiempo_tratamiento').val().trim();
    if (!tiempoTratamiento) {
        errors.push('El tiempo de tratamiento es requerido');
        isValid = false;
    }

    const fechaVacunacion = $('#fecha_vacunacion').val();
    if (!fechaVacunacion) {
        errors.push('La fecha de vacunación es requerida');
        isValid = false;
    }

    const idVacunacionPaciente = $('#id_vacuna_paciente').val();
    if (!idVacunacionPaciente) {
        errors.push('No se puede identificar la vacunación a actualizar');
        isValid = false;
    }

    if (!isValid) {
        alert('Por favor corrija los siguientes errores:\n• ' + errors.join('\n• '));

        if (!nombreCompleto) $('#nombre_completo').focus();
        else if (!idVacuna) $('#id_vacuna').focus();
        else if (!dosis) $('#dosis').focus();
        else if (!tiempoTratamiento) $('#tiempo_tratamiento').focus();
        else if (!fechaVacunacion) $('#fecha_vacunacion').focus();
    }

    return isValid;
}

window.actualizarVacunacion = actualizarVacunacion;
window.validarFormularioVacunacionEdit = validarFormularioVacunacionEdit;

function validarId(id, accion) {
    if (!id || id === 'undefined' || id === 'null' || id === '' || isNaN(id)) {
        console.error(`ID inválido para ${accion}:`, id);
        alert(`Error: ID de vacunación no válido para ${accion}`);
        return false;
    }
    return true;
}

function escapeHtml(text) {
    if (!text) return '';
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

function filtrarTabla(searchTerm) {
    let totalVisible = 0;

    $('#vacunasTableBody tr').each(function () {
        const row = $(this);
        const rowText = row.text().toLowerCase();

        if (rowText.includes(searchTerm)) {
            row.show();
            totalVisible++;
        } else {
            row.hide();
        }
    });

    if (searchTerm && totalVisible === 0) {
        if ($('#vacunasTableBody').find('.no-results').length === 0) {
            $('#vacunasTableBody').append('<tr class="no-results"><td colspan="6" class="text-center text-muted">No se encontraron resultados para la búsqueda</td></tr>');
        }
    } else {
        $('.no-results').remove();
    }
}

function getEstadoBadge(vacunacion) {
    if (vacunacion.id_estado == 2) {
        return '<span class="badge bg-secondary">Inactiva</span>';
    }
    return '<span class="badge bg-success">Aplicada</span>';
}

function formatearFecha(fecha) {
    if (!fecha) return 'No especificada';

    try {
        let date;
        if (fecha.includes('T')) {
            date = new Date(fecha);
        } else {
            date = new Date(fecha + 'T00:00:00');
        }

        if (isNaN(date.getTime())) {
            console.warn('Fecha inválida:', fecha);
            return 'Fecha inválida';
        }

        return date.toLocaleDateString('es-ES', {
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        });
    } catch (error) {
        console.error('Error al formatear fecha:', error, fecha);
        return 'Error en fecha';
    }
}

window.verDetalles = verDetalles;
window.editarVacunacion = editarVacunacion;
window.confirmarEliminacion = confirmarEliminacion;
window.confirmarDeshabilitacion = confirmarDeshabilitacion;
window.buscarPaciente = buscarPaciente;
window.limpiarFormulario = function () {
    $('#form-vacunacion')[0].reset();
    $('#nombre_completo').val('');
    $('#cedula').removeData('paciente-id');
    const today = new Date().toISOString().split('T')[0];
    $('#fecha_vacunacion').val(today);
    $('#cedula').focus();
};
