<?php
require_once 'app/models/Medicamento.php';

class MedicamentoController {

    public function asignar() {
        try {
            $medicamento = new Medicamento();
            $nombre_completo = $_POST['nombre_completo'] ?? '';
            $fecha_preescripcion = $_POST['fecha_preescripcion'] ?? date('Y-m-d');
            $tiempo_tratamiento = $_POST['tiempo_tratamiento'] ?? '';
            $indicaciones = $_POST['indicaciones'] ?? '';
            $id_medicamento = (int)($_POST['id_medicamento'] ?? 0);
            $id_paciente = (int)($_POST['id_paciente'] ?? 0);
            $id_estado = (int)($_POST['id_estado'] ?? 1);

            if (empty($nombre_completo) || empty($tiempo_tratamiento) || empty($indicaciones) || $id_medicamento == 0 || $id_paciente == 0) {
                echo json_encode(['status' => 'error', 'message' => 'Todos los campos son requeridos.']);
                return;
            }

            if ($medicamento->verificarMedicamentoAsignado($id_medicamento, $id_paciente)) {
                echo json_encode(['status' => 'error', 'message' => 'Este medicamento se encuentra asignado al paciente.']);
                return;
            }

            if ($medicamento->asignarMedicamento($nombre_completo, $fecha_preescripcion, $tiempo_tratamiento, $indicaciones, $id_medicamento, $id_paciente, $id_estado)) {
                echo json_encode(['status' => 'success', 'message' => 'Medicamento asignado exitosamente.']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'No se pudo asignar el medicamento.']);
            }
        } catch (Exception $e) {
            error_log("Error en asignar: " . $e->getMessage());
            echo json_encode(['status' => 'error', 'message' => 'Error interno del servidor.']);
        }
    }

    public function listMisMedicamentos() {
        try {
            $medicamento = new Medicamento();
            $medicamentos = $medicamento->obtenerMedicamentosPacienteSesion();

            echo json_encode(['status' => 'success', 'data' => $medicamentos]);
        } catch (Exception $e) {
            error_log("Error en listMisMedicamentos: " . $e->getMessage());
            echo json_encode(['status' => 'error', 'message' => 'Error al obtener medicamentos: ' . $e->getMessage()]);
        }
    }

    public function listMedicamentosPaciente() {
        try {
            $medicamento = new Medicamento();
            $id_paciente = (int)($_GET['id_paciente'] ?? 0);

            if ($id_paciente == 0) {
                echo json_encode(['status' => 'error', 'message' => 'ID de paciente requerido.']);
                return;
            }

            $medicamentos = $medicamento->obtenerMedicamentosPaciente($id_paciente);
            echo json_encode(['status' => 'success', 'data' => $medicamentos]);
        } catch (Exception $e) {
            error_log("Error en listMedicamentosPaciente: " . $e->getMessage());
            echo json_encode(['status' => 'error', 'message' => 'Error al obtener medicamentos del paciente.']);
        }
    }

    public function showMedicamentoPaciente() {
        try {
            $medicamento = new Medicamento();
            $id = (int)($_GET['id'] ?? 0);

            if ($id == 0) {
                echo json_encode(['status' => 'error', 'message' => 'ID requerido.']);
                return;
            }

            $medicamentoData = $medicamento->obtenerMedicamentoPacientePorId($id);

            if ($medicamentoData) {
                echo json_encode(['status' => 'success', 'data' => $medicamentoData]);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Medicamento no encontrado.']);
            }
        } catch (Exception $e) {
            error_log("Error en showMedicamentoPaciente: " . $e->getMessage());
            echo json_encode(['status' => 'error', 'message' => 'Error al obtener el medicamento.']);
        }
    }

    public function actualizar() {
        try {
            $medicamento = new Medicamento();
            $id_medicamento_paciente = (int)($_POST['id_medicamento_paciente'] ?? 0);
            $nombre_completo = $_POST['nombre_completo'] ?? '';
            $fecha_preescripcion = $_POST['fecha_preescripcion'] ?? '';
            $tiempo_tratamiento = $_POST['tiempo_tratamiento'] ?? '';
            $indicaciones = $_POST['indicaciones'] ?? '';
            $id_medicamento = (int)($_POST['id_medicamento'] ?? 0);
            $id_estado = (int)($_POST['id_estado'] ?? 1);

            if ($id_medicamento_paciente == 0) {
                echo json_encode(['status' => 'error', 'message' => 'ID de medicamento requerido.']);
                return;
            }

            if (empty($nombre_completo) || empty($fecha_preescripcion) || empty($tiempo_tratamiento) || empty($indicaciones) || $id_medicamento == 0) {
                echo json_encode(['status' => 'error', 'message' => 'Todos los campos son requeridos.']);
                return;
            }

            if ($medicamento->actualizarMedicamentoPaciente($id_medicamento_paciente, $nombre_completo, $fecha_preescripcion, $tiempo_tratamiento, $indicaciones, $id_medicamento, $id_estado)) {
                echo json_encode(['status' => 'success', 'message' => 'Medicamento actualizado exitosamente.']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'No se pudo actualizar el medicamento.']);
            }
        } catch (Exception $e) {
            error_log("Error en actualizar: " . $e->getMessage());
            echo json_encode(['status' => 'error', 'message' => 'Error al actualizar el medicamento.']);
        }
    }
    
    public function actualizarEstado() {
        try {
            $medicamento = new Medicamento();
            $id_medicamento_paciente = (int)($_POST['id_medicamento_paciente'] ?? 0);
            $id_estado = (int)($_POST['id_estado'] ?? 0);

            if ($id_medicamento_paciente == 0 || $id_estado == 0) {
                echo json_encode(['status' => 'error', 'message' => 'ID de medicamento y estado requeridos.']);
                return;
            }

            if ($medicamento->actualizarEstadoMedicamentoPaciente($id_medicamento_paciente, $id_estado)) {
                echo json_encode(['status' => 'success', 'message' => 'Estado actualizado exitosamente.']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'No se pudo actualizar el estado.']);
            }
        } catch (Exception $e) {
            error_log("Error en actualizarEstado: " . $e->getMessage());
            echo json_encode(['status' => 'error', 'message' => 'Error al actualizar el estado.']);
        }
    }

    public function eliminar() {
        try {
            $medicamento = new Medicamento();
            $id = (int)($_POST['id'] ?? 0);

            if ($id == 0) {
                echo json_encode(['status' => 'error', 'message' => 'ID requerido.']);
                return;
            }

            if ($medicamento->eliminarMedicamentoPaciente($id)) {
                echo json_encode(['status' => 'success', 'message' => 'Medicamento eliminado exitosamente.']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'No se pudo eliminar el medicamento.']);
            }
        } catch (Exception $e) {
            error_log("Error en eliminar: " . $e->getMessage());
            echo json_encode(['status' => 'error', 'message' => 'Error al eliminar el medicamento.']);
        }
    }

    public function listMedicamentosActivos() {
        try {
            $medicamento = new Medicamento();
            $id_paciente = (int)($_GET['id_paciente'] ?? 0);

            if ($id_paciente == 0) {
                if (session_status() == PHP_SESSION_NONE) {
                    session_start();
                }
                
                if (isset($_SESSION['user']['id'])) {
                    $id_paciente = $_SESSION['user']['id'];
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'Paciente no identificado.']);
                    return;
                }
            }

            $medicamentos = $medicamento->obtenerMedicamentosActivosPaciente($id_paciente);
            echo json_encode(['status' => 'success', 'data' => $medicamentos]);
        } catch (Exception $e) {
            error_log("Error en listMedicamentosActivos: " . $e->getMessage());
            echo json_encode(['status' => 'error', 'message' => 'Error al obtener medicamentos activos.']);
        }
    }

    public function buscarPaciente() {
        try {
            $medicamento = new Medicamento();
            $cedula = trim($_GET['cedula'] ?? '');

            if (empty($cedula)) {
                echo json_encode(['status' => 'error', 'message' => 'Cédula requerida.']);
                return;
            }

            $paciente = $medicamento->buscarPacientePorCedula($cedula);

            if ($paciente) {
                echo json_encode(['status' => 'success', 'data' => $paciente]);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Paciente no encontrado.']);
            }
        } catch (Exception $e) {
            error_log("Error en buscarPaciente: " . $e->getMessage());
            echo json_encode(['status' => 'error', 'message' => 'Error al buscar el paciente.']);
        }
    }

    public function getCatalogoMedicamentos() {
        try {
            $medicamento = new Medicamento();
            $medicamentos = $medicamento->obtenerTodos();

            $medicamentosActivos = array_filter($medicamentos, function($med) {
                return $med['id_estado'] == 1;
            });

            echo json_encode(['status' => 'success', 'data' => array_values($medicamentosActivos)]);
        } catch (Exception $e) {
            error_log("Error en getCatalogoMedicamentos: " . $e->getMessage());
            echo json_encode(['status' => 'error', 'message' => 'Error al obtener el catálogo de medicamentos.']);
        }
    }
    
    public function listMedicacionesPacientes() {
        try {
            $medicamento = new Medicamento();
            $medicaciones = $medicamento->obtenerTodasMedicacionesPacientes();

            echo json_encode(['status' => 'success', 'data' => $medicaciones]);
        } catch (Exception $e) {
            error_log("Error en listMedicacionesPacientes: " . $e->getMessage());
            echo json_encode(['status' => 'error', 'message' => 'Error al obtener medicaciones de pacientes.']);
        }
    }

    public function create() {
        try {
            $medicamento = new Medicamento();
            
            $nombre = $_POST['nombre'] ?? '';
            $id_forma_farmaceutica = (int)($_POST['id_forma_farmaceutica'] ?? 0);
            $id_grupo_terapeutico = (int)($_POST['id_grupo_terapeutico'] ?? 0);
            $id_via_administracion = (int)($_POST['id_via_administracion'] ?? 0);
            $id_estado = (int)($_POST['id_estado'] ?? 1);

            if (empty($nombre) || $id_forma_farmaceutica == 0 || $id_grupo_terapeutico == 0 || $id_via_administracion == 0) {
                echo json_encode(['status' => 'error', 'message' => 'Todos los campos son requeridos.']);
                return;
            }

            if ($medicamento->crear($nombre, $id_forma_farmaceutica, $id_grupo_terapeutico, $id_via_administracion, $id_estado)) {
                echo json_encode(['status' => 'success', 'message' => 'Medicamento creado exitosamente.']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'No se pudo crear el medicamento.']);
            }
        } catch (Exception $e) {
            error_log("Error en create: " . $e->getMessage());
            echo json_encode(['status' => 'error', 'message' => 'Error al crear el medicamento.']);
        }
    }

    public function list() {
        try {
            $medicamento = new Medicamento();
            $medicamentos = $medicamento->obtenerTodos();

            echo json_encode(['status' => 'success', 'data' => $medicamentos]);
        } catch (Exception $e) {
            error_log("Error en list: " . $e->getMessage());
            echo json_encode(['status' => 'error', 'message' => 'Error al obtener la lista de medicamentos.']);
        }
    }

    public function show() {
        try {
            $medicamento = new Medicamento();
            $id = (int)($_GET['id'] ?? 0);

            if ($id == 0) {
                echo json_encode(['status' => 'error', 'message' => 'ID requerido.']);
                return;
            }

            $medicamentoData = $medicamento->obtenerPorId($id);

            if ($medicamentoData) {
                echo json_encode(['status' => 'success', 'data' => $medicamentoData]);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Medicamento no encontrado.']);
            }
        } catch (Exception $e) {
            error_log("Error en show: " . $e->getMessage());
            echo json_encode(['status' => 'error', 'message' => 'Error al obtener el medicamento.']);
        }
    }

    public function update() {
        try {
            $medicamento = new Medicamento();
            $id = (int)($_POST['id'] ?? 0);
            $nombre = $_POST['nombre'] ?? '';
            $id_forma_farmaceutica = (int)($_POST['id_forma_farmaceutica'] ?? 0);
            $id_grupo_terapeutico = (int)($_POST['id_grupo_terapeutico'] ?? 0);
            $id_via_administracion = (int)($_POST['id_via_administracion'] ?? 0);
            $id_estado = (int)($_POST['id_estado'] ?? 1);

            if ($id == 0) {
                echo json_encode(['status' => 'error', 'message' => 'ID requerido.']);
                return;
            }

            if (empty($nombre) || $id_forma_farmaceutica == 0 || $id_grupo_terapeutico == 0 || $id_via_administracion == 0) {
                echo json_encode(['status' => 'error', 'message' => 'Todos los campos son requeridos.']);
                return;
            }

            if ($medicamento->actualizar($id, $nombre, $id_forma_farmaceutica, $id_grupo_terapeutico, $id_via_administracion, $id_estado)) {
                echo json_encode(['status' => 'success', 'message' => 'Medicamento actualizado exitosamente.']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'No se pudo actualizar el medicamento.']);
            }
        } catch (Exception $e) {
            error_log("Error en update: " . $e->getMessage());
            echo json_encode(['status' => 'error', 'message' => 'Error al actualizar el medicamento.']);
        }
    }

    public function updateStatus() {
        try {
            $medicamento = new Medicamento();
            $id = (int)($_POST['id'] ?? 0);
            $id_estado = (int)($_POST['id_estado'] ?? 0);

            if ($id == 0 || $id_estado == 0) {
                echo json_encode(['status' => 'error', 'message' => 'ID y estado requeridos.']);
                return;
            }

            if ($medicamento->actualizarEstado($id, $id_estado)) {
                echo json_encode(['status' => 'success', 'message' => 'Estado actualizado exitosamente.']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'No se pudo actualizar el estado.']);
            }
        } catch (Exception $e) {
            error_log("Error en updateStatus: " . $e->getMessage());
            echo json_encode(['status' => 'error', 'message' => 'Error al actualizar el estado.']);
        }
    }
    
    public function delete() {
        try {
            $medicamento = new Medicamento();
            $id = (int)($_POST['id'] ?? 0);

            if ($id == 0) {
                echo json_encode(['status' => 'error', 'message' => 'ID requerido.']);
                return;
            }

            if ($medicamento->eliminar($id)) {
                echo json_encode(['status' => 'success', 'message' => 'Medicamento eliminado exitosamente.']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'No se pudo eliminar el medicamento.']);
            }
        } catch (Exception $e) {
            error_log("Error en delete: " . $e->getMessage());
            echo json_encode(['status' => 'error', 'message' => 'Error al eliminar el medicamento.']);
        }
    }

    public function search() {
        try {
            $medicamento = new Medicamento();
            $termino = $_GET['termino'] ?? '';

            if (empty($termino)) {
                echo json_encode(['status' => 'error', 'message' => 'Término de búsqueda requerido.']);
                return;
            }

            $medicamentos = $medicamento->buscar($termino);
            echo json_encode(['status' => 'success', 'data' => $medicamentos]);
        } catch (Exception $e) {
            error_log("Error en search: " . $e->getMessage());
            echo json_encode(['status' => 'error', 'message' => 'Error en la búsqueda.']);
        }
    }

    public function getFormasFarmaceuticas() {
        try {
            $medicamento = new Medicamento();
            $formas = $medicamento->obtenerFormasFarmaceuticas();
            echo json_encode(['status' => 'success', 'data' => $formas]);
        } catch (Exception $e) {
            error_log("Error en getFormasFarmaceuticas: " . $e->getMessage());
            echo json_encode(['status' => 'error', 'message' => 'Error al obtener formas farmacéuticas.']);
        }
    }

    public function getGruposTerapeuticos() {
        try {
            $medicamento = new Medicamento();
            $grupos = $medicamento->obtenerGruposTerapeuticos();
            echo json_encode(['status' => 'success', 'data' => $grupos]);
        } catch (Exception $e) {
            error_log("Error en getGruposTerapeuticos: " . $e->getMessage());
            echo json_encode(['status' => 'error', 'message' => 'Error al obtener grupos terapéuticos.']);
        }
    }

    public function getViasAdministracion() {
        try {
            $medicamento = new Medicamento();
            $vias = $medicamento->obtenerViasAdministracion();
            echo json_encode(['status' => 'success', 'data' => $vias]);
        } catch (Exception $e) {
            error_log("Error en getViasAdministracion: " . $e->getMessage());
            echo json_encode(['status' => 'error', 'message' => 'Error al obtener vías de administración.']);
        }
    }
}
?>
