<?php
session_start();

if (isset($_GET['logout'])) {
    session_unset();
    session_destroy();
    session_start();
}

if (isset($_SESSION['user']) && !isset($_GET['logout'])) {
    switch ($_SESSION['user']['rol']) {
        case 1: header('Location: ../Administrativo/inicioAdmin.html'); exit;
        case 2: header('Location: ../Medicos/inicioMedico.html'); exit;
        case 3: header('Location: ../Paciente/inicioPaciente.html'); exit;
    }
}
?>

<!DOCTYPE html>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Iniciar Sesion</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/style.css">
    <style>
        .alert {
            margin-bottom: 1rem;
        }

        .loading {
            display: none;
        }
    </style>
</head>

<body>
    <div class="container-fluid vh-100 d-flex align-items-center justify-content-center">
        <div class="row justify-content-center w-100">
            <div class="col-md-4 col-lg-3">
                <div class="text-center mb-4">
                    <div class="login-logo mb-4"></div>
                    <h1 class="mb-4">Iniciar Sesion</h1>
                    <p class="text-muted mb-4">Bienvenido al sistema de gestion medica</p>
                </div>

                <div id="messageContainer"></div>

                <form id="loginForm">
                    <div class="mb-3">
                        <label for="cedula" class="form-label">Cedula</label>
                        <input type="text" id="cedula" name="cedula" class="form-control"
                            placeholder="Ingrese su cedula" required>
                    </div>

                    <div class="mb-4">
                        <label for="password" class="form-label">Contrasena</label>
                        <input type="password" id="password" name="password" class="form-control"
                            placeholder="Ingrese su contrasena" required>
                    </div>

                    <div class="text-center mb-4">
                        <button type="submit" class="btn btn-login" id="loginBtn">
                            <span class="loading spinner-border spinner-border-sm me-2" role="status"></span>
                            <span id="btnText">Iniciar Sesion</span>
                        </button>
                    </div>
                </form>

                <div class="text-center">
                    <p class="text-muted mb-2">No tienes cuenta?</p>
                    <a href="Registro.php" class="back-link">Registrarse aqui</a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('loginForm').addEventListener('submit', function (e) {
            e.preventDefault();

            var messageContainer = document.getElementById('messageContainer');
            var loginBtn = document.getElementById('loginBtn');
            var btnText = document.getElementById('btnText');
            var loading = document.querySelector('.loading');

            messageContainer.innerHTML = '';

            loading.style.display = 'inline-block';
            btnText.textContent = 'Iniciando sesion...';
            loginBtn.disabled = true;

            var formData = new FormData(this);

            fetch('../router.php?action=login', {
                method: 'POST',
                body: formData
            })
                .then(function (response) {
                    return response.text();
                })
                .then(function (text) {
                    console.log('Respuesta del servidor:', text);

                    var jsonStart = text.indexOf('{');
                    var jsonEnd = text.lastIndexOf('}') + 1;

                    if (jsonStart === -1 || jsonEnd === 0) {
                        throw new Error('No se encontró JSON válido en la respuesta');
                    }

                    var jsonText = text.substring(jsonStart, jsonEnd);
                    console.log('JSON extraído:', jsonText);

                    try {
                        return JSON.parse(jsonText);
                    } catch (e) {
                        console.error('Error parsing JSON:', e);
                        console.error('Texto completo:', text);
                        throw new Error('Respuesta JSON inválida');
                    }
                })
                .then(function (result) {
                    console.log('Resultado procesado:', result);

                    if (result.success) {
                        messageContainer.innerHTML = '<div class="alert alert-success" role="alert">' + result.message + '. Redirigiendo...</div>';

                        setTimeout(function () {
                            window.location.href = result.redirect;
                        }, 1000);
                    } else {
                        messageContainer.innerHTML = '<div class="alert alert-danger" role="alert">' + (result.message || 'Error de login') + '</div>';
                    }
                })
                .catch(function (error) {
                    console.error('Error completo:', error);
                    messageContainer.innerHTML = '<div class="alert alert-danger" role="alert">Error de conexión: ' + error.message + '</div>';
                })
                .finally(function () {
                    loading.style.display = 'none';
                    btnText.textContent = 'Iniciar Sesion';
                    loginBtn.disabled = false;
                });
        });
    </script>
</body>

</html>
