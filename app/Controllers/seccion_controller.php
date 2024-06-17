<?php
include("../../database/conexion.php");
session_start();
if (empty($_SESSION["id"])) {
    header("location: ../sesiones/login.php");
    exit;
}

// Recibir el parámetro de la sección
$seccion = $_GET['seccion'];

// Consultar las preguntas de la sección desde la base de datos
$sql = $conexion->query("SELECT * FROM preguntas WHERE seccion = '$seccion'");

// Incluir la vista de la sección
include("vistas/seccion.php");
