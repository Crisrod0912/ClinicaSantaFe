<?php
require_once 'app/models/Usuario.php';

class UsuarioController {
    public function create() {
        try {
            $usuario = new Usuario();
            $cedula = $_POST['cedula'] ?? '';
            $nombre = $_POST['nombre'] ?? '';
            $apellidos = $_POST['apellidos'] ?? '';
            $correo = $_POST['correo'] ?? '';
            $telefono = $_POST['telefono'] ?? '';
            $fecha_nacimiento = $_POST['fecha_nacimiento'] ?? null;
            $direccion = $_POST['direccion'] ?? '';
            $password = $_POST['password'] ?? '';
            $id_genero = $_POST['id_genero'] ?? null;
            $id_estado_civil = $_POST['id_estado_civil'] ?? null;
            $id_rol = $_POST['id_rol'] ?? '';
            $id_estado = $_POST['id_estado'] ?? 1;

            if (empty($cedula) || empty($nombre) || empty($apellidos) || empty($correo) || empty($direccion) || empty($password) || empty($id_rol)) {
                echo json_encode(['status' => 'error', 'message' => 'Todos los campos obligatorios son requeridos.']);
                return;
            }

            if ($usuario->registrar($cedula, $nombre, $apellidos, $correo, $telefono, $fecha_nacimiento, $direccion, $password, $id_genero, $id_estado_civil, $id_rol, $id_estado)) {
                echo json_encode(['status' => 'success', 'message' => 'Usuario registrado exitosamente.']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'No se pudo registrar el usuario. Verifique que la cédula o correo no estén duplicados.']);
            }
        } catch (Exception $e) {
            echo json_encode(['status' => 'error', 'message' => 'Error interno: ' . $e->getMessage()]);
        }
    }

    public function list() {
        try {
            $usuario = new Usuario();
            $usuarios = $usuario->obtenerTodos();

            echo json_encode(['status' => 'success', 'data' => $usuarios]);
        } catch (Exception $e) {
            echo json_encode(['status' => 'error', 'message' => 'Error al obtener usuarios: ' . $e->getMessage()]);
        }
    }

    public function show() {
        try {
            $usuario = new Usuario();
            $id = $_GET['id'] ?? 0;

            if (empty($id) || !is_numeric($id)) {
                echo json_encode(['status' => 'error', 'message' => 'ID de usuario inválido.']);
                return;
            }

            $item = $usuario->obtenerPorId($id);

            if ($item) {
                echo json_encode(['status' => 'success', 'data' => $item]);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Usuario no encontrado.']);
            }
        } catch (Exception $e) {
            echo json_encode(['status' => 'error', 'message' => 'Error al obtener usuario: ' . $e->getMessage()]);
        }
    }

    public function update() {
        try {
            $usuario = new Usuario();
            $id_usuario = $_POST['id_usuario'] ?? 0;
            $cedula = $_POST['cedula'] ?? '';
            $nombre = $_POST['nombre'] ?? '';
            $apellidos = $_POST['apellidos'] ?? '';
            $correo = $_POST['correo'] ?? '';
            $telefono = $_POST['telefono'] ?? '';
            $fecha_nacimiento = $_POST['fecha_nacimiento'] ?? null;
            $direccion = $_POST['direccion'] ?? '';
            $password = $_POST['password'] ?? '';
            $id_genero = $_POST['id_genero'] ?? null;
            $id_estado_civil = $_POST['id_estado_civil'] ?? null;
            $id_rol = $_POST['id_rol'] ?? '';
            $id_estado = $_POST['id_estado'] ?? 1;

            if (empty($id_usuario) || !is_numeric($id_usuario)) {
                echo json_encode(['status' => 'error', 'message' => 'ID de usuario inválido.']);
                return;
            }

            if (empty($cedula) || empty($nombre) || empty($apellidos) || empty($correo) || empty($direccion) || empty($id_rol)) {
                echo json_encode(['status' => 'error', 'message' => 'Todos los campos obligatorios son requeridos.']);
                return;
            }

            if ($usuario->actualizar($id_usuario, $cedula, $nombre, $apellidos, $correo, $telefono, $fecha_nacimiento, $direccion, $password, $id_genero, $id_estado_civil, $id_rol, $id_estado)) {
                echo json_encode(['status' => 'success', 'message' => 'Usuario actualizado exitosamente.']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'No se pudo actualizar el usuario. Verifique que el correo no esté duplicado.']);
            }
        } catch (Exception $e) {
            echo json_encode(['status' => 'error', 'message' => 'Error al actualizar usuario: ' . $e->getMessage()]);
        }
    }

    public function updateStatus() {
        try {
            $usuario = new Usuario();
            $id_usuario = $_POST['id_usuario'] ?? 0;
            $id_estado = $_POST['id_estado'] ?? 0;

            if (empty($id_usuario) || !is_numeric($id_usuario)) {
                echo json_encode(['status' => 'error', 'message' => 'ID de usuario inválido.']);
                return;
            }

            if (empty($id_estado) || !is_numeric($id_estado)) {
                echo json_encode(['status' => 'error', 'message' => 'Estado inválido.']);
                return;
            }

            if ($usuario->actualizarEstado($id_usuario, $id_estado)) {
                echo json_encode(['status' => 'success', 'message' => 'Estado del usuario actualizado.']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'No se pudo actualizar el estado.']);
            }
        } catch (Exception $e) {
            echo json_encode(['status' => 'error', 'message' => 'Error al actualizar estado: ' . $e->getMessage()]);
        }
    }

    public function delete() {
        try {
            $usuario = new Usuario();
            $id = $_POST['id'] ?? 0;

            if (empty($id) || !is_numeric($id)) {
                echo json_encode(['status' => 'error', 'message' => 'ID de usuario inválido.']);
                return;
            }

            if ($usuario->eliminar($id)) {
                echo json_encode(['status' => 'success', 'message' => 'Usuario eliminado exitosamente.']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'No se pudo eliminar el usuario. Puede que tenga registros asociados.']);
            }
        } catch (Exception $e) {
            echo json_encode(['status' => 'error', 'message' => 'Error al eliminar usuario: ' . $e->getMessage()]);
        }
    }

    public function getStates() {
        try {
            $usuario = new Usuario();
            $estados = $usuario->obtenerEstados();

            echo json_encode(['status' => 'success', 'data' => $estados]);
        } catch (Exception $e) {
            echo json_encode(['status' => 'error', 'message' => 'Error al obtener estados: ' . $e->getMessage()]);
        }
    }

    public function getRoles() {
        try {
            $usuario = new Usuario();
            $roles = $usuario->obtenerRoles();

            echo json_encode(['status' => 'success', 'data' => $roles]);
        } catch (Exception $e) {
            echo json_encode(['status' => 'error', 'message' => 'Error al obtener roles: ' . $e->getMessage()]);
        }
    }

    public function getGeneros() {
        try {
            $usuario = new Usuario();
            $generos = $usuario->obtenerGeneros();

            echo json_encode(['status' => 'success', 'data' => $generos]);
        } catch (Exception $e) {
            echo json_encode(['status' => 'error', 'message' => 'Error al obtener géneros: ' . $e->getMessage()]);
        }
    }
    
    public function getEstadosCiviles() {
        try {
            $usuario = new Usuario();
            $estados_civiles = $usuario->obtenerEstadosCiviles();

            echo json_encode(['status' => 'success', 'data' => $estados_civiles]);
        } catch (Exception $e) {
            echo json_encode(['status' => 'error', 'message' => 'Error al obtener estados civiles: ' . $e->getMessage()]);
        }
    }
}
?>
