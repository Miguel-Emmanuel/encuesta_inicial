<?php
require("../../../app/Controllers/auth.php"); // Valida la sesión y variables de sesion
include("../../../app/Models/conexion.php"); // Incluye la conexión a la base de datos

switch ($rol):
    case 1:
        $content = 'tablaroles.php';
        include('../dashboard/dashboard.php'); // Incluye la plantilla del dashboard
        break;
    case 2:
    case 3:
    case 4:
        header("location: /public/views/sesiones/login.php");
        break;
    default:
        header("location: /public/views/sesiones/login.php");
        break;
endswitch;