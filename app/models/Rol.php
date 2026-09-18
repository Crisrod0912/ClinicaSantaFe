<?php
require_once 'app/config/db.php';

class Rol {
    private $db;

    public function __construct() {
        try {
            $this->db = Database::connect();
            if (!$this->db) {
                throw new Exception("Error de conexión a base de datos.");
            }
        } catch (Exception $e) {
            error_log("Error en constructor Rol: " . $e->getMessage());
            throw $e;
        }
    }

    public function registrar($nombre, $descripcion, $id_estado = 1) {
        try {
            if ($this->existePorNombre($nombre)) {
                return false;
            }

            $stmt = $this->db->prepare("INSERT INTO rol (nombre, descripcion, id_estado) VALUES (?, ?, ?)");
            if (!$stmt) {
                throw new Exception("Error al preparar consulta: " . $this->db->error);
            }
            
            $stmt->bind_param("ssi", $nombre, $descripcion, $id_estado);
            $result = $stmt->execute();
            $stmt->close();
            
            return $result;
        } catch (Exception $e) {
            error_log("Error en registrar rol: " . $e->getMessage());
            return false;
        }
    }

    public function obtenerTodos() {
        try {
            $stmt = $this->db->prepare("
                SELECT r.*, 
                       e.nombre as nombre_estado
                FROM rol r
                LEFT JOIN estado e ON r.id_estado = e.id_estado
                ORDER BY r.nombre
            ");
            
            if (!$stmt) {
                throw new Exception("Error al preparar consulta: " . $this->db->error);
            }
            
            $stmt->execute();
            $result = $stmt->get_result();
            $roles = $result->fetch_all(MYSQLI_ASSOC);
            $stmt->close();
            
            return $roles;
        } catch (Exception $e) {
            error_log("Error en obtenerTodos roles: " . $e->getMessage());
            return [];
        }
    }

    public function obtenerPorId($id_rol) {
        try {
            $stmt = $this->db->prepare("SELECT * FROM rol WHERE id_rol = ?");
            if (!$stmt) {
                throw new Exception("Error al preparar consulta: " . $this->db->error);
            }
            
            $stmt->bind_param("i", $id_rol);
            $stmt->execute();
            $result = $stmt->get_result();
            $rol = $result->fetch_assoc();
            $stmt->close();
            
            return $rol;
        } catch (Exception $e) {
            error_log("Error en obtenerPorId rol: " . $e->getMessage());
            return null;
        }
    }

    public function actualizar($id_rol, $nombre, $descripcion, $id_estado) {
        try {
            if ($this->existePorNombre($nombre, $id_rol)) {
                return false;
            }

            $stmt = $this->db->prepare("UPDATE rol SET nombre = ?, descripcion = ?, id_estado = ? WHERE id_rol = ?");
            if (!$stmt) {
                throw new Exception("Error al preparar consulta: " . $this->db->error);
            }
            
            $stmt->bind_param("ssii", $nombre, $descripcion, $id_estado, $id_rol);
            $result = $stmt->execute();
            $stmt->close();
            
            return $result;
        } catch (Exception $e) {
            error_log("Error en actualizar rol: " . $e->getMessage());
            return false;
        }
    }

    public function actualizarEstado($id_rol, $id_estado) {
        try {
            $stmt = $this->db->prepare("UPDATE rol SET id_estado = ? WHERE id_rol = ?");
            if (!$stmt) {
                throw new Exception("Error al preparar consulta: " . $this->db->error);
            }
            
            $stmt->bind_param("ii", $id_estado, $id_rol);
            $result = $stmt->execute();
            $stmt->close();
            
            return $result;
        } catch (Exception $e) {
            error_log("Error en actualizarEstado rol: " . $e->getMessage());
            return false;
        }
    }

    public function eliminar($id_rol) {
        try {
            $stmt = $this->db->prepare("SELECT COUNT(*) as total FROM usuario WHERE id_rol = ?");
            if (!$stmt) {
                throw new Exception("Error al preparar consulta: " . $this->db->error);
            }
            
            $stmt->bind_param("i", $id_rol);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            $stmt->close();
            
            if ($row['total'] > 0) {
                return false;
            }
            
            $stmt = $this->db->prepare("DELETE FROM rol WHERE id_rol = ?");
            if (!$stmt) {
                throw new Exception("Error al preparar consulta: " . $this->db->error);
            }
            
            $stmt->bind_param("i", $id_rol);
            $result = $stmt->execute();
            $stmt->close();
            
            return $result;
        } catch (Exception $e) {
            error_log("Error en eliminar rol: " . $e->getMessage());
            return false;
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

    public function existePorNombre($nombre, $id_rol_excluir = null) {
        try {
            if ($id_rol_excluir) {
                $stmt = $this->db->prepare("SELECT COUNT(*) as total FROM rol WHERE nombre = ? AND id_rol != ?");
                if (!$stmt) {
                    throw new Exception("Error al preparar consulta: " . $this->db->error);
                }
                $stmt->bind_param("si", $nombre, $id_rol_excluir);
            } else {
                $stmt = $this->db->prepare("SELECT COUNT(*) as total FROM rol WHERE nombre = ?");
                if (!$stmt) {
                    throw new Exception("Error al preparar consulta: " . $this->db->error);
                }
                $stmt->bind_param("s", $nombre);
            }
            
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            $stmt->close();
            
            return $row['total'] > 0;
        } catch (Exception $e) {
            error_log("Error en existePorNombre: " . $e->getMessage());
            return false;
        }
    }
}
?>
