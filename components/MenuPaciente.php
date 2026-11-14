<?php
$opcionesPaciente = '
<a class="nav-link" href="HistorialCitas.php">Citas</a>
<a class="nav-link" href="Expediente.php">Expediente</a>
<a class="nav-link" href="Medicamentos.php">Medicamentos</a>
<a class="nav-link" href="Vacunas.php">Vacunas</a>
';
?>

<nav class="navbar navbar-expand-lg navbar-dark">

    <div class="container-fluid">

        <a href="InicioPaciente.php" class="navbar-brand d-flex align-items-center">
            <div class="logo"></div>
        </a>

        <div class="navbar-nav me-auto">
            <?php echo $opcionesPaciente?>
        </div>

        <div class="d-flex align-items-center">
            <a href="../router.php?action=logout" class="text-black me-3 text-decoration-none" onclick="return confirm('Esta seguro que desea cerrar sesion?')">Cerrar sesion</a>
        </div>

    </div>
    
</nav>
