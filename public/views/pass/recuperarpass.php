<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enviar Solicitud de Cambio</title>
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
                <h2 class="text-center bp-3"> Recuperacion de Contraseña </h2> <br>
                <form method="post" action=""> <!-- ESTE ES EL FORMULARIO -->
                <?php
                include("../../../app/Controllers/correos_controller.php");
                ?>
                    <!-- Email input -->
                    <input type="email" name="email" class="form-control form-control-sm" placeholder="Correo Electronico" aria-describedby="basic-addon3" required>
                    <div class="form-text" id="basic-addon3">Verifica el dominio y datos de tu correo para poder identificarlo.</div>

                    <br>

                    <!-- Submit -->


                    <br>

                    <center><input type="submit" name="btnenviarcorreo" class="btn btn-success" value="Ingresar"></center>
                </form>
            </div>
        </div> 
    </div>








    <script src="../../../bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>