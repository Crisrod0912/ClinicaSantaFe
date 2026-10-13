$(document).ready(function () {
    console.log('Inicializando medicamentos...');
    loadPatientMedications();

    $('#buscarMedicamento').on('input', function () {
        filtrarMedicamentos($(this).val());
    });
});

let medicamentosData = [];

function determineRouterUrl(action) {
    return `../router.php?action=${action}`;
}

function loadPatientMedications() {
    console.log('Cargando medicamentos del paciente...');
    const url = determineRouterUrl('listMisMedicamentos');

    $.ajax({
        url: url,
        method: 'GET',
        dataType: 'json',
        success: function (response) {
            console.log('Medicamentos cargados:', response);
            if (response.status === 'success') {
                medicamentosData = response.data;
                populateMedicationsTable(response.data);
            } else {
                $('#medicamentosTableBody').html('<tr><td colspan="6" class="text-center">No se pudieron cargar los medicamentos</td></tr>');
                mostrarError('Error al cargar medicamentos: ' + response.message);
            }
        },
        error: function (xhr, status, error) {
            console.error('Error al cargar medicamentos:', error, xhr.responseText);
            $('#medicamentosTableBody').html('<tr><td colspan="6" class="text-center">Error al cargar los medicamentos</td></tr>');
            mostrarError('Error de conexión al cargar los medicamentos.');
        }
    });
}

function verificarEstadoMedicamento(fechaPreescripcion, tiempoTratamiento, idEstado) {
    if (idEstado !== 1) {
        return { esActivo: false, razon: 'Inactivo' };
    }

    const tratamientoCronico = /cronicos?|continuo|diario|permanente|segun\s+necesidad/i;
    if (tratamientoCronico.test(tiempoTratamiento)) {
        return { esActivo: true, razon: 'Activo' };
    }

    const diasMatch = tiempoTratamiento.match(/(\d+)\s*dias?/i);
    if (!diasMatch) {
        return { esActivo: true, razon: 'Activo' };
    }

    const diasTratamiento = parseInt(diasMatch[1]);
    const fechaInicio = new Date(fechaPreescripcion + 'T00:00:00');
    const fechaFin = new Date(fechaInicio);
    fechaFin.setDate(fechaFin.getDate() + diasTratamiento);

    const hoy = new Date();
    hoy.setHours(0, 0, 0, 0);

    if (hoy > fechaFin) {
        return { esActivo: false, razon: 'Tratamiento finalizado' };
    }

    return { esActivo: true, razon: 'Activo' };
}

function populateMedicationsTable(medicamentos) {
    const tbody = $('#medicamentosTableBody');

    if (!medicamentos || medicamentos.length === 0) {
        tbody.html('<tr><td colspan="6" class="text-center">No tienes medicamentos registrados</td></tr>');
        return;
    }

    let rows = '';
    medicamentos.forEach(medicamento => {
        const estadoInfo = verificarEstadoMedicamento(
            medicamento.fecha_preescripcion,
            medicamento.tiempo_tratamiento,
            medicamento.id_estado
        );

        const estadoClass = estadoInfo.esActivo ? 'badge bg-success' : 'badge bg-secondary';
        const estadoTexto = estadoInfo.razon;

        let actionsHtml = `
            <button class="btn btn-sm" style="background-color: #44C1F2; border-color: #44C1F2; color: white;" onclick="verDetallesMedicamento(${medicamento.id_medicamento_paciente})" title="Ver Detalles">
                <i class="fas fa-eye"></i>
            </button>
        `;

        rows += `
            <tr>
                <td>
                    ${medicamento.nombre_medicamento || 'Medicamento N/A'}
                </td>
                <td>${medicamento.grupo_terapeutico || 'N/A'}</td>
                <td>${medicamento.via_administracion || 'N/A'}</td>
                <td>${medicamento.forma_farmaceutica || 'N/A'}</td>
                <td><span class="${estadoClass}">${estadoTexto}</span></td>
                <td>${actionsHtml}</td>
            </tr>
        `;
    });

    tbody.html(rows);
}

function filtrarMedicamentos(filtro) {
    if (!medicamentosData) {
        return;
    }

    const filtroLower = filtro.toLowerCase();
    const medicamentosFiltrados = medicamentosData.filter(medicamento => {
        const nombre = (medicamento.nombre_medicamento || '').toLowerCase();
        const grupo = (medicamento.grupo_terapeutico || '').toLowerCase();
        const via = (medicamento.via_administracion || '').toLowerCase();
        const forma = (medicamento.forma_farmaceutica || '').toLowerCase();

        return nombre.includes(filtroLower) ||
            grupo.includes(filtroLower) ||
            via.includes(filtroLower) ||
            forma.includes(filtroLower);
    });

    populateMedicationsTable(medicamentosFiltrados);
}

function verDetallesMedicamento(idMedicamentoPaciente) {
    const url = determineRouterUrl(`showMedicamentoPaciente&id=${idMedicamentoPaciente}`);

    $.ajax({
        url: url,
        method: 'GET',
        dataType: 'json',
        success: function (response) {
            if (response.status === 'success') {
                mostrarModalDetalles(response.data);
            } else {
                mostrarError('Error al cargar detalles: ' + response.message);
            }
        },
        error: function (xhr, status, error) {
            console.error('Error:', error);
            mostrarError('Error de conexión al cargar detalles.');
        }
    });
}

function mostrarModalDetalles(medicamento) {
    const estadoInfo = verificarEstadoMedicamento(
        medicamento.fecha_preescripcion,
        medicamento.tiempo_tratamiento,
        medicamento.id_estado
    );

    const estadoClass = estadoInfo.esActivo ? 'badge bg-success' : 'badge bg-secondary';

    let detallesHtml = `
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Nombre Completo:</label>
                <div style="background-color: white; padding: 12px 20px; border-radius: 25px; border: 1px solid #d0d0d0;">
                    ${medicamento.nombre_paciente || 'N/A'}
                </div>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Medicamento:</label>
                <div style="background-color: white; padding: 12px 20px; border-radius: 25px; border: 1px solid #d0d0d0;">
                    ${medicamento.nombre_medicamento || 'N/A'}
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Fecha de Prescripción:</label>
                <div style="background-color: white; padding: 12px 20px; border-radius: 25px; border: 1px solid #d0d0d0;">
                    ${formatearFecha(medicamento.fecha_preescripcion)}
                </div>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Estado:</label>
                <div style="background-color: white; padding: 12px 20px; border-radius: 25px; border: 1px solid #d0d0d0;">
                    <span class="${estadoClass}">${estadoInfo.razon}</span>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Forma Farmacéutica:</label>
                <div style="background-color: white; padding: 12px 20px; border-radius: 25px; border: 1px solid #d0d0d0;">
                    ${medicamento.forma_farmaceutica || 'N/A'}
                </div>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Vía de Administración:</label>
                <div style="background-color: white; padding: 12px 20px; border-radius: 25px; border: 1px solid #d0d0d0;">
                    ${medicamento.via_administracion || 'N/A'}
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 mb-3">
                <label class="form-label">Tiempo de Tratamiento:</label>
                <div style="background-color: white; padding: 12px 20px; border-radius: 25px; border: 1px solid #d0d0d0;">
                    ${medicamento.tiempo_tratamiento || 'N/A'}
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 mb-3">
                <label class="form-label">Grupo Terapéutico:</label>
                <div style="background-color: white; padding: 12px 20px; border-radius: 25px; border: 1px solid #d0d0d0;">
                    ${medicamento.grupo_terapeutico || 'N/A'}
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 mb-3">
                <label class="form-label">Indicaciones:</label>
                <div style="background-color: white; padding: 12px 20px; border-radius: 25px; border: 1px solid #d0d0d0; min-height: 60px;">
                    ${medicamento.indicaciones || 'Sin indicaciones específicas'}
                </div>
            </div>
        </div>
    `;

    $('#medicamentoDetalles').html(detallesHtml);
    $('#medicamentoModal').modal('show');
}

function formatearFecha(fecha) {
    if (!fecha) return 'N/A';

    try {
        const date = new Date(fecha + 'T00:00:00');
        return date.toLocaleDateString('es-ES', {
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        });
    } catch (error) {
        return fecha;
    }
}

function mostrarError(mensaje) {
    console.error(mensaje);
    alert('⚠️ ' + mensaje);
}

function mostrarExito(mensaje) {
    console.log(mensaje);
    alert('✅ ' + mensaje);
}

function debugLog(message, data = '') {
    if (console && console.log) {
        console.log('[Medicamentos Debug]', message, data);
    }
}
