<?php
require_once 'app/models/Cita.php';

class CitaController {

    public function create() {
        error_reporting(E_ALL);
        ini_set('log_errors', 1);
        
        error_log("=== INICIO CREATE CITA ADMIN ===");
        error_log("POST data: " . print_r($_POST, true));
        
        $cita = new Cita();

        $fecha = $_POST['fecha'] ?? '';
        $hora = $_POST['hora'] ?? '';
        $cedula_paciente = $_POST['cedula_paciente'] ?? '';
        $id_medico = $_POST['id_medico'] ?? 0;
        $id_servicio = $_POST['id_servicio'] ?? 0;
        $id_especialidad = $_POST['id_especialidad'] ?? 0;
        $id_estado = $_POST['id_estado'] ?? 3;

        if (empty($fecha) || empty($hora) || empty($cedula_paciente) || 
            empty($id_servicio) || empty($id_especialidad)) {
            error_log("ERROR: Campos obligatorios vacíos");
            error_log("Fecha: '$fecha', Hora: '$hora', Cedula: '$cedula_paciente'");
            error_log("Servicio: '$id_servicio', Especialidad: '$id_especialidad'");
            echo json_encode(['status' => 'error', 'message' => 'Todos los campos son obligatorios']);
            return;
        }

        error_log("Buscando paciente con cédula: " . $cedula_paciente);
        $paciente = $cita->buscarPacientePorCedula($cedula_paciente);
        
        if (!$paciente) {
            error_log("ERROR: Paciente no encontrado con cédula: " . $cedula_paciente);
            echo json_encode(['status' => 'error', 'message' => 'Paciente no encontrado']);
            return;
        }
        
        error_log("Paciente encontrado: " . print_r($paciente, true));

        error_log("Buscando médico disponible para especialidad: $id_especialidad, fecha: $fecha, hora: $hora");
        $medico_disponible = $cita->buscarMedicoDisponible($id_especialidad, $fecha, $hora);
        
        if (!$medico_disponible) {
            error_log("ERROR: No hay médicos disponibles");
            echo json_encode(['status' => 'error', 'message' => 'No hay médicos disponibles en este horario']);
            return;
        }
        
        error_log("Médico disponible encontrado: " . $medico_disponible);

        error_log("Intentando registrar cita con datos:");
        error_log("Fecha: $fecha, Hora: $hora, Paciente: $cedula_paciente");
        error_log("Médico: $medico_disponible, Servicio: $id_servicio, Especialidad: $id_especialidad");
        
        $resultado = $cita->registrarCitaAdmin($fecha, $hora, $cedula_paciente, $medico_disponible, $id_servicio, $id_especialidad, $id_estado);
        
        if ($resultado) {
            error_log("SUCCESS: Cita registrada exitosamente");
            echo json_encode(['status' => 'success', 'message' => 'Cita registrada exitosamente']);
        } else {
            error_log("ERROR: No se pudo registrar la cita - método retornó false");
            echo json_encode(['status' => 'error', 'message' => 'No se pudo registrar la cita - Error en base de datos']);
        }
        
        error_log("=== FIN CREATE CITA ADMIN ===");
    }

    public function createForPatient() {
        error_reporting(E_ALL);
        ini_set('log_errors', 1);
        
        error_log("=== INICIO CREATE CITA PACIENTE ===");
        error_log("POST data: " . print_r($_POST, true));
        
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        error_log("Session data: " . print_r($_SESSION, true));
        
        if (!isset($_SESSION['user']['id'])) {
            error_log("ERROR: No hay sesión activa");
            echo json_encode(['status' => 'error', 'message' => 'Sesión no iniciada']);
            return;
        }

        $cita = new Cita();

        $fecha = $_POST['fecha'] ?? '';
        $hora = $_POST['hora'] ?? '';
        $id_servicio = $_POST['id_servicio'] ?? 0;
        $id_especialidad = $_POST['id_especialidad'] ?? 0;
        $id_estado = $_POST['id_estado'] ?? 3;

        if (empty($fecha) || empty($hora) || empty($id_servicio) || empty($id_especialidad)) {
            error_log("ERROR: Campos obligatorios vacíos");
            error_log("Fecha: '$fecha', Hora: '$hora'");
            error_log("Servicio: '$id_servicio', Especialidad: '$id_especialidad'");
            echo json_encode(['status' => 'error', 'message' => 'Todos los campos son obligatorios']);
            return;
        }

        error_log("Buscando médico disponible para especialidad: $id_especialidad, fecha: $fecha, hora: $hora");
        $medico_disponible = $cita->buscarMedicoDisponible($id_especialidad, $fecha, $hora);
        
        if (!$medico_disponible) {
            error_log("ERROR: No hay médicos disponibles");
            echo json_encode(['status' => 'error', 'message' => 'No hay médicos disponibles en este horario']);
            return;
        }
        
        error_log("Médico disponible encontrado: " . $medico_disponible);

        error_log("Intentando registrar cita para paciente con datos:");
        error_log("Usuario ID: " . $_SESSION['user']['id'] . ", Fecha: $fecha, Hora: $hora");
        error_log("Servicio: $id_servicio, Especialidad: $id_especialidad");
        
        $resultado = $cita->registrarCitaPaciente($fecha, $hora, $id_servicio, $id_especialidad, $id_estado);

        if ($resultado) {
            error_log("SUCCESS: Cita registrada exitosamente.");
            echo json_encode(['status' => 'success', 'message' => 'Cita registrada exitosamente']);
        } else {
            error_log("ERROR: No se pudo registrar la cita - método retornó false");
            echo json_encode(['status' => 'error', 'message' => 'No se pudo registrar la cita o no hay médicos disponibles']);
        }
        
        error_log("=== FIN CREATE CITA PACIENTE ===");
    }

    public function list() {
        $cita = new Cita();
        $citas = $cita->obtenerTodas();

        echo json_encode(['status' => 'success', 'data' => $citas]);
    }

    public function listByUser() {
        $cita = new Cita();
        $id_usuario = $_GET['id_usuario'] ?? 0;

        if ($id_usuario > 0) {
            $citas = $cita->obtenerPorUsuario($id_usuario);
            echo json_encode(['status' => 'success', 'data' => $citas]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'ID de usuario requerido']);
        }
    }

    public function listMyAppointmentsAsDoctor() {
        if (ob_get_level()) {
            ob_clean();
        }
        
        header('Content-Type: application/json; charset=utf-8');
        
        error_log("=== INICIO listMyAppointmentsAsDoctor ===");
        
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        if (!isset($_SESSION['user']['id'])) {
            error_log("ERROR: No hay ID de usuario en sesión.");
            echo json_encode(['status' => 'error', 'message' => 'Sesión no iniciada']);
            exit;
        }
        
        $user_role = $_SESSION['user']['id_rol'] ?? $_SESSION['user']['rol'] ?? null;
        error_log("Rol del usuario: " . $user_role);
        
        if ($user_role != 2) {
            error_log("ERROR: Usuario no es médico. Rol actual: " . $user_role);
            echo json_encode(['status' => 'error', 'message' => 'Acceso denegado - Solo médicos']);
            exit;
        }
        
        try {
            $cita = new Cita();
            $id_medico = $_SESSION['user']['id'];
            $fecha = $_GET['fecha'] ?? null;
            
            error_log("Buscando citas para médico ID: " . $id_medico);
            
            $citas = $cita->obtenerCitasMedicoSimple($id_medico, $fecha);
            
            error_log("Citas encontradas: " . count($citas));
            
            echo json_encode([
                'status' => 'success', 
                'data' => $citas,
                'debug' => [
                    'medico_id' => $id_medico,
                    'total_citas' => count($citas)
                ]
            ]);
            exit;
            
        } catch (Exception $e) {
            error_log("ERROR: Excepción: " . $e->getMessage());
            echo json_encode(['status' => 'error', 'message' => 'Error interno: ' . $e->getMessage()]);
            exit;
        }
    }

    public function show() {
        $cita = new Cita();
        $id = $_GET['id'] ?? 0;

        $item = $cita->obtenerPorId($id);

        if ($item) {
            echo json_encode(['status' => 'success', 'data' => $item]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Cita no encontrada']);
        }
    }

    public function update() {
        $cita = new Cita();

        $id_cita = $_POST['id_cita'] ?? 0;
        $fecha = $_POST['fecha'] ?? '';
        $hora = $_POST['hora'] ?? '';
        $id_servicio = $_POST['id_servicio'] ?? 0;
        $id_especialidad = $_POST['id_especialidad'] ?? 0;
        $id_estado = $_POST['id_estado'] ?? 3;

        $id_medico = $cita->buscarMedicoDisponible($id_especialidad, $fecha, $hora);
        
        if (!$id_medico) {
            echo json_encode(['status' => 'error', 'message' => 'No hay médicos disponibles para esta fecha y hora']);
            return;
        }

        if ($cita->actualizar($id_cita, $fecha, $hora, $id_medico, $id_servicio, $id_especialidad, $id_estado)) {
            echo json_encode(['status' => 'success', 'message' => 'Cita actualizada exitosamente']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'No se pudo actualizar la cita']);
        }
    }

    public function updateCitaAdmin() {
        error_reporting(E_ALL);
        ini_set('display_errors', 0);
        ini_set('log_errors', 1);
        
        header('Content-Type: application/json');
        
        try {
            error_log("=== INICIO UPDATE CITA ADMIN ===");
            error_log("POST data: " . print_r($_POST, true));
            
            $cita = new Cita();

            $required_fields = ['id_cita', 'fecha', 'hora', 'cedula_paciente', 'id_servicio', 'id_especialidad'];
            $missing_fields = [];
            
            foreach ($required_fields as $field) {
                if (!isset($_POST[$field]) || $_POST[$field] === '') {
                    $missing_fields[] = $field;
                }
            }
            
            if (!empty($missing_fields)) {
                error_log("ERROR: Campos obligatorios faltantes: " . implode(', ', $missing_fields));
                echo json_encode(['status' => 'error', 'message' => 'Campos obligatorios faltantes: ' . implode(', ', $missing_fields)]);
                return;
            }
            
            $id_cita = intval($_POST['id_cita']);
            $fecha = $_POST['fecha'];
            $hora = $_POST['hora'];
            $cedula_paciente = $_POST['cedula_paciente'];
            $id_servicio = intval($_POST['id_servicio']);
            $id_especialidad = intval($_POST['id_especialidad']);
            $id_estado = intval($_POST['id_estado'] ?? 3);

            $citaExistente = $cita->obtenerPorId($id_cita);
            if (!$citaExistente) {
                error_log("ERROR: Cita no encontrada con ID: " . $id_cita);
                echo json_encode(['status' => 'error', 'message' => 'Cita no encontrada']);
                return;
            }

            error_log("Buscando paciente con cédula: " . $cedula_paciente);
            $paciente = $cita->buscarPacientePorCedula($cedula_paciente);
            if (!$paciente) {
                error_log("ERROR: Paciente no encontrado con cédula: " . $cedula_paciente);
                echo json_encode(['status' => 'error', 'message' => 'Paciente no encontrado con la cédula proporcionada']);
                return;
            }

            $id_usuario = $paciente['id_usuario'];
            error_log("Paciente encontrado - ID: " . $id_usuario . ", Nombre: " . $paciente['nombre_completo']);

            error_log("Buscando médico disponible para especialidad: $id_especialidad, fecha: $fecha, hora: $hora");
            $id_medico = $cita->buscarMedicoDisponible($id_especialidad, $fecha, $hora);
            
            if (!$id_medico) {
                error_log("ERROR: No hay médicos disponibles para los parámetros dados");
                echo json_encode(['status' => 'error', 'message' => 'No hay médicos disponibles para esta fecha, hora y especialidad']);
                return;
            }
            
            error_log("Médico disponible encontrado - ID: " . $id_medico);

            error_log("Intentando actualizar cita con datos:");
            error_log("ID Cita: $id_cita, Fecha: $fecha, Hora: $hora");
            error_log("Usuario: $id_usuario, Médico: $id_medico, Servicio: $id_servicio, Especialidad: $id_especialidad, Estado: $id_estado");
            
            $resultado = $cita->actualizarCompleta($id_cita, $fecha, $hora, $id_usuario, $id_medico, $id_servicio, $id_especialidad, $id_estado);
            
            if ($resultado) {
                error_log("SUCCESS: Cita actualizada exitosamente");
                echo json_encode(['status' => 'success', 'message' => 'Cita actualizada exitosamente']);
            } else {
                error_log("ERROR: No se pudo actualizar la cita - método retornó false");
                echo json_encode(['status' => 'error', 'message' => 'No se pudo actualizar la cita. Error en base de datos']);
            }
            
        } catch (Exception $e) {
            error_log("ERROR: Excepción en updateCitaAdmin: " . $e->getMessage());
            error_log("Stack trace: " . $e->getTraceAsString());
            echo json_encode(['status' => 'error', 'message' => 'Error interno del servidor: ' . $e->getMessage()]);
        } catch (Error $e) {
            error_log("ERROR: Error fatal en updateCitaAdmin: " . $e->getMessage());
            error_log("Stack trace: " . $e->getTraceAsString());
            echo json_encode(['status' => 'error', 'message' => 'Error fatal del servidor: ' . $e->getMessage()]);
        }
        
        error_log("=== FIN UPDATE CITA ADMIN ===");
    }

    public function updateStatus() {
        $cita = new Cita();

        $id_cita = $_POST['id_cita'] ?? 0;
        $id_estado = $_POST['id_estado'] ?? 0;

        if ($cita->actualizarEstado($id_cita, $id_estado)) {
            echo json_encode(['status' => 'success', 'message' => 'Estado de cita actualizado']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'No se pudo actualizar el estado']);
        }
    }

    public function delete() {
        $cita = new Cita();
        $id = $_POST['id'] ?? 0;

        if ($cita->eliminar($id)) {
            echo json_encode(['status' => 'success', 'message' => 'Cita eliminada exitosamente']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'No se pudo eliminar la cita']);
        }
    }

    public function checkAvailability() {
        $cita = new Cita();
        
        $fecha = $_GET['fecha'] ?? '';
        $hora = $_GET['hora'] ?? '';
        $id_medico = $_GET['id_medico'] ?? 0;

        $disponible = $cita->verificarDisponibilidad($fecha, $hora, $id_medico);

        echo json_encode([
            'status' => 'success', 
            'disponible' => $disponible,
            'message' => $disponible ? 'Horario disponible' : 'Horario no disponible'
        ]);
    }

    public function getByDate() {
        $cita = new Cita();
        $fecha = $_GET['fecha'] ?? '';

        if ($fecha) {
            $citas = $cita->obtenerPorFecha($fecha);
            echo json_encode(['status' => 'success', 'data' => $citas]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Fecha requerida']);
        }
    }

    public function getAvailableDoctors() {
        $cita = new Cita();
        
        $id_especialidad = $_GET['id_especialidad'] ?? 0;
        $fecha = $_GET['fecha'] ?? '';
        $hora = $_GET['hora'] ?? '';

        if ($id_especialidad && $fecha && $hora) {
            $medicos = $cita->obtenerMedicosDisponibles($id_especialidad, $fecha, $hora);
            echo json_encode(['status' => 'success', 'data' => $medicos]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Especialidad, fecha y hora requeridos']);
        }
    }

    public function searchPatient() {
        error_log("=== SEARCH PATIENT ===");
        error_log("GET data: " . print_r($_GET, true));
        
        $cita = new Cita();
        $cedula = $_GET['cedula'] ?? '';

        if ($cedula) {
            error_log("Buscando paciente con cédula: " . $cedula);
            $paciente = $cita->buscarPacientePorCedula($cedula);
            
            if ($paciente) {
                error_log("Paciente encontrado: " . print_r($paciente, true));
                echo json_encode(['status' => 'success', 'data' => $paciente]);
            } else {
                error_log("Paciente no encontrado");
                echo json_encode(['status' => 'error', 'message' => 'Paciente no encontrado']);
            }
        } else {
            error_log("ERROR: Cédula no proporcionada");
            echo json_encode(['status' => 'error', 'message' => 'Cédula requerida']);
        }
    }

    public function getSpecialties() {
        error_log("=== GET SPECIALTIES ===");
        
        $cita = new Cita();
        $especialidades = $cita->obtenerEspecialidades();

        error_log("Especialidades encontradas: " . count($especialidades));
        error_log("Especialidades: " . print_r($especialidades, true));

        echo json_encode(['status' => 'success', 'data' => $especialidades]);
    }

    public function getServices() {
        error_log("=== GET SERVICES ===");
        
        $cita = new Cita();
        $servicios = $cita->obtenerServicios();

        error_log("Servicios encontrados: " . count($servicios));
        error_log("Servicios: " . print_r($servicios, true));

        echo json_encode(['status' => 'success', 'data' => $servicios]);
    }

    public function listMyAppointments() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        if (!isset($_SESSION['user']['id'])) {
            echo json_encode(['status' => 'error', 'message' => 'Sesión no iniciada']);
            return;
        }
        
        $cita = new Cita();
        $citas = $cita->obtenerPorUsuario($_SESSION['user']['id']);
        echo json_encode(['status' => 'success', 'data' => $citas]);
    }
}
?>
