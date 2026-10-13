let editingMedicamentoId = null;

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
    console.log('Inicializando página de medicamentos admin...');
    const currentPage = window.location.pathname;
    const fileName = currentPage.split('/').pop();

    if (fileName === 'RegistrarMedicamentos.html') {
        console.log('Detectado formulario de registro de medicamento');
        loadFormData();
    }

    else if (fileName === 'EditarMedicamento.html') {
        console.log('Detectado formulario de actualización de medicamento');
        loadFormData();
        loadMedicamentoForEditing();
    }

    else if (fileName === 'Medicamentos.php' || document.querySelector('.custom-table')) {
        console.log('Detectada tabla de medicamentos');
        loadMedicamentos();
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
        if (editingMedicamentoId) {
            handleMedicamentoUpdate(e);
        } else {
            handleMedicamentoSubmit(e);
        }
    });

    $(document).off('keyup', 'input[placeholder*="Buscar"]').on('keyup', 'input[placeholder*="Buscar"]', function () {
        searchInTable($(this).val());
    });
}

function loadMedicamentoForEditing() {
    const urlParams = new URLSearchParams(window.location.search);
    const medicamentoId = urlParams.get('id');

    if (!medicamentoId) {
        alert('Error: No se especificó qué medicamento editar');
        window.location.href = 'Medicamentos.php';
        return;
    }

    editingMedicamentoId = medicamentoId;
    showLoadingOverlay();

    const url = determineRouterUrl('showMedicamento');

    $.ajax({
        url: url,
        method: 'GET',
        data: { id: medicamentoId },
        dataType: 'json',
        success: function (response) {
            hideLoadingOverlay();
            if (response.status === 'success') {
                setTimeout(() => {
                    fillFormWithMedicamentoData(response.data);
                }, 1500);
            } else {
                alert('Error al cargar los datos del medicamento: ' + (response.message || ''));
                window.location.href = 'Medicamentos.php';
            }
        },
        error: function (xhr, status, error) {
            hideLoadingOverlay();
            console.error('Error al cargar medicamento:', error);
            alert('Error de conexión al cargar el medicamento');
            window.location.href = 'Medicamentos.php';
        }
    });
}

function fillFormWithMedicamentoData(medicamento) {
    console.log('Llenando formulario con:', medicamento);

    $('#nombre').val(medicamento.nombre || '');
    $('#forma-farmaceutica').val(medicamento.id_forma_farmaceutica || '');
    $('#grupo-terapeutico').val(medicamento.id_grupo_terapeutico || '');
    $('#via-administracion').val(medicamento.id_via_administracion || '');
    $('#estado').val(medicamento.id_estado || '');
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

function loadMedicamentos() {
    console.log('Cargando medicamentos...');
    const url = determineRouterUrl('listMedicamentos');

    $.ajax({
        url: url,
        method: 'GET',
        dataType: 'json',
        success: function (response) {
            console.log('Medicamentos cargados:', response);
            if (response.status === 'success') {
                populateMedicamentosTable(response.data);
            } else {
                $('.custom-table tbody').html('<tr><td colspan="6" class="text-center">No se pudieron cargar los medicamentos</td></tr>');
            }
        },
        error: function (xhr, status, error) {
            console.error('Error al cargar medicamentos:', error, xhr.responseText);
            $('.custom-table tbody').html('<tr><td colspan="6" class="text-center">Error al cargar los medicamentos</td></tr>');
        }
    });
}

function populateMedicamentosTable(medicamentos) {
    const tbody = $('.custom-table tbody');

    if (!medicamentos || medicamentos.length === 0) {
        tbody.html('<tr><td colspan="6" class="text-center">No hay medicamentos registrados</td></tr>');
        return;
    }

    let rows = '';
    medicamentos.forEach(medicamento => {
        let actionsHtml = '';

        actionsHtml += `
                <a class="btn btn-sm me-1" style="background-color: #44C1F2; border-color: #44C1F2; color: white;" href="EditarMedicamento.html?id=${medicamento.id_medicamento}" title="Editar">
                    <i class="fas fa-edit"></i> 
                </a>
            `;

        if (medicamento.id_estado == 1) {
            actionsHtml += `
                <button class="btn btn-sm" style="background-color: #dc3545; border-color: #dc3545; color: white;" onclick="disableMedicamento(${medicamento.id_medicamento})" title="Deshabilitar" data-bs-toggle="modal" data-bs-target="#modalConfirmacion">
                    <i class="fas fa-ban"></i> 
                </button>
            `;
        } else if (medicamento.id_estado == 2) {
            actionsHtml += `
                <button class="btn btn-sm" style="background-color: #28a745; border-color: #28a745; color: white;" onclick="enableMedicamento(${medicamento.id_medicamento})" title="Habilitar">
                    <i class="fas fa-check"></i> 
                </button>
            `;
        }

        rows += `
            <tr data-medicamento-id="${medicamento.id_medicamento}">
                <td>${medicamento.nombre}</td>
                <td>${medicamento.grupo_terapeutico || 'N/A'}</td>
                <td>${medicamento.via_administracion || 'N/A'}</td>
                <td>${medicamento.forma_farmaceutica || 'N/A'}</td>
                <td><span class="badge ${getStatusBadgeClass(medicamento.id_estado)}">${medicamento.estado || 'N/A'}</span></td>
                <td>${actionsHtml}</td>
            </tr>
        `;
    });

    tbody.html(rows);
}

function disableMedicamento(medicamentoId) {
    window.currentMedicamentoId = medicamentoId;
}

function deshabilitarMedicamento() {
    const medicamentoId = window.currentMedicamentoId;

    if (!medicamentoId) {
        alert('Error: No se encontró el medicamento a deshabilitar');
        return;
    }

    const url = determineRouterUrl('updateMedicamentoStatus');

    $.ajax({
        url: url,
        method: 'POST',
        data: {
            id: medicamentoId,
            id_estado: 2
        },
        dataType: 'json',
        success: function (response) {
            if (response.status === 'success') {
                alert('Medicamento deshabilitado exitosamente');
                $('#modalConfirmacion').modal('hide');
                loadMedicamentos();
            } else {
                alert(response.message || 'Error al deshabilitar el medicamento');
            }
        },
        error: function (xhr, status, error) {
            console.error('Error al deshabilitar medicamento:', error, xhr.responseText);
            alert('Error de conexión al deshabilitar el medicamento');
        }
    });
}

function enableMedicamento(medicamentoId) {
    if (!confirm('¿Está seguro que desea habilitar este medicamento?')) {
        return;
    }

    const url = determineRouterUrl('updateMedicamentoStatus');

    $.ajax({
        url: url,
        method: 'POST',
        data: {
            id: medicamentoId,
            id_estado: 1
        },
        dataType: 'json',
        success: function (response) {
            if (response.status === 'success') {
                alert('Medicamento habilitado exitosamente');
                loadMedicamentos();
            } else {
                alert(response.message || 'Error al habilitar el medicamento');
            }
        },
        error: function (xhr, status, error) {
            console.error('Error al habilitar medicamento:', error, xhr.responseText);
            alert('Error de conexión al habilitar el medicamento');
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

window.disableMedicamento = disableMedicamento;
window.enableMedicamento = enableMedicamento;
window.deshabilitarMedicamento = deshabilitarMedicamento;

function loadFormData() {
    console.log('Cargando datos del formulario...');

    let loadedCount = 0;
    const totalToLoad = 4;

    function checkIfAllLoaded() {
        loadedCount++;
        console.log(`Cargados ${loadedCount} de ${totalToLoad} elementos`);
    }

    loadFormasFarmaceuticas(checkIfAllLoaded);
    loadGruposTerapeuticos(checkIfAllLoaded);
    loadViasAdministracion(checkIfAllLoaded);
    loadStates(checkIfAllLoaded);
}

function loadFormasFarmaceuticas(callback) {
    const url = determineRouterUrl('getFormasFarmaceuticas');
    console.log('Cargando formas farmacéuticas desde:', url);

    $.ajax({
        url: url,
        method: 'GET',
        dataType: 'json',
        success: function (response) {
            console.log('Respuesta formas farmacéuticas:', response);
            if (response.status === 'success') {
                const select = $('#forma-farmaceutica');
                if (select.length > 0) {
                    populateSelect(select, response.data, 'id_forma_farmaceutica', 'nombre');
                }
            } else {
                console.error('Error al cargar formas farmacéuticas:', response.message);
            }
            if (callback) callback();
        },
        error: function (xhr, status, error) {
            console.error('Error al cargar formas farmacéuticas:', error, xhr.responseText);
            if (callback) callback();
        }
    });
}

function loadGruposTerapeuticos(callback) {
    const url = determineRouterUrl('getGruposTerapeuticos');
    console.log('Cargando grupos terapéuticos desde:', url);

    $.ajax({
        url: url,
        method: 'GET',
        dataType: 'json',
        success: function (response) {
            console.log('Respuesta grupos terapéuticos:', response);
            if (response.status === 'success') {
                const select = $('#grupo-terapeutico');
                if (select.length > 0) {
                    populateSelect(select, response.data, 'id_grupo_farmaceutico', 'nombre');
                }
            } else {
                console.error('Error al cargar grupos terapéuticos:', response.message);
            }
            if (callback) callback();
        },
        error: function (xhr, status, error) {
            console.error('Error al cargar grupos terapéuticos:', error, xhr.responseText);
            if (callback) callback();
        }
    });
}

function loadViasAdministracion(callback) {
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

function handleMedicamentoSubmit(e) {
    e.preventDefault();
    console.log('Registrando nuevo medicamento...');

    const formData = {
        nombre: $('#nombre').val().trim(),
        id_forma_farmaceutica: parseInt($('#forma-farmaceutica').val()) || 0,
        id_grupo_terapeutico: parseInt($('#grupo-terapeutico').val()) || 0,
        id_via_administracion: parseInt($('#via-administracion').val()) || 0,
        id_estado: parseInt($('#estado').val()) || 1
    };

    console.log('Datos del formulario:', formData);

    if (!formData.nombre || !formData.id_forma_farmaceutica || !formData.id_grupo_terapeutico || !formData.id_via_administracion) {
        alert('Por favor completa todos los campos obligatorios');
        return;
    }

    const url = determineRouterUrl('createMedicamento');
    console.log('Enviando a:', url);

    $.ajax({
        url: url,
        method: 'POST',
        data: formData,
        dataType: 'json',
        success: function (response) {
            console.log('Respuesta del servidor:', response);
            if (response.status === 'success') {
                alert('Medicamento registrado exitosamente');

                $('#form-registro')[0].reset();
                $('#forma-farmaceutica, #grupo-terapeutico, #via-administracion, #estado').val('');

                setTimeout(() => {
                    window.location.href = 'Medicamentos.php';
                }, 1000);
            } else {
                alert(response.message || 'Error al registrar el medicamento');
            }
        },
        error: function (xhr, status, error) {
            console.error('Error AJAX:', error, xhr.responseText);
            alert('Error de conexión con el servidor. Revisa la consola para más detalles.');
        }
    });
}

function handleMedicamentoUpdate(e) {
    e.preventDefault();
    console.log('Actualizando medicamento...');

    if (!editingMedicamentoId) {
        alert('Error: No se encontró el ID del medicamento a actualizar');
        return;
    }

    const formData = {
        id: editingMedicamentoId,
        nombre: $('#nombre').val().trim(),
        id_forma_farmaceutica: parseInt($('#forma-farmaceutica').val()) || 0,
        id_grupo_terapeutico: parseInt($('#grupo-terapeutico').val()) || 0,
        id_via_administracion: parseInt($('#via-administracion').val()) || 0,
        id_estado: parseInt($('#estado').val()) || 1
    };

    console.log('Datos de actualización:', formData);

    if (!formData.nombre || !formData.id_forma_farmaceutica || !formData.id_grupo_terapeutico || !formData.id_via_administracion) {
        alert('Por favor completa todos los campos obligatorios');
        return;
    }

    const url = determineRouterUrl('updateMedicamento');
    console.log('Enviando actualización a:', url);

    $.ajax({
        url: url,
        method: 'POST',
        data: formData,
        dataType: 'json',
        success: function (response) {
            console.log('Respuesta del servidor:', response);
            if (response.status === 'success') {
                alert('Medicamento actualizado exitosamente');

                setTimeout(() => {
                    window.location.href = 'Medicamentos.php';
                }, 1000);
            } else {
                alert(response.message || 'Error al actualizar el medicamento');
            }
        },
        error: function (xhr, status, error) {
            console.error('Error AJAX:', error, xhr.responseText);
            alert('Error de conexión con el servidor. Revisa la consola para más detalles.');
        }
    });
}
