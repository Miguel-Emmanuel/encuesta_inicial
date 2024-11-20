<?php
include("../../database/conexion.php");

session_start();

if (isset($_SESSION['id'])) {
    $idUsuario = $_SESSION['id'];

    // Consulta segura usando consultas preparadas
    $stmt = $conexion->prepare("SELECT rol_id FROM usuarios WHERE id = ?");
    $stmt->bind_param("i", $idUsuario);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $rol = $row['rol_id'];

        // Redirección a partir del rol del usuario
        switch ($rol) {
            case 1: // Admin
            case 2: // User
            case 4: // Otro
                header("Location: ../../public/views/sesiones/index.php");
                exit();
            case 3: // Encuestador
                header("Location: ../../public/views/encuesta/menu_secciones.php");
                exit();
            default: // Rol desconocido
                header("Location: ../../public/views/sesiones/login.php");
                exit();
        }
    } else {
        // Si no se encuentra el usuario, redirigir al login
        header("Location: ../../public/views/sesiones/login.php");
        exit();
    }
} else {
    // Si no hay sesión, redirigir al login
    header("Location: ../../public/views/sesiones/login.php");
    exit();
}
?>
