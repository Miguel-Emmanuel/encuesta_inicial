<?php

require("../../../app/Controllers/auth.php");
$filtro = (int) $_GET['f'];


switch ($filtro):
    case 1:
        $content = 'PE.php';  // Acción para cuando filtro es 1
        break;
    case 2:
        $content = 'GV.php';  // Acción para cuando filtro es 2
        break;
    case 3:
        $content = 'GT.php';  // Acción para cuando filtro es 3
        break;
    case 4:
        $content = 'GTT.php';  // Acción para cuando filtro es 4
        break;

    default:
        header("location: /public/views/sesiones/login.php");
        break;
endswitch;

include('../dashboard/dashboard.php');
