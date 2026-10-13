document.addEventListener("DOMContentLoaded", function () {
    const form = document.getElementById("form-registro");
    const titleElem = document.querySelector(".register-container h2");
    if (!form || !titleElem) return;

    const has = id => !!document.getElementById(id);
    let pageType;
    if (has("cedula-paciente")) pageType = "cita";
    else if (has("descripcion")) pageType = "rol";
    else if (has("forma-farmaceutica")) pageType = "medicamento";
    else if (has("esquema")) pageType = "vacuna";
    else pageType = "usuario";

    if (pageType === "usuario") {
        const email = document.getElementById("email");
        email.addEventListener("blur", () => {
            clearError(email);
            if (email.value.trim() && !email.value.includes("@")) {
                showError(email, "Correo inválido");
            }
        });
    } else if (pageType === "cita") {
        const ced = document.getElementById("cedula-paciente");
        ced.addEventListener("blur", () => {
            clearError(ced);
            if (!ced.value.trim() || isNaN(ced.value.trim())) {
                showError(ced, "Cédula inválida");
            }
        });
    }

    form.addEventListener("submit", function (e) {
        e.preventDefault();
        document.querySelectorAll(".error-message").forEach(el => el.remove());
        let valid = true;

        switch (pageType) {
            case "usuario":
                ["cedula", "email", "direccion", "nombre", "telefono",
                    "genero", "apellidos", "fecha-nacimiento", "rol", "estado"]
                    .forEach(id => {
                        const el = document.getElementById(id);
                        if (!el.value.trim()) {
                            showError(el, "Campo obligatorio");
                            valid = false;
                        }
                    });
                break;

            case "cita":
                ["cedula-paciente", "nombre-paciente",
                    "servicio", "especialidad", "hora", "fecha"]
                    .forEach(id => {
                        const el = document.getElementById(id);
                        if (!el.value.trim()) {
                            showError(el, "Campo obligatorio");
                            valid = false;
                        }
                    });
                const fechaVal = document.getElementById("fecha").value;
                if (fechaVal) {
                    const sel = new Date(fechaVal),
                        hoy = new Date();
                    hoy.setHours(0, 0, 0, 0);
                    if (sel < hoy) {
                        showError(document.getElementById("fecha"), "Fecha inválida");
                        valid = false;
                    }
                }
                break;

            case "rol":
                ["nombre", "estado", "descripcion"]
                    .forEach(id => {
                        const el = document.getElementById(id);
                        if (!el.value.trim()) {
                            showError(el, "Campo obligatorio");
                            valid = false;
                        }
                    });
                break;

            case "medicamento":
                ["nombre", "forma-farmaceutica", "grupo-terapeutico",
                    "estado", "via-administracion"]
                    .forEach(id => {
                        const el = document.getElementById(id);
                        if (!el.value.trim()) {
                            showError(el, "Campo obligatorio");
                            valid = false;
                        }
                    });
                break;

            case "vacuna":
                ["nombre", "esquema", "estado", "enfermedad", "via-administracion"]
                    .forEach(id => {
                        const el = document.getElementById(id);
                        if (!el.value.trim()) {
                            showError(el, "Campo obligatorio");
                            valid = false;
                        }
                    });
                const checks = Array.from(document.querySelectorAll("input[name='grupo']"));
                if (!checks.some(c => c.checked)) {
                    const container = document.querySelector(".checkbox-group");
                    showError(container, "Selecciona al menos un grupo");
                    valid = false;
                }
                break;
        }

        if (!valid) return;

        const datos = Object.fromEntries(new FormData(form).entries());
        console.log(`Registrando ${pageType}:`, datos);
        alert(`¡${titleElem.innerText} exitoso!`);
        form.reset();
    });

    function showError(el, msg) {
        const wrapper = el.tagName === "DIV" ? el : el.parentElement;
        const div = document.createElement("div");
        div.className = "error-message";
        div.textContent = msg;
        wrapper.appendChild(div);
    }
    function clearError(el) {
        const wrapper = el.parentElement;
        const prev = wrapper.querySelector(".error-message");
        if (prev) prev.remove();
    }
});

document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('form-registro');
    if (form) {
        form.addEventListener('submit', onSubmitRegistro);
    }
});

function onSubmitRegistro(event) {
    event.preventDefault();
    console.log('Formulario de registro enviado');

    document.querySelectorAll('.error-message').forEach(el => el.remove());

    const formData = new FormData(event.target);

    const requiredFields = {
        'cedula': 'Cédula',
        'nombre': 'Nombre',
        'apellidos': 'Apellidos',
        'email': 'Correo electrónico',
        'direccion': 'Dirección',
        'password': 'Contraseña',
        'confirm_password': 'Confirmar contraseña'
    };

    let hasErrors = false;

    for (const [fieldName, label] of Object.entries(requiredFields)) {
        const value = formData.get(fieldName);
        if (!value || value.trim() === '') {
            showErrorByFieldName(fieldName, `${label} es obligatorio`);
            hasErrors = true;
        }
    }

    const password = formData.get('password');
    const confirmPassword = formData.get('confirm_password');

    if (password && confirmPassword && password !== confirmPassword) {
        showErrorByFieldName('confirm_password', 'Las contraseñas no coinciden');
        hasErrors = true;
    }

    const email = formData.get('email');
    if (email && !email.includes('@')) {
        showErrorByFieldName('email', 'El formato del correo es inválido');
        hasErrors = true;
    }

    if (hasErrors) {
        return;
    }

    const dataToSend = new FormData();
    dataToSend.append('cedula', formData.get('cedula'));
    dataToSend.append('nombre', formData.get('nombre'));
    dataToSend.append('apellidos', formData.get('apellidos'));
    dataToSend.append('correo', formData.get('email'));
    dataToSend.append('telefono', formData.get('telefono') || '');
    dataToSend.append('fecha_nacimiento', formData.get('fecha_nacimiento') || '');
    dataToSend.append('direccion', formData.get('direccion'));
    dataToSend.append('password', formData.get('password'));

    const genero = formData.get('genero');
    const estadoCivil = formData.get('estado_civil');

    let idGenero = 0;
    if (genero === 'masculino') idGenero = 1;
    else if (genero === 'femenino') idGenero = 2;
    else if (genero === 'otro') idGenero = 3;

    let idEstadoCivil = 0;
    if (estadoCivil === 'soltero') idEstadoCivil = 1;
    else if (estadoCivil === 'casado') idEstadoCivil = 2;
    else if (estadoCivil === 'divorciado') idEstadoCivil = 3;
    else if (estadoCivil === 'viudo') idEstadoCivil = 4;

    dataToSend.append('id_genero', idGenero);
    dataToSend.append('id_estado_civil', idEstadoCivil);
    dataToSend.append('id_rol', 3);
    dataToSend.append('id_estado', 1);

    console.log('Enviando datos al servidor...');

    fetch('../router.php?action=register', {
        method: 'POST',
        body: dataToSend
    })
        .then(response => {
            console.log('Respuesta recibida:', response.status);
            return response.json();
        })
        .then(data => {
            console.log('Datos de respuesta:', data);
            if (data.success) {
                alert('¡Registro exitoso! Ahora puedes iniciar sesión.');
                event.target.reset();
                window.location.href = 'Login.php';
            } else {
                alert('Error en el registro: ' + (data.message || 'Error desconocido'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error de conexión. Por favor, intenta de nuevo.');
        });
}

function showErrorByFieldName(fieldName, message) {
    const field = document.getElementById(fieldName) ||
        document.querySelector(`[name="${fieldName}"]`);
    if (field) {
        showError(field, message);
    }
}

function showError(field, message) {
    const err = document.createElement('div');
    err.className = 'error-message';
    err.textContent = message;
    err.style.color = 'red';
    err.style.fontSize = '0.875rem';
    err.style.marginTop = '0.25rem';
    field.parentElement.appendChild(err);
}

function deshabilitarRol() {
    console.log('Rol deshabilitado');
    const modal = bootstrap.Modal.getInstance(document.getElementById('modalConfirmacion'));
    modal.hide();
}
