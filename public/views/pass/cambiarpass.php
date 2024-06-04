<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="../../../bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <?php 
        include("../../../app/Models/conexion.php");
    ?>

    <link rel="stylesheet" href="../../css/login.css">
</head>
<body>
    <div class="contenedor rounded shadow"> <!-- CUERPO DE LA PAGINA -->
        <div class="row m-2 align-items-stretch"> <!-- CUERPO DEL LOGIN -->
        <!-- COLUMNAS -->
            <div class="col form">
                <h2 class="text-center bp-3"> Restablecer Contraseña </h2> <br>
                <form method="post" action=""> <!-- ESTE ES EL FORMULARIO -->
                <?php
                    include("../../../app/controllers/cambiarpass_controller.php");
                ?>
                    <!-- id del usuario -->
                    <input type="hidden" name="id" aria-describedby="basic-addon3" value='<?php echo $_GET["id"] ?>'>
                    <!-- Email input -->
                    <input type="password" name="pass1" class="form-control form-control-sm" placeholder="Nueva Contraseña" aria-describedby="basic-addon3" required>
                    <br>
                    <!-- Email input2 -->
                    <input type="password" name="pass2" class="form-control form-control-sm" placeholder="Repita Nueva Contraseña" aria-describedby="basic-addon3">
                    <div class="form-text" id="basic-addon3">Asegurate que coincidan ambos campos.</div>
                    <br>

                    <!-- Submit -->
                    <br>
                    <center><input type="submit" name="btncambiarpass" class="btn btn-success" value="Cambiar Contraseña"></center>
                </form>
            </div>
        </div> 
    </div>








    <script src="../../../bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>