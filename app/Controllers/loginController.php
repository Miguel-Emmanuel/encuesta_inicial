<?php

// Revisa que la petición llegue por método POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Incluye el archivo de conexión a MySQL
    include("../../database/conexionlogin.php");

    // Incluye el archivo de conexión a MongoDB
    include("../../database/mongo_conexion.php");

    // Formatea el correo del usuario
    $email = filter_var($_REQUEST['email'], FILTER_SANITIZE_EMAIL);
    
    // Valida el correo
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $emailUser = ($_REQUEST['email']);
    }

    // Obtiene la contraseña ingresada por el usuario
    $passwordUser = trim($_REQUEST['password']);

    // Intentamos verificar si la base de datos encuesta_02 existe en MySQL
    if ($conexion !== null) {
        // La base de datos 'encuesta_02' existe en MySQL, usamos MySQL para el login
        $consultaLogin = "SELECT 
            id, 
            CONCAT(nombre, ' ', apellido_paterno, ' ', apellido_materno) AS nombre, 
            email, 
            pass,
            email_verified,
            email_verified_at,
            rol_id AS rol 
        FROM usuarios 
        WHERE email = '$emailUser'";
        $resultLogin = mysqli_query($conexion, $consultaLogin) or die(mysqli_error($conexion));
        $numLogin = mysqli_num_rows($resultLogin);

        // Si encontró el correo, procede a verificar que la contraseña sea correcta
        if ($numLogin != 0) {
            while ($rowData = mysqli_fetch_assoc($resultLogin)) {
                $passwordDB = $rowData['pass'];

                // Compara la contraseña ingresada por el usuario con la que se encuentra en BD
                if (password_verify($passwordUser, $passwordDB)) {
                    session_start(); // Creando la sesión ya que los datos son validados

                    $_SESSION['id'] = $rowData['id'];
                    $_SESSION['email'] = $rowData['email'];
                    $_SESSION['rol'] = $rowData['rol'];
                    $_SESSION['nombre'] = $rowData['nombre'];
                    $_SESSION['email_verified'] = $rowData['email_verified'];
                    $_SESSION['email_verified_at'] = $rowData['email_verified_at'];

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
                    // Si la contraseña no es correcta
                    header("Location: ../../public/views/sesiones/login.php?e=1");
                    exit();
                }
            }
        } else {
            // Si el correo no se encuentra en MySQL
            header("Location: ../../public/views/sesiones/login.php?e=1");
            exit();
        }
    } else {
        // Si no existe la base de datos en MySQL, usamos MongoDB
        $usuario = $db->usuarios->findOne(["email" => $emailUser]);

        if ($usuario) {
            // El hash de la contraseña está en $usuario['pass']
            $passwordDB = $usuario['pass'];

            // Compara la contraseña ingresada por el usuario con el hash almacenado en MongoDB
            if (password_verify($passwordUser, $passwordDB)) {
                session_start();
                $_SESSION['id'] = (string)$usuario['_id'];
                $_SESSION['email'] = $usuario['email'];
                $_SESSION['rol'] = $usuario['rol_id'];
                $_SESSION['nombre'] = $usuario['nombre'];
                $_SESSION['email_verified'] = $usuario['email_verified'] ?? false;

                // Redirigir a cargar.php
                header("Location: ../../database/restore/cargar.php");
                exit();
            } else {
                // Si la contraseña no coincide en MongoDB
                header("Location: ../../public/views/sesiones/login.php?e=1");
                exit();
            }
        } else {
            // Si el usuario no se encuentra en MongoDB
            header("Location: ../../public/views/sesiones/login.php?e=1");
            exit();
        }
    }
}
?>
