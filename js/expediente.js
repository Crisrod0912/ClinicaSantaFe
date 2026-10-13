document.addEventListener('DOMContentLoaded', function () {
    cargarExpediente();

    const expedienteForm = document.getElementById('expedienteForm');
    if (expedienteForm) {
        expedienteForm.addEventListener('submit', function (e) {
            e.preventDefault();
            actualizarExpediente();
        });
    }
});

function cargarExpediente() {
    const nombreElement = document.getElementById('nombreCompleto');
    if (nombreElement) {
        nombreElement.textContent = 'Cargando...';
    }

    fetch('../router.php?action=showExpediente')
        .then(response => {
            if (!response.ok) {
                throw new Error(`Error HTTP: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            if (data.status === 'success') {
                llenarFormulario(data.data);
            } else {
                console.error('Error del servidor:', data.message);
                mostrarError('Error al cargar el expediente: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error de conexión:', error);
            mostrarError('Error de conexión al cargar el expediente. Verifique su conexión a internet.');
        });
}

function llenarFormulario(data) {
    try {
        const nombreCompleto = (data.nombre || '') + ' ' + (data.apellidos || '');
        document.getElementById('nombreCompleto').textContent = nombreCompleto.trim() || 'Sin nombre';

        setValue('cedula', data.cedula_usuario);
        setValue('correo', data.correo);
        setValue('telefono', data.telefono);
        setValue('estadoCivil', data.estado_civil);
        setValue('fechaNacimiento', data.fecha_nacimiento);
        setValue('genero', data.genero);
        setValue('direccion', data.direccion);

        setValue('peso', data.peso);
        setValue('altura', data.altura);
        setValue('tipoSangre', data.tipo_sangre);
        setValue('enfermedades', data.enfermedades);
        setValue('alergias', data.alergias);
        setValue('cirugias', data.cirugias);

    } catch (error) {
        console.error('Error al llenar formulario:', error);
        mostrarError('Error al mostrar la información del expediente');
    }
}

function setValue(fieldId, value) {
    const field = document.getElementById(fieldId);
    if (field) {
        field.value = value || '';
    }
}

function actualizarExpediente() {
    const form = document.getElementById('expedienteForm');
    if (!form) {
        console.error('Formulario de expediente no encontrado');
        return;
    }

    if (!validarFormulario(form)) {
        return;
    }

    const formData = new FormData(form);

    const submitBtn = form.querySelector('button[type="submit"]');
    const originalText = submitBtn.textContent;
    submitBtn.textContent = 'Guardando...';
    submitBtn.disabled = true;

    fetch('../router.php?action=updateExpediente', {
        method: 'POST',
        body: formData
    })
        .then(response => {
            if (!response.ok) {
                throw new Error(`Error HTTP: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            if (data.status === 'success') {
                mostrarExito('Expediente actualizado exitosamente');
                setTimeout(() => {
                    window.location.href = 'Expediente.html';
                }, 1500);
            } else {
                mostrarError('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error de conexión:', error);
            mostrarError('Error de conexión al actualizar el expediente. Intente nuevamente.');
        })
        .finally(() => {
            submitBtn.textContent = originalText;
            submitBtn.disabled = false;
        });
}

function validarFormulario(form) {
    const correo = form.querySelector('[name="correo"]');

    if (!correo || !correo.value.trim()) {
        mostrarError('El correo electrónico es requerido');
        correo?.focus();
        return false;
    }

    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(correo.value.trim())) {
        mostrarError('Por favor ingrese un correo electrónico válido');
        correo.focus();
        return false;
    }

    return true;
}

function mostrarError(mensaje) {
    alert('❌ ' + mensaje);
}

function mostrarExito(mensaje) {
    alert('✅ ' + mensaje);
}

function debugLog(message, data = '') {
    if (console && console.log) {
        console.log('[Expediente Debug]', message, data);
    }
}
