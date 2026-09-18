<?php
error_log("AuthController: Archivo cargado");

class AuthController {

    public function __construct() {
        error_log("AuthController: Constructor ejecutado.");
    }
    
    public function login() {
        error_log("AuthController: Método de sesión iniciado.");
        
        try {
            if (ob_get_level()) {
                ob_clean();
            }
            
            header('Content-Type: application/json; charset=utf-8');
            
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                error_log("AuthController: Método no es POST, es: " . $_SERVER['REQUEST_METHOD']);
                echo json_encode(['success' => false, 'message' => 'Método no permitido']);
                return;
            }

            $cedula = trim($_POST['cedula'] ?? '');
            $password = trim($_POST['password'] ?? '');
            
            error_log("AuthController: Cedula recibida: " . $cedula);
            error_log("AuthController: Password longitud: " . strlen($password));

            if (empty($cedula) || empty($password)) {
                error_log("AuthController: Campos vacíos");
                echo json_encode(['success' => false, 'message' => 'Por favor complete todos los campos']);
                return;
            }

            $usuarioPath = 'app/models/Usuario.php';
            if (!file_exists($usuarioPath)) {
                error_log("AuthController: Usuario.php no encontrado en: " . $usuarioPath);
                error_log("AuthController: Directorio actual: " . getcwd());
                
                echo json_encode(['success' => false, 'message' => 'Modelo Usuario no encontrado']);
                return;
            }

            require_once $usuarioPath;
            error_log("AuthController: Usuario.php incluido correctamente.");

            $usuario = new Usuario();
            error_log("AuthController: Instancia Usuario creada.");
            
            $result = $usuario->login($cedula, $password);
            error_log("AuthController: Login ejecutado, resultado: " . json_encode($result));

            echo json_encode($result, JSON_UNESCAPED_UNICODE);

        } catch (Exception $e) {
            error_log("AuthController: Excepción capturada: " . $e->getMessage());
            error_log("AuthController: Archivo: " . $e->getFile() . " Línea: " . $e->getLine());
            
            if (ob_get_level()) {
                ob_clean();
            }
            
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode([
                'success' => false,
                'message' => 'Error del servidor: ' . $e->getMessage()
            ], JSON_UNESCAPED_UNICODE);
        }
    }

    public function register() {
        if (ob_get_level()) {
            ob_clean();
        }
        
        header('Content-Type: application/json; charset=utf-8');
        
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                echo json_encode(['success' => false, 'message' => 'Método no permitido']);
                return;
            }

            require_once 'app/models/Usuario.php';
            
            $usuario = new Usuario();
            
            $cedula = trim($_POST['cedula'] ?? '');
            $nombre = trim($_POST['nombre'] ?? '');
            $apellidos = trim($_POST['apellidos'] ?? '');
            $correo = trim($_POST['correo'] ?? '');
            $telefono = trim($_POST['telefono'] ?? '');
            $fecha_nacimiento = $_POST['fecha_nacimiento'] ?? '';
            $direccion = trim($_POST['direccion'] ?? '');
            $password = trim($_POST['password'] ?? '');
            $id_genero = intval($_POST['id_genero'] ?? 0);
            $id_estado_civil = intval($_POST['id_estado_civil'] ?? 0);
            $id_rol = intval($_POST['id_rol'] ?? 3);
            $id_estado = intval($_POST['id_estado'] ?? 1);

            if (empty($cedula) || empty($nombre) || empty($apellidos) || 
                empty($correo) || empty($password)) {
                echo json_encode(['success' => false, 'message' => 'Todos los campos obligatorios deben ser completados']);
                return;
            }

            $result = $usuario->registrar(
                $cedula, $nombre, $apellidos, $correo, $telefono,
                $fecha_nacimiento, $direccion, $password, 
                $id_genero, $id_estado_civil, $id_rol, $id_estado
            );

            if ($result) {
                echo json_encode(['success' => true, 'message' => 'Usuario registrado exitosamente']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Error al registrar usuario. Posiblemente la cédula o correo ya existen']);
            }

        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => 'Error del servidor: ' . $e->getMessage()
            ]);
        }
    }

    public function logout() {
        session_start();
        session_unset();
        session_destroy();
        
        if (isset($_COOKIE[session_name()])) {
            setcookie(session_name(), '', time() - 3600, '/');
        }
        
        header('Cache-Control: no-cache, no-store, must-revalidate');
        header('Pragma: no-cache');
        header('Expires: 0');
        
        header('Location: /sc502-3c2025-grupo3/Registro/Login.php?logout=1');
        exit;
    }
}
?>
