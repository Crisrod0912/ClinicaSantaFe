<?php
require_once 'app/models/Rol.php';

class RolController {
    public function create() {
        try {
            $rol = new Rol();
            $nombre = $_POST['nombre'] ?? '';
            $descripcion = $_POST['descripcion'] ?? '';
            $id_estado = $_POST['id_estado'] ?? 1;

            if (empty($nombre) || empty($descripcion) || empty($id_estado)) {
                echo json_encode(['status' => 'error', 'message' => 'Todos los campos son obligatorios.']);
                return;
            }

            if ($rol->registrar($nombre, $descripcion, $id_estado)) {
                echo json_encode(['status' => 'success', 'message' => 'Rol registrado exitosamente.']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'No se pudo registrar el rol.']);
            }
        } catch (Exception $e) {
            echo json_encode(['status' => 'error', 'message' => 'Error interno: ' . $e->getMessage()]);
        }
    }

    public function list() {
        try {
            $rol = new Rol();
            $roles = $rol->obtenerTodos();

            echo json_encode(['status' => 'success', 'data' => $roles]);
        } catch (Exception $e) {
            echo json_encode(['status' => 'error', 'message' => 'Error al obtener roles: ' . $e->getMessage()]);
        }
    }

    public function show() {
        try {
            $rol = new Rol();
            $id = $_GET['id'] ?? 0;

            if (empty($id) || !is_numeric($id)) {
                echo json_encode(['status' => 'error', 'message' => 'ID de rol inválido.']);
                return;
            }

            $item = $rol->obtenerPorId($id);

            if ($item) {
                echo json_encode(['status' => 'success', 'data' => $item]);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Rol no encontrado.']);
            }
        } catch (Exception $e) {
            echo json_encode(['status' => 'error', 'message' => 'Error al obtener rol: ' . $e->getMessage()]);
        }
    }

    public function update() {
        try {
            $rol = new Rol();
            $id_rol = $_POST['id_rol'] ?? 0;
            $nombre = $_POST['nombre'] ?? '';
            $descripcion = $_POST['descripcion'] ?? '';
            $id_estado = $_POST['id_estado'] ?? 1;

            if (empty($id_rol) || !is_numeric($id_rol)) {
                echo json_encode(['status' => 'error', 'message' => 'ID de rol inválido.']);
                return;
            }

            if (empty($nombre) || empty($descripcion) || empty($id_estado)) {
                echo json_encode(['status' => 'error', 'message' => 'Todos los campos son obligatorios.']);
                return;
            }

            if ($rol->actualizar($id_rol, $nombre, $descripcion, $id_estado)) {
                echo json_encode(['status' => 'success', 'message' => 'Rol actualizado exitosamente.']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'No se pudo actualizar el rol.']);
            }
        } catch (Exception $e) {
            echo json_encode(['status' => 'error', 'message' => 'Error al actualizar rol: ' . $e->getMessage()]);
        }
    }

    public function updateStatus() {
        try {
            $rol = new Rol();
            $id_rol = $_POST['id_rol'] ?? 0;
            $id_estado = $_POST['id_estado'] ?? 0;

            if (empty($id_rol) || !is_numeric($id_rol)) {
                echo json_encode(['status' => 'error', 'message' => 'ID de rol inválido.']);
                return;
            }

            if (empty($id_estado) || !is_numeric($id_estado)) {
                echo json_encode(['status' => 'error', 'message' => 'Estado inválido.']);
                return;
            }

            if ($rol->actualizarEstado($id_rol, $id_estado)) {
                echo json_encode(['status' => 'success', 'message' => 'Estado del rol actualizado.']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'No se pudo actualizar el estado.']);
            }
        } catch (Exception $e) {
            echo json_encode(['status' => 'error', 'message' => 'Error al actualizar estado: ' . $e->getMessage()]);
        }
    }

    public function delete() {
        try {
            $rol = new Rol();
            $id = $_POST['id'] ?? 0;

            if (empty($id) || !is_numeric($id)) {
                echo json_encode(['status' => 'error', 'message' => 'ID de rol inválido.']);
                return;
            }

            if ($rol->eliminar($id)) {
                echo json_encode(['status' => 'success', 'message' => 'Rol eliminado exitosamente.']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'No se pudo eliminar el rol. Puede que tenga usuarios asignados.']);
            }
        } catch (Exception $e) {
            echo json_encode(['status' => 'error', 'message' => 'Error al eliminar rol: ' . $e->getMessage()]);
        }
    }

    public function getStates() {
        try {
            $rol = new Rol();
            $estados = $rol->obtenerEstados();

            echo json_encode(['status' => 'success', 'data' => $estados]);
        } catch (Exception $e) {
            echo json_encode(['status' => 'error', 'message' => 'Error al obtener estados: ' . $e->getMessage()]);
        }
    }
}
?>
