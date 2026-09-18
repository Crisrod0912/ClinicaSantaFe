<?php
require_once 'app/config/db.php';

class Medicamento {
    private $db;

    public function __construct() {
        $this-> = Database::connect();
    }

    public function asignarMedicamento($nombre_completo, $fecha_preescripcion, $tiempo_tratamiento, $indicaciones, $id_medicamento, $id_paciente, $id_estado = 1) {
        try {
            $stmt = $this->db->prepare("INSERT INTO medicamento_paciente (nombre_completo, fecha_preescripcion, tiempo_tratamiento, indicaciones, id_medicamento, id_paciente, id_estado) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssiii", $nombre_completo, $fecha_preescripcion, $tiempo_tratamiento, $indicaciones, $id_medicamento, $id_paciente, $id_estado);
            return $stmt->execute();
        } catch (Exception $e) {
            error_log("Error en asignarMedicamento: " . $e->getMessage());
            return false;
        }
    }

    public function obtenerTodos() {
        try {
            $stmt = $this->db->prepare("
                SELECT m.*, 
                       ff.nombre as forma_farmaceutica,
                       gt.nombre as grupo_terapeutico,
                       va.nombre as via_administracion,
                       e.nombre as estado
                FROM medicamento m
                LEFT JOIN forma_farmaceutica ff ON m.id_forma_farmaceutica = ff.id_forma_farmaceutica
                LEFT JOIN grupo_terapeutico gt ON m.id_grupo_terapeutico = gt.id_grupo_farmaceutico
                LEFT JOIN via_administracion va ON m.id_via_administracion = va.id_via_administracion
                LEFT JOIN estado e ON m.id_estado = e.id_estado
                ORDER BY m.nombre ASC
            ");
            $stmt->execute();
            return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        } catch (Exception $e) {
            error_log("Error en obtenerTodos: " . $e->getMessage());
            return [];
        }
    }

    public function obtenerMedicamentosPacienteSesion() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        if (!isset($_SESSION['user']['id'])) {
            return [];
        }
        
        return $this->obtenerMisMedicamentos($_SESSION['user']['id']);
    }

    public function obtenerMedicamentosPaciente($id_paciente) {
        try {
            $stmt = $this->db->prepare("
                SELECT mp.*, 
                    m.nombre as nombre_medicamento,
                    ff.nombre as forma_farmaceutica,
                    gt.nombre as grupo_terapeutico,
                    va.nombre as via_administracion,
                    e.nombre as estado,
                    u.cedula_usuario,
                    CONCAT(u.nombre, ' ', u.apellidos) as nombre_paciente
                FROM medicamento_paciente mp
                INNER JOIN medicamento m ON mp.id_medicamento = m.id_medicamento
                INNER JOIN usuario u ON mp.id_paciente = u.id_usuario
                LEFT JOIN forma_farmaceutica ff ON m.id_forma_farmaceutica = ff.id_forma_farmaceutica
                LEFT JOIN grupo_terapeutico gt ON m.id_grupo_terapeutico = gt.id_grupo_farmaceutico
                LEFT JOIN via_administracion va ON m.id_via_administracion = va.id_via_administracion
                LEFT JOIN estado e ON mp.id_estado = e.id_estado
                WHERE mp.id_paciente = ?
                ORDER BY mp.fecha_preescripcion DESC
            ");
            $stmt->bind_param("i", $id_paciente);
            $stmt->execute();
            return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        } catch (Exception $e) {
            error_log("Error en obtenerMedicamentosPaciente: " . $e->getMessage());
            return [];
        }
    }

    public function obtenerMisMedicamentos($id_paciente) {
        try {
            $stmt = $this->db->prepare("
                SELECT mp.id_medicamento_paciente,
                    mp.fecha_preescripcion,
                    mp.tiempo_tratamiento, 
                    mp.indicaciones,
                    mp.id_estado,
                    mp.id_medicamento,
                    mp.id_paciente,
                    m.nombre as nombre_medicamento,
                    ff.nombre as forma_farmaceutica,
                    gt.nombre as grupo_terapeutico,
                    va.nombre as via_administracion,
                    e.nombre as estado
                FROM medicamento_paciente mp
                INNER JOIN medicamento m ON mp.id_medicamento = m.id_medicamento
                LEFT JOIN forma_farmaceutica ff ON m.id_forma_farmaceutica = ff.id_forma_farmaceutica
                LEFT JOIN grupo_terapeutico gt ON m.id_grupo_terapeutico = gt.id_grupo_farmaceutico
                LEFT JOIN via_administracion va ON m.id_via_administracion = va.id_via_administracion
                LEFT JOIN estado e ON mp.id_estado = e.id_estado
                WHERE mp.id_paciente = ?
                ORDER BY mp.fecha_preescripcion DESC
            ");
            $stmt->bind_param("i", $id_paciente);
            $stmt->execute();
            return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        } catch (Exception $e) {
            error_log("Error en obtenerMisMedicamentos: " . $e->getMessage());
            return [];
        }
    }

    public function obtenerMedicamentoPacientePorId($id_medicamento_paciente) {
        try {
            $stmt = $this->db->prepare("
                SELECT mp.*, 
                    m.nombre as nombre_medicamento,
                    ff.nombre as forma_farmaceutica,
                    gt.nombre as grupo_terapeutico,
                    va.nombre as via_administracion,
                    u.cedula_usuario,
                    CONCAT(u.nombre, ' ', u.apellidos) as nombre_paciente
                FROM medicamento_paciente mp
                INNER JOIN medicamento m ON mp.id_medicamento = m.id_medicamento
                INNER JOIN usuario u ON mp.id_paciente = u.id_usuario
                LEFT JOIN forma_farmaceutica ff ON m.id_forma_farmaceutica = ff.id_forma_farmaceutica
                LEFT JOIN grupo_terapeutico gt ON m.id_grupo_terapeutico = gt.id_grupo_farmaceutico
                LEFT JOIN via_administracion va ON m.id_via_administracion = va.id_via_administracion
                WHERE mp.id_medicamento_paciente = ?
            ");
            $stmt->bind_param("i", $id_medicamento_paciente);
            $stmt->execute();
            return $stmt->get_result()->fetch_assoc();
        } catch (Exception $e) {
            error_log("Error en obtenerMedicamentoPacientePorId: " . $e->getMessage());
            return null;
        }
    }

    public function actualizarMedicamentoPaciente($id_medicamento_paciente, $nombre_completo, $fecha_preescripcion, $tiempo_tratamiento, $indicaciones, $id_medicamento, $id_estado) {
        try {
            $stmt = $this->db->prepare("UPDATE medicamento_paciente SET nombre_completo = ?, fecha_preescripcion = ?, tiempo_tratamiento = ?, indicaciones = ?, id_medicamento = ?, id_estado = ? WHERE id_medicamento_paciente = ?");
            
            $stmt->bind_param("ssssiii", $nombre_completo, $fecha_preescripcion, $tiempo_tratamiento, $indicaciones, $id_medicamento, $id_estado, $id_medicamento_paciente);
            
            return $stmt->execute() && $stmt->affected_rows > 0;
        } catch (Exception $e) {
            error_log("Error en actualizarMedicamentoPaciente: " . $e->getMessage());
            return false;
        }
    }

    public function actualizarEstadoMedicamentoPaciente($id_medicamento_paciente, $id_estado) {
        try {
            $stmt = $this->db->prepare("UPDATE medicamento_paciente SET id_estado = ? WHERE id_medicamento_paciente = ?");
            $stmt->bind_param("ii", $id_estado, $id_medicamento_paciente);
            return $stmt->execute() && $stmt->affected_rows > 0;
        } catch (Exception $e) {
            error_log("Error en actualizarEstadoMedicamentoPaciente: " . $e->getMessage());
            return false;
        }
    }

    public function eliminarMedicamentoPaciente($id_medicamento_paciente) {
        try {
            $stmt = $this->db->prepare("DELETE FROM medicamento_paciente WHERE id_medicamento_paciente = ?");
            $stmt->bind_param("i", $id_medicamento_paciente);
            return $stmt->execute() && $stmt->affected_rows > 0;
        } catch (Exception $e) {
            error_log("Error en eliminarMedicamentoPaciente: " . $e->getMessage());
            return false;
        }
    }

    public function obtenerMedicamentosActivosPaciente($id_paciente) {
        try {
            $stmt = $this->db->prepare("
                SELECT mp.id_medicamento_paciente,
                    mp.fecha_preescripcion,
                    mp.tiempo_tratamiento, 
                    mp.indicaciones,
                    mp.id_estado,
                    mp.id_medicamento,
                    mp.id_paciente,
                    m.nombre as nombre_medicamento,
                    ff.nombre as forma_farmaceutica,
                    gt.nombre as grupo_terapeutico,
                    va.nombre as via_administracion
                FROM medicamento_paciente mp
                INNER JOIN medicamento m ON mp.id_medicamento = m.id_medicamento
                LEFT JOIN forma_farmaceutica ff ON m.id_forma_farmaceutica = ff.id_forma_farmaceutica
                LEFT JOIN grupo_terapeutico gt ON m.id_grupo_terapeutico = gt.id_grupo_farmaceutico
                LEFT JOIN via_administracion va ON m.id_via_administracion = va.id_via_administracion
                WHERE mp.id_paciente = ? AND mp.id_estado = 1
                ORDER BY mp.fecha_preescripcion DESC
            ");
            $stmt->bind_param("i", $id_paciente);
            $stmt->execute();
            return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        } catch (Exception $e) {
            error_log("Error en obtenerMedicamentosActivosPaciente: " . $e->getMessage());
            return [];
        }
    }

    public function verificarMedicamentoAsignado($id_medicamento, $id_paciente) {
        try {
            $stmt = $this->db->prepare("SELECT COUNT(*) as total FROM medicamento_paciente WHERE id_medicamento = ? AND id_paciente = ? AND id_estado = 1");
            $stmt->bind_param("ii", $id_medicamento, $id_paciente);
            $stmt->execute();
            $result = $stmt->get_result()->fetch_assoc();
            return $result['total'] > 0;
        } catch (Exception $e) {
            error_log("Error en verificarMedicamentoAsignado: " . $e->getMessage());
            return false;
        }
    }

    public function buscarPacientePorCedula($cedula) {
        try {
            $stmt = $this->db->prepare("SELECT u.id_usuario, u.cedula_usuario, CONCAT(u.nombre, ' ', u.apellidos) as nombre_completo FROM usuario u WHERE u.cedula_usuario = ? AND u.id_rol = 3 AND u.id_estado = 1");
            $stmt->bind_param("s", $cedula);
            $stmt->execute();
            return $stmt->get_result()->fetch_assoc();
        } catch (Exception $e) {
            error_log("Error en buscarPacientePorCedula: " . $e->getMessage());
            return null;
        }
    }

    public function crear($nombre, $id_forma_farmaceutica, $id_grupo_terapeutico, $id_via_administracion, $id_estado = 1) {
        try {
            $stmt = $this->db->prepare("INSERT INTO medicamento (nombre, id_forma_farmaceutica, id_grupo_terapeutico, id_via_administracion, id_estado) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("siiii", $nombre, $id_forma_farmaceutica, $id_grupo_terapeutico, $id_via_administracion, $id_estado);
            return $stmt->execute();
        } catch (Exception $e) {
            error_log("Error en crear medicamento: " . $e->getMessage());
            return false;
        }
    }

    public function obtenerPorId($id_medicamento) {
        try {
            $stmt = $this->db->prepare("
                SELECT m.*, 
                       ff.nombre as forma_farmaceutica,
                       gt.nombre as grupo_terapeutico,
                       va.nombre as via_administracion,
                       e.nombre as estado
                FROM medicamento m
                LEFT JOIN forma_farmaceutica ff ON m.id_forma_farmaceutica = ff.id_forma_farmaceutica
                LEFT JOIN grupo_terapeutico gt ON m.id_grupo_terapeutico = gt.id_grupo_farmaceutico
                LEFT JOIN via_administracion va ON m.id_via_administracion = va.id_via_administracion
                LEFT JOIN estado e ON m.id_estado = e.id_estado
                WHERE m.id_medicamento = ?
            ");
            $stmt->bind_param("i", $id_medicamento);
            $stmt->execute();
            return $stmt->get_result()->fetch_assoc();
        } catch (Exception $e) {
            error_log("Error en obtenerPorId: " . $e->getMessage());
            return null;
        }
    }

    public function actualizar($id_medicamento, $nombre, $id_forma_farmaceutica, $id_grupo_terapeutico, $id_via_administracion, $id_estado) {
        try {
            $stmt = $this->db->prepare("UPDATE medicamento SET nombre = ?, id_forma_farmaceutica = ?, id_grupo_terapeutico = ?, id_via_administracion = ?, id_estado = ? WHERE id_medicamento = ?");
            $stmt->bind_param("siiiii", $nombre, $id_forma_farmaceutica, $id_grupo_terapeutico, $id_via_administracion, $id_estado, $id_medicamento);
            return $stmt->execute();
        } catch (Exception $e) {
            error_log("Error en actualizar medicamento: " . $e->getMessage());
            return false;
        }
    }

    public function actualizarEstado($id_medicamento, $id_estado) {
        try {
            $stmt = $this->db->prepare("UPDATE medicamento SET id_estado = ? WHERE id_medicamento = ?");
            $stmt->bind_param("ii", $id_estado, $id_medicamento);
            return $stmt->execute();
        } catch (Exception $e) {
            error_log("Error en actualizarEstado medicamento: " . $e->getMessage());
            return false;
        }
    }

    public function eliminar($id_medicamento) {
        try {
            $stmt = $this->db->prepare("DELETE FROM medicamento WHERE id_medicamento = ?");
            $stmt->bind_param("i", $id_medicamento);
            return $stmt->execute();
        } catch (Exception $e) {
            error_log("Error en eliminar medicamento: " . $e->getMessage());
            return false;
        }
    }

    public function buscar($termino) {
        try {
            $termino = "%$termino%";
            $stmt = $this->db->prepare("
                SELECT m.*, 
                       ff.nombre as forma_farmaceutica,
                       gt.nombre as grupo_terapeutico,
                       va.nombre as via_administracion,
                       e.nombre as estado
                FROM medicamento m
                LEFT JOIN forma_farmaceutica ff ON m.id_forma_farmaceutica = ff.id_forma_farmaceutica
                LEFT JOIN grupo_terapeutico gt ON m.id_grupo_terapeutico = gt.id_grupo_farmaceutico
                LEFT JOIN via_administracion va ON m.id_via_administracion = va.id_via_administracion
                LEFT JOIN estado e ON m.id_estado = e.id_estado
                WHERE m.nombre LIKE ? OR ff.nombre LIKE ? OR gt.nombre LIKE ? OR va.nombre LIKE ?
                ORDER BY m.nombre ASC
            ");
            $stmt->bind_param("ssss", $termino, $termino, $termino, $termino);
            $stmt->execute();
            return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        } catch (Exception $e) {
            error_log("Error en buscar: " . $e->getMessage());
            return [];
        }
    }

    public function obtenerFormasFarmaceuticas() {
        try {
            $stmt = $this->db->prepare("SELECT * FROM forma_farmaceutica ORDER BY nombre");
            $stmt->execute();
            return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        } catch (Exception $e) {
            error_log("Error en obtenerFormasFarmaceuticas: " . $e->getMessage());
            return [];
        }
    }

    public function obtenerGruposTerapeuticos() {
        try {
            $stmt = $this->db->prepare("SELECT * FROM grupo_terapeutico ORDER BY nombre");
            $stmt->execute();
            return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        } catch (Exception $e) {
            error_log("Error en obtenerGruposTerapeuticos: " . $e->getMessage());
            return [];
        }
    }

    public function obtenerViasAdministracion() {
        try {
            $stmt = $this->db->prepare("SELECT * FROM via_administracion ORDER BY nombre");
            $stmt->execute();
            return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        } catch (Exception $e) {
            error_log("Error en obtenerViasAdministracion: " . $e->getMessage());
            return [];
        }
    }

    public function obtenerTodasMedicacionesPacientes() {
        try {
            $stmt = $this->db->prepare("
                SELECT mp.id_medicamento_paciente,
                    mp.nombre_completo as nombre_paciente,
                    mp.fecha_preescripcion,
                    mp.tiempo_tratamiento, 
                    mp.indicaciones,
                    mp.id_estado,
                    mp.id_medicamento,
                    mp.id_paciente,
                    m.nombre as nombre_medicamento,
                    ff.nombre as forma_farmaceutica,
                    gt.nombre as grupo_terapeutico,
                    va.nombre as via_administracion,
                    e.nombre as estado,
                    u.cedula_usuario as cedula_paciente
                FROM medicamento_paciente mp
                INNER JOIN medicamento m ON mp.id_medicamento = m.id_medicamento
                INNER JOIN usuario u ON mp.id_paciente = u.id_usuario
                LEFT JOIN forma_farmaceutica ff ON m.id_forma_farmaceutica = ff.id_forma_farmaceutica
                LEFT JOIN grupo_terapeutico gt ON m.id_grupo_terapeutico = gt.id_grupo_farmaceutico
                LEFT JOIN via_administracion va ON m.id_via_administracion = va.id_via_administracion
                LEFT JOIN estado e ON mp.id_estado = e.id_estado
                ORDER BY mp.fecha_preescripcion DESC, mp.nombre_completo ASC
            ");
            $stmt->execute();
            return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        } catch (Exception $e) {
            error_log("Error en obtenerTodasMedicacionesPacientes: " . $e->getMessage());
            return [];
        }
    }
}
?>
