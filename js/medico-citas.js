document.addEventListener('DOMContentLoaded', function () {
    if (typeof $ === 'undefined') {
        console.error('jQuery no está cargado');
        return;
    }

    $(document).ready(function () {
        loadMedicoCitas();

        $('.search-input').on('keyup', function () {
            const searchTerm = $(this).val().toLowerCase();
            $('.custom-table tbody tr').each(function () {
                const text = $(this).text().toLowerCase();
                $(this).toggle(text.includes(searchTerm) || searchTerm === '');
            });
        });
    });
});

function loadMedicoCitas() {
    if (typeof $ === 'undefined') {
        console.error('jQuery no disponible para AJAX');
        return;
    }

    $.ajax({
        url: '../router.php?action=listCitasByMedico',
        method: 'GET',
        dataType: 'text',
        success: function (response) {
            console.log('Raw response:', response);
            console.log('Response length:', response.length);

            try {
                const jsonData = JSON.parse(response);
                console.log('Parsed JSON:', jsonData);
                if (jsonData.status === 'success') {
                    populateTable(jsonData.data);
                } else {
                    $('.custom-table tbody').html('<tr><td colspan="5" class="text-center text-danger">' + (jsonData.message || 'Error al cargar citas') + '</td></tr>');
                }
            } catch (e) {
                console.error('JSON parse error:', e);
                console.log('First 500 chars of response:', response.substring(0, 500));
            }
        },
        error: function (xhr, status, error) {
            console.error('Error AJAX:', { xhr, status, error });
            console.log('Response text:', xhr.responseText);
        }
    });
}

function populateTable(citas) {
    const tbody = $('.custom-table tbody');

    if (!citas || citas.length === 0) {
        tbody.html('<tr><td colspan="5" class="text-center">No tienes citas programadas</td></tr>');
        return;
    }

    let html = '';
    citas.forEach(cita => {
        const fecha = new Date(cita.fecha).toLocaleDateString('es-ES');
        const hora = cita.hora.substring(0, 5);
        const estadoClass = getStatusClass(cita.id_estado);

        html += `
            <tr>
                <td>${cita.cedula_usuario || 'N/A'}</td>
                <td>${cita.nombre_paciente || 'N/A'}</td>
                <td>${fecha}</td>
                <td>${hora}</td>
                <td><span class="badge ${estadoClass}">${cita.nombre_estado || 'Sin estado'}</span></td>
            </tr>
        `;
    });

    tbody.html(html);
}

function getStatusClass(estadoId) {
    switch (parseInt(estadoId)) {
        case 1: return 'bg-success';
        case 2: return 'bg-info';
        case 3: return 'bg-warning';
        case 4: return 'bg-danger';
        case 5: return 'bg-secondary';
        default: return 'bg-light text-dark';
    }
}
