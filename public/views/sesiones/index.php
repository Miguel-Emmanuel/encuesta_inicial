<?php
include("../../../app/Models/conexion.php");
session_start();
if (isset($_SESSION['id']) != "") {
    $idUsuario = $_SESSION['id'];
    $rolu = "SELECT rol_id FROM usuarios WHERE id = $idUsuario";
    $result = $conexion->query($rolu);
    $row = $result->fetch_assoc();
    $rol = $row['rol_id']; 

    $emailUsuario = $_SESSION['email'];
    $content = 'inicio.php';
    include('../dashboard/dashboard.php');
} else {
    header("location: login.php");
}
