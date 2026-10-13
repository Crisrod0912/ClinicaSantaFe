$(document).ready(function () {
    cargarMisEspecialidades();

    $('input[placeholder*="Buscar especialidad"]').on('keyup', function () {
        const searchTerm = $(this).val().toLowerCase();
        filterEspecialidadesTable(searchTerm);
    });
});

function cargarMisEspecialidades() {
    $.ajax({
        url: '../router.php?action=listMySpecialties',
        method: 'GET',
        dataType: 'json',
        success: function (response) {
            if (response.status === 'success') {
                mostrarEspecialidades(response.data);
            } else {
                $('.custom-table tbody').html('<tr><td colspan="3" class="text-center">' + response.message + '</td></tr>');
            }
        },
        error: function (xhr, status, error) {
            console.error('Error al cargar especialidades:', error);
            $('.custom-table tbody').html('<tr><td colspan="3" class="text-center">Error al cargar las especialidades</td></tr>');
        }
    });
}

function mostrarEspecialidades(especialidades) {
    let html = '';

    if (especialidades.length === 0) {
        html = '<tr><td colspan="3" class="text-center">No tienes especialidades asignadas</td></tr>';
    } else {
        especialidades.forEach(function (especialidad) {
            html += `
                <tr>
                    <td>${especialidad.id_medico_especialidad}</td>
                    <td>${especialidad.nombre_especialidad || 'N/A'}</td>
                    <td><span class="badge ${getStatusBadgeClass(especialidad.id_estado)}">${especialidad.nombre_estado || 'N/A'}</span></td>
                </tr>
            `;
        });
    }

    $('.custom-table tbody').html(html);
}

function filterEspecialidadesTable(searchTerm) {
    $('.custom-table tbody tr').each(function () {
        const rowText = $(this).text().toLowerCase();
        if (rowText.indexOf(searchTerm) === -1) {
            $(this).hide();
        } else {
            $(this).show();
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
