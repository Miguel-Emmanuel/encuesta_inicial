<?php
session_start();
if (!isset($_SESSION['id']) || !isset($_SESSION['rol'])) {
    header("location: /public/views/sesiones/login.php");
    exit();
}

// Obtener el rol
$rol = $_SESSION['rol'];
$idUsuario = $_SESSION['id']; // ID del usuario ya validado en auth.php
$emailUsuario = $_SESSION['email']; // Correo electrónico del usuario

?>
