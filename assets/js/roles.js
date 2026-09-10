let editingRolId = null;

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
    console.log('Inicializando página de roles...');
    const currentPage = window.location.pathname;
    const fileName = currentPage.split('/').pop();

    if (fileName === 'RegistrarRol.html') {
        console.log('Detectado formulario de registro de rol');
        loadFormData();
    }

    else if (fileName === 'EditarRol.html') {
        console.log('Detectado formulario de actualización de rol');
        loadFormData();
        loadRolForEditing();
    }

    else if (fileName === 'Roles.php' || document.querySelector('.custom-table') || document.getElementById('rolesTable')) {
        console.log('Detectada tabla de roles');
        loadRoles();
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
        if (editingRolId) {
            handleRolUpdate(e);
        } else {
            handleRolSubmit(e);
        }
    });

    $(document).off('keyup', 'input[placeholder*="Buscar"]').on('keyup', 'input[placeholder*="Buscar"]', function () {
        searchInTable($(this).val());
    });
}

function loadFormData() {
    console.log('Cargando datos del formulario...');
    loadStates();
}

function loadStates() {
    const url = determineRouterUrl('getStatesRol');
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

function handleRolSubmit(e) {
    e.preventDefault();
    console.log('Registrando nuevo rol...');

    const formData = {
        nombre: $('#nombre').val().trim(),
        descripcion: $('#descripcion').val().trim(),
        id_estado: parseInt($('#estado').val())
    };

    console.log('Datos del formulario:', formData);

    if (!formData.nombre || !formData.descripcion || !formData.id_estado) {
        alert('Por favor completa todos los campos obligatorios');
        return;
    }

    const url = determineRouterUrl('createRol');
    console.log('Enviando a:', url);

    $.ajax({
        url: url,
        method: 'POST',
        data: formData,
        dataType: 'json',
        success: function (response) {
            console.log('Respuesta del servidor:', response);
            if (response.status === 'success') {
                alert('Rol registrado exitosamente');

                $('#nombre, #descripcion').val('');
                $('#estado').val('');

                setTimeout(() => {
                    window.location.href = 'Roles.php';
                }, 1000);
            } else {
                alert(response.message || 'Error al registrar el rol');
            }
        },
        error: function (xhr, status, error) {
            console.error('Error AJAX:', error, xhr.responseText);
            alert('Error de conexión con el servidor. Revisa la consola para más detalles.');
        }
    });
}

function handleRolUpdate(e) {
    e.preventDefault();
    console.log('Actualizando rol...');

    if (!editingRolId) {
        alert('Error: No se encontró el ID del rol a actualizar');
        return;
    }
    
    const formData = {
        id_rol: editingRolId,
        nombre: $('#nombre').val().trim(),
        descripcion: $('#descripcion').val().trim(),
        id_estado: parseInt($('#estado').val())
    };

    console.log('Datos de actualización:', formData);

    if (!formData.nombre || !formData.descripcion || !formData.id_estado) {
        alert('Por favor completa todos los campos obligatorios');
        return;
    }

    const url = determineRouterUrl('updateRol');
    console.log('Enviando actualización a:', url);

    $.ajax({
        url: url,
        method: 'POST',
        data: formData,
        dataType: 'json',
        success: function (response) {
            console.log('Respuesta del servidor:', response);
            if (response.status === 'success') {
                alert('Rol actualizado exitosamente');

                setTimeout(() => {
                    window.location.href = 'Roles.php';
                }, 1000);
            } else {
                alert(response.message || 'Error al actualizar el rol');
            }
        },
        error: function (xhr, status, error) {
            console.error('Error AJAX:', error, xhr.responseText);
            alert('Error de conexión con el servidor. Revisa la consola para más detalles.');
        }
    });
}

function loadRolForEditing() {
    const urlParams = new URLSearchParams(window.location.search);
    const rolId = urlParams.get('id');

    if (!rolId) {
        alert('Error: No se especificó qué rol editar');
        window.location.href = 'Roles.php';
        return;
    }

    editingRolId = rolId;
    showLoadingOverlay();

    const url = determineRouterUrl('showRol');

    $.ajax({
        url: url,
        method: 'GET',
        data: { id: rolId },
        dataType: 'json',
        success: function (response) {
            hideLoadingOverlay();
            if (response.status === 'success') {
                setTimeout(() => {
                    fillFormWithRolData(response.data);
                }, 1000);
            } else {
                alert('Error al cargar los datos del rol');
                window.location.href = 'Roles.php';
            }
        },
        error: function (xhr, status, error) {
            hideLoadingOverlay();
            console.error('Error al cargar rol:', error);
            alert('Error de conexión al cargar el rol');
            window.location.href = 'Roles.php';
        }
    });
}

function fillFormWithRolData(rol) {
    console.log('Llenando formulario con:', rol);

    $('#nombre').val(rol.nombre);
    $('#descripcion').val(rol.descripcion);
    $('#estado').val(rol.id_estado);
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

function loadRoles() {
    console.log('Cargando roles...');
    const url = determineRouterUrl('listRoles');

    $.ajax({
        url: url,
        method: 'GET',
        dataType: 'json',
        success: function (response) {
            console.log('Roles cargados:', response);
            if (response.status === 'success') {
                populateRolesTable(response.data);
            } else {
                $('.custom-table tbody').html('<tr><td colspan="4" class="text-center">No se pudieron cargar los roles</td></tr>');
            }
        },
        error: function (xhr, status, error) {
            console.error('Error al cargar roles:', error, xhr.responseText);
            $('.custom-table tbody').html('<tr><td colspan="4" class="text-center">Error al cargar los roles</td></tr>');
        }
    });
}

function populateRolesTable(roles) {
    const tbody = $('.custom-table tbody');

    if (!roles || roles.length === 0) {
        tbody.html('<tr><td colspan="4" class="text-center">No hay roles registrados</td></tr>');
        return;
    }

    let rows = '';
    roles.forEach(rol => {
        let actionsHtml = '';

        actionsHtml += `
            <a class="btn btn-sm me-1" style="background-color: #44C1F2; border-color: #44C1F2; color: white;" href="EditarRol.html?id=${rol.id_rol}" title="Editar Rol">
                <i class="fas fa-edit"></i>
            </a>
        `;

        if (rol.id_estado == 1) {
            actionsHtml += `
                <button class="btn btn-sm" style="background-color: #dc3545; border-color: #dc3545; color: white;" onclick="disableRole(${rol.id_rol})" title="Deshabilitar Rol">
                    <i class="fas fa-ban"></i>
                </button>
            `;
        } else if (rol.id_estado == 2) {
            actionsHtml += `
                <button class="btn btn-sm" style="background-color: #28a745; border-color: #28a745; color: white;" onclick="enableRole(${rol.id_rol})" title="Habilitar Rol">
                    <i class="fas fa-check"></i>
                </button>
            `;
        }

        rows += `
            <tr data-rol-id="${rol.id_rol}">
                <td>${rol.nombre}</td>
                <td>${rol.descripcion}</td>
                <td><span class="badge ${getStatusBadgeClass(rol.id_estado)}">${rol.nombre_estado || 'N/A'}</span></td>
                <td>${actionsHtml}</td>
            </tr>
        `;
    });

    tbody.html(rows);
}

function disableRole(rolId) {
    if (!confirm('¿Está seguro que desea deshabilitar este rol?')) {
        return;
    }

    const url = determineRouterUrl('updateRolStatus');

    $.ajax({
        url: url,
        method: 'POST',
        data: {
            id_rol: rolId,
            id_estado: 2
        },
        dataType: 'json',
        success: function (response) {
            if (response.status === 'success') {
                alert('Rol deshabilitado exitosamente');
                loadRoles();
            } else {
                alert(response.message || 'Error al deshabilitar el rol');
            }
        },
        error: function (xhr, status, error) {
            console.error('Error al deshabilitar rol:', error);
            alert('Error de conexión al deshabilitar el rol');
        }
    });
}

function enableRole(rolId) {
    if (!confirm('¿Está seguro que desea habilitar este rol?')) {
        return;
    }

    const url = determineRouterUrl('updateRolStatus');

    $.ajax({
        url: url,
        method: 'POST',
        data: {
            id_rol: rolId,
            id_estado: 1
        },
        dataType: 'json',
        success: function (response) {
            if (response.status === 'success') {
                alert('Rol habilitado exitosamente');
                loadRoles();
            } else {
                alert(response.message || 'Error al habilitar el rol');
            }
        },
        error: function (xhr, status, error) {
            console.error('Error al habilitar rol:', error);
            alert('Error de conexión al habilitar el rol');
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

window.disableRole = disableRole;
window.enableRole = enableRole;
