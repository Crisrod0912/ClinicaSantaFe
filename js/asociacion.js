let editingMedicoEspecialidadId = null;

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
    console.log('Inicializando página de médico-especialidad...');
    const currentPage = window.location.pathname;
    const fileName = currentPage.split('/').pop();

    if (fileName === 'RegistrarAsociacion.html') {
        console.log('Detectado formulario de registro de asignación');
        loadFormData();
    }

    else if (fileName === 'EditarAsociacion.html') {
        console.log('Detectado formulario de actualización de asignación');
        loadFormData();
        loadMedicoEspecialidadForEditing();
    }

    else if (fileName === 'Asociacion.php' || fileName === 'MedicoEspecialidad.php' || document.querySelector('.custom-table')) {
        console.log('Detectada tabla de asignaciones médico-especialidad');
        loadMedicoEspecialidades();
    }
}

function setupEventListeners() {
    if (typeof $ === 'undefined') {
        console.error('jQuery no disponible para event listeners');
        return;
    }

    console.log('Configurando event listeners...');

    $(document).off('submit', '#form-registro').on('submit', '#form-registro', function (e) {
        e.preventDefault();
        if (editingMedicoEspecialidadId) {
            handleMedicoEspecialidadUpdate(e);
        } else {
            handleMedicoEspecialidadSubmit(e);
        }
    });

    $(document).off('keyup', 'input[placeholder*="Buscar"]').on('keyup', 'input[placeholder*="Buscar"]', function () {
        searchInTable($(this).val());
    });
}

function loadMedicoEspecialidadForEditing() {
    const urlParams = new URLSearchParams(window.location.search);
    const medicoEspecialidadId = urlParams.get('id');

    if (!medicoEspecialidadId) {
        alert('Error: No se especificó qué asignación editar');
        window.location.href = 'Asociacion.php';
        return;
    }

    editingMedicoEspecialidadId = medicoEspecialidadId;
    showLoadingOverlay();

    const url = determineRouterUrl('showMedicoEspecialidad');

    $.ajax({
        url: url,
        method: 'GET',
        data: { id: medicoEspecialidadId },
        dataType: 'json',
        success: function (response) {
            hideLoadingOverlay();
            if (response.status === 'success') {
                setTimeout(() => {
                    fillFormWithMedicoEspecialidadData(response.data);
                }, 1000);
            } else {
                alert('Error al cargar los datos de la asignación');
                window.location.href = 'Asociacion.php';
            }
        },
        error: function (xhr, status, error) {
            hideLoadingOverlay();
            console.error('Error al cargar asignación:', error);
            alert('Error de conexión al cargar la asignación');
            window.location.href = 'Asociacion.php';
        }
    });
}

function fillFormWithMedicoEspecialidadData(asignacion) {
    console.log('Llenando formulario con:', asignacion);

    $('#medico').val(asignacion.id_medico);
    $('#especialidad').val(asignacion.id_especialidad);
    $('#estado').val(asignacion.id_estado);

    if ($('#id_medico_especialidad').length) {
        $('#id_medico_especialidad').val(asignacion.id_medico_especialidad);
    }
}

function showLoadingOverlay() {
    if ($('#loadingOverlay').length === 0) {
        $('body').append(`
            <div id="loadingOverlay" style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 9999;">
                <div class="d-flex justify-content-center align-items-center h-100">
                <div class="text-white text-center">
                    <i class="fas fa-spinner fa-spin fa-3x mb-3"></i>
                    <div>Cargando datos...</div>
                </div>
                </div>
            </div>
        `);
    }
    $('#loadingOverlay').show();
}

function hideLoadingOverlay() {
    $('#loadingOverlay').hide();
}

function loadMedicoEspecialidades() {
    console.log('Cargando asignaciones médico-especialidad...');
    const url = determineRouterUrl('listMedicoEspecialidad');

    $.ajax({
        url: url,
        method: 'GET',
        dataType: 'json',
        success: function (response) {
            console.log('Asignaciones cargadas:', response);
            if (response.status === 'success') {
                populateMedicoEspecialidadTable(response.data);
            } else {
                $('.custom-table tbody').html('<tr><td colspan="5" class="text-center">No se pudieron cargar las asignaciones</td></tr>');
            }
        },
        error: function (xhr, status, error) {
            console.error('Error al cargar asignaciones:', error, xhr.responseText);
            $('.custom-table tbody').html('<tr><td colspan="5" class="text-center">Error al cargar las asignaciones</td></tr>');
        }
    });
}

function populateMedicoEspecialidadTable(asignaciones) {
    const tbody = $('.custom-table tbody');

    if (!asignaciones || asignaciones.length === 0) {
        tbody.html('<tr><td colspan="5" class="text-center">No hay asignaciones registradas</td></tr>');
        return;
    }

    let rows = '';
    asignaciones.forEach(asignacion => {
        let actionsHtml = '';

        actionsHtml += `
                <a class="btn btn-sm me-1" style="background-color: #44C1F2; border-color: #44C1F2; color: white;" href="EditarAsociacion.html?id=${asignacion.id_medico_especialidad}" title="Editar">
                    <i class="fas fa-edit"></i>
                </a>
            `;

        if (asignacion.id_estado == 1) {
            actionsHtml += `
                <button class="btn btn-sm" style="background-color: #dc3545; border-color: #dc3545; color: white;" onclick="disableMedicoEspecialidad(${asignacion.id_medico_especialidad})" title="Deshabilitar">
                    <i class="fas fa-ban"></i>
                </button>
            `;
        } else if (asignacion.id_estado == 2) {
            actionsHtml += `
                <button class="btn btn-sm" style="background-color: #28a745; border-color: #28a745; color: white;" onclick="enableMedicoEspecialidad(${asignacion.id_medico_especialidad})" title="Habilitar" style="background-color: #28a745; border-color: #28a745;">
                    <i class="fas fa-check"></i>
                </button>
            `;
        }

        rows += `
            <tr data-asignacion-id="${asignacion.id_medico_especialidad}">
                <td>${asignacion.id_medico_especialidad}</td>
                <td>${asignacion.nombre_medico}</td>
                <td>${asignacion.nombre_especialidad}</td>
                <td><span class="badge ${getStatusBadgeClass(asignacion.id_estado)}">${asignacion.nombre_estado || 'N/A'}</span></td>
                <td>${actionsHtml}</td>
            </tr>
        `;
    });

    tbody.html(rows);
}

function disableMedicoEspecialidad(medicoEspecialidadId) {
    if (!confirm('¿Está seguro que desea deshabilitar esta asignación?')) {
        return;
    }

    const url = determineRouterUrl('updateMedicoEspecialidadStatus');

    $.ajax({
        url: url,
        method: 'POST',
        data: {
            id_medico_especialidad: medicoEspecialidadId,
            id_estado: 2
        },
        dataType: 'json',
        success: function (response) {
            if (response.status === 'success') {
                alert('Asignación deshabilitada exitosamente');
                loadMedicoEspecialidades();
            } else {
                alert(response.message || 'Error al deshabilitar la asignación');
            }
        },
        error: function (xhr, status, error) {
            console.error('Error al deshabilitar asignación:', error);
            alert('Error de conexión al deshabilitar la asignación');
        }
    });
}

function enableMedicoEspecialidad(medicoEspecialidadId) {
    if (!confirm('¿Está seguro que desea habilitar esta asignación?')) {
        return;
    }

    const url = determineRouterUrl('updateMedicoEspecialidadStatus');

    $.ajax({
        url: url,
        method: 'POST',
        data: {
            id_medico_especialidad: medicoEspecialidadId,
            id_estado: 1
        },
        dataType: 'json',
        success: function (response) {
            if (response.status === 'success') {
                alert('Asignación habilitada exitosamente');
                loadMedicoEspecialidades();
            } else {
                alert(response.message || 'Error al habilitar la asignación');
            }
        },
        error: function (xhr, status, error) {
            console.error('Error al habilitar asignación:', error);
            alert('Error de conexión al habilitar la asignación');
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

function getStatusBadgeClass(estadoId) {
    switch (parseInt(estadoId)) {
        case 1: return 'bg-success';
        case 2: return 'bg-danger';
        default: return 'bg-light text-dark';
    }
}

function deshabilitarMedicoEspecialidad() {
    console.log('Función deshabilitarMedicoEspecialidad llamada desde modal');
}

window.disableMedicoEspecialidad = disableMedicoEspecialidad;
window.enableMedicoEspecialidad = enableMedicoEspecialidad;
window.deshabilitarMedicoEspecialidad = deshabilitarMedicoEspecialidad;
function loadFormData() {
    console.log('Cargando datos del formulario...');
    loadMedicos();
    loadEspecialidades();
    loadStates();
}

function loadMedicos() {
    const url = determineRouterUrl('getMedicosME');
    console.log('Cargando médicos desde:', url);

    $.ajax({
        url: url,
        method: 'GET',
        dataType: 'json',
        success: function (response) {
            console.log('Respuesta médicos:', response);
            if (response.status === 'success') {
                const select = $('#medico');
                populateSelect(select, response.data, 'id_usuario', 'nombre_completo');
            }
        },
        error: function (xhr, status, error) {
            console.error('Error al cargar médicos:', error, xhr.responseText);
        }
    });
}

function loadEspecialidades() {
    const url = determineRouterUrl('getEspecialidadesME');
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

function loadStates() {
    const url = determineRouterUrl('getStatesME');
    console.log('Cargando estados desde:', url);

    $.ajax({
        url: url,
        method: 'GET',
        dataType: 'json',
        success: function (response) {
            console.log('Respuesta estados:', response);
            if (response.status === 'success') {
                const select = $('#estado');
                populateSelect(select, response.data, 'id_estado', 'nombre');
            }
        },
        error: function (xhr, status, error) {
            console.error('Error al cargar estados:', error, xhr.responseText);
        }
    });
}

function determineRouterUrl(action) {
    const currentPath = window.location.pathname;
    let basePath = '';

    if (currentPath.includes('/Administrativo/') || currentPath.includes('/administrativo/')) {
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
    select.empty().append('<option value="">-- Selecciona --</option>');

    if (data && Array.isArray(data)) {
        data.forEach(item => {
            select.append(`<option value="${item[valueField]}">${item[textField]}</option>`);
        });
    }

    if (currentValue) {
        select.val(currentValue);
    }
}

function handleMedicoEspecialidadSubmit(e) {
    e.preventDefault();
    console.log('Registrando nueva asignación médico-especialidad...');

    const formData = {
        id_medico: parseInt($('#medico').val()),
        id_especialidad: parseInt($('#especialidad').val()),
        id_estado: parseInt($('#estado').val())
    };

    console.log('Datos del formulario:', formData);

    if (!formData.id_medico || !formData.id_especialidad || !formData.id_estado) {
        alert('Por favor completa todos los campos obligatorios');
        return;
    }

    const url = determineRouterUrl('createMedicoEspecialidad');
    console.log('Enviando a:', url);

    $.ajax({
        url: url,
        method: 'POST',
        data: formData,
        dataType: 'json',
        success: function (response) {
            console.log('Respuesta del servidor:', response);
            if (response.status === 'success') {
                alert('Especialidad asignada exitosamente');

                $('#medico, #especialidad, #estado').val('');

                setTimeout(() => {
                    window.location.href = 'Asociacion.php';
                }, 1000);
            } else {
                alert(response.message || 'Error al asignar la especialidad');
            }
        },
        error: function (xhr, status, error) {
            console.error('Error AJAX:', error, xhr.responseText);
            alert('Error de conexión con el servidor. Revisa la consola para más detalles.');
        }
    });
}

function handleMedicoEspecialidadUpdate(e) {
    e.preventDefault();
    console.log('Actualizando asignación médico-especialidad...');

    if (!editingMedicoEspecialidadId) {
        alert('Error: No se encontró el ID de la asignación a actualizar');
        return;
    }

    const formData = {
        id_medico_especialidad: editingMedicoEspecialidadId,
        id_medico: parseInt($('#medico').val()),
        id_especialidad: parseInt($('#especialidad').val()),
        id_estado: parseInt($('#estado').val())
    };

    console.log('Datos de actualización:', formData);

    if (!formData.id_medico || !formData.id_especialidad || !formData.id_estado) {
        alert('Por favor completa todos los campos obligatorios');
        return;
    }

    const url = determineRouterUrl('updateMedicoEspecialidad');
    console.log('Enviando actualización a:', url);

    $.ajax({
        url: url,
        method: 'POST',
        data: formData,
        dataType: 'json',
        success: function (response) {
            console.log('Respuesta del servidor:', response);
            if (response.status === 'success') {
                alert('Asignación actualizada exitosamente');

                setTimeout(() => {
                    window.location.href = 'Asociacion.php';
                }, 1000);
            } else {
                alert(response.message || 'Error al actualizar la asignación');
            }
        },
        error: function (xhr, status, error) {
            console.error('Error AJAX:', error, xhr.responseText);
            alert('Error de conexión con el servidor. Revisa la consola para más detalles.');
        }
    });
}
