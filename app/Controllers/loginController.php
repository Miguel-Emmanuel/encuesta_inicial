<?php

//  Revisa que la petición llegue por metodo POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include("../../database/conexion.php");

    //  Formatea el correo del usuario
    $email = filter_var($_REQUEST['email'], FILTER_SANITIZE_EMAIL);
    //  Valida el correo
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $emailUser = ($_REQUEST['email']);
    }
    //  Obtiene la contraseña ingresada por el usuario
    $passwordUser = trim($_REQUEST['password']);

    //  Busca y valida en BD el correo que ingresó el usuario
    $consultaLogin = ("SELECT id, email, pass, rol_id as rol FROM usuarios WHERE email = '$emailUser'");
    $resultLogin = mysqli_query($conexion, $consultaLogin) or die(mysqli_error($conexion));;
    $numLogin = mysqli_num_rows($resultLogin);

    //  Si encontró el correo procede a verificar que la contraseña sea correcta
    if ($numLogin != 0) {
        while ($rowData = mysqli_fetch_assoc($resultLogin)) {
            $passwordDB = $rowData['pass'];

            //  Compara la contraseña ingresada por el usuario con la que se encuentra en BD
            if (password_verify($passwordUser, $passwordDB)) {
                session_start();    // Creando la sesión ya que los datos son validados

            $_SESSION['id'] = $rowData['id'];
            $_SESSION['email'] = $rowData['email'];
            $_SESSION['rol'] = $rowData['rol'];

            $rol = $rowData['rol'];

            // Redirección a partir del rol del usuario
            switch ($rol) {
                case 1: // Admin
                case 2: // PTC
                case 4: // Otro
                    header("Location: ../../public/views/sesiones/index.php");
                    exit();
                case 3: // Estudiante
                    header("Location: ../../public/views/encuesta/menu_secciones.php");
                    exit();
                default: // Rol desconocido
                    header("Location: ../../public/views/sesiones/login.php");
                    exit();
            }
            } else {
                // echo "Login incorrecto por contraseña";
                header("Location: ../../public/views/sesiones/login.php?e=1");
            }
        }
    } else {
        // echo "Login incorrecto por correo";
        header("Location: ../../public/views/sesiones/login.php?e=1");
    }
}
