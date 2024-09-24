<?php
require '../../database/conexion.php';

$nombre = $conexion->real_escape_string($_POST['nombre']);
$programa_e = $conexion->real_escape_string($_POST['programa_e']);
$nomenclatura = $conexion->real_escape_string($_POST['nomenclatura']);
$tutor = $conexion->real_escape_string($_POST['tutor']);
$periodo_e = $conexion->real_escape_string($_POST['periodo_e']);


$sql = "INSERT INTO t_grupos (nombre, programa_e, nomenclatura, tutor, periodo_e) VALUES ('$nombre','$programa_e','$nomenclatura','$tutor', '$periodo_e')";
if($conexion->query($sql)){
    $id = $conexion->insert_id;
}

header('Location: /public/views/grupos/index.php');

?>