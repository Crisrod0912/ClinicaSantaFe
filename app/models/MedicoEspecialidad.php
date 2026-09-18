<?php
require_once 'app/config/db.php';

class MedicoEspecialidad {
    private $db;

    public function __construct() {
        try {
            $this->db = Database::connect();
            if (!$this->db) {
                throw new Exception("Error de conexión a base de datos.");
            }
        } catch (Exception $e) {
            error_log("Error en constructor MedicoEspecialidad: " . $e->getMessage());
            throw $e;
        }
    }

    public function registrar($id_medico, $id_especialidad, $id_estado = 1) {
        try {e
            if ($this->existeAsignacion($id_medico, $id_especialidad)) {
                return false;
            }

            $stmt = $this->db->prepare("INSERT INTO medico_especialidad (id_medico, id_especialidad, id_estado) VALUES (?, ?, ?)");
            if (!$stmt) {
                throw new Exception("Error al preparar consulta: " . $this->db->error);
            }
            
            $stmt->bind_param("iii", $id_medico, $id_especialidad, $id_estado);
            $result = $stmt->execute();
            $stmt->close();
            
            return $result;
        } catch (Exception $e) {
            error_log("Error en registrar medico_especialidad: " . $e->getMessage());
            return false;
        }
    }

    public function obtenerTodos() {
        try {
            $stmt = $this->db->prepare("
                SELECT me.*, 
                       CONCAT(u.nombre, ' ', u.apellidos) as nombre_medico,
                       esp.nombre as nombre_especialidad,
                       est.nombre as nombre_estado
                FROM medico_especialidad me
                INNER JOIN usuario u ON me.id_medico = u.id_usuario
                INNER JOIN especialidad esp ON me.id_especialidad = esp.id_especialidad
                LEFT JOIN estado est ON me.id_estado = est.id_estado
                WHERE u.id_rol = (SELECT id_rol FROM rol WHERE nombre = 'Medico')
                ORDER BY u.nombre, u.apellidos, esp.nombre
            ");
            
            if (!$stmt) {
                throw new Exception("Error al preparar consulta: " . $this->db->error);
            }
            
            $stmt->execute();
            $result = $stmt->get_result();
            $asignaciones = $result->fetch_all(MYSQLI_ASSOC);
            $stmt->close();
            
            return $asignaciones;
        } catch (Exception $e) {
            error_log("Error en obtenerTodos medico_especialidad: " . $e->getMessage());
            return [];
        }
    }

    public function obtenerPorId($id_medico_especialidad) {
        try {
            $stmt = $this->db->prepare("
                SELECT me.*, 
                       CONCAT(u.nombre, ' ', u.apellidos) as nombre_medico,
                       esp.nombre as nombre_especialidad,
                       est.nombre as nombre_estado
                FROM medico_especialidad me
                INNER JOIN usuario u ON me.id_medico = u.id_usuario
                INNER JOIN especialidad esp ON me.id_especialidad = esp.id_especialidad
                LEFT JOIN estado est ON me.id_estado = est.id_estado
                WHERE me.id_medico_especialidad = ?
            ");
            if (!$stmt) {
                throw new Exception("Error al preparar consulta: " . $this->db->error);
            }
            
            $stmt->bind_param("i", $id_medico_especialidad);
            $stmt->execute();
            $result = $stmt->get_result();
            $asignacion = $result->fetch_assoc();
            $stmt->close();
            
            return $asignacion;
        } catch (Exception $e) {
            error_log("Error en obtenerPorId medico_especialidad: " . $e->getMessage());
            return null;
        }
    }

    public function actualizar($id_medico_especialidad, $id_medico, $id_especialidad, $id_estado) {
        try {
            if ($this->existeAsignacion($id_medico, $id_especialidad, $id_medico_especialidad)) {
                return false;
            }

            $stmt = $this->db->prepare("UPDATE medico_especialidad SET id_medico = ?, id_especialidad = ?, id_estado = ? WHERE id_medico_especialidad = ?");
            if (!$stmt) {
                throw new Exception("Error al preparar consulta: " . $this->db->error);
            }
            
            $stmt->bind_param("iiii", $id_medico, $id_especialidad, $id_estado, $id_medico_especialidad);
            $result = $stmt->execute();
            $stmt->close();
            
            return $result;
        } catch (Exception $e) {
            error_log("Error en actualizar medico_especialidad: " . $e->getMessage());
            return false;
        }
    }

    public function actualizarEstado($id_medico_especialidad, $id_estado) {
        try {
            $stmt = $this->db->prepare("UPDATE medico_especialidad SET id_estado = ? WHERE id_medico_especialidad = ?");
            if (!$stmt) {
                throw new Exception("Error al preparar consulta: " . $this->db->error);
            }
            
            $stmt->bind_param("ii", $id_estado, $id_medico_especialidad);
            $result = $stmt->execute();
            $stmt->close();
            
            return $result;
        } catch (Exception $e) {
            error_log("Error en actualizarEstado medico_especialidad: " . $e->getMessage());
            return false;
        }
    }

    public function eliminar($id_medico_especialidad) {
        try {
            $stmt = $this->db->prepare("DELETE FROM medico_especialidad WHERE id_medico_especialidad = ?");
            if (!$stmt) {
                throw new Exception("Error al preparar consulta: " . $this->db->error);
            }
            
            $stmt->bind_param("i", $id_medico_especialidad);
            $result = $stmt->execute();
            $stmt->close();
            
            return $result;
        } catch (Exception $e) {
            error_log("Error en eliminar medico_especialidad: " . $e->getMessage());
            return false;
        }
    }

    public function obtenerMedicos() {
        try {
            $stmt = $this->db->prepare("
                SELECT u.id_usuario, CONCAT(u.nombre, ' ', u.apellidos) as nombre_completo
                FROM usuario u
                INNER JOIN rol r ON u.id_rol = r.id_rol
                WHERE r.nombre = 'Medico' AND u.id_estado = 1
                ORDER BY u.nombre, u.apellidos
            ");
            if (!$stmt) {
                throw new Exception("Error al preparar consulta: " . $this->db->error);
            }
            
            $stmt->execute();
            $result = $stmt->get_result();
            $medicos = $result->fetch_all(MYSQLI_ASSOC);
            $stmt->close();
            
            return $medicos;
        } catch (Exception $e) {
            error_log("Error en obtenerMedicos: " . $e->getMessage());
            return [];
        }
    }

    public function obtenerEspecialidades() {
        try {
            $stmt = $this->db->prepare("SELECT * FROM especialidad ORDER BY nombre");
            if (!$stmt) {
                throw new Exception("Error al preparar consulta: " . $this->db->error);
            }
            
            $stmt->execute();
            $result = $stmt->get_result();
            $especialidades = $result->fetch_all(MYSQLI_ASSOC);
            $stmt->close();
            
            return $especialidades;
        } catch (Exception $e) {
            error_log("Error en obtenerEspecialidades: " . $e->getMessage());
            return [];
        }
    }

    public function obtenerEstados() {
        try {
            $stmt = $this->db->prepare("SELECT * FROM estado ORDER BY nombre");
            if (!$stmt) {
                throw new Exception("Error al preparar consulta: " . $this->db->error);
            }
            
            $stmt->execute();
            $result = $stmt->get_result();
            $estados = $result->fetch_all(MYSQLI_ASSOC);
            $stmt->close();
            
            return $estados;
        } catch (Exception $e) {
            error_log("Error en obtenerEstados: " . $e->getMessage());
            return [];
        }
    }

    public function existeAsignacion($id_medico, $id_especialidad, $id_excluir = null) {
        try {
            if ($id_excluir) {
                $stmt = $this->db->prepare("SELECT COUNT(*) as total FROM medico_especialidad WHERE id_medico = ? AND id_especialidad = ? AND id_medico_especialidad != ?");
                if (!$stmt) {
                    throw new Exception("Error al preparar consulta: " . $this->db->error);
                }
                $stmt->bind_param("iii", $id_medico, $id_especialidad, $id_excluir);
            } else {
                $stmt = $this->db->prepare("SELECT COUNT(*) as total FROM medico_especialidad WHERE id_medico = ? AND id_especialidad = ?");
                if (!$stmt) {
                    throw new Exception("Error al preparar consulta: " . $this->db->error);
                }
                $stmt->bind_param("ii", $id_medico, $id_especialidad);
            }
            
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            $stmt->close();
            
            return $row['total'] > 0;
        } catch (Exception $e) {
            error_log("Error en existeAsignacion: " . $e->getMessage());
            return false;
        }
    }

    public function obtenerEspecialidadesMedicoSesion() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        if (!isset($_SESSION['user']['id'])) {
            error_log("ERROR: No hay sesión de usuario activa para obtener especialidades.");
            return [];
        }
        
        $id_medico = $_SESSION['user']['id'];
        error_log("DEBUG: Obteniendo especialidades para médico: " . $id_medico);
        
        return $this->obtenerEspecialidadesPorMedico($id_medico);
    }

    public function obtenerEspecialidadesPorMedico($id_medico) {
        try {
            $stmt = $this->db->prepare("
                SELECT me.id_medico_especialidad,
                    me.id_medico,
                    me.id_especialidad,
                    me.id_estado,
                    esp.nombre as nombre_especialidad,
                    est.nombre as nombre_estado,
                    CONCAT(u.nombre, ' ', u.apellidos) as nombre_medico
                FROM medico_especialidad me
                INNER JOIN especialidad esp ON me.id_especialidad = esp.id_especialidad
                INNER JOIN usuario u ON me.id_medico = u.id_usuario
                LEFT JOIN estado est ON me.id_estado = est.id_estado
                WHERE me.id_medico = ? 
                ORDER BY esp.nombre
            ");
            
            if (!$stmt) {
                throw new Exception("Error al preparar consulta: " . $this->db->error);
            }
            
            $stmt->bind_param("i", $id_medico);
            $stmt->execute();
            $result = $stmt->get_result();
            $especialidades = $result->fetch_all(MYSQLI_ASSOC);
            $stmt->close();
            
            return $especialidades;
        } catch (Exception $e) {
            error_log("Error en obtenerEspecialidadesPorMedico: " . $e->getMessage());
            return [];
        }
    }
}
?>
