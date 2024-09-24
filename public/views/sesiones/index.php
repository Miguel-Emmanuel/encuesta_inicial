<?php
include("../../../app/Models/conexion.php");
session_start();
if (isset($_SESSION['id']) != "") {
    $idUsuario = $_SESSION['id'];
    $emailUsuario = $_SESSION['email'];
    $content = 'inicio.php';
    include('../dashboard/dashboard.php');
} else {
    header("location: login.php");
}
