<?php
include("../../database/conexion.php");

session_start();

if(isset($_SESSION['id']) != ""){
    $rol = $_SESSION['rol'];
    //  Redirección a partir del rol del usuario
    switch ($rol) {
        case 1:
            header("Location: ../../public/views/sesiones/index.php");
            exit();
        case 2:
            header("Location: ../../public/views/sesiones/index.php");
            exit();
        case 3:
            header("Location: ../../public/views/encuesta/menu_secciones.php");
            exit();
        case 4:
            header("Location: ../../public/views/encuesta/menu_secciones.php");
            exit();
    }
}else{
    header("Location: ../../public/views/sesiones/login.php");
}

?>