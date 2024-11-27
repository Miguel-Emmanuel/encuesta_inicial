<?php

require ("../../../app/Controllers/auth.php");
$filtro= (int) $_GET['f'];

if ($filtro == 1) {
    $content = 'PE.php';  // Acción para cuando filtro es 1
} elseif ($filtro == 2) {
    $content = 'GV.php';  // Acción para cuando filtro es 2
} elseif ($filtro == 3) {
    $content = 'GT.php';  // Acción para cuando filtro es 3
} elseif ($filtro == 4) {
    $content = 'Contenido para filtro 4';  // Acción para cuando filtro es 4
}

include('../dashboard/dashboard.php');


?>