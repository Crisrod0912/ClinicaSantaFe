<?php
require_once 'app/config/db.php';

class Usuario {
    private $db;

    public function __construct() {
        try {
            $this->db = Database::connect();
            error_log("Usuario: Conexión a base de datos establecida correctamente.");
        } catch (Exception $e) {
            error_log("Usuario: Error en constructor - " . $e->getMessage());
            throw $e;
        }
    }

    public function login($cedula, $password) {
        try {
            error_log("Usuario: Iniciando sesión para cédula: " . $cedula);
            
            if (!$this->db) {
                throw new Exception("No hay conexión establecida a base de datos.");
            }
            
            $stmt = $this->db->prepare("
                SELECT u.*, 
                       r.nombre as rol_nombre 
                FROM usuario u 
                INNER JOIN rol r ON u.id_rol = r.id_rol 
                WHERE u.cedula_usuario = ? AND u.id_estado = 1
            ");
            
            if (!$stmt) {
                error_log("Usuario: Error preparando statement: " . $this->db->error);
                throw new Exception("Error preparando consulta: " . $this->db->error);
            }
            
            $stmt->bind_param("s", $cedula);
            
            if (!$stmt->execute()) {
                error_log("Usuario: Error al ejecutar query: " . $stmt->error);
                throw new Exception("Error al ejecutar consulta: " . $stmt->error);
            }
            
            $result = $stmt->get_result()->fetch_assoc();
            error_log("Usuario: Resultado de consulta: " . ($result ? "Usuario encontrado" : "Usuario no encontrado"));

            if ($result) {
                $passwordMatch = false;
                
                error_log("Usuario: Verificando contraseña...");
                error_log("Usuario: Longitud de contraseña almacenada: " . strlen($result['contrasena']));
                
                if (strlen($result['contrasena']) > 20) {
                    $passwordMatch = password_verify($password, $result['contrasena']);
                    error_log("Usuario: Verificación con hash: " . ($passwordMatch ? "exitosa" : "fallida"));
                } else {
                    $passwordMatch = ($password === $result['contrasena']);
                    error_log("Usuario: Verificación de texto plano: " . ($passwordMatch ? "exitosa" : "fallida"));
                    
                    if ($passwordMatch) {
                        $this->updatePasswordHash($result['id_usuario'], $password);
                    }
                }

                if ($passwordMatch) {
                    if (session_status() === PHP_SESSION_NONE) {
                        session_start();
                    }
                    
                    $_SESSION['user'] = [
                        'id' => $result['id_usuario'],
                        'cedula' => $result['cedula_usuario'],
                        'nombre' => $result['nombre'],
                        'apellidos' => $result['apellidos'],
                        'correo' => $result['correo'],
                        'telefono' => $result['telefono'],
                        'rol' => $result['id_rol'],
                        'rol_nombre' => $result['rol_nombre'] ?? ''
                    ];

                    error_log("Usuario: Sesión iniciada para usuario ID: " . $result['id_usuario']);

                    $redirect = $this->getRedirectByRole($result['id_rol']);

                    return [
                        'success' => true,
                        'message' => 'Login exitoso',
                        'redirect' => $redirect,
                        'user' => $_SESSION['user']
                    ];
                } else {
                    error_log("Usuario: Contraseña incorrecta para cedula: " . $cedula);
                }
            } else {
                error_log("Usuario: Usuario no encontrado o inactivo para cedula: " . $cedula);
            }

            return [
                'success' => false,
                'message' => 'Credenciales incorrectas.'
            ];

        } catch (Exception $e) {
            error_log("Usuario: Error en login: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Error interno del servidor: ' . $e->getMessage()
            ];
        }
    }

    private function getRedirectByRole($rolId) {
        switch ($rolId) {
            case 1: 
                return '../Administrativo/inicioAdmin.html';
            case 2: 
                return '../Medicos/inicioMedico.html';
            case 3: 
                return '../Paciente/inicioPaciente.html';
            default:
                return '../index.php';
        }
    }

    private function updatePasswordHash($userId, $password) {
        try {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $this->db->prepare("UPDATE usuario SET contrasena = ? WHERE id_usuario = ?");
            $stmt->bind_param("si", $hashedPassword, $userId);
            $stmt->execute();
            error_log("Usuario: Contraseña hasheada actualizada para usuario ID: " . $userId);
        } catch (Exception $e) {
            error_log("Usuario: Error actualizando hash de contraseña: " . $e->getMessage());
        }
    }

    public function registrar($cedula, $nombre, $apellidos, $correo, $telefono, $fecha_nacimiento, $direccion, $password, $id_genero, $id_estado_civil, $id_rol, $id_estado) {
        try {
            if ($this->cedulaExists($cedula)) {
                return false;
            }

            if ($this->emailExists($correo)) {
                return false;
            }

            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $this->db->prepare("INSERT INTO usuario (cedula_usuario, nombre, apellidos, correo, telefono, fecha_nacimiento, direccion, contrasena, id_genero, id_estado_civil, id_rol, id_estado) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

            $stmt->bind_param("ssssssssiiii", 
                $cedula, $nombre, $apellidos, $correo, $telefono, $fecha_nacimiento,
                $direccion, $hashedPassword, $id_genero, $id_estado_civil, $id_rol, $id_estado
            );

            return $stmt->execute();

        } catch (Exception $e) {
            error_log("Error en registro: " . $e->getMessage());
            return false;
        }
    }

    public function obtenerTodos() {
        try {
            $stmt = $this->db->prepare("
                SELECT u.*, 
                       r.nombre as rol_nombre, 
                       e.nombre as estado_nombre,
                       g.nombre as genero_nombre, 
                       ec.nombre as estado_civil_nombre
                FROM usuario u 
                LEFT JOIN rol r ON u.id_rol = r.id_rol
                LEFT JOIN estado e ON u.id_estado = e.id_estado
                LEFT JOIN genero g ON u.id_genero = g.id_genero
                LEFT JOIN estado_civil ec ON u.id_estado_civil = ec.id_estado_civil
                ORDER BY u.nombre, u.apellidos
            ");
            $stmt->execute();
            return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        } catch (Exception $e) {
            error_log("Error al obtener usuarios: " . $e->getMessage());
            return [];
        }
    }

    private function cedulaExists($cedula) {
        $stmt = $this->db->prepare("SELECT id_usuario FROM usuario WHERE cedula_usuario = ?");
        $stmt->bind_param("s", $cedula);
        $stmt->execute();
        return $stmt->get_result()->num_rows > 0;
    }

    private function emailExists($email) {
        $stmt = $this->db->prepare("SELECT id_usuario FROM usuario WHERE correo = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        return $stmt->get_result()->num_rows > 0;
    }

    private function emailExistsForUpdate($email, $userId) {
        $stmt = $this->db->prepare("SELECT id_usuario FROM usuario WHERE correo = ? AND id_usuario != ?");
        $stmt->bind_param("si", $email, $userId);
        $stmt->execute();
        return $stmt->get_result()->num_rows > 0;
    }
}
?>
