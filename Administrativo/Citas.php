<!DOCTYPE html>

<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Citas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/style.css" />
</head>

<body>
    <?php include("../components/MenuAdmin.php") ?>

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <h1 class="text-center mb-5">Citas</h1>
                <div class="row mb-4">
                    <div class="col-md-12">
                        <input type="text" class="form-control" placeholder="Buscar">
                    </div>
                </div>
                <div class="row mb-4">
                    <div class="col-4">
                        <a type="button" class="btn btn-nuevo" href="RegistrarCita.html">+ Registrar Cita</a>
                    </div>
                </div>
                <table class="custom-table table">
                    <thead>
                        <tr>
                            <th>Cédula</th>
                            <th>Nombre Paciente</th>
                            <th>Fecha</th>
                            <th>Hora</th>
                            <th>Servicio</th>
                            <th>Especialidad</th>
                            <th>Médico</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/js/all.min.js"></script>
    <script src="../js/jquery-3.7.1.min.js"></script>
    <script src="../js/admin-citas.js"></script>
</body>

</html>
