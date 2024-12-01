<?php
require("../../../app/Controllers/auth.php"); // Valida la sesión y obtiene el rol
include("../../../app/Models/conexion.php"); // Incluye la conexión a la base de datos


switch ($rol):
    case 1:
    case 2:
    case 4:
        $content = 'inicio.php'; // Define el contenido principal
        include('../dashboard/dashboard.php'); // Incluye la plantilla del dashboard
        break;
    case 3:
        header("location: /public/views/sesiones/login.php");
        break;
    default:
        header("location: /public/views/sesiones/login.php");
        break;
endswitch;



