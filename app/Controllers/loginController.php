<?php
session_start(); // Iniciar sesión al inicio

// Revisa que la petición sea POST
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../../public/views/sesiones/login.php");
    exit();
}

// Incluye los archivos de conexión
require_once("../../database/conexionlogin.php");
require_once("../../database/mongo_conexion.php");

// Validar y limpiar correo
$email = filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL);
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header("Location: ../../public/views/sesiones/login.php?e=1");
    exit();
}

// Obtener y limpiar la contraseña
$passwordUser = trim($_POST['password'] ?? '');
if (empty($passwordUser)) {
    header("Location: ../../public/views/sesiones/login.php?e=1");
    exit();
}

// Intentamos verificar si la base de datos MySQL está disponible
if ($conexion) {
    // Consulta usando prepared statements para mayor seguridad
    $query = "SELECT id, CONCAT(nombre, ' ', apellido_paterno, ' ', apellido_materno) AS nombre, email, pass, email_verified, email_verified_at, rol_id AS rol 
              FROM usuarios WHERE email = ?";
    $stmt = mysqli_prepare($conexion, $query);
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($result)) {
        // Verificar contraseña
        if (password_verify($passwordUser, $row['pass'])) {
            // Almacenar datos en sesión
            $_SESSION['id'] = $row['id'];
            $_SESSION['email'] = $row['email'];
            $_SESSION['rol'] = $row['rol'];
            $_SESSION['nombre'] = $row['nombre'];
            $_SESSION['email_verified'] = $row['email_verified'];
            $_SESSION['email_verified_at'] = $row['email_verified_at'];

            // Redirigir según el rol del usuario
            $redirect = match ($row['rol']) {
                1, 2, 4 => "../../public/views/sesiones/index.php", // Admin, PTC, Otro
                3 => "../../public/views/encuesta/menu_secciones.php", // Estudiante
                default => "../../public/views/sesiones/login.php", // Rol desconocido
            };
            header("Location: $redirect");
            exit();
        }
    }
    
    // Si no encontró el usuario o la contraseña no es válida
    header("Location: ../../public/views/sesiones/login.php?e=1");
    exit();
}

// Si no hay conexión con MySQL, intentamos en MongoDB
try {
    $usuario = $mongoDB->usuarios->findOne(["email" => $email]);
    if ($usuario && password_verify($passwordUser, $usuario['pass'] ?? '')) {
        $_SESSION['id'] = (string)$usuario['_id'];
        $_SESSION['email'] = $usuario['email'];
        $_SESSION['rol'] = $usuario['rol_id'];
        $_SESSION['nombre'] = $usuario['nombre'];
        $_SESSION['email_verified'] = $usuario['email_verified'] ?? false;

        // Redirigir a la restauración de la base de datos
        header("Location: ../../../../public/views/emergency/index.php");
        exit();
    }
} catch (Exception $e) {
    header("Location: ../../public/views/sesiones/login.php?e=4");
    exit();
}

// Si no hay conexión en ninguna de las bases de datos
header("Location: ../../public/views/sesiones/login.php?e=2");
exit();
?>
