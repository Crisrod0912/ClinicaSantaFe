let editingVacunaId = null;

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
    console.log('Inicializando página de vacunas admin...');
    const currentPage = window.location.pathname;
    const fileName = currentPage.split('/').pop();

    if (fileName === 'RegistrarVacunas.html') {
        console.log('Detectado formulario de registro de vacuna');
        loadFormData();
    }

    else if (fileName === 'EditarVacuna.html') {
        console.log('Detectado formulario de actualización de vacuna');
        loadFormData();
        loadVacunaForEditing();
    }

    else if (fileName === 'Vacunas.php' || document.querySelector('.custom-table')) {
        console.log('Detectada tabla de vacunas');
        loadVacunas();
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
        if (editingVacunaId) {
            handleVacunaUpdate(e);
        } else {
            handleVacunaSubmit(e);
        }
    });

    $(document).off('keyup', '#buscarVacuna').on('keyup', '#buscarVacuna', function () {
        searchInTable($(this).val());
    });
}

function loadVacunaForEditing() {
    const urlParams = new URLSearchParams(window.location.search);
    const vacunaId = urlParams.get('id');

    if (!vacunaId) {
        alert('Error: No se especificó qué vacuna editar');
        window.location.href = 'Vacunas.php';
        return;
    }

    editingVacunaId = vacunaId;
    showLoadingOverlay();

    const url = determineRouterUrl('showVacunaCatalogo');

    $.ajax({
        url: url,
        method: 'GET',
        data: { id: vacunaId },
        dataType: 'json',
        success: function (response) {
            hideLoadingOverlay();
            if (response.status === 'success') {
                setTimeout(() => {
                    fillFormWithVacunaData(response.data);
                }, 1500);
            } else {
                alert('Error al cargar los datos de la vacuna: ' + (response.message || ''));
                window.location.href = 'Vacunas.php';
            }
        },
        error: function (xhr, status, error) {
            hideLoadingOverlay();
            console.error('Error al cargar vacuna:', error);
            alert('Error de conexión al cargar la vacuna');
            window.location.href = 'Vacunas.php';
        }
    });
}

function fillFormWithVacunaData(vacuna) {
    console.log('Llenando formulario con:', vacuna);

    $('#nombre').val(vacuna.nombre || '');
    $('#enfermedad').val(vacuna.id_enfermedad || '');
    $('#esquema').val(vacuna.id_esquema_vacunacion || '');
    $('#via-administracion').val(vacuna.id_via_administracion || '');
    $('#estado').val(vacuna.id_estado || '');
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

function loadVacunas() {
    console.log('Cargando vacunas...');
    const url = determineRouterUrl('listVacunasCatalogo');

    $.ajax({
        url: url,
        method: 'GET',
        dataType: 'json',
        success: function (response) {
            console.log('Vacunas cargadas:', response);
            if (response.status === 'success') {
                populateVacunasTable(response.data);
            } else {
                $('.custom-table tbody').html('<tr><td colspan="6" class="text-center">No se pudieron cargar las vacunas</td></tr>');
            }
        },
        error: function (xhr, status, error) {
            console.error('Error al cargar vacunas:', error, xhr.responseText);
            $('.custom-table tbody').html('<tr><td colspan="6" class="text-center">Error al cargar las vacunas</td></tr>');
        }
    });
}

function populateVacunasTable(vacunas) {
    const tbody = $('.custom-table tbody');

    if (!vacunas || vacunas.length === 0) {
        tbody.html('<tr><td colspan="6" class="text-center">No hay vacunas registradas</td></tr>');
        return;
    }

    let rows = '';
    vacunas.forEach(vacuna => {
        let actionsHtml = '';

        actionsHtml += `
                <a class="btn btn-sm me-1" style="background-color: #44C1F2; border-color: #44C1F2; color: white;" href="EditarVacuna.html?id=${vacuna.id_vacuna}" title="Editar">
                    <i class="fas fa-edit"></i> 
                </a>
            `;

        if (vacuna.id_estado == 1) {
            actionsHtml += `
                <button class="btn btn-sm" style="background-color: #dc3545; border-color: #dc3545; color: white;" onclick="disableVacuna(${vacuna.id_vacuna})" title="Deshabilitar" data-bs-toggle="modal" data-bs-target="#modalConfirmacion">
                    <i class="fas fa-ban"></i> 
                </button>
            `;
        } else if (vacuna.id_estado == 2) {
            actionsHtml += `
                <button class="btn btn-sm" style="background-color: #28a745; border-color: #28a745; color: white;" onclick="enableVacuna(${vacuna.id_vacuna})" title="Habilitar">
                    <i class="fas fa-check"></i> 
                </button>
            `;
        }

        rows += `
            <tr data-vacuna-id="${vacuna.id_vacuna}">
                <td>${vacuna.nombre}</td>
                <td>${vacuna.enfermedad || 'N/A'}</td>
                <td>${vacuna.esquema_vacunacion || 'N/A'}</td>
                <td>${vacuna.via_administracion || 'N/A'}</td>
                <td><span class="badge ${getStatusBadgeClass(vacuna.id_estado)}">${vacuna.estado || 'N/A'}</span></td>
                <td>${actionsHtml}</td>
            </tr>
        `;
    });

    tbody.html(rows);
}

function disableVacuna(vacunaId) {
    window.currentVacunaId = vacunaId;
}

function deshabilitarVacuna() {
    const vacunaId = window.currentVacunaId;

    if (!vacunaId) {
        alert('Error: No se encontró la vacuna a deshabilitar');
        return;
    }

    const url = determineRouterUrl('updateVacunaStatus');

    $.ajax({
        url: url,
        method: 'POST',
        data: {
            id: vacunaId,
            id_estado: 2
        },
        dataType: 'json',
        success: function (response) {
            if (response.status === 'success') {
                alert('Vacuna deshabilitada exitosamente');
                $('#modalConfirmacion').modal('hide');
                loadVacunas();
            } else {
                alert(response.message || 'Error al deshabilitar la vacuna');
            }
        },
        error: function (xhr, status, error) {
            console.error('Error al deshabilitar vacuna:', error, xhr.responseText);
            alert('Error de conexión al deshabilitar la vacuna');
        }
    });
}

function enableVacuna(vacunaId) {
    if (!confirm('¿Está seguro que desea habilitar esta vacuna?')) {
        return;
    }

    const url = determineRouterUrl('updateVacunaStatus');

    $.ajax({
        url: url,
        method: 'POST',
        data: {
            id: vacunaId,
            id_estado: 1
        },
        dataType: 'json',
        success: function (response) {
            if (response.status === 'success') {
                alert('Vacuna habilitada exitosamente');
                loadVacunas();
            } else {
                alert(response.message || 'Error al habilitar la vacuna');
            }
        },
        error: function (xhr, status, error) {
            console.error('Error al habilitar vacuna:', error, xhr.responseText);
            alert('Error de conexión al habilitar la vacuna');
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

window.disableVacuna = disableVacuna;
window.enableVacuna = enableVacuna;
window.deshabilitarVacuna = deshabilitarVacuna;

function loadFormData() {
    console.log('Cargando datos del formulario...');

    let loadedCount = 0;
    const totalToLoad = 4;

    function checkIfAllLoaded() {
        loadedCount++;
        console.log(`Cargados ${loadedCount} de ${totalToLoad} elementos`);
    }

    loadEnfermedades(checkIfAllLoaded);
    loadEsquemasVacunacion(checkIfAllLoaded);
    loadViasAdministracionVacunas(checkIfAllLoaded);
    loadStates(checkIfAllLoaded);
}

function loadEnfermedades(callback) {
    const url = determineRouterUrl('getEnfermedades');
    console.log('Cargando enfermedades desde:', url);

    $.ajax({
        url: url,
        method: 'GET',
        dataType: 'json',
        success: function (response) {
            console.log('Respuesta enfermedades:', response);
            if (response.status === 'success') {
                const select = $('#enfermedad');
                if (select.length > 0) {
                    populateSelect(select, response.data, 'id_enfermedad', 'nombre');
                }
            } else {
                console.error('Error al cargar enfermedades:', response.message);
            }
            if (callback) callback();
        },
        error: function (xhr, status, error) {
            console.error('Error al cargar enfermedades:', error, xhr.responseText);
            if (callback) callback();
        }
    });
}

function loadEsquemasVacunacion(callback) {
    const url = determineRouterUrl('getEsquemasVacunacion');
    console.log('Cargando esquemas de vacunación desde:', url);

    $.ajax({
        url: url,
        method: 'GET',
        dataType: 'json',
        success: function (response) {
            console.log('Respuesta esquemas vacunación:', response);
            if (response.status === 'success') {
                const select = $('#esquema');
                if (select.length > 0) {
                    populateSelect(select, response.data, 'id_esquema', 'nombre');
                }
            } else {
                console.error('Error al cargar esquemas de vacunación:', response.message);
            }
            if (callback) callback();
        },
        error: function (xhr, status, error) {
            console.error('Error al cargar esquemas de vacunación:', error, xhr.responseText);
            if (callback) callback();
        }
    });
}

function loadViasAdministracionVacunas(callback) {
    const url = determineRouterUrl('getViasAdministracion');
    console.log('Cargando vías de administración desde:', url);

    $.ajax({
        url: url,
        method: 'GET',
        dataType: 'json',
        success: function (response) {
            console.log('Respuesta vías de administración:', response);
            if (response.status === 'success') {
                const select = $('#via-administracion');
                if (select.length > 0) {
                    populateSelect(select, response.data, 'id_via_administracion', 'nombre');
                }
            } else {
                console.error('Error al cargar vías de administración:', response.message);
            }
            if (callback) callback();
        },
        error: function (xhr, status, error) {
            console.error('Error al cargar vías de administración:', error, xhr.responseText);
            if (callback) callback();
        }
    });
}

function loadStates(callback) {
    const url = determineRouterUrl('getStatesVacuna');
    console.log('Cargando estados desde:', url);

    $.ajax({
        url: url,
        method: 'GET',
        dataType: 'json',
        success: function (response) {
            console.log('Respuesta estados:', response);
            if (response.status === 'success') {
                const select = $('#estado');
                if (select.length > 0) {
                    populateSelect(select, response.data, 'id_estado', 'nombre');
                }
            } else {
                console.error('Error al cargar estados:', response.message);
            }
            if (callback) callback();
        },
        error: function (xhr, status, error) {
            console.error('Error al cargar estados:', error, xhr.responseText);
            if (callback) callback();
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

function handleVacunaSubmit(e) {
    e.preventDefault();
    console.log('Registrando nueva vacuna...');

    const formData = {
        nombre: $('#nombre').val().trim(),
        id_enfermedad: parseInt($('#enfermedad').val()) || 0,
        id_esquema_vacunacion: parseInt($('#esquema').val()) || 0,
        id_via_administracion: parseInt($('#via-administracion').val()) || 0,
        id_estado: parseInt($('#estado').val()) || 1
    };

    console.log('Datos del formulario:', formData);

    if (!formData.nombre || !formData.id_enfermedad || !formData.id_esquema_vacunacion || !formData.id_via_administracion) {
        alert('Por favor completa todos los campos obligatorios');
        return;
    }

    const url = determineRouterUrl('createVacunaCatalogo');
    console.log('Enviando a:', url);

    $.ajax({
        url: url,
        method: 'POST',
        data: formData,
        dataType: 'json',
        success: function (response) {
            console.log('Respuesta del servidor:', response);
            if (response.status === 'success') {
                alert('Vacuna registrada exitosamente');

                $('#form-registro')[0].reset();
                $('#enfermedad, #esquema, #via-administracion, #estado').val('');

                setTimeout(() => {
                    window.location.href = 'Vacunas.php';
                }, 1000);
            } else {
                alert(response.message || 'Error al registrar la vacuna');
            }
        },
        error: function (xhr, status, error) {
            console.error('Error AJAX:', error, xhr.responseText);
            alert('Error de conexión con el servidor. Revisa la consola para más detalles.');
        }
    });
}

function handleVacunaUpdate(e) {
    e.preventDefault();
    console.log('Actualizando vacuna...');

    if (!editingVacunaId) {
        alert('Error: No se encontró el ID de la vacuna a actualizar');
        return;
    }

    const formData = {
        id: editingVacunaId,
        nombre: $('#nombre').val().trim(),
        id_enfermedad: parseInt($('#enfermedad').val()) || 0,
        id_esquema_vacunacion: parseInt($('#esquema').val()) || 0,
        id_via_administracion: parseInt($('#via-administracion').val()) || 0,
        id_estado: parseInt($('#estado').val()) || 1
    };

    console.log('Datos de actualización:', formData);

    if (!formData.nombre || !formData.id_enfermedad || !formData.id_esquema_vacunacion || !formData.id_via_administracion) {
        alert('Por favor completa todos los campos obligatorios');
        return;
    }

    const url = determineRouterUrl('updateVacunaCatalogo');
    console.log('Enviando actualización a:', url);

    $.ajax({
        url: url,
        method: 'POST',
        data: formData,
        dataType: 'json',
        success: function (response) {
            console.log('Respuesta del servidor:', response);
            if (response.status === 'success') {
                alert('Vacuna actualizada exitosamente');

                setTimeout(() => {
                    window.location.href = 'Vacunas.php';
                }, 1000);
            } else {
                alert(response.message || 'Error al actualizar la vacuna');
            }
        },
        error: function (xhr, status, error) {
            console.error('Error AJAX:', error, xhr.responseText);
            alert('Error de conexión con el servidor. Revisa la consola para más detalles.');
        }
    });
}
