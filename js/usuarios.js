let editingUserId = null;

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
    console.log('Inicializando página de usuarios...');
    const currentPage = window.location.pathname;
    const fileName = currentPage.split('/').pop();

    if (fileName === 'RegistrarUsuario.html') {
        console.log('Detectado formulario de registro de usuario');
        loadFormData();
    }

    else if (fileName === 'EditarUsuario.html') {
        console.log('Detectado formulario de actualización de usuario');
        loadFormData();
        loadUserForEditing();
    }

    else if (fileName === 'Usuarios.php' || document.querySelector('.custom-table') || document.getElementById('usuariosTable')) {
        console.log('Detectada tabla de usuarios');
        loadUsers();
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
        if (editingUserId) {
            handleUserUpdate(e);
        } else {
            handleUserSubmit(e);
        }
    });

    $(document).off('keyup', 'input[placeholder*="Buscar"]').on('keyup', 'input[placeholder*="Buscar"]', function () {
        searchInTable($(this).val());
    });
}

function loadFormData() {
    console.log('Cargando datos del formulario...');
    loadStates();
    loadRoles();
    loadGeneros();
    loadEstadosCiviles();
}

function loadStates() {
    const url = determineRouterUrl('getStatesUsuario');
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

function loadRoles() {
    const url = determineRouterUrl('getRolesUsuario');
    console.log('Cargando roles desde:', url);

    $.ajax({
        url: url,
        method: 'GET',
        dataType: 'json',
        success: function (response) {
            console.log('Respuesta roles:', response);
            if (response.status === 'success') {
                const select = $('#rol');
                populateSelect(select, response.data, 'id_rol', 'nombre');
            }
        },
        error: function (xhr, status, error) {
            console.error('Error al cargar roles:', error, xhr.responseText);
        }
    });
}

function loadGeneros() {
    const url = determineRouterUrl('getGenerosUsuario');
    console.log('Cargando géneros desde:', url);

    $.ajax({
        url: url,
        method: 'GET',
        dataType: 'json',
        success: function (response) {
            console.log('Respuesta géneros:', response);
            if (response.status === 'success') {
                const select = $('#genero');
                populateSelect(select, response.data, 'id_genero', 'nombre');
            }
        },
        error: function (xhr, status, error) {
            console.error('Error al cargar géneros:', error, xhr.responseText);
        }
    });
}

function loadEstadosCiviles() {
    const url = determineRouterUrl('getEstadosCivilesUsuario');
    console.log('Cargando estados civiles desde:', url);

    $.ajax({
        url: url,
        method: 'GET',
        dataType: 'json',
        success: function (response) {
            console.log('Respuesta estados civiles:', response);
            if (response.status === 'success') {
                const select = $('#estado-civil');
                populateSelect(select, response.data, 'id_estado_civil', 'nombre');
            }
        },
        error: function (xhr, status, error) {
            console.error('Error al cargar estados civiles:', error, xhr.responseText);
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

function handleUserSubmit(e) {
    e.preventDefault();
    console.log('Registrando nuevo usuario...');

    const formData = {
        cedula: $('#cedula').val().trim(),
        nombre: $('#nombre').val().trim(),
        apellidos: $('#apellidos').val().trim(),
        correo: $('#email').val().trim(),
        telefono: $('#telefono').val().trim(),
        fecha_nacimiento: $('#fecha-nacimiento').val() || null,
        direccion: $('#direccion').val().trim(),
        password: $('#password').val(),
        id_genero: parseInt($('#genero').val()) || null,
        id_estado_civil: parseInt($('#estado-civil').val()) || null,
        id_rol: parseInt($('#rol').val()),
        id_estado: parseInt($('#estado').val())
    };

    console.log('Datos del formulario:', formData);

    if (!formData.cedula || !formData.nombre || !formData.apellidos || !formData.correo || !formData.direccion || !formData.password || !formData.id_rol || !formData.id_estado) {
        alert('Por favor completa todos los campos obligatorios');
        return;
    }

    const url = determineRouterUrl('createUsuario');
    console.log('Enviando a:', url);

    $.ajax({
        url: url,
        method: 'POST',
        data: formData,
        dataType: 'json',
        success: function (response) {
            console.log('Respuesta del servidor:', response);
            if (response.status === 'success') {
                alert('Usuario registrado exitosamente');

                $('#cedula, #nombre, #apellidos, #email, #telefono, #fecha-nacimiento, #direccion, #password').val('');
                $('#genero, #estado-civil, #rol, #estado').val('');

                setTimeout(() => {
                    window.location.href = 'Usuarios.php';
                }, 1000);
            } else {
                alert(response.message || 'Error al registrar el usuario');
            }
        },
        error: function (xhr, status, error) {
            console.error('Error AJAX:', error, xhr.responseText);
            alert('Error de conexión con el servidor. Revisa la consola para más detalles.');
        }
    });
}

function handleUserUpdate(e) {
    e.preventDefault();
    console.log('Actualizando usuario...');

    if (!editingUserId) {
        alert('Error: No se encontró el ID del usuario a actualizar');
        return;
    }

    const formData = {
        id_usuario: editingUserId,
        cedula: $('#cedula').val().trim(),
        nombre: $('#nombre').val().trim(),
        apellidos: $('#apellidos').val().trim(),
        correo: $('#email').val().trim(),
        telefono: $('#telefono').val().trim(),
        fecha_nacimiento: $('#fecha-nacimiento').val() || null,
        direccion: $('#direccion').val().trim(),
        password: $('#password').val(),
        id_genero: parseInt($('#genero').val()) || null,
        id_estado_civil: parseInt($('#estado-civil').val()) || null,
        id_rol: parseInt($('#rol').val()),
        id_estado: parseInt($('#estado').val())
    };

    console.log('Datos de actualización:', formData);

    if (!formData.cedula || !formData.nombre || !formData.apellidos || !formData.correo || !formData.direccion || !formData.id_rol || !formData.id_estado) {
        alert('Por favor completa todos los campos obligatorios');
        return;
    }

    const url = determineRouterUrl('updateUsuario');
    console.log('Enviando actualización a:', url);

    $.ajax({
        url: url,
        method: 'POST',
        data: formData,
        dataType: 'json',
        success: function (response) {
            console.log('Respuesta del servidor:', response);
            if (response.status === 'success') {
                alert('Usuario actualizado exitosamente');

                setTimeout(() => {
                    window.location.href = 'Usuarios.php';
                }, 1000);
            } else {
                alert(response.message || 'Error al actualizar el usuario');
            }
        },
        error: function (xhr, status, error) {
            console.error('Error AJAX:', error, xhr.responseText);
            alert('Error de conexión con el servidor. Revisa la consola para más detalles.');
        }
    });
}

function loadUserForEditing() {
    const urlParams = new URLSearchParams(window.location.search);
    const userId = urlParams.get('id');

    if (!userId) {
        alert('Error: No se especificó qué usuario editar');
        window.location.href = 'Usuarios.php';
        return;
    }

    editingUserId = userId;
    showLoadingOverlay();

    const url = determineRouterUrl('showUsuario');

    $.ajax({
        url: url,
        method: 'GET',
        data: { id: userId },
        dataType: 'json',
        success: function (response) {
            hideLoadingOverlay();
            if (response.status === 'success') {
                setTimeout(() => {
                    fillFormWithUserData(response.data);
                }, 1500);
            } else {
                alert('Error al cargar los datos del usuario');
                window.location.href = 'Usuarios.php';
            }
        },
        error: function (xhr, status, error) {
            hideLoadingOverlay();
            console.error('Error al cargar usuario:', error);
            alert('Error de conexión al cargar el usuario');
            window.location.href = 'Usuarios.php';
        }
    });
}

function fillFormWithUserData(usuario) {
    console.log('Llenando formulario con:', usuario);

    $('#cedula').val(usuario.cedula_usuario);
    $('#nombre').val(usuario.nombre);
    $('#apellidos').val(usuario.apellidos);
    $('#email').val(usuario.correo);
    $('#telefono').val(usuario.telefono);
    $('#fecha-nacimiento').val(usuario.fecha_nacimiento);
    $('#direccion').val(usuario.direccion);
    $('#genero').val(usuario.id_genero);
    $('#estado-civil').val(usuario.id_estado_civil);
    $('#rol').val(usuario.id_rol);
    $('#estado').val(usuario.id_estado);
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

function loadUsers() {
    console.log('Cargando usuarios...');
    const url = determineRouterUrl('listUsuarios');

    $.ajax({
        url: url,
        method: 'GET',
        dataType: 'json',
        success: function (response) {
            console.log('Usuarios cargados:', response);
            if (response.status === 'success') {
                populateUsersTable(response.data);
            } else {
                $('.custom-table tbody').html('<tr><td colspan="7" class="text-center">No se pudieron cargar los usuarios</td></tr>');
            }
        },
        error: function (xhr, status, error) {
            console.error('Error al cargar usuarios:', error, xhr.responseText);
            $('.custom-table tbody').html('<tr><td colspan="7" class="text-center">Error al cargar los usuarios</td></tr>');
        }
    });
}

function populateUsersTable(usuarios) {
    const tbody = $('.custom-table tbody');

    if (!usuarios || usuarios.length === 0) {
        tbody.html('<tr><td colspan="7" class="text-center">No hay usuarios registrados</td></tr>');
        return;
    }

    let rows = '';
    usuarios.forEach(usuario => {
        let actionsHtml = `<div class="d-flex gap-1 justify-content-center">`;

        actionsHtml += `
            <a class="btn btn-sm" style="background-color: #44C1F2; border-color: #44C1F2; color: white; min-width: 35px;" href="EditarUsuario.html?id=${usuario.id_usuario}" title="Editar Usuario">
                <i class="fas fa-edit"></i>
            </a>
        `;

        if (usuario.id_estado == 1) {
            actionsHtml += `
                <button class="btn btn-sm" style="background-color: #dc3545; border-color: #dc3545; color: white; min-width: 35px;" onclick="disableUser(${usuario.id_usuario})" title="Deshabilitar Usuario">
                    <i class="fas fa-ban"></i>
                </button>
            `;
        } else if (usuario.id_estado == 2) {
            actionsHtml += `
                <button class="btn btn-sm" style="background-color: #28a745; border-color: #28a745; color: white; min-width: 35px;" onclick="enableUser(${usuario.id_usuario})" title="Habilitar Usuario">
                    <i class="fas fa-check"></i>
                </button>
            `;
        }

        actionsHtml += `</div>`;

        rows += `
            <tr data-user-id="${usuario.id_usuario}">
                <td>${usuario.cedula_usuario}</td>
                <td>${usuario.nombre} ${usuario.apellidos}</td>
                <td>${usuario.correo}</td>
                <td>${usuario.telefono || 'N/A'}</td>
                <td>${usuario.rol_nombre || 'N/A'}</td>
                <td><span class="badge ${getStatusBadgeClass(usuario.id_estado)}">${usuario.estado_nombre || 'N/A'}</span></td>
                <td class="text-center">${actionsHtml}</td>
            </tr>
        `;
    });

    tbody.html(rows);
}

function disableUser(userId) {
    if (!confirm('¿Está seguro que desea deshabilitar este usuario?')) {
        return;
    }

    const url = determineRouterUrl('updateUsuarioStatus');

    $.ajax({
        url: url,
        method: 'POST',
        data: {
            id_usuario: userId,
            id_estado: 2
        },
        dataType: 'json',
        success: function (response) {
            if (response.status === 'success') {
                alert('Usuario deshabilitado exitosamente');
                loadUsers();
            } else {
                alert(response.message || 'Error al deshabilitar el usuario');
            }
        },
        error: function (xhr, status, error) {
            console.error('Error al deshabilitar usuario:', error);
            alert('Error de conexión al deshabilitar el usuario');
        }
    });
}

function enableUser(userId) {
    if (!confirm('¿Está seguro que desea habilitar este usuario?')) {
        return;
    }

    const url = determineRouterUrl('updateUsuarioStatus');

    $.ajax({
        url: url,
        method: 'POST',
        data: {
            id_usuario: userId,
            id_estado: 1
        },
        dataType: 'json',
        success: function (response) {
            if (response.status === 'success') {
                alert('Usuario habilitado exitosamente');
                loadUsers();
            } else {
                alert(response.message || 'Error al habilitar el usuario');
            }
        },
        error: function (xhr, status, error) {
            console.error('Error al habilitar usuario:', error);
            alert('Error de conexión al habilitar el usuario');
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

window.disableUser = disableUser;
window.enableUser = enableUser;
