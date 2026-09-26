<!DOCTYPE html>

<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Mis Vacunas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/style.css" />
</head>

<body>
    <?php include("../components/MenuPaciente.php") ?>

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <h1 class="text-center mb-5">Mis Vacunas</h1>

                <div class="row mb-4">
                    <div class="col-md-12">
                        <input type="text" id="buscarVacuna" class="form-control" placeholder="Buscar vacuna">
                    </div>
                </div>

                <table class="table custom-table">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Descripción</th>
                            <th>Fecha</th>
                            <th>Dosis</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="vacunasTableBody">
                    </tbody>
                </table>

                <div id="vacunasResult" class="mt-3"></div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="vacunaModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content" style="border-radius: 20px; border: none;">
                <div class="modal-header"
                    style="background-color: #4B94F2; color: white; border-radius: 20px 20px 0 0; border: none;">
                    <h5 class="modal-title" style="font-weight: 500;">Detalles de la Vacuna</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" style="padding: 30px; background-color: #f8f9fa;">
                    <div id="vacunaDetalles"></div>
                </div>
                <div class="modal-footer"
                    style="background-color: #f8f9fa; border: none; border-radius: 0 0 20px 20px; padding: 20px 30px;">
                    <button type="button" class="btn-register" style="padding: 8px 30px; font-size: 16px;" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <script src="../js/jquery-3.7.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="../js/vacuna.js"></script>
</body>

</html>
