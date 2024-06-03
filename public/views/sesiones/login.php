<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="../../../bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <?php
    include("../../../app/Models/conexion.php");
    // Verificar si la sesión ya está activa
    session_start();

    // Verificar si la clave 'rol' existe en la sesión
    if (isset($_SESSION['rol'])) {
        $rol = $_SESSION['rol'];
    } else {
        $rol = ''; // Valor predeterminado si no existe
    }

    //en ESTA LINEA ES PARA OMITIR EL O=PROBLEMA DE ARRAY INDEFINIDO -->

    // $rol =$_SESSION["rol"] ;

    if (!empty($_SESSION["id"])) {

        switch ($rol) {
            case 1:
                header("Location: inicio.php");
                exit();
            case 2:
                header("Location: inicio.php");
                exit();
            case 3:
                header("Location: ../encuesta/encuesta.php");
                exit();
            case 3:
                header("Location: ../encuesta/encuesta.php");
                exit();
        }
    }
    ?>

    <link rel="stylesheet" href="../../css/login.css">
</head>

<body>
    <div class="contenedor rounded shadow"> <!-- CUERPO DE LA PAGINA -->
        <div class="row m-2 align-items-stretch"> <!-- CUERPO DEL LOGIN -->
            <!-- COLUMNAS -->
            <div class="bg col d-none d-lg-block col-md-6 col-lg-6 col-xl-6 rounded">
                <!-- ESTE ES LA IMAGEN PORTADA DE LA DERECHA -->
            </div>
            <div class="col form">
                <h2 class="text-center bp-3"> Inicia Sesión </h2> <br>
                <form method="post" action=""> <!-- ESTE ES EL FORMULARIO -->
                    <?php
                    include("../../../app/Controllers/sesiones_controller.php");
                    ?>
                    <!-- Email input -->
                    <input type="email" name="email" class="form-control form-control-sm" placeholder="Correo Electronico" aria-describedby="basic-addon3">
                    <div class="form-text" id="basic-addon3">Recuerda usar tu correo institucional proporcionado.</div>

                    <br>

                    <!-- Password input -->
                    <input type="password" name="password" class="form-control form-control-sm" placeholder="Contraseña" aria-describedby="basic-addon3">
                    <div class="form-text" id="basic-addon3">No olvides cambiar tu contraseña si es primera vez que ingresas.</div>

                    <br>

                    <!-- Submit -->

                    <a href="../pass/recuperarpass.php">Olvidaste tu contraseña?</a>

                    <br>

                    <center><input type="submit" name="btningresar" class="btn btn-success" value="Ingresar"></center>
                </form>
            </div>
        </div>
    </div>








    <script src="../../../bootstrap/js/bootstrap.bundle.min.js"></script>
</body>

</html>