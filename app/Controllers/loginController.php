<?php
session_start(); // Iniciar sesión al inicio

// Revisa que la petición sea POST
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../../public/views/sesiones/login.php");
    exit();
}

// Incluye los archivos de conexión
require_once("../../database/conexionlogin.php");
require_once("../../database/mongo_conexion.php"); // Conexión de respaldo

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

$usuarioEncontrado = false;
$conexionDisponible = $conexion || $conexion_respaldo;

// Intentar verificar usuario en la base de datos principal
if ($conexion) {
    $query = "SELECT id, CONCAT(nombre, ' ', apellido_paterno, ' ', apellido_materno) AS nombre, email, pass, email_verified, email_verified_at, rol_id AS rol FROM usuarios WHERE email = ?";
    $stmt = mysqli_prepare($conexion, $query);
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($result)) {
        $usuarioEncontrado = true;
        if (password_verify($passwordUser, $row['pass'])) {
            $_SESSION = [
                'id' => $row['id'],
                'email' => $row['email'],
                'rol' => $row['rol'],
                'nombre' => $row['nombre'],
                'email_verified' => $row['email_verified'],
                'email_verified_at' => $row['email_verified_at']
            ];

            $redirect = match ($row['rol']) {
                1, 2, 4 => "../../public/views/sesiones/index.php",
                3 => "../../public/views/encuesta/menu_secciones.php",
                default => "../../public/views/sesiones/login.php",
            };
            header("Location: $redirect");
            exit();
        }
    }
}

// Si no se encontró en la principal, buscar en respaldo
if (!$usuarioEncontrado && $conexion_respaldo) {
    $query = "SELECT id, nombre, email, pass, email_verified, rol_id AS rol FROM usuarios WHERE email = ?";
    $stmt = mysqli_prepare($conexion_respaldo, $query);
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($result)) {
        if (password_verify($passwordUser, $row['pass'])) {
            $_SESSION = [
                'id' => $row['id'],
                'email' => $row['email'],
                'rol' => $row['rol'],
                'nombre' => $row['nombre'],
                'email_verified' => $row['email_verified'] ?? false,
            ];
            header("Location: ../../../../public/views/emergency/index.php");
            exit();
        }
    }
}

// Si no hay conexión ni respaldo
if (!$conexionDisponible) {
    header("Location: ../../public/views/sesiones/login.php?e=4");
    exit();
}

// Si no encontró al usuario en ninguna base de datos
header("Location: ../../public/views/sesiones/login.php?e=1");
exit();