<?php
require_once 'app/models/MedicoEspecialidad.php';

class MedicoEspecialidadController {

    public function create() {
        try {
            $medicoEspecialidad = new MedicoEspecialidad();
            $id_medico = $_POST['id_medico'] ?? 0;
            $id_especialidad = $_POST['id_especialidad'] ?? 0;
            $id_estado = $_POST['id_estado'] ?? 1;

            if (empty($id_medico) || empty($id_especialidad) || empty($id_estado)) {
                echo json_encode(['status' => 'error', 'message' => 'Todos los campos son obligatorios.']);
                return;
            }

            if (!is_numeric($id_medico) || !is_numeric($id_especialidad) || !is_numeric($id_estado)) {
                echo json_encode(['status' => 'error', 'message' => 'Los datos deben ser válidos.']);
                return;
            }

            if ($medicoEspecialidad->registrar($id_medico, $id_especialidad, $id_estado)) {
                echo json_encode(['status' => 'success', 'message' => 'Especialidad asignada exitosamente.']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Esta asignación ya existe o no se pudo crear.']);
            }
        } catch (Exception $e) {
            echo json_encode(['status' => 'error', 'message' => 'Error interno: ' . $e->getMessage()]);
        }
    }

    public function list() {
        try {
            $medicoEspecialidad = new MedicoEspecialidad();
            $asignaciones = $medicoEspecialidad->obtenerTodos();

            echo json_encode(['status' => 'success', 'data' => $asignaciones]);
        } catch (Exception $e) {
            echo json_encode(['status' => 'error', 'message' => 'Error al obtener asignaciones: ' . $e->getMessage()]);
        }
    }

    public function show() {
        try {
            $medicoEspecialidad = new MedicoEspecialidad();
            $id = $_GET['id'] ?? 0;

            if (empty($id) || !is_numeric($id)) {
                echo json_encode(['status' => 'error', 'message' => 'ID de asignación inválido.']);
                return;
            }

            $item = $medicoEspecialidad->obtenerPorId($id);

            if ($item) {
                echo json_encode(['status' => 'success', 'data' => $item]);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Asignación no encontrada.']);
            }
        } catch (Exception $e) {
            echo json_encode(['status' => 'error', 'message' => 'Error al obtener asignación: ' . $e->getMessage()]);
        }
    }

    public function update() {
        try {
            $medicoEspecialidad = new MedicoEspecialidad();
            $id_medico_especialidad = $_POST['id_medico_especialidad'] ?? 0;
            $id_medico = $_POST['id_medico'] ?? 0;
            $id_especialidad = $_POST['id_especialidad'] ?? 0;
            $id_estado = $_POST['id_estado'] ?? 1;

            if (empty($id_medico_especialidad) || !is_numeric($id_medico_especialidad)) {
                echo json_encode(['status' => 'error', 'message' => 'ID de asignación inválido.']);
                return;
            }

            if (empty($id_medico) || empty($id_especialidad) || empty($id_estado)) {
                echo json_encode(['status' => 'error', 'message' => 'Todos los campos son obligatorios.']);
                return;
            }

            if (!is_numeric($id_medico) || !is_numeric($id_especialidad) || !is_numeric($id_estado)) {
                echo json_encode(['status' => 'error', 'message' => 'Los datos deben ser válidos.']);
                return;
            }

            if ($medicoEspecialidad->actualizar($id_medico_especialidad, $id_medico, $id_especialidad, $id_estado)) {
                echo json_encode(['status' => 'success', 'message' => 'Asignación actualizada exitosamente.']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Esta asignación ya existe o no se pudo actualizar.']);
            }
        } catch (Exception $e) {
            echo json_encode(['status' => 'error', 'message' => 'Error al actualizar asignación: ' . $e->getMessage()]);
        }
    }

    public function updateStatus() {
        try {
            $medicoEspecialidad = new MedicoEspecialidad();

            $id_medico_especialidad = $_POST['id_medico_especialidad'] ?? 0;
            $id_estado = $_POST['id_estado'] ?? 0;

            if (empty($id_medico_especialidad) || !is_numeric($id_medico_especialidad)) {
                echo json_encode(['status' => 'error', 'message' => 'ID de asignación inválido.']);
                return;
            }

            if (empty($id_estado) || !is_numeric($id_estado)) {
                echo json_encode(['status' => 'error', 'message' => 'Estado inválido.']);
                return;
            }

            if ($medicoEspecialidad->actualizarEstado($id_medico_especialidad, $id_estado)) {
                echo json_encode(['status' => 'success', 'message' => 'Estado de la asignación actualizado.']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'No se pudo actualizar el estado.']);
            }
        } catch (Exception $e) {
            echo json_encode(['status' => 'error', 'message' => 'Error al actualizar estado: ' . $e->getMessage()]);
        }
    }

    public function delete() {
        try {
            $medicoEspecialidad = new MedicoEspecialidad();
            $id = $_POST['id'] ?? 0;

            if (empty($id) || !is_numeric($id)) {
                echo json_encode(['status' => 'error', 'message' => 'ID de asignación inválido.']);
                return;
            }

            if ($medicoEspecialidad->eliminar($id)) {
                echo json_encode(['status' => 'success', 'message' => 'Asignación eliminada exitosamente.']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'No se pudo eliminar la asignación.']);
            }
        } catch (Exception $e) {
            echo json_encode(['status' => 'error', 'message' => 'Error al eliminar asignación: ' . $e->getMessage()]);
        }
    }

    public function getMedicos() {
        try {
            $medicoEspecialidad = new MedicoEspecialidad();
            $medicos = $medicoEspecialidad->obtenerMedicos();

            echo json_encode(['status' => 'success', 'data' => $medicos]);
        } catch (Exception $e) {
            echo json_encode(['status' => 'error', 'message' => 'Error al obtener médicos: ' . $e->getMessage()]);
        }
    }

    public function getEspecialidades() {
        try {
            $medicoEspecialidad = new MedicoEspecialidad();
            $especialidades = $medicoEspecialidad->obtenerEspecialidades();

            echo json_encode(['status' => 'success', 'data' => $especialidades]);
        } catch (Exception $e) {
            echo json_encode(['status' => 'error', 'message' => 'Error al obtener especialidades: ' . $e->getMessage()]);
        }
    }

    public function getStates() {
        try {
            $medicoEspecialidad = new MedicoEspecialidad();
            $estados = $medicoEspecialidad->obtenerEstados();

            echo json_encode(['status' => 'success', 'data' => $estados]);
        } catch (Exception $e) {
            echo json_encode(['status' => 'error', 'message' => 'Error al obtener estados: ' . $e->getMessage()]);
        }
    }
    
    public function listMySpecialties() {
        try {
            $medicoEspecialidad = new MedicoEspecialidad();
            $especialidades = $medicoEspecialidad->obtenerEspecialidadesMedicoSesion();

            echo json_encode(['status' => 'success', 'data' => $especialidades]);
        } catch (Exception $e) {
            error_log("Error en listMySpecialties: " . $e->getMessage());
            echo json_encode(['status' => 'error', 'message' => 'Error al obtener tus especialidades.']);
        }
    }
}
?>
