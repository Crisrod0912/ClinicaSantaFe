<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);

error_log("Router iniciado - Action: " . ($_GET['action'] ?? 'none'));

try {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    require_once 'app/controllers/AuthController.php';
    require_once 'app/controllers/CitaController.php';
    require_once 'app/controllers/ExpedienteController.php';
    require_once 'app/controllers/MedicamentoController.php';
    require_once 'app/controllers/VacunaController.php';
    require_once 'app/controllers/RolController.php';
    require_once 'app/controllers/UsuarioController.php';
    require_once 'app/controllers/MedicoEspecialidadController.php';

    $protectedRoutes = [
        'createCitaPatient', 'listMyCitas', 'showExpediente', 'updateExpediente',
        'listMisMedicamentos', 'showMedicamentoPaciente', 
        'createVacunaPatient', 'listMyVaccines', 'showVacunaPaciente', 'updateVacuna', 'deleteVacuna',
        'listMySpecialties','getCatalogoMedicamentos', 'buscarPacienteMedicamento',
        'asignarMedicamento', 'listMedicacionesPacientes', 'listCitasByMedico'
    ];

    $action = $_GET['action'] ?? '';

    if (in_array($action, $protectedRoutes) && !isset($_SESSION['user']['id'])) {
        ob_clean();
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(['success' => false, 'message' => 'Sesión no iniciada'], JSON_UNESCAPED_UNICODE);
        exit;
    }

    ob_clean();

    $auth = new AuthController();
    $cita = new CitaController();
    $expediente = new ExpedienteController();
    $medicamento = new MedicamentoController();
    $vacuna = new VacunaController();
    $rol = new RolController();
    $usuario = new UsuarioController();
    $medicoEspecialidad = new MedicoEspecialidadController();

    switch ($action) {
        case 'login':
            $auth->login();
            break;
        case 'register':
            $auth->register();
            break;
        case 'logout':
            $auth->logout();
            break;
        case 'createCita':
            $cita->create();
            break;
        case 'createCitaPatient':
            $cita->createForPatient();
            break;
        case 'listCitas':
            $cita->list();
            break;
        case 'listCitasByUser':
            $cita->listByUser();
            break;
        case 'listMyAppointments':
            $cita->listMyAppointments();
            break;
        case 'showCita':
            $cita->show();
            break;
        case 'updateCita':
            $cita->update();
            break;
        case 'updateCitaAdmin':
            $cita->updateCitaAdmin();
            break;
        case 'updateCitaStatus':
            $cita->updateStatus();
            break;
        case 'deleteCita':
            $cita->delete();
            break;
        case 'checkAvailability':
            $cita->checkAvailability();
            break;
        case 'getCitasByDate':
            $cita->getByDate();
            break;
        case 'getAvailableDoctors':
            $cita->getAvailableDoctors();
            break;
        case 'searchPatient':
            $cita->searchPatient();
            break;
        case 'getSpecialties':
            $cita->getSpecialties();
            break;
        case 'getServices':
            $cita->getServices();
            break;
        case 'listCitasByMedico':
            $cita->listMyAppointmentsAsDoctor();
            break;
        case 'showExpediente':
            $expediente->show();
            break;
        case 'updateExpediente':
            $expediente->update();
            break;
        case 'showExpedienteByUser':
            $expediente->showByUser();
            break;
        case 'searchPatientByCedula':
            $expediente->searchPatient();
            break;
        case 'listExpedientes':
            $expediente->list();
            break;
        case 'createMedicamento':
            $medicamento->create();
            break;
        case 'listMedicamentos':
            $medicamento->list();
            break;
        case 'showMedicamento':
            $medicamento->show();
            break;
        case 'updateMedicamento':
            $medicamento->update();
            break;
        case 'updateMedicamentoStatus':
            $medicamento->updateStatus();
            break;
        case 'deleteMedicamento':
            $medicamento->delete();
            break;
        case 'searchMedicamentos':
            $medicamento->search();
            break;
        case 'getFormasFarmaceuticas':
            $medicamento->getFormasFarmaceuticas();
            break;
        case 'getGruposTerapeuticos':
            $medicamento->getGruposTerapeuticos();
            break;
        case 'getViasAdministracion':
            $medicamento->getViasAdministracion();
            break;
        case 'asignarMedicamento':
            $medicamento->asignar();
            break;
        case 'listMisMedicamentos':
            $medicamento->listMisMedicamentos();
            break;
        case 'listMedicamentosPaciente':
            $medicamento->listMedicamentosPaciente();
            break;
        case 'listMedicacionesPacientes':
            $medicamento->listMedicacionesPacientes();
            break;
        case 'showMedicamentoPaciente':
            $medicamento->showMedicamentoPaciente();
            break;
        case 'actualizarMedicamentoPaciente':
            $medicamento->actualizar();
            break;
        case 'actualizarEstadoMedicamentoPaciente':
            $medicamento->actualizarEstado();
            break;
        case 'eliminarMedicamentoPaciente':
            $medicamento->eliminar();
            break;
        case 'listMedicamentosActivos':
            $medicamento->listMedicamentosActivos();
            break;
        case 'buscarPacienteMedicamento':
            $medicamento->buscarPaciente();
            break;
        case 'getCatalogoMedicamentos':
            $medicamento->getCatalogoMedicamentos();
            break;
        case 'createVacuna':
            $vacuna->create();
            break;
        case 'createVacunaPatient':
            $vacuna->createForPatient();
            break;
        case 'listVacunas':
            $vacuna->list();
            break;
        case 'listVacunasByUser':
            $vacuna->listByUser();
            break;
        case 'listMyVaccines':
            $vacuna->listMyVaccines();
            break;
        case 'showVacuna':
            $vacuna->show();
            break;
        case 'showVacunaPaciente':
            $vacuna->showVacunaPaciente();
            break;
        case 'updateVacuna':
            $vacuna->update();
            break;
        case 'deleteVacuna':
            $vacuna->delete();
            break;
        case 'getVacunasByDate':
            $vacuna->getByDate();
            break;
        case 'getAvailableVaccines':
            $vacuna->getAvailableVaccines();
            break;
        case 'searchPatientVacuna':
            $vacuna->searchPatient();
            break;
        case 'getVacunasCatalogo':
            $vacuna->getVacunasCatalogo();
            break;
        case 'createVacunaCatalogo':
            $vacuna->createVacunaCatalogo();
            break;
        case 'listVacunasCatalogo':
            $vacuna->listVacunasCatalogo();
            break;
        case 'showVacunaCatalogo':
            $vacuna->showVacunaCatalogo();
            break;
        case 'updateVacunaCatalogo':
            $vacuna->updateVacunaCatalogo();
            break;
        case 'updateVacunaStatus':
            $vacuna->updateVacunaStatus();
            break;
        case 'deleteVacunaCatalogo':
            $vacuna->deleteVacunaCatalogo();
            break;
        case 'searchVacunasCatalogo':
            $vacuna->searchVacunasCatalogo();
            break;
        case 'getEnfermedades':
            $vacuna->getEnfermedades();
            break;
        case 'getEsquemasVacunacion':
            $vacuna->getEsquemasVacunacion();
            break;
        case 'getStatesVacuna':
            $vacuna->getStatesVacuna();
            break;
        case 'getViasAdministracion':
            $vacuna->getViasAdministracion();
            break;
        case 'createRol':
            $rol->create();
            break;
        case 'listRoles':
            $rol->list();
            break;
        case 'showRol':
            $rol->show();
            break;
        case 'updateRol':
            $rol->update();
            break;
        case 'updateRolStatus':
            $rol->updateStatus();
            break;
        case 'deleteRol':
            $rol->delete();
            break;
        case 'getStatesRol':
            $rol->getStates();
            break;
        case 'createUsuario':
            $usuario->create();
            break;
        case 'listUsuarios':
            $usuario->list();
            break;
        case 'showUsuario':
            $usuario->show();
            break;
        case 'updateUsuario':
            $usuario->update();
            break;
        case 'updateUsuarioStatus':
            $usuario->updateStatus();
            break;
        case 'deleteUsuario':
            $usuario->delete();
            break;
        case 'getStatesUsuario':
            $usuario->getStates();
            break;
        case 'getRolesUsuario':
            $usuario->getRoles();
            break;
        case 'getGenerosUsuario':
            $usuario->getGeneros();
            break;
        case 'getEstadosCivilesUsuario':
            $usuario->getEstadosCiviles();
            break;
        case 'createMedicoEspecialidad':
            $medicoEspecialidad->create();
            break;
        case 'listMedicoEspecialidad':
            $medicoEspecialidad->list();
            break;
        case 'listMySpecialties':
            $medicoEspecialidad->listMySpecialties();
            break;
        case 'showMedicoEspecialidad':
            $medicoEspecialidad->show();
            break;
        case 'updateMedicoEspecialidad':
            $medicoEspecialidad->update();
            break;
        case 'updateMedicoEspecialidadStatus':
            $medicoEspecialidad->updateStatus();
            break;
        case 'deleteMedicoEspecialidad':
            $medicoEspecialidad->delete();
            break;
        case 'getMedicosME':
            $medicoEspecialidad->getMedicos();
            break;
        case 'getEspecialidadesME':
            $medicoEspecialidad->getEspecialidades();
            break;
        case 'getStatesME':
            $medicoEspecialidad->getStates();
            break;

        case 'updateExpedienteAdmin':
            $expediente->updateExpedienteAdmin();
            break;
        
        default:
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode(['success' => false, 'message' => 'Ruta no encontrada: ' . $action], JSON_UNESCAPED_UNICODE);
    }

} catch (Exception $e) {
    ob_clean();
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode([
        'success' => false, 
        'message' => 'Error del sistema: ' . $e->getMessage()
    ], JSON_UNESCAPED_UNICODE);
}
?>
