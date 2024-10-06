<?php
require '../../../database/conexion.php';

$nombre = $conexion->real_escape_string($_POST['nombre']);
$programa_e = $conexion->real_escape_string($_POST['programa_e']);
$nomenclatura = $conexion->real_escape_string($_POST['nomenclatura']);


$sql = "INSERT INTO t_grupos (nombre, programa_e, nomenclatura) VALUES ('$nombre','$programa_e','$nomenclatura')";
if($conexion->query($sql)){
    $id = $conexion->insert_id;
}

header('Location: /public/views/grupos/index.php');

?>