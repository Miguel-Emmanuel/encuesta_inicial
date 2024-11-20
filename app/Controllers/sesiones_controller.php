<?php
include("../../database/conexion.php");

session_start();

if(isset($_SESSION['id']) != ""){
    $idUsuario = $_SESSION['id'];
    $rolu = "SELECT rol_id FROM usuarios WHERE id = $idUsuario";
    $result = $conexion->query($rolu);
    $row = $result->fetch_assoc();
    $rol = $row['rol_id']; 
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
            header("Location: ../../public/views/sesiones/index.php");
            exit();
    }
}else{
    header("Location: ../../public/views/sesiones/login.php");
}

?>