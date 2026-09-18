<?php
require_once 'app/config/db.php';

class Vacuna {
    private $db;

    public function __construct() {
        $this->db = Database::connect();
    }

    public function registrarVacunaPaciente($nombre_completo, $fecha_vacunacion, $tiempo_tratamiento, $dosis, $descripcion, $id_vacuna) {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        if (!isset($_SESSION['user']['id'])) {
            error_log("ERROR: No hay sesión de usuario activa.");
            return false;
        }
        
        $id_usuario = $_SESSION['user']['id'];
        error_log("DEBUG: Usuario en sesión: " . $id_usuario);
        
        try {
            $stmt = $this->db->prepare("INSERT INTO vacuna_paciente (nombre_completo, fecha_vacunacion, tiempo_tratamiento, dosis, descripcion, id_usuario, id_vacuna) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sssssii", $nombre_completo, $fecha_vacunacion, $tiempo_tratamiento, $dosis, $descripcion, $id_usuario, $id_vacuna);
            return $stmt->execute();
        } catch (Exception $e) {
            error_log("Error en registrarVacunaPaciente: " . $e->getMessage());
            return false;
        }
    }

    public function registrarVacunaAdmin($nombre_completo, $fecha_vacunacion, $tiempo_tratamiento, $dosis, $descripcion, $cedula_paciente, $id_vacuna) {
        $paciente = $this->buscarPacientePorCedula($cedula_paciente);
        if (!$paciente) {
            error_log("ERROR: Paciente no encontrado con cédula: " . $cedula_paciente);
            return false;
        }
        
        $id_usuario = $paciente['id_usuario'];
        
        try {
            $stmt = $this->db->prepare("INSERT INTO vacuna_paciente (nombre_completo, fecha_vacunacion, tiempo_tratamiento, dosis, descripcion, id_usuario, id_vacuna) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sssssii", $nombre_completo, $fecha_vacunacion, $tiempo_tratamiento, $dosis, $descripcion, $id_usuario, $id_vacuna);
            return $stmt->execute();
        } catch (Exception $e) {
            error_log("Error en registrarVacunaAdmin: " . $e->getMessage());
            return false;
        }
    }

    public function obtenerTodas() {
        try {
            $stmt = $this->db->prepare("
                SELECT vp.*, 
                       u.cedula_usuario, 
                       CONCAT(u.nombre, ' ', u.apellidos) as nombre_paciente,
                       v.nombre as nombre_vacuna
                FROM vacuna_paciente vp
                LEFT JOIN usuario u ON vp.id_usuario = u.id_usuario
                LEFT JOIN vacuna v ON vp.id_vacuna = v.id_vacuna
                ORDER BY vp.fecha_vacunacion DESC
            ");
            $stmt->execute();
            return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        } catch (Exception $e) {
            error_log("Error en obtenerTodas: " . $e->getMessage());
            return [];
        }
    }

    public function obtenerPorUsuario($id_usuario) {
        try {
            $stmt = $this->db->prepare("
                SELECT vp.*, 
                       v.nombre as nombre_vacuna
                FROM vacuna_paciente vp
                LEFT JOIN vacuna v ON vp.id_vacuna = v.id_vacuna
                WHERE vp.id_usuario = ? 
                ORDER BY vp.fecha_vacunacion DESC
            ");
            $stmt->bind_param("i", $id_usuario);
            $stmt->execute();
            return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        } catch (Exception $e) {
            error_log("Error en obtenerPorUsuario: " . $e->getMessage());
            return [];
        }
    }

    public function obtenerPorId($id_vacuna_paciente) {
        try {
            $stmt = $this->db->prepare("
                SELECT vp.*, 
                       v.nombre as nombre_vacuna,
                       CONCAT(u.nombre, ' ', u.apellidos) as nombre_paciente_completo
                FROM vacuna_paciente vp
                LEFT JOIN vacuna v ON vp.id_vacuna = v.id_vacuna
                LEFT JOIN usuario u ON vp.id_usuario = u.id_usuario
                WHERE vp.id_vacuna_paciente = ?
            ");
            $stmt->bind_param("i", $id_vacuna_paciente);
            $stmt->execute();
            return $stmt->get_result()->fetch_assoc();
        } catch (Exception $e) {
            error_log("Error en obtenerPorId: " . $e->getMessage());
            return null;
        }
    }

    public function obtenerVacunaPacientePorId($id_vacuna_paciente) {
        try {
            $stmt = $this->db->prepare("
                SELECT vp.*, 
                    v.nombre as nombre_vacuna,
                    u.cedula_usuario,
                    CONCAT(u.nombre, ' ', u.apellidos) as nombre_paciente
                FROM vacuna_paciente vp
                INNER JOIN usuario u ON vp.id_usuario = u.id_usuario
                LEFT JOIN vacuna v ON vp.id_vacuna = v.id_vacuna
                WHERE vp.id_vacuna_paciente = ?
            ");
            $stmt->bind_param("i", $id_vacuna_paciente);
            $stmt->execute();
            return $stmt->get_result()->fetch_assoc();
        } catch (Exception $e) {
            error_log("Error en obtenerVacunaPacientePorId: " . $e->getMessage());
            return null;
        }
    }

    public function actualizar($id_vacuna_paciente, $nombre_completo, $fecha_vacunacion, $tiempo_tratamiento, $dosis, $descripcion, $id_vacuna) {
        try {
            $stmt = $this->db->prepare("UPDATE vacuna_paciente SET nombre_completo = ?, fecha_vacunacion = ?, tiempo_tratamiento = ?, dosis = ?, descripcion = ?, id_vacuna = ? WHERE id_vacuna_paciente = ?");
            $stmt->bind_param("sssssii", $nombre_completo, $fecha_vacunacion, $tiempo_tratamiento, $dosis, $descripcion, $id_vacuna, $id_vacuna_paciente);
            return $stmt->execute();
        } catch (Exception $e) {
            error_log("Error en actualizar: " . $e->getMessage());
            return false;
        }
    }

    public function eliminar($id_vacuna_paciente) {
        try {
            $stmt = $this->db->prepare("DELETE FROM vacuna_paciente WHERE id_vacuna_paciente = ?");
            $stmt->bind_param("i", $id_vacuna_paciente);
            return $stmt->execute();
        } catch (Exception $e) {
            error_log("Error en eliminar: " . $e->getMessage());
            return false;
        }
    }

    public function obtenerVacunasDisponibles() {
        try {
            $stmt = $this->db->prepare("
                SELECT v.*, 
                       e.nombre as enfermedad,
                       ev.nombre as esquema_vacunacion,
                       va.nombre as via_administracion
                FROM vacuna v
                LEFT JOIN enfermedad e ON v.id_enfermedad = e.id_enfermedad
                LEFT JOIN esquema_vacunacion ev ON v.id_esquema_vacunacion = ev.id_esquema
                LEFT JOIN via_administracion va ON v.id_via_administracion = va.id_via_administracion
                WHERE v.id_estado = 1 
                ORDER BY v.nombre
            ");
            $stmt->execute();
            return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        } catch (Exception $e) {
            error_log("Error en obtenerVacunasDisponibles: " . $e->getMessage());
            return [];
        }
    }

    public function obtenerCatalogoVacunas() {
        try {
            $stmt = $this->db->prepare("
                SELECT id_vacuna, 
                       nombre 
                FROM vacuna 
                WHERE id_estado = 1 
                ORDER BY nombre
            ");
            $stmt->execute();
            return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        } catch (Exception $e) {
            error_log("Error en obtenerCatalogoVacunas: " . $e->getMessage());
            return [];
        }
    }

    public function buscarPacientePorCedula($cedula) {
        try {
            $stmt = $this->db->prepare("
                SELECT u.id_usuario, 
                       u.cedula_usuario, 
                       CONCAT(u.nombre, ' ', u.apellidos) as nombre_completo,
                       u.nombre, 
                       u.apellidos, 
                       u.correo, 
                       u.telefono
                FROM usuario u
                WHERE u.cedula_usuario = ? AND u.id_rol = 3 AND u.id_estado = 1
            ");
            $stmt->bind_param("s", $cedula);
            $stmt->execute();
            return $stmt->get_result()->fetch_assoc();
        } catch (Exception $e) {
            error_log("Error en buscarPacientePorCedula: " . $e->getMessage());
            return null;
        }
    }

    public function obtenerVacunasUsuarioSesion() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        if (!isset($_SESSION['user']['id'])) {
            error_log("ERROR: No hay sesión de usuario activa para obtener vacunas.");
            return [];
        }
        
        $id_usuario = $_SESSION['user']['id'];
        error_log("DEBUG: Obteniendo vacunas para usuario: " . $id_usuario);
        
        return $this->obtenerPorUsuario($id_usuario);
    }

    public function obtenerPorFecha($fecha) {
        try {
            $stmt = $this->db->prepare("
                SELECT vp.*, 
                       v.nombre as nombre_vacuna,
                       CONCAT(u.nombre, ' ', u.apellidos) as nombre_paciente_completo
                FROM vacuna_paciente vp
                LEFT JOIN vacuna v ON vp.id_vacuna = v.id_vacuna
                LEFT JOIN usuario u ON vp.id_usuario = u.id_usuario
                WHERE vp.fecha_vacunacion = ? 
                ORDER BY vp.nombre_completo
            ");
            $stmt->bind_param("s", $fecha);
            $stmt->execute();
            return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        } catch (Exception $e) {
            error_log("Error en obtenerPorFecha: " . $e->getMessage());
            return [];
        }
    }

    public function verificarVacunaAsignada($id_vacuna, $id_usuario) {
        try {
            $stmt = $this->db->prepare("
                SELECT COUNT(*) as total 
                FROM vacuna_paciente 
                WHERE id_vacuna = ? AND id_usuario = ?
            ");
            $stmt->bind_param("ii", $id_vacuna, $id_usuario);
            $stmt->execute();
            $result = $stmt->get_result()->fetch_assoc();
            return $result['total'] > 0;
        } catch (Exception $e) {
            error_log("Error en verificarVacunaAsignada: " . $e->getMessage());
            return false;
        }
    }
    
    public function obtenerEstadisticasPaciente($id_usuario) {
        try {
            $stmt = $this->db->prepare("
                SELECT COUNT(*) as total_vacunas,
                       COUNT(CASE WHEN YEAR(fecha_vacunacion) = YEAR(CURDATE()) THEN 1 END) as vacunas_este_ano
                FROM vacuna_paciente 
                WHERE id_usuario = ?
            ");
            $stmt->bind_param("i", $id_usuario);
            $stmt->execute();
            return $stmt->get_result()->fetch_assoc();
        } catch (Exception $e) {
            error_log("Error en obtenerEstadisticasPaciente: " . $e->getMessage());
            return null;
        }
    }

    public function crearVacunaCatalogo($nombre, $id_enfermedad, $id_esquema_vacunacion, $id_via_administracion, $id_estado = 1) {
        try {
            $stmt = $this->db->prepare("INSERT INTO vacuna (nombre, id_enfermedad, id_esquema_vacunacion, id_via_administracion, id_estado) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("siiii", $nombre, $id_enfermedad, $id_esquema_vacunacion, $id_via_administracion, $id_estado);
            return $stmt->execute();
        } catch (Exception $e) {
            error_log("Error en crearVacunaCatalogo: " . $e->getMessage());
            return false;
        }
    }

    public function obtenerTodasVacunasCatalogo() {
        try {
            $stmt = $this->db->prepare("
                SELECT v.*, 
                    e.nombre as enfermedad,
                    ev.nombre as esquema_vacunacion,
                    va.nombre as via_administracion,
                    CASE 
                        WHEN v.id_estado = 1 THEN 'Activo'
                        WHEN v.id_estado = 2 THEN 'Inactivo'
                        ELSE 'Desconocido'
                    END as estado
                FROM vacuna v
                LEFT JOIN enfermedad e ON v.id_enfermedad = e.id_enfermedad
                LEFT JOIN esquema_vacunacion ev ON v.id_esquema_vacunacion = ev.id_esquema
                LEFT JOIN via_administracion va ON v.id_via_administracion = va.id_via_administracion
                ORDER BY v.nombre ASC
            ");
            $stmt->execute();
            return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        } catch (Exception $e) {
            error_log("Error en obtenerTodasVacunasCatalogo: " . $e->getMessage());
            return [];
        }
    }

    public function obtenerVacunaCatalogoPorId($id_vacuna) {
        try {
            $stmt = $this->db->prepare("
                SELECT v.*, 
                    e.nombre as enfermedad,
                    ev.nombre as esquema_vacunacion,
                    va.nombre as via_administracion,
                    CASE 
                        WHEN v.id_estado = 1 THEN 'Activo'
                        WHEN v.id_estado = 2 THEN 'Inactivo'
                        ELSE 'Desconocido'
                    END as estado
                FROM vacuna v
                LEFT JOIN enfermedad e ON v.id_enfermedad = e.id_enfermedad
                LEFT JOIN esquema_vacunacion ev ON v.id_esquema_vacunacion = ev.id_esquema
                LEFT JOIN via_administracion va ON v.id_via_administracion = va.id_via_administracion
                WHERE v.id_vacuna = ?
            ");
            $stmt->bind_param("i", $id_vacuna);
            $stmt->execute();
            return $stmt->get_result()->fetch_assoc();
        } catch (Exception $e) {
            error_log("Error en obtenerVacunaCatalogoPorId: " . $e->getMessage());
            return null;
        }
    }

    public function actualizarVacunaCatalogo($id_vacuna, $nombre, $id_enfermedad, $id_esquema_vacunacion, $id_via_administracion, $id_estado) {
        try {
            $stmt = $this->db->prepare("UPDATE vacuna SET nombre = ?, id_enfermedad = ?, id_esquema_vacunacion = ?, id_via_administracion = ?, id_estado = ?WHERE id_vacuna = ?");
            $stmt->bind_param("siiiii", $nombre, $id_enfermedad, $id_esquema_vacunacion, $id_via_administracion, $id_estado, $id_vacuna);
            return $stmt->execute();
        } catch (Exception $e) {
            error_log("Error en actualizarVacunaCatalogo: " . $e->getMessage());
            return false;
        }
    }

    public function actualizarEstadoVacunaCatalogo($id_vacuna, $id_estado) {
        try {
            $stmt = $this->db->prepare("UPDATE vacuna SET id_estado = ? WHERE id_vacuna = ?");
            $stmt->bind_param("ii", $id_estado, $id_vacuna);
            return $stmt->execute();
        } catch (Exception $e) {
            error_log("Error en actualizarEstadoVacunaCatalogo: " . $e->getMessage());
            return false;
        }
    }

    public function eliminarVacunaCatalogo($id_vacuna) {
        try {
            $stmt = $this->db->prepare("DELETE FROM vacuna WHERE id_vacuna = ?");
            $stmt->bind_param("i", $id_vacuna);
            return $stmt->execute();
        } catch (Exception $e) {
            error_log("Error en eliminarVacunaCatalogo: " . $e->getMessage());
            return false;
        }
    }

    public function buscarVacunasCatalogo($termino) {
        try {
            $termino = "%$termino%";
            $stmt = $this->db->prepare("
                SELECT v.*, 
                    e.nombre as enfermedad,
                    ev.nombre as esquema_vacunacion,
                    va.nombre as via_administracion,
                    CASE 
                        WHEN v.id_estado = 1 THEN 'Activo'
                        WHEN v.id_estado = 2 THEN 'Inactivo'
                        ELSE 'Desconocido'
                    END as estado
                FROM vacuna v
                LEFT JOIN enfermedad e ON v.id_enfermedad = e.id_enfermedad
                LEFT JOIN esquema_vacunacion ev ON v.id_esquema_vacunacion = ev.id_esquema
                LEFT JOIN via_administracion va ON v.id_via_administracion = va.id_via_administracion
                WHERE v.nombre LIKE ? OR e.nombre LIKE ? OR ev.nombre LIKE ? OR va.nombre LIKE ?
                ORDER BY v.nombre ASC
            ");
            $stmt->bind_param("ssss", $termino, $termino, $termino, $termino);
            $stmt->execute();
            return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        } catch (Exception $e) {
            error_log("Error en buscarVacunasCatalogo: " . $e->getMessage());
            return [];
        }
    }

    public function obtenerEnfermedades() {
        try {
            $stmt = $this->db->prepare("SELECT * FROM enfermedad ORDER BY nombre");
            $stmt->execute();
            return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        } catch (Exception $e) {
            error_log("Error en obtenerEnfermedades: " . $e->getMessage());
            return [];
        }
    }

    public function obtenerEsquemasVacunacion() {
        try {
            $stmt = $this->db->prepare("SELECT * FROM esquema_vacunacion ORDER BY nombre");
            $stmt->execute();
            return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        } catch (Exception $e) {
            error_log("Error en obtenerEsquemasVacunacion: " . $e->getMessage());
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

    public function obtenerEstados() {
        try {
            return [
                ['id_estado' => 1, 'nombre' => 'Activo'],
                ['id_estado' => 2, 'nombre' => 'Inactivo']
            ];
        } catch (Exception $e) {
            error_log("Error en obtenerEstados: " . $e->getMessage());
            return [];
        }
    }

    public function verificarNombreVacunaExiste($nombre, $id_vacuna_excluir = null) {
        try {
            $sql = "SELECT COUNT(*) as total FROM vacuna WHERE nombre = ?";
            $params = [$nombre];
            $types = "s";

            if ($id_vacuna_excluir !== null) {
                $sql .= " AND id_vacuna != ?";
                $params[] = $id_vacuna_excluir;
                $types .= "i";
            }

            $stmt = $this->db->prepare($sql);
            $stmt->bind_param($types, ...$params);
            $stmt->execute();
            $result = $stmt->get_result()->fetch_assoc();
            return $result['total'] > 0;
        } catch (Exception $e) {
            error_log("Error en verificarNombreVacunaExiste: " . $e->getMessage());
            return false;
        }
    }

    public function obtenerEstadisticasGenerales() {
        try {
            $stmt = $this->db->prepare("
                SELECT 
                    COUNT(*) as total_vacunas_catalogo,
                    COUNT(CASE WHEN id_estado = 1 THEN 1 END) as vacunas_activas,
                    COUNT(CASE WHEN id_estado = 2 THEN 1 END) as vacunas_inactivas
                FROM vacuna
            ");
            $stmt->execute();
            return $stmt->get_result()->fetch_assoc();
        } catch (Exception $e) {
            error_log("Error en obtenerEstadisticasGenerales: " . $e->getMessage());
            return null;
        }
    }

    public function obtenerEstadisticasVacunacionesAplicadas() {
        try {
            $stmt = $this->db->prepare("
                SELECT 
                    COUNT(*) as total_vacunaciones,
                    COUNT(CASE WHEN YEAR(fecha_vacunacion) = YEAR(CURDATE()) THEN 1 END) as vacunaciones_este_ano,
                    COUNT(CASE WHEN MONTH(fecha_vacunacion) = MONTH(CURDATE()) AND YEAR(fecha_vacunacion) = YEAR(CURDATE()) THEN 1 END) as vacunaciones_este_mes
                FROM vacuna_paciente
            ");
            $stmt->execute();
            return $stmt->get_result()->fetch_assoc();
        } catch (Exception $e) {
            error_log("Error en obtenerEstadisticasVacunacionesAplicadas: " . $e->getMessage());
            return null;
        }
    }

    public function obtenerVacunasMasAplicadas($limite = 10) {
        try {
            $stmt = $this->db->prepare("
                SELECT v.nombre, COUNT(vp.id_vacuna) as total_aplicaciones
                FROM vacuna v
                LEFT JOIN vacuna_paciente vp ON v.id_vacuna = vp.id_vacuna
                GROUP BY v.id_vacuna, v.nombre
                ORDER BY total_aplicaciones DESC
                LIMIT ?
            ");
            $stmt->bind_param("i", $limite);
            $stmt->execute();
            return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        } catch (Exception $e) {
            error_log("Error en obtenerVacunasMasAplicadas: " . $e->getMessage());
            return [];
        }
    }
}
?>
