<?php
require_once 'app/config/db.php';

class Cita {
    private $db;

    public function __construct() {
        $this->db = Database::connect();
    }

    public function registrarCitaPaciente($fecha, $hora, $id_servicio, $id_especialidad, $id_estado = 3) {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        if (!isset($_SESSION['user']['id'])) {
            error_log("ERROR: No hay sesión de usuario activa.");
            return false;
        }
        
        $id_usuario = $_SESSION['user']['id'];
        error_log("DEBUG: Usuario en sesión: " . $id_usuario);
        
        $id_medico = $this->buscarMedicoDisponible($id_especialidad, $fecha, $hora);
        
        if (!$id_medico) {
            error_log("ERROR: No hay médicos disponibles.");
            return false;
        }
        
        error_log("DEBUG: Médico encontrado ID: " . $id_medico);
        
        if (!$this->db) {
            error_log("ERROR: No hay conexión establecida a base de datos.");
            return false;
        }
        
        try {
            $stmt = $this->db->prepare("INSERT INTO cita (fecha, hora, id_usuario, id_medico, id_servicio, id_especialidad, id_estado) VALUES (?, ?, ?, ?, ?, ?, ?)");
            
            if (!$stmt) {
                error_log("ERROR: Error al preparar statement: " . $this->db->error);
                return false;
            }
            
            $stmt->bind_param("ssiiiii", $fecha, $hora, $id_usuario, $id_medico, $id_servicio, $id_especialidad, $id_estado);
            $result = $stmt->execute();
            
            if (!$result) {
                error_log("ERROR: Error al ejecutar query: " . $stmt->error);
                return false;
            }
            
            $stmt->close();
            return true;
            
        } catch (Exception $e) {
            error_log("ERROR: Excepción en registrarCitaPaciente: " . $e->getMessage());
            return false;
        }
    }

    public function registrarCitaAdmin($fecha, $hora, $cedula_paciente, $id_medico, $id_servicio, $id_especialidad, $id_estado = 3) {
        try {
            $paciente = $this->buscarPacientePorCedula($cedula_paciente);
            if (!$paciente) {
                error_log("ERROR: Paciente no encontrado con cédula: " . $cedula_paciente);
                return false;
            }
            
            $id_usuario = $paciente['id_usuario'];
            
            if (!$this->verificarDisponibilidad($fecha, $hora, $id_medico)) {
                error_log("ERROR: Médico no disponible.");
                return false;
            }
            
            if (!$this->db) {
                error_log("ERROR: No hay conexión establecida a base de datos.");
                return false;
            }
            
            $stmt = $this->db->prepare("INSERT INTO cita (fecha, hora, id_usuario, id_medico, id_servicio, id_especialidad, id_estado) VALUES (?, ?, ?, ?, ?, ?, ?)");
            
            if (!$stmt) {
                error_log("ERROR: Error al preparar statement: " . $this->db->error);
                return false;
            }
            
            $stmt->bind_param("ssiiiii", $fecha, $hora, $id_usuario, $id_medico, $id_servicio, $id_especialidad, $id_estado);
            $result = $stmt->execute();
            
            if (!$result) {
                error_log("ERROR: Error al ejecutar query: " . $stmt->error);
                return false;
            }
            
            $stmt->close();
            return true;
            
        } catch (Exception $e) {
            error_log("ERROR: Excepción en registrarCitaAdmin: " . $e->getMessage());
            return false;
        }
    }

    public function obtenerTodas() {
        try {
            if (!$this->db) {
                error_log("ERROR: No hay conexión establecida a base de datos.");
                return [];
            }
            
            $stmt = $this->db->prepare("
                SELECT c.*, 
                       u.cedula_usuario, 
                       CONCAT(u.nombre, ' ', u.apellidos) as nombre_paciente,
                       CONCAT(m.nombre, ' ', m.apellidos) as nombre_medico,
                       s.nombre as nombre_servicio,
                       e.nombre as nombre_especialidad,
                       est.nombre as nombre_estado
                FROM cita c
                LEFT JOIN usuario u ON c.id_usuario = u.id_usuario
                LEFT JOIN usuario m ON c.id_medico = m.id_usuario
                LEFT JOIN servicio s ON c.id_servicio = s.id_servicio
                LEFT JOIN especialidad e ON c.id_especialidad = e.id_especialidad
                LEFT JOIN estado est ON c.id_estado = est.id_estado
                ORDER BY c.fecha DESC, c.hora DESC
            ");
            
            if (!$stmt) {
                error_log("ERROR: Error al preparar statement: " . $this->db->error);
                return [];
            }
            
            $stmt->execute();
            $result = $stmt->get_result();
            $data = $result->fetch_all(MYSQLI_ASSOC);
            $stmt->close();
            
            return $data;
            
        } catch (Exception $e) {
            error_log("ERROR: Excepción en obtenerTodas: " . $e->getMessage());
            return [];
        }
    }

    public function obtenerPorUsuario($id_usuario) {
        try {
            if (!$this->db) {
                error_log("ERROR: No hay conexión establecida a base de datos.");
                return [];
            }
            
            $stmt = $this->db->prepare("
                SELECT c.*, 
                       CONCAT(m.nombre, ' ', m.apellidos) as nombre_medico,
                       s.nombre as nombre_servicio,
                       e.nombre as nombre_especialidad,
                       est.nombre as nombre_estado
                FROM cita c
                LEFT JOIN usuario m ON c.id_medico = m.id_usuario
                LEFT JOIN servicio s ON c.id_servicio = s.id_servicio
                LEFT JOIN especialidad e ON c.id_especialidad = e.id_especialidad
                LEFT JOIN estado est ON c.id_estado = est.id_estado
                WHERE c.id_usuario = ? 
                ORDER BY c.fecha DESC, c.hora DESC
            ");
            
            if (!$stmt) {
                error_log("ERROR: Error al preparar statement: " . $this->db->error);
                return [];
            }
            
            $stmt->bind_param("i", $id_usuario);
            $stmt->execute();
            $result = $stmt->get_result();
            $data = $result->fetch_all(MYSQLI_ASSOC);
            $stmt->close();
            
            return $data;
            
        } catch (Exception $e) {
            error_log("ERROR: Excepción en obtenerPorUsuario: " . $e->getMessage());
            return [];
        }
    }

    public function obtenerPorId($id_cita) {
        try {
            if (!$this->db) {
                error_log("ERROR: No hay conexión establecida a base de datos.");
                return null;
            }
            
            $stmt = $this->db->prepare("
                SELECT c.*, 
                    u.cedula_usuario, 
                    CONCAT(u.nombre, ' ', u.apellidos) as nombre_paciente,
                    u.nombre as nombre_usuario,
                    u.apellidos as apellidos_usuario,
                    CONCAT(m.nombre, ' ', m.apellidos) as nombre_medico,
                    s.nombre as nombre_servicio,
                    e.nombre as nombre_especialidad,
                    est.nombre as nombre_estado
                FROM cita c
                LEFT JOIN usuario u ON c.id_usuario = u.id_usuario
                LEFT JOIN usuario m ON c.id_medico = m.id_usuario
                LEFT JOIN servicio s ON c.id_servicio = s.id_servicio
                LEFT JOIN especialidad e ON c.id_especialidad = e.id_especialidad
                LEFT JOIN estado est ON c.id_estado = est.id_estado
                WHERE c.id_cita = ?
            ");
            
            if (!$stmt) {
                error_log("ERROR: Error al preparar statement: " . $this->db->error);
                return null;
            }
            
            $stmt->bind_param("i", $id_cita);
            $stmt->execute();
            $result = $stmt->get_result();
            $data = $result->fetch_assoc();
            $stmt->close();
            
            if ($data) {
                error_log("Datos de cita encontrados: " . print_r($data, true));
            } else {
                error_log("No se encontró cita con ID: " . $id_cita);
            }
            
            return $data;
            
        } catch (Exception $e) {
            error_log("ERROR: Excepción en obtenerPorId: " . $e->getMessage());
            return null;
        }
    }

    public function actualizarEstado($id_cita, $id_estado) {
        try {
            if (!$this->db) {
                error_log("ERROR: No hay conexión establecida a base de datos.");
                return false;
            }
            
            $stmt = $this->db->prepare("UPDATE cita SET id_estado = ? WHERE id_cita = ?");
            
            if (!$stmt) {
                error_log("ERROR: Error al preparar statement: " . $this->db->error);
                return false;
            }
            
            $stmt->bind_param("ii", $id_estado, $id_cita);
            $result = $stmt->execute();
            
            if (!$result) {
                error_log("ERROR: Error al ejecutar query: " . $stmt->error);
                return false;
            }
            
            $stmt->close();
            return true;
            
        } catch (Exception $e) {
            error_log("ERROR: Excepción en actualizarEstado: " . $e->getMessage());
            return false;
        }
    }

    public function actualizar($id_cita, $fecha, $hora, $id_medico, $id_servicio, $id_especialidad, $id_estado) {
        try {
            if (!$this->db) {
                error_log("ERROR: No hay conexión establecida a base de datos.");
                return false;
            }
            
            $stmt = $this->db->prepare("UPDATE cita SET fecha = ?, hora = ?, id_medico = ?, id_servicio = ?, id_especialidad = ?, id_estado = ? WHERE id_cita = ?");
            
            if (!$stmt) {
                error_log("ERROR: Error al preparar statement: " . $this->db->error);
                return false;
            }
            
            $stmt->bind_param("ssiiiii", $fecha, $hora, $id_medico, $id_servicio, $id_especialidad, $id_estado, $id_cita);
            $result = $stmt->execute();
            
            if (!$result) {
                error_log("ERROR: Error al ejecutar query: " . $stmt->error);
                return false;
            }
            
            $stmt->close();
            return true;
            
        } catch (Exception $e) {
            error_log("ERROR: Excepción en actualizar: " . $e->getMessage());
            return false;
        }
    }

    public function actualizarCompleta($id_cita, $fecha, $hora, $id_usuario, $id_medico, $id_servicio, $id_especialidad, $id_estado) {
        try {
            if (!$this->db) {
                error_log("ERROR: No hay conexión establecida a base de datos.");
                return false;
            }
            
            $stmt = $this->db->prepare("UPDATE cita SET fecha = ?, hora = ?, id_usuario = ?, id_medico = ?, id_servicio = ?, id_especialidad = ?, id_estado = ? WHERE id_cita = ?");
            
            if (!$stmt) {
                error_log("ERROR: Error al preparar statement: " . $this->db->error);
                return false;
            }
            
            $stmt->bind_param("ssiiiiii", $fecha, $hora, $id_usuario, $id_medico, $id_servicio, $id_especialidad, $id_estado, $id_cita);
            $result = $stmt->execute();
            
            if (!$result) {
                error_log("ERROR: Error al ejecutar query: " . $stmt->error);
                return false;
            }
            
            $stmt->close();
            return true;
            
        } catch (Exception $e) {
            error_log("ERROR: Excepción en actualizarCompleta: " . $e->getMessage());
            return false;
        }
    }

    public function eliminar($id_cita) {
        try {
            if (!$this->db) {
                error_log("ERROR: No hay conexión establecida a base de datos.");
                return false;
            }
            
            $stmt = $this->db->prepare("DELETE FROM cita WHERE id_cita = ?");
            
            if (!$stmt) {
                error_log("ERROR: Error al preparar statement: " . $this->db->error);
                return false;
            }
            
            $stmt->bind_param("i", $id_cita);
            $result = $stmt->execute();
            
            if (!$result) {
                error_log("ERROR: Error al ejecutar query: " . $stmt->error);
                return false;
            }
            
            $stmt->close();
            return true;
            
        } catch (Exception $e) {
            error_log("ERROR: Excepción en eliminar: " . $e->getMessage());
            return false;
        }
    }

    public function verificarDisponibilidad($fecha, $hora, $id_medico) {
        try {
            if (!$this->db) {
                error_log("ERROR: No hay conexión establecida a base de datos.");
                return false;
            }
            
            $stmt = $this->db->prepare("SELECT COUNT(*) as total FROM cita WHERE fecha = ? AND hora = ? AND id_medico = ? AND id_estado != 4");
            
            if (!$stmt) {
                error_log("ERROR: Error al preparar statement: " . $this->db->error);
                return false;
            }
            
            $stmt->bind_param("ssi", $fecha, $hora, $id_medico);
            $stmt->execute();
            $result = $stmt->get_result();
            $data = $result->fetch_assoc();
            $stmt->close();
            
            return $data['total'] == 0;
            
        } catch (Exception $e) {
            error_log("ERROR: Excepción en verificarDisponibilidad: " . $e->getMessage());
            return false;
        }
    }

    public function obtenerPorFecha($fecha) {
        try {
            if (!$this->db) {
                error_log("ERROR: No hay conexión establecida a base de datos.");
                return [];
            }
            
            $stmt = $this->db->prepare("SELECT * FROM cita WHERE fecha = ? ORDER BY hora");
            
            if (!$stmt) {
                error_log("ERROR: Error al preparar statement: " . $this->db->error);
                return [];
            }
            
            $stmt->bind_param("s", $fecha);
            $stmt->execute();
            $result = $stmt->get_result();
            $data = $result->fetch_all(MYSQLI_ASSOC);
            $stmt->close();
            
            return $data;
            
        } catch (Exception $e) {
            error_log("ERROR: Excepción en obtenerPorFecha: " . $e->getMessage());
            return [];
        }
    }

    public function buscarMedicoDisponible($id_especialidad, $fecha, $hora) {
        try {
            if (!$this->db) {
                error_log("ERROR: No hay conexión establecida a base de datos.");
                return null;
            }
            
            $stmt = $this->db->prepare("
                SELECT u.id_usuario
                FROM usuario u
                INNER JOIN medico_especialidad me ON u.id_usuario = me.id_medico
                LEFT JOIN cita c ON u.id_usuario = c.id_medico AND c.fecha = ? AND c.hora = ? AND c.id_estado != 4
                WHERE u.id_rol = 2 
                AND u.id_estado = 1 
                AND me.id_especialidad = ?
                AND me.id_estado = 1
                AND c.id_cita IS NULL
                ORDER BY RAND()
                LIMIT 1
            ");
            
            if (!$stmt) {
                error_log("ERROR: Error al preparar statement: " . $this->db->error);
                return null;
            }
            
            $stmt->bind_param("ssi", $fecha, $hora, $id_especialidad);
            $stmt->execute();
            $result = $stmt->get_result();
            $data = $result->fetch_assoc();
            $stmt->close();
            
            return $data ? $data['id_usuario'] : null;
            
        } catch (Exception $e) {
            error_log("ERROR: Excepción en buscarMedicoDisponible: " . $e->getMessage());
            return null;
        }
    }

    public function obtenerMedicosDisponibles($id_especialidad, $fecha, $hora) {
        try {
            if (!$this->db) {
                error_log("ERROR: No hay conexión establecida a base de datos.");
                return [];
            }
            
            $stmt = $this->db->prepare("
                SELECT u.id_usuario, CONCAT(u.nombre, ' ', u.apellidos) as nombre_completo
                FROM usuario u
                INNER JOIN medico_especialidad me ON u.id_usuario = me.id_medico
                LEFT JOIN cita c ON u.id_usuario = c.id_medico AND c.fecha = ? AND c.hora = ? AND c.id_estado != 4
                WHERE u.id_rol = 2 
                AND u.id_estado = 1 
                AND me.id_especialidad = ?
                AND me.id_estado = 1
                AND c.id_cita IS NULL
                ORDER BY u.nombre
            ");
            
            if (!$stmt) {
                error_log("ERROR: Error al preparar statement: " . $this->db->error);
                return [];
            }
            
            $stmt->bind_param("ssi", $fecha, $hora, $id_especialidad);
            $stmt->execute();
            $result = $stmt->get_result();
            $data = $result->fetch_all(MYSQLI_ASSOC);
            $stmt->close();
            
            return $data;
            
        } catch (Exception $e) {
            error_log("ERROR: Excepción en obtenerMedicosDisponibles: " . $e->getMessage());
            return [];
        }
    }

    public function obtenerEspecialidades() {
        try {
            if (!$this->db) {
                error_log("ERROR: No hay conexión establecida a base de datos.");
                return [];
            }
            
            $stmt = $this->db->prepare("SELECT * FROM especialidad ORDER BY nombre");
            
            if (!$stmt) {
                error_log("ERROR: Error al preparar statement: " . $this->db->error);
                return [];
            }
            
            $stmt->execute();
            $result = $stmt->get_result();
            $data = $result->fetch_all(MYSQLI_ASSOC);
            $stmt->close();
            
            return $data;
            
        } catch (Exception $e) {
            error_log("ERROR: Excepción en obtenerEspecialidades: " . $e->getMessage());
            return [];
        }
    }

    public function obtenerServicios() {
        try {
            if (!$this->db) {
                error_log("ERROR: No hay conexión establecida a base de datos.");
                return [];
            }
            
            $stmt = $this->db->prepare("SELECT * FROM servicio ORDER BY nombre");
            
            if (!$stmt) {
                error_log("ERROR: Error al preparar statement: " . $this->db->error);
                return [];
            }
            
            $stmt->execute();
            $result = $stmt->get_result();
            $data = $result->fetch_all(MYSQLI_ASSOC);
            $stmt->close();
            
            return $data;
            
        } catch (Exception $e) {
            error_log("ERROR: Excepción en obtenerServicios: " . $e->getMessage());
            return [];
        }
    }

    public function buscarPacientePorCedula($cedula) {
        try {
            if (!$this->db) {
                error_log("ERROR: No hay conexión establecida a base de datos.");
                return null;
            }
            
            $stmt = $this->db->prepare("SELECT id_usuario, cedula_usuario, CONCAT(nombre, ' ', apellidos) as nombre_completo, nombre, apellidos, correo, telefono FROM usuario WHERE cedula_usuario = ? AND id_rol = 3 AND id_estado = 1");
            
            if (!$stmt) {
                error_log("ERROR: Error al preparar statement: " . $this->db->error);
                return null;
            }
            
            $stmt->bind_param("i", $cedula);
            $stmt->execute();
            $result = $stmt->get_result();
            $data = $result->fetch_assoc();
            $stmt->close();
            
            return $data;
            
        } catch (Exception $e) {
            error_log("ERROR: Excepción en buscarPacientePorCedula: " . $e->getMessage());
            return null;
        }
    }

    public function obtenerCitasUsuarioSesion() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        if (!isset($_SESSION['user']['id'])) {
            return [];
        }
        
        return $this->obtenerPorUsuario($_SESSION['user']['id']);
    }

    public function obtenerPorMedico($id_medico, $fecha = null) {
        try {
            if (!$this->db) {
                error_log("ERROR: No hay conexión establecida a base de datos.");
                return [];
            }
            
            $query_with_joins = "
                SELECT c.*, 
                    u.cedula_usuario, 
                    CONCAT(u.nombre, ' ', u.apellidos) as nombre_paciente,
                    s.nombre as nombre_servicio,
                    e.nombre as nombre_especialidad,
                    est.nombre as nombre_estado
                FROM cita c
                LEFT JOIN usuario u ON c.id_usuario = u.id_usuario
                LEFT JOIN servicio s ON c.id_servicio = s.id_servicio
                LEFT JOIN especialidad e ON c.id_especialidad = e.id_especialidad
                LEFT JOIN estado est ON c.id_estado = est.id_estado
                WHERE c.id_medico = ?
            ";
            
            $query_simple = "
                SELECT c.*,
                    'N/A' as cedula_usuario,
                    'Paciente' as nombre_paciente,
                    'Servicio' as nombre_servicio,
                    'Especialidad' as nombre_especialidad,
                    CASE 
                        WHEN c.id_estado = 1 THEN 'Completada'
                        WHEN c.id_estado = 2 THEN 'En Proceso'
                        WHEN c.id_estado = 3 THEN 'Programada'
                        WHEN c.id_estado = 4 THEN 'Cancelada'
                        WHEN c.id_estado = 5 THEN 'Reprogramada'
                        ELSE 'Desconocido'
                    END as nombre_estado
                FROM cita c
                WHERE c.id_medico = ?
            ";
            
            if ($fecha) {
                $query_with_joins .= " AND c.fecha = ? ORDER BY c.hora ASC";
                $query_simple .= " AND c.fecha = ? ORDER BY c.hora ASC";
            } else {
                $query_with_joins .= " ORDER BY c.fecha DESC, c.hora ASC";
                $query_simple .= " ORDER BY c.fecha DESC, c.hora ASC";
            }
            
            $stmt = $this->db->prepare($query_with_joins);
            if (!$stmt) {
                error_log("WARN: Error con JOINs, intentando query simple: " . $this->db->error);
                $stmt = $this->db->prepare($query_simple);
                if (!$stmt) {
                    error_log("ERROR: Error al preparar statement simple: " . $this->db->error);
                    return [];
                }
            }
            
            if ($fecha) {
                $stmt->bind_param("is", $id_medico, $fecha);
            } else {
                $stmt->bind_param("i", $id_medico);
            }
            
            $stmt->execute();
            $result = $stmt->get_result();
            
            if (!$result) {
                error_log("ERROR: Error al obtener resultado: " . $this->db->error);
                $stmt->close();
                return [];
            }
            
            $data = $result->fetch_all(MYSQLI_ASSOC);
            $stmt->close();
            
            error_log("INFO: Citas encontradas para médico $id_medico: " . count($data));
            
            return $data;
            
        } catch (Exception $e) {
            error_log("ERROR: Excepción en obtenerPorMedico: " . $e->getMessage());
            return [];
        }
    }

    public function obtenerCitasMedicoSimple($id_medico, $fecha = null) {
        try {
            if (!$this->db) {
                error_log("ERROR: No hay conexión establecida a base de datos.");
                return [];
            }
            
            $query = "SELECT * FROM cita WHERE id_medico = ?";
            $params = [$id_medico];
            $types = "i";
            
            if ($fecha) {
                $query .= " AND fecha = ?";
                $params[] = $fecha;
                $types .= "s";
            }
            
            $query .= " ORDER BY fecha DESC, hora ASC";
            
            error_log("Query ejecutándose: " . $query);
            error_log("Parámetros: " . implode(", ", $params));
            
            $stmt = $this->db->prepare($query);
            if (!$stmt) {
                error_log("ERROR: Error al preparar statement: " . $this->db->error);
                return [];
            }
            
            $stmt->bind_param($types, ...$params);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if (!$result) {
                error_log("ERROR: Error al obtener resultado: " . $this->db->error);
                $stmt->close();
                return [];
            }
            
            $data = $result->fetch_all(MYSQLI_ASSOC);
            $stmt->close();
            
            foreach ($data as &$cita) {
                $cita['cedula_usuario'] = 'N/A';
                $cita['nombre_paciente'] = 'Paciente ID: ' . $cita['id_usuario'];
                $cita['nombre_servicio'] = 'Servicio ID: ' . $cita['id_servicio'];
                $cita['nombre_especialidad'] = 'Especialidad ID: ' . $cita['id_especialidad'];
                
                switch (intval($cita['id_estado'])) {
                    case 1: $cita['nombre_estado'] = 'Completada'; break;
                    case 2: $cita['nombre_estado'] = 'En Proceso'; break;
                    case 3: $cita['nombre_estado'] = 'Programada'; break;
                    case 4: $cita['nombre_estado'] = 'Cancelada'; break;
                    case 5: $cita['nombre_estado'] = 'Reprogramada'; break;
                    default: $cita['nombre_estado'] = 'Estado ' . $cita['id_estado']; break;
                }
            }
            
            error_log("INFO: Citas encontradas: " . count($data));
            if (count($data) > 0) {
                error_log("Primera cita: " . print_r($data[0], true));
            }
            
            return $data;
            
        } catch (Exception $e) {
            error_log("ERROR: Excepción en obtenerCitasMedicoSimple: " . $e->getMessage());
            return [];
        }
    }
}
?>
