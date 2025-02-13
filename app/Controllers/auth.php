<?php
session_start();
if (!isset($_SESSION['id']) || !isset($_SESSION['rol'])) {
    header("location: /public/views/sesiones/login.php");
    exit();
}

$nombre = $_SESSION['nombre'];
// Obtener el rol
$rol = $_SESSION['rol'];
$idUsuario = $_SESSION['id']; // ID del usuario ya validado en auth.php
$emailUsuario = $_SESSION['email']; // Correo electrónico del usuario
$email_verificado = $_SESSION['email_verified'];

switch($rol):
    case 1:
        $nrol = "Director";
        break;
    case 2:
        $nrol = "PTC";
        break;
    case 3:
        $nrol = "Estudiante";
        break;
    case 4:
        $nrol = "Psicologia";
        break;
    endswitch;
?>
