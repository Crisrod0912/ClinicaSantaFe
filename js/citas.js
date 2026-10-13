let editingCitaId = null;

if (typeof $ === 'undefined') {
    console.error('jQuery no está cargado');
}

document.addEventListener("DOMContentLoaded", function () {
    setTimeout(function () {
        if (typeof $ !== 'undefined') {
            initializePage();
            setupEventListeners();
        } else {
            console.error('jQuery no disponible, reintentando...');
            setTimeout(function () {
                if (typeof $ !== 'undefined') {
                    initializePage();
                    setupEventListeners();
                }
            }, 500);
        }
    }, 100);
});

function initializePage() {
    console.log('Inicializando página...');
    const currentPage = window.location.pathname;
    const fileName = currentPage.split('/').pop();

    if (fileName === 'RegistrarCita.html') {
        console.log('Detectado formulario de registro');
        loadFormData();
        setupDateRestrictions();
        initializeCalendar();
    }

    else if (fileName === 'ActualizarCita.html') {
        console.log('Detectado formulario de actualización');
        loadFormData();
        setupDateRestrictions();
        initializeCalendar();
        loadCitaForEditing();
    }

    else if (fileName === 'HistorialCitas.php' || document.querySelector('.custom-table') || document.getElementById('citasTable')) {
        console.log('Detectada tabla de citas');
        loadPatientAppointments();
    }
}

function loadPatientAppointments() {
    console.log('Cargando citas del paciente...');

    const url = determineRouterUrl('listMyAppointments');
    console.log('URL para cargar citas:', url);

    const tbody = $('#citasTable tbody');
    tbody.html('<tr><td colspan="7" class="text-center"><i class="fas fa-spinner fa-spin"></i> Cargando citas...</td></tr>');

    $.ajax({
        url: url,
        method: 'GET',
        dataType: 'json',
        timeout: 10000,
        success: function (response) {
            console.log('Respuesta del servidor:', response);

            if (response.status === 'success') {
                console.log('Citas cargadas:', response.data.length);
                populateAppointmentsTable(response.data);
            } else {
                console.error('Error en respuesta:', response.message);
                tbody.html('<tr><td colspan="7" class="text-center text-danger">Error al cargar las citas: ' + (response.message || 'Error desconocido') + '</td></tr>');
            }
        },
        error: function (xhr, status, error) {
            console.error('Error AJAX al cargar citas:', {
                status: status,
                error: error,
                responseText: xhr.responseText,
                readyState: xhr.readyState,
                statusText: xhr.statusText
            });

            let errorMsg = 'Error de conexión al cargar las citas';
            if (xhr.status === 404) {
                errorMsg = 'Endpoint no encontrado (404)';
            } else if (xhr.status === 500) {
                errorMsg = 'Error interno del servidor (500)';
            } else if (status === 'timeout') {
                errorMsg = 'Timeout - El servidor tardó demasiado en responder';
            }

            tbody.html('<tr><td colspan="7" class="text-center text-danger">' + errorMsg + '</td></tr>');
        }
    });
}

function setupEventListeners() {
    if (typeof $ === 'undefined') {
        console.error('jQuery no disponible para event listeners');
        return;
    }

    console.log('Configurando event listeners...');

    $(document).off('click', '.btn-register').on('click', '.btn-register', function (e) {
        if ($(this).attr('id') === 'btnActualizar') {
            handleUpdateCita(e);
        } else {
            handleCitaSubmit(e);
        }
    });

    $(document).off('click', '#btnActualizar').on('click', '#btnActualizar', handleUpdateCita);

    $(document).off('click', '#btnCancelar').on('click', '#btnCancelar', function () {
        if (confirm('¿Está seguro de que desea cancelar? Los cambios se perderán.')) {
            window.location.href = 'HistorialCitas.php';
        }
    });

    $(document).off('change', '#servicio, #especialidad, #hora, #fecha').on('change', '#servicio, #especialidad, #hora, #fecha', function () {
        setTimeout(checkAvailability, 100);
    });

    $(document).off('keyup', 'input[placeholder*="Buscar"]').on('keyup', 'input[placeholder*="Buscar"]', function () {
        searchInTable($(this).val());
    });

    setupDateRestrictions();
}

function setupDateRestrictions() {
    const today = new Date().toISOString().split('T')[0];
    $('#fecha, .date-input').each(function () {
        if ($(this).attr('type') !== 'text') {
            $(this).attr('min', today);
        }
    });
}

function initializeCalendar() {
    if (typeof window.seleccionarFecha === 'undefined') {
        window.seleccionarFecha = function (dia) {
            const fechaClick = new Date(window.añoActual || new Date().getFullYear(),
                window.mesActual || new Date().getMonth(), dia);
            const hoy = new Date();
            hoy.setHours(0, 0, 0, 0);

            if (fechaClick < hoy) {
                alert('No puedes seleccionar una fecha pasada');
                return;
            }

            const mes = String((window.mesActual || new Date().getMonth()) + 1).padStart(2, '0');
            const diaSeleccionado = String(dia).padStart(2, '0');
            const inputFecha = document.getElementById('fecha');
            if (inputFecha) {
                inputFecha.value = `${window.añoActual || new Date().getFullYear()}-${mes}-${diaSeleccionado}`;
                $(inputFecha).trigger('change');
            }
        };
    }
}

function loadFormData() {
    console.log('Cargando datos del formulario...');
    loadSpecialties();
    loadServices();
}

function loadSpecialties() {
    const url = determineRouterUrl('getSpecialties');
    console.log('Cargando especialidades desde:', url);

    $.ajax({
        url: url,
        method: 'GET',
        dataType: 'json',
        success: function (response) {
            console.log('Respuesta especialidades:', response);
            if (response.status === 'success') {
                const select = $('#especialidad');
                populateSelect(select, response.data, 'id_especialidad', 'nombre');
            }
        },
        error: function (xhr, status, error) {
            console.error('Error al cargar especialidades:', error, xhr.responseText);
        }
    });
}

function loadServices() {
    const url = determineRouterUrl('getServices');
    console.log('Cargando servicios desde:', url);

    $.ajax({
        url: url,
        method: 'GET',
        dataType: 'json',
        success: function (response) {
            console.log('Respuesta servicios:', response);
            if (response.status === 'success') {
                const select = $('#servicio');
                populateSelect(select, response.data, 'id_servicio', 'nombre');
            }
        },
        error: function (xhr, status, error) {
            console.error('Error al cargar servicios:', error, xhr.responseText);
        }
    });
}

function determineRouterUrl(action) {
    const currentPath = window.location.pathname;
    let basePath = '';

    if (currentPath.includes('/Paciente/') || currentPath.includes('/paciente/')) {
        basePath = '../router.php';
    } else {
        basePath = 'router.php';
    }

    return `${basePath}?action=${action}`;
}

function populateSelect(select, data, valueField, textField) {
    if (!select || select.length === 0) {
        console.warn('Select no encontrado para poblar');
        return;
    }

    const currentValue = select.val();
    select.empty().append('<option value="">Seleccionar...</option>');

    if (data && Array.isArray(data)) {
        data.forEach(item => {
            select.append(`<option value="${item[valueField]}">${item[textField]}</option>`);
        });
    }

    if (currentValue) {
        select.val(currentValue);
    }
}

function checkAvailability() {
    const especialidad = $('#especialidad').val();
    const fecha = $('#fecha').val();
    const hora = $('#hora').val();

    if (!especialidad || !fecha || !hora) {
        return;
    }

    const url = determineRouterUrl('getAvailableDoctors');

    $.ajax({
        url: url,
        method: 'GET',
        data: {
            id_especialidad: especialidad,
            fecha: fecha,
            hora: hora
        },
        dataType: 'json',
        success: function (response) {
            if (response.status === 'success') {
                if (response.data && response.data.length > 0) {
                    console.log('Médicos disponibles:', response.data.length);
                } else {
                    alert('No hay médicos disponibles en este horario. Por favor selecciona otro.');
                }
            }
        },
        error: function (xhr, status, error) {
            console.warn('No se pudo verificar la disponibilidad:', error);
        }
    });
}

function handleCitaSubmit(e) {
    e.preventDefault();
    console.log('Registrando nueva cita...');

    const formData = {
        id_servicio: parseInt($('#servicio').val()),
        id_especialidad: parseInt($('#especialidad').val()),
        hora: $('#hora').val(),
        fecha: $('#fecha').val(),
        id_estado: 3
    };

    console.log('Datos del formulario:', formData);

    if (!formData.id_servicio || !formData.id_especialidad || !formData.hora || !formData.fecha) {
        alert('Por favor completa todos los campos obligatorios');
        return;
    }

    const url = determineRouterUrl('createCitaPatient');
    console.log('Enviando a:', url);

    $.ajax({
        url: url,
        method: 'POST',
        data: formData,
        dataType: 'json',
        success: function (response) {
            console.log('Respuesta del servidor:', response);
            if (response.status === 'success') {
                alert('Cita registrada exitosamente');

                $('#servicio, #especialidad, #hora').val('');
                $('#fecha').val('');

                setTimeout(() => {
                    window.location.href = 'HistorialCitas.php';
                }, 1000);
            } else {
                alert(response.message || 'Error al registrar la cita');
            }
        },
        error: function (xhr, status, error) {
            console.error('Error AJAX:', error, xhr.responseText);
            alert('Error de conexión con el servidor. Revisa la consola para más detalles.');
        }
    });
}

function handleUpdateCita(e) {
    e.preventDefault();
    console.log('Actualizando cita...');

    if (!editingCitaId) {
        alert('Error: No se encontró el ID de la cita a actualizar');
        return;
    }

    const formData = {
        id_cita: editingCitaId,
        id_servicio: parseInt($('#servicio').val()),
        id_especialidad: parseInt($('#especialidad').val()),
        hora: $('#hora').val(),
        fecha: $('#fecha').val(),
        id_estado: 3
    };

    console.log('Datos de actualización:', formData);

    if (!formData.id_servicio || !formData.id_especialidad || !formData.hora || !formData.fecha) {
        alert('Por favor completa todos los campos obligatorios');
        return;
    }

    const url = determineRouterUrl('updateCita');
    console.log('Enviando actualización a:', url);

    $.ajax({
        url: url,
        method: 'POST',
        data: formData,
        dataType: 'json',
        success: function (response) {
            console.log('Respuesta del servidor:', response);
            if (response.status === 'success') {
                alert('Cita actualizada exitosamente');

                setTimeout(() => {
                    window.location.href = 'HistorialCitas.php';
                }, 1000);
            } else {
                alert(response.message || 'Error al actualizar la cita');
            }
        },
        error: function (xhr, status, error) {
            console.error('Error AJAX:', error, xhr.responseText);
            alert('Error de conexión con el servidor. Revisa la consola para más detalles.');
        }
    });
}

function loadCitaForEditing() {
    const urlParams = new URLSearchParams(window.location.search);
    const citaId = urlParams.get('id');

    if (!citaId) {
        alert('Error: No se especificó qué cita editar');
        window.location.href = 'HistorialCitas.php';
        return;
    }

    editingCitaId = citaId;
    showLoadingOverlay();

    const url = determineRouterUrl('showCita');

    $.ajax({
        url: url,
        method: 'GET',
        data: { id: citaId },
        dataType: 'json',
        success: function (response) {
            hideLoadingOverlay();
            if (response.status === 'success') {
                setTimeout(() => {
                    fillFormWithCitaData(response.data);
                }, 1000);
            } else {
                alert('Error al cargar los datos de la cita');
                window.location.href = 'HistorialCitas.php';
            }
        },
        error: function (xhr, status, error) {
            hideLoadingOverlay();
            console.error('Error al cargar cita:', error);
            alert('Error de conexión al cargar la cita');
            window.location.href = 'HistorialCitas.php';
        }
    });
}

function fillFormWithCitaData(cita) {
    console.log('Llenando formulario con:', cita);

    $('#servicio').val(cita.id_servicio);
    $('#especialidad').val(cita.id_especialidad);
    $('#hora').val(cita.hora);
    $('#fecha').val(cita.fecha);

    $('#servicio, #especialidad, #hora, #fecha').trigger('change');
}

function showLoadingOverlay() {
    $('#loadingOverlay').show();
}

function hideLoadingOverlay() {
    $('#loadingOverlay').hide();
}

function determineRouterUrl(action) {
    const currentPath = window.location.pathname;
    const currentFile = currentPath.split('/').pop();

    console.log('Ruta actual:', currentPath);
    console.log('Archivo actual:', currentFile);

    let basePath = '';

    if (currentPath.includes('/Paciente/') || currentPath.includes('/paciente/')) {
        basePath = '../router.php';
    }
    else {
        basePath = 'router.php';
    }

    const fullUrl = `${basePath}?action=${action}`;
    console.log('URL construida:', fullUrl);

    return fullUrl;
}

function populateAppointmentsTable(citas) {
    const tbody = $('#citasTable tbody');

    if (!citas || citas.length === 0) {
        tbody.html('<tr><td colspan="7" class="text-center">No tienes citas registradas</td></tr>');
        return;
    }

    let rows = '';
    citas.forEach(cita => {
        const canEdit = canEditAppointment(cita.fecha, cita.id_estado);
        const canCancel = canCancelAppointment(cita.id_estado);

        let actionsHtml = '';

        if (canEdit) {
            actionsHtml += `
                <button class="btn btn-sm me-1" style="background-color: #44C1F2; border-color: #44C1F2; color: white;" onclick="editAppointment(${cita.id_cita})" title="Editar Cita">
                    <i class="fas fa-edit"></i>
                </button>
            `;
        }

        if (canCancel) {
            actionsHtml += `
                <button class="btn btn-sm" style="background-color: #dc3545; border-color: #dc3545; color: white;" onclick="cancelAppointment(${cita.id_cita})" title="Cancelar Cita">
                    <i class="fas fa-times"></i>
                </button>
            `;
        }

        rows += `
            <tr data-cita-id="${cita.id_cita}">
                <td>${formatDate(cita.fecha)}</td>
                <td>${formatTime(cita.hora)}</td>
                <td>${cita.nombre_servicio || 'N/A'}</td>
                <td>${cita.nombre_especialidad || 'N/A'}</td>
                <td>${cita.nombre_medico || 'N/A'}</td>
                <td><span class="badge ${getStatusBadgeClass(cita.id_estado)}">${cita.nombre_estado}</span></td>
                <td>${actionsHtml}</td>
            </tr>
        `;
    });

    tbody.html(rows);
}

function canEditAppointment(fechaCita, estadoId) {
    const citaDate = new Date(fechaCita);
    const today = new Date();
    today.setHours(0, 0, 0, 0);

    return estadoId == 3;
}

function canCancelAppointment(estadoId) {
    return estadoId == 1 || estadoId == 3;
}

function editAppointment(citaId) {
    console.log('Redirigiendo a editar cita:', citaId);
    window.location.href = `ActualizarCita.html?id=${citaId}`;
}

function cancelAppointment(citaId) {
    if (!confirm('¿Estás seguro de que deseas cancelar esta cita?')) {
        return;
    }

    const url = determineRouterUrl('updateCitaStatus');

    $.ajax({
        url: url,
        method: 'POST',
        data: {
            id_cita: citaId,
            id_estado: 4
        },
        dataType: 'json',
        success: function (response) {
            if (response.status === 'success') {
                alert('Cita cancelada exitosamente');
                loadPatientAppointments();
            } else {
                alert(response.message || 'Error al cancelar la cita');
            }
        },
        error: function (xhr, status, error) {
            console.error('Error al cancelar cita:', error);
            alert('Error de conexión al cancelar la cita');
        }
    });
}

function searchInTable(searchTerm) {
    const rows = $('#citasTable tbody tr');

    if (!searchTerm) {
        rows.show();
        return;
    }

    rows.each(function () {
        const text = $(this).text().toLowerCase();
        if (text.includes(searchTerm.toLowerCase())) {
            $(this).show();
        } else {
            $(this).hide();
        }
    });
}

function formatDate(dateString) {
    if (!dateString) return 'N/A';

    const date = new Date(dateString);
    return date.toLocaleDateString('es-ES', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric'
    });
}

function formatTime(timeString) {
    if (!timeString) return 'N/A';

    const time = timeString.substring(0, 5);
    const [hour, minute] = time.split(':');
    const hourInt = parseInt(hour);

    const ampm = hourInt >= 12 ? 'PM' : 'AM';
    const hour12 = hourInt === 0 ? 12 : hourInt > 12 ? hourInt - 12 : hourInt;

    return `${hour12}:${minute} ${ampm}`;
}

function getStatusBadgeClass(estadoId) {
    switch (parseInt(estadoId)) {
        case 1: return 'bg-success';
        case 2: return 'bg-info';
        case 3: return 'bg-warning';
        case 4: return 'bg-danger';
        case 5: return 'bg-secondary';
        default: return 'bg-light text-dark';
    }
}

window.editAppointment = editAppointment;
window.cancelAppointment = cancelAppointment;
window.loadPatientAppointments = loadPatientAppointments;
