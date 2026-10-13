let editingCitaId = null;
let availableDoctors = [];

document.addEventListener("DOMContentLoaded", function () {
    function checkJquery() {
        if (typeof $ !== 'undefined' && typeof $.ajax !== 'undefined') {
            console.log('jQuery cargado correctamente');
            initializePage();
            setupEventListeners();
            return true;
        }
        return false;
    }

    if (!checkJquery()) {
        console.log('jQuery no disponible, reintentando...');

        let attempts = 0;
        const maxAttempts = 25;
        const retryInterval = setInterval(function () {
            attempts++;
            if (checkJquery()) {
                clearInterval(retryInterval);
            } else if (attempts >= maxAttempts) {
                clearInterval(retryInterval);
                console.error('No se pudo cargar jQuery después de múltiples intentos');
                showErrorMessage('Error: No se pudo cargar jQuery. Recarga la página.');
            }
        }, 200);
    }
});

function showErrorMessage(message) {
    const errorDiv = document.createElement('div');
    errorDiv.className = 'alert alert-danger alert-dismissible fade show';
    errorDiv.innerHTML = `
    ${message}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
  `;
    document.body.insertBefore(errorDiv, document.body.firstChild);
}

function initializePage() {
    console.log('Inicializando página de administrador...');
    const currentPage = window.location.pathname;
    const fileName = currentPage.split('/').pop();

    if (fileName === 'RegistrarCita.html') {
        console.log('Detectado formulario de registro admin');
        loadFormData();
        setupDateRestrictions();
        initializeCalendar();
    }

    else if (fileName === 'EditarCita.html' || fileName === 'EditarCita.html') {
        console.log('Detectado formulario de actualización admin');
        loadFormData();
        setupDateRestrictions();
        initializeCalendar();
        loadCitaForEditing();
    }

    else if (fileName === 'Citas.php' || document.querySelector('.custom-table')) {
        console.log('Detectada tabla de citas admin');
        loadAllAppointments();
    }
}

function setupEventListeners() {
    if (typeof $ === 'undefined') {
        console.error('jQuery no disponible para event listeners');
        return;
    }

    console.log('Configurando event listeners admin...');

    $(document).off('click', '.btn-register').on('click', '.btn-register', function (e) {
        e.preventDefault();
        if (editingCitaId) {
            handleUpdateCitaAdmin(e);
        } else {
            handleCreateCitaAdmin(e);
        }
    });

    $(document).off('change', '#servicio, #especialidad, #hora, #fecha').on('change', '#servicio, #especialidad, #hora, #fecha', function () {
        setTimeout(updateAvailableDoctors, 100);
    });

    $(document).off('input', '#cedula-paciente').on('input', '#cedula-paciente', function () {
        const cedula = $(this).val().trim();
        if (cedula.length >= 8) {
            searchPatientByCedula(cedula);
        } else {
            clearPatientInfo();
        }
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
    console.log('Cargando datos del formulario admin...');
    loadSpecialties();
    loadServices();
}

function loadSpecialties() {
    $.ajax({
        url: '../router.php?action=getSpecialties',
        method: 'GET',
        dataType: 'json',
        timeout: 10000,
        success: function (response) {
            if (response.status === 'success') {
                const select = $('#especialidad');
                populateSelect(select, response.data, 'id_especialidad', 'nombre');
            } else {
                console.error('Error en respuesta de especialidades:', response);
            }
        },
        error: function (xhr, status, error) {
            console.error('Error al cargar especialidades:', {
                status: status,
                error: error,
                responseText: xhr.responseText,
                statusCode: xhr.status
            });
            showErrorMessage('Error al cargar especialidades. Verifica la conexión.');
        }
    });
}

function loadServices() {
    $.ajax({
        url: '../router.php?action=getServices',
        method: 'GET',
        dataType: 'json',
        timeout: 10000,
        success: function (response) {
            if (response.status === 'success') {
                const select = $('#servicio');
                populateSelect(select, response.data, 'id_servicio', 'nombre');
            } else {
                console.error('Error en respuesta de servicios:', response);
            }
        },
        error: function (xhr, status, error) {
            console.error('Error al cargar servicios:', {
                status: status,
                error: error,
                responseText: xhr.responseText,
                statusCode: xhr.status
            });
            showErrorMessage('Error al cargar servicios. Verifica la conexión.');
        }
    });
}

function loadCitaForEditing() {
    const urlParams = new URLSearchParams(window.location.search);
    const citaId = urlParams.get('id');

    if (!citaId) {
        alert('Error: No se especificó qué cita editar');
        window.location.href = 'Citas.php';
        return;
    }

    editingCitaId = citaId;
    console.log('Cargando cita para edición:', citaId);

    $.ajax({
        url: '../router.php?action=showCita',
        method: 'GET',
        data: { id: citaId },
        dataType: 'json',
        timeout: 10000,
        success: function (response) {
            console.log('Respuesta completa del servidor:', response);
            if (response.status === 'success') {
                console.log('Datos de cita recibidos:', response.data);
                setTimeout(() => {
                    fillFormWithCitaData(response.data);
                }, 1000);
            } else {
                alert('Error al cargar los datos de la cita: ' + (response.message || ''));
                window.location.href = 'Citas.php';
            }
        },
        error: function (xhr, status, error) {
            console.error('Error al cargar cita:', {
                status: status,
                error: error,
                responseText: xhr.responseText
            });
            alert('Error de conexión al cargar la cita');
            window.location.href = 'Citas.php';
        }
    });
}

function fillFormWithCitaData(cita) {
    console.log('Llenando formulario con datos:', cita);

    if (cita.cedula_usuario) {
        $('#cedula-paciente').val(cita.cedula_usuario);
        $('#cedula-paciente').removeClass('is-invalid').addClass('is-valid');
    }

    if (cita.nombre_paciente) {
        $('#nombre-paciente').val(cita.nombre_paciente);
        $('#nombre-paciente').removeClass('is-invalid').addClass('is-valid');
    }

    if (cita.fecha) {
        $('#fecha').val(cita.fecha);
    }

    if (cita.id_servicio) {
        console.log('Seleccionando servicio:', cita.id_servicio);
        $('#servicio').val(cita.id_servicio);
    }

    if (cita.id_especialidad) {
        console.log('Seleccionando especialidad:', cita.id_especialidad);
        $('#especialidad').val(cita.id_especialidad);
    }

    if (cita.hora) {
        console.log('Hora recibida:', cita.hora, 'Tipo:', typeof cita.hora);

        let horaParaSelect = cita.hora;

        if (horaParaSelect.length === 5) {
            horaParaSelect += ':00';
        }

        console.log('Intentando seleccionar hora:', horaParaSelect);

        $('#hora option').each(function () {
            console.log('Opción disponible:', $(this).val(), '- Texto:', $(this).text());
        });

        $('#hora').val(horaParaSelect);

        if ($('#hora').val() !== horaParaSelect) {
            console.warn('No se pudo seleccionar la hora directamente, buscando por coincidencia...');

            const horaCorta = horaParaSelect.substring(0, 5);
            let horaEncontrada = false;

            $('#hora option').each(function () {
                if ($(this).val().substring(0, 5) === horaCorta) {
                    $('#hora').val($(this).val());
                    console.log('Hora encontrada por coincidencia:', $(this).val());
                    horaEncontrada = true;
                    return false;
                }
            });

            if (!horaEncontrada) {
                console.error('No se pudo encontrar la hora en las opciones disponibles');
                console.error('Hora buscada:', horaParaSelect);
                console.error('Opciones disponibles:', $('#hora option').map(function () { return this.value; }).get());
            }
        } else {
            console.log('✅ Hora seleccionada correctamente:', $('#hora').val());
        }
    }

    setTimeout(() => {
        console.log('=== VERIFICACIÓN FINAL ===');
        console.log('Cédula:', $('#cedula-paciente').val());
        console.log('Nombre:', $('#nombre-paciente').val());
        console.log('Fecha:', $('#fecha').val());
        console.log('Servicio:', $('#servicio').val());
        console.log('Especialidad:', $('#especialidad').val());
        console.log('Hora seleccionada:', $('#hora').val());
        console.log('Hora texto:', $('#hora option:selected').text());

        if (!$('#hora').val() && cita.hora) {
            console.error('❌ PROBLEMA: La hora no se seleccionó correctamente');
            console.error('Hora original:', cita.hora);
            console.error('Intentando selección manual...');

            $('#hora').val(cita.hora.length === 5 ? cita.hora + ':00' : cita.hora);
        }
    }, 100);
}

function verificarDatosCompletos(citaOriginal) {
    console.log('=== VERIFICACIÓN FINAL ===');
    console.log('Datos originales de la cita:', citaOriginal);
    console.log('Datos actuales del formulario:');
    console.log('- Cédula:', $('#cedula-paciente').val());
    console.log('- Nombre:', $('#nombre-paciente').val());
    console.log('- Fecha:', $('#fecha').val());
    console.log('- Servicio:', $('#servicio').val(), '- Texto:', $('#servicio option:selected').text());
    console.log('- Especialidad:', $('#especialidad').val(), '- Texto:', $('#especialidad option:selected').text());
    console.log('- Hora:', $('#hora').val(), '- Texto:', $('#hora option:selected').text());

    let camposFaltantes = [];

    if (!$('#cedula-paciente').val() && citaOriginal.cedula_usuario) {
        $('#cedula-paciente').val(citaOriginal.cedula_usuario);
        camposFaltantes.push('cédula');
    }

    if (!$('#nombre-paciente').val() && citaOriginal.nombre_paciente) {
        $('#nombre-paciente').val(citaOriginal.nombre_paciente);
        camposFaltantes.push('nombre');
    }

    if (!$('#fecha').val() && citaOriginal.fecha) {
        $('#fecha').val(citaOriginal.fecha);
        camposFaltantes.push('fecha');
    }

    if (!$('#servicio').val() && citaOriginal.id_servicio) {
        $('#servicio').val(citaOriginal.id_servicio);
        camposFaltantes.push('servicio');
    }

    if (!$('#especialidad').val() && citaOriginal.id_especialidad) {
        $('#especialidad').val(citaOriginal.id_especialidad);
        camposFaltantes.push('especialidad');
    }

    if (!$('#hora').val() && citaOriginal.hora) {
        let horaFormateada = citaOriginal.hora;
        if (horaFormateada.length === 5) {
            horaFormateada += ':00';
        }
        $('#hora').val(horaFormateada);
        camposFaltantes.push('hora');
    }

    if (camposFaltantes.length > 0) {
        console.warn('Campos que se tuvieron que rellenar manualmente:', camposFaltantes);
    } else {
        console.log('✅ Todos los datos se cargaron correctamente');
    }
}

function clearForm() {
    $('#cedula-paciente, #nombre-paciente, #fecha').val('');
    $('#servicio, #especialidad, #hora').val('').prop('selectedIndex', 0);
    $('#cedula-paciente, #nombre-paciente').removeClass('is-valid is-invalid');
}

function handleUpdateCitaAdmin(e) {
    e.preventDefault();
    console.log('Actualizando cita como admin...');

    if (!editingCitaId) {
        alert('Error: No se encontró el ID de la cita a actualizar');
        return;
    }

    const formData = {
        id_cita: editingCitaId,
        fecha: $('#fecha').val(),
        hora: $('#hora').val(),
        cedula_paciente: $('#cedula-paciente').val(),
        id_servicio: parseInt($('#servicio').val()),
        id_especialidad: parseInt($('#especialidad').val()),
        id_estado: 3
    };

    console.log('Datos a enviar:', formData);

    if (!formData.fecha || !formData.hora || !formData.cedula_paciente ||
        !formData.id_servicio || !formData.id_especialidad) {
        alert('Por favor completa todos los campos obligatorios');
        console.error('Campos faltantes:', {
            fecha: !formData.fecha,
            hora: !formData.hora,
            cedula: !formData.cedula_paciente,
            servicio: !formData.id_servicio,
            especialidad: !formData.id_especialidad
        });
        return;
    }

    if ($('#nombre-paciente').hasClass('is-invalid') ||
        $('#nombre-paciente').val() === 'Paciente no encontrado' ||
        $('#nombre-paciente').val() === '') {
        alert('Por favor ingresa una cédula de paciente válida');
        return;
    }

    console.log('Enviando solicitud AJAX...');

    $.ajax({
        url: '../router.php?action=updateCitaAdmin',
        method: 'POST',
        data: formData,
        dataType: 'json',
        timeout: 15000,
        beforeSend: function () {
            $('.btn-register').prop('disabled', true).text('Actualizando...');
        },
        success: function (response) {
            console.log('Respuesta exitosa del servidor:', response);
            $('.btn-register').prop('disabled', false).text('Actualizar Cita');

            if (response.status === 'success') {
                alert('Cita actualizada exitosamente');
                setTimeout(() => {
                    window.location.href = 'Citas.php';
                }, 1000);
            } else {
                alert(response.message || 'Error al actualizar la cita');
                console.error('Error del servidor:', response);
            }
        },
        error: function (xhr, status, error) {
            console.error('Error AJAX completo:', {
                status: status,
                error: error,
                responseText: xhr.responseText,
                statusCode: xhr.status,
                readyState: xhr.readyState
            });

            $('.btn-register').prop('disabled', false).text('Actualizar Cita');

            console.error('Respuesta completa del servidor:');
            console.error(xhr.responseText);

            let errorMessage = 'Error de conexión con el servidor';

            if (xhr.status === 200 && xhr.responseText.includes('<br />')) {
                errorMessage = 'Error en el servidor PHP. Revisa los logs del servidor.';

                const phpErrorMatch = xhr.responseText.match(/Fatal error[^<]*/);
                if (phpErrorMatch) {
                    console.error('Error PHP detectado:', phpErrorMatch[0]);
                    errorMessage += '\nError PHP: ' + phpErrorMatch[0];
                }

                console.error('Primeros 500 caracteres de la respuesta:');
                console.error(xhr.responseText.substring(0, 500));

            } else if (xhr.status === 404) {
                errorMessage = 'Archivo router.php no encontrado';
            } else if (xhr.status === 500) {
                errorMessage = 'Error interno del servidor (500)';
            } else if (xhr.status === 0) {
                errorMessage = 'No se pudo conectar al servidor';
            }

            alert(errorMessage);
        },
        complete: function () {
            $('.btn-register').prop('disabled', false).text('Actualizar Cita');
        }
    });
}

function debugFormDataBeforeSend() {
    console.log('=== DEBUG ANTES DEL ENVÍO ===');
    console.log('editingCitaId:', editingCitaId);
    console.log('Fecha:', $('#fecha').val());
    console.log('Hora:', $('#hora').val());
    console.log('Cédula:', $('#cedula-paciente').val());
    console.log('Nombre:', $('#nombre-paciente').val());
    console.log('Servicio:', $('#servicio').val(), '- Texto:', $('#servicio option:selected').text());
    console.log('Especialidad:', $('#especialidad').val(), '- Texto:', $('#especialidad option:selected').text());
    console.log('Estado del nombre paciente:', {
        hasInvalid: $('#nombre-paciente').hasClass('is-invalid'),
        hasValid: $('#nombre-paciente').hasClass('is-valid'),
        value: $('#nombre-paciente').val()
    });

    const routerUrl = '../router.php?action=updateCitaAdmin';
    console.log('URL del router:', routerUrl);

    console.log('Probando conectividad con el router...');
    $.ajax({
        url: '../router.php?action=ping',
        method: 'GET',
        timeout: 3000,
        success: function (response) {
            console.log('✅ Conectividad con router OK');
        },
        error: function (xhr) {
            console.error('❌ Problema de conectividad:', xhr.status, xhr.statusText);
        }
    });
}

function populateSelect(select, data, valueField, textField) {
    if (!select || select.length === 0) return;

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

function searchPatientByCedula(cedula) {
    if (!cedula || cedula.length < 8) return;

    $.ajax({
        url: '../router.php?action=searchPatient',
        method: 'GET',
        data: { cedula: cedula },
        dataType: 'json',
        timeout: 10000,
        success: function (response) {
            if (response.status === 'success' && response.data) {
                fillPatientInfo(response.data);
            } else {
                clearPatientInfo();
                if (cedula.length >= 8) {
                    showPatientNotFound();
                }
            }
        },
        error: function (xhr, status, error) {
            console.error('Error al buscar paciente:', {
                status: status,
                error: error,
                responseText: xhr.responseText
            });
            clearPatientInfo();
        }
    });
}

function fillPatientInfo(patient) {
    $('#nombre-paciente').val(patient.nombre_completo || `${patient.nombre} ${patient.apellidos}`);
    $('#nombre-paciente').removeClass('is-invalid').addClass('is-valid');
    $('#cedula-paciente').removeClass('is-invalid').addClass('is-valid');
}

function clearPatientInfo() {
    $('#nombre-paciente').val('');
    $('#nombre-paciente').removeClass('is-valid is-invalid');
    $('#cedula-paciente').removeClass('is-valid is-invalid');
}

function showPatientNotFound() {
    $('#nombre-paciente').val('Paciente no encontrado');
    $('#nombre-paciente').addClass('is-invalid');
    $('#cedula-paciente').addClass('is-invalid');
}

function updateAvailableDoctors() {
    const especialidad = $('#especialidad').val();
    const fecha = $('#fecha').val();
    const hora = $('#hora').val();

    if (!especialidad || !fecha || !hora) {
        availableDoctors = [];
        return;
    }

    $.ajax({
        url: '../router.php?action=getAvailableDoctors',
        method: 'GET',
        data: {
            id_especialidad: especialidad,
            fecha: fecha,
            hora: hora
        },
        dataType: 'json',
        timeout: 10000,
        success: function (response) {
            console.log('Respuesta de médicos disponibles:', response);
            if (response.status === 'success') {
                availableDoctors = response.data || [];
                console.log('Médicos disponibles encontrados:', availableDoctors.length);

                if (availableDoctors.length === 0) {
                    if (!editingCitaId) {
                        alert('No hay médicos disponibles en este horario. Por favor selecciona otro.');
                    } else {
                        console.warn('No hay médicos disponibles para este horario');
                    }
                } else {
                    console.log('✓ Médicos disponibles para este horario:', availableDoctors.length);
                }
            } else {
                console.warn('Error en respuesta de disponibilidad:', response.message);
                availableDoctors = [];
            }
        },
        error: function (xhr, status, error) {
            console.warn('Error al verificar disponibilidad:', {
                status: status,
                error: error,
                responseText: xhr.responseText
            });
            availableDoctors = [];

            if (!editingCitaId) {
                console.error('No se pudo verificar la disponibilidad de médicos');
            }
        }
    });
}

function handleCreateCitaAdmin(e) {
    e.preventDefault();
    console.log('Registrando nueva cita como admin...');

    const formData = {
        fecha: $('#fecha').val(),
        hora: $('#hora').val(),
        cedula_paciente: $('#cedula-paciente').val(),
        id_medico: 0,
        id_servicio: parseInt($('#servicio').val()),
        id_especialidad: parseInt($('#especialidad').val()),
        id_estado: 3
    };

    if (!formData.fecha || !formData.hora || !formData.cedula_paciente ||
        !formData.id_servicio || !formData.id_especialidad) {
        alert('Por favor completa todos los campos obligatorios');
        return;
    }

    if ($('#nombre-paciente').hasClass('is-invalid') || $('#nombre-paciente').val() === 'Paciente no encontrado') {
        alert('Por favor ingresa una cédula de paciente válida');
        return;
    }

    if (availableDoctors.length === 0) {
        alert('No hay médicos disponibles para esta fecha y hora. Por favor selecciona otro horario.');
        return;
    }

    $.ajax({
        url: '../router.php?action=createCita',
        method: 'POST',
        data: formData,
        dataType: 'json',
        timeout: 15000,
        success: function (response) {
            console.log('Respuesta del servidor:', response);
            if (response.status === 'success') {
                alert('Cita registrada exitosamente');
                clearForm();

                if (window.location.pathname.includes('Citas.php')) {
                    loadAllAppointments();
                } else {
                    setTimeout(() => {
                        window.location.href = 'Citas.php';
                    }, 1000);
                }
            } else {
                alert(response.message || 'Error al registrar la cita');
            }
        },
        error: function (xhr, status, error) {
            console.error('Error AJAX:', {
                status: status,
                error: error,
                responseText: xhr.responseText,
                statusCode: xhr.status
            });
            alert('Error de conexión con el servidor. Verifica que el servidor esté funcionando.');
        }
    });
}

function loadAllAppointments() {
    console.log('Cargando todas las citas...');

    const tbody = $('.custom-table tbody');
    if (tbody.length) {
        tbody.html('<tr><td colspan="9" class="text-center">Cargando citas...</td></tr>');
    }

    $.ajax({
        url: '../router.php?action=listCitas',
        method: 'GET',
        dataType: 'json',
        timeout: 15000,
        success: function (response) {
            console.log('Citas cargadas:', response);
            if (response.status === 'success') {
                populateAdminAppointmentsTable(response.data);
            } else {
                $('.custom-table tbody').html('<tr><td colspan="9" class="text-center">No se pudieron cargar las citas</td></tr>');
                console.error('Error en respuesta:', response);
            }
        },
        error: function (xhr, status, error) {
            console.error('Error al cargar citas:', {
                status: status,
                error: error,
                responseText: xhr.responseText,
                statusCode: xhr.status
            });

            let errorMessage = 'Error al cargar las citas';
            if (xhr.status === 404) {
                errorMessage += ' - Archivo router.php no encontrado';
            } else if (xhr.status === 500) {
                errorMessage += ' - Error del servidor';
            }

            $('.custom-table tbody').html(`<tr><td colspan="9" class="text-center text-danger">${errorMessage}</td></tr>`);
        }
    });
}

function populateAdminAppointmentsTable(citas) {
    const tbody = $('.custom-table tbody');

    if (!tbody.length) {
        $('.custom-table').append('<tbody></tbody>');
    }

    if (!citas || citas.length === 0) {
        $('.custom-table tbody').html('<tr><td colspan="9" class="text-center">No hay citas registradas</td></tr>');
        return;
    }

    let rows = '';
    citas.forEach(cita => {
        const canEdit = cita.id_estado == 1 || cita.id_estado == 3;
        const canCancel = cita.id_estado == 1 || cita.id_estado == 3;

        let actionsHtml = '';

        if (canEdit) {
            actionsHtml += `
                <button class="btn btn-sm me-1" style="background-color: #44C1F2; color: white;" onclick="editAppointmentAdmin(${cita.id_cita})" title="Editar">
                    <i class="fas fa-edit"></i>
                </button>
            `;
        }

        if (canCancel) {
            actionsHtml += `
                <button class="btn btn-sm" style="background-color: #dc3545; color: white;" onclick="cancelAppointmentAdmin(${cita.id_cita})" title="Cancelar">
                    <i class="fas fa-times"></i>
                </button>
            `;
        }

        rows += `
            <tr data-cita-id="${cita.id_cita}">
                <td>${cita.cedula_usuario || 'N/A'}</td>
                <td>${cita.nombre_paciente || 'N/A'}</td>
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

    $('.custom-table tbody').html(rows);
}

function editAppointmentAdmin(citaId) {
    console.log('Redirigiendo a editar cita admin:', citaId);
    window.location.href = `EditarCita.html?id=${citaId}`;
}

function cancelAppointmentAdmin(citaId) {
    if (!confirm('¿Estás seguro de que deseas cancelar esta cita?')) {
        return;
    }

    $.ajax({
        url: '../router.php?action=updateCitaStatus',
        method: 'POST',
        data: {
            id_cita: citaId,
            id_estado: 4
        },
        dataType: 'json',
        success: function (response) {
            if (response.status === 'success') {
                alert('Cita cancelada exitosamente');
                loadAllAppointments();
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
    const rows = $('.custom-table tbody tr');

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
    return time;
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

window.editAppointmentAdmin = editAppointmentAdmin;
window.cancelAppointmentAdmin = cancelAppointmentAdmin;
window.debugFormDataBeforeSend = debugFormDataBeforeSend;
