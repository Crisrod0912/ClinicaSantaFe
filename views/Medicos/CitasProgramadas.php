<?php
session_start();
echo "<!-- Session debug: " . print_r($_SESSION, true) . " -->";
?>

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
    <?php include("../components/MenuMedico.php") ?>

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <h1 class="text-center mb-5">Citas</h1>

                <div class="mb-4">
                    <input type="text" class="search-input w-100" placeholder="Buscar">
                </div>

                <table class="custom-table table">
                    <thead>
                        <tr>
                            <th>Cédula</th>
                            <th>Nombre Paciente</th>
                            <th>Fecha</th>
                            <th>Hora</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody id="citasTableBody">
                        <tr>
                            <td colspan="6" class="text-center">Cargando citas...</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="../js/jquery-3.7.1.min.js"></script>
    <script src="../js/medico-citas.js"></script>
</body>

</html>
