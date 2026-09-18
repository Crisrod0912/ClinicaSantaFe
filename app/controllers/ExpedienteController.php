<?php
require_once 'app/models/Expediente.php';

class ExpedienteController {
    
    public function show() {
        $expediente = new Expediente();
        $data = $expediente->obtenerPorUsuarioSesion();

        if ($data) {
            echo json_encode(['status' => 'success', 'data' => $data]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Expediente no encontrado']);
        }
    }

    public function showByUser() {
        $expediente = new Expediente();
        $id_usuario = $_GET['id_usuario'] ?? 0;

        if ($id_usuario > 0) {
            $data = $expediente->obtenerPorUsuario($id_usuario);
            if ($data) {
                echo json_encode(['status' => 'success', 'data' => $data]);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Expediente no encontrado']);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'ID de usuario requerido']);
        }
    }

    public function update() {
        $expediente = new Expediente();
        $correo = $_POST['correo'] ?? '';
        $telefono = $_POST['telefono'] ?? '';
        $estado_civil = $_POST['estado_civil'] ?? '';
        $fecha_nacimiento = $_POST['fecha_nacimiento'] ?? '';
        $genero = $_POST['genero'] ?? '';
        $direccion = $_POST['direccion'] ?? '';
        $peso = $_POST['peso'] ?? '';
        $altura = $_POST['altura'] ?? '';
        $tipo_sangre = $_POST['tipo_sangre'] ?? '';
        $enfermedades = $_POST['enfermedades'] ?? '';
        $alergias = $_POST['alergias'] ?? '';
        $cirugias = $_POST['cirugias'] ?? '';

        if ($expediente->actualizarExpediente($correo, $telefono, $estado_civil, $fecha_nacimiento, $genero, $direccion, $peso, $altura, $tipo_sangre, $enfermedades, $alergias, $cirugias)) {
            echo json_encode(['status' => 'success', 'message' => 'Expediente actualizado exitosamente']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'No se pudo actualizar el expediente']);
        }
    }

    public function searchPatient() {
        $expediente = new Expediente();
        $cedula = $_GET['cedula'] ?? '';

        if ($cedula) {
            $paciente = $expediente->buscarPacientePorCedula($cedula);
            
            if ($paciente) {
                echo json_encode(['status' => 'success', 'data' => $paciente]);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Paciente no encontrado']);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Cédula requerida']);
        }
    }

    public function list() {
        $expediente = new Expediente();
        $expedientes = $expediente->obtenerTodos();

        echo json_encode(['status' => 'success', 'data' => $expedientes]);
    }

    public function updateExpedienteAdmin() {
        $expediente = new Expediente();
        $id_usuario = $_POST['id_usuario'] ?? 0;
        $correo = $_POST['correo'] ?? '';
        $telefono = $_POST['telefono'] ?? '';
        $estado_civil = $_POST['estado_civil'] ?? '';
        $fecha_nacimiento = $_POST['fecha_nacimiento'] ?? '';
        $genero = $_POST['genero'] ?? '';
        $direccion = $_POST['direccion'] ?? '';
        $peso = $_POST['peso'] ?? '';
        $altura = $_POST['altura'] ?? '';
        $tipo_sangre = $_POST['tipo_sangre'] ?? '';
        $enfermedades = $_POST['enfermedades'] ?? '';
        $alergias = $_POST['alergias'] ?? '';
        $cirugias = $_POST['cirugias'] ?? '';

        if ($id_usuario > 0) {
            if ($expediente->actualizarExpedienteAdmin($id_usuario, $correo, $telefono, $estado_civil, $fecha_nacimiento, $genero, $direccion, $peso, $altura, $tipo_sangre, $enfermedades, $alergias, $cirugias)) {
                echo json_encode(['status' => 'success', 'message' => 'Expediente actualizado exitosamente']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'No se pudo actualizar el expediente']);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'ID de usuario requerido']);
        }
    }
}
?>
